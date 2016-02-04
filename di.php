<?php
/*
*
* BillingFuse
*
* @copyright 2016 BillingFuse International Limited.
*
* @license Apache V2.0
*
* THIS SOURCE CODE FORM IS SUBJECT TO THE TERMS OF THE PUBLIC
* APACHE LICENSE V2.0. A COMPLETE COPY OF THE LICENSE TEXT IS
* INCLUDED IN THE LICENSE FILE. 
*
*/

$di = new Fuse_Di();
$di['config'] = function() {
    $array = include BF_PATH_ROOT . '/config.php';
    return new Fuse_Config($array);
};
$di['logger'] = function () use ($di) {
    $logFile = $di['config']['path_logs'];
    $writer  = new Fuse_LogStream($logFile);
    $log     = new Fuse_Log();
    $log->addWriter($writer);

    $log_to_db = isset($di['config']['log_to_db']) && $di['config']['log_to_db'];
    if ($log_to_db) {
        $activity_service = $di['mod_service']('activity');
        $writer2          = new Fuse_LogDb($activity_service);
        if ($di['auth']->isAdminLoggedIn()){
                $admin = $di['loggedin_admin'];
                $log->setEventItem('admin_id', $admin->id);
        }
        elseif ($di['auth']->isClientLoggedIn()) {
            $client = $di['loggedin_client'];
            $log->setEventItem('client_id', $client->id);
        }
        $log->addWriter($writer2);
    }

    return $log;
};
$di['crypt'] = function() use ($di) {
    $crypt =  new Fuse_Crypt();
    $crypt->setDi($di);
    return $crypt;
};
$di['pdo'] = function() use ($di) {
    $c = $di['config']['db'];

    $pdo = new PDO($c['type'].':host='.$c['host'].';dbname='.$c['name'],
        $c['user'],
        $c['password'],
        array(
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY         => true,
            PDO::ATTR_ERRMODE                          => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE               => PDO::FETCH_ASSOC,
        )
    );

    if(isset($c['debug']) && $c['debug']) {
        $pdo->setAttribute(PDO::ATTR_STATEMENT_CLASS, array ('Fuse_DbLoggedPDOStatement'));
    }

    if($c['type'] == 'mysql') {
        $pdo->exec( 'SET NAMES "utf8"' );
        $pdo->exec( 'SET CHARACTER SET utf8' );
        $pdo->exec( 'SET CHARACTER_SET_CONNECTION = utf8' );
        $pdo->exec( 'SET CHARACTER_SET_DATABASE = utf8' );
        $pdo->exec( 'SET character_set_results = utf8' );
        $pdo->exec( 'SET character_set_server = utf8' );
        $pdo->exec( 'SET SESSION interactive_timeout = 28800' );
        $pdo->exec( 'SET SESSION wait_timeout = 28800' );
    }

    return $pdo;
};
$di['db'] = function() use ($di) {

    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'rb.php';
    R::setup($di['pdo']);

    $helper = new Fuse_BeanHelper();
    $helper->setDi($di);

    $mapper = new \RedBeanPHP\Facade();
    $mapper->getRedBean()->setBeanHelper($helper);
    $freeze = isset($di['config']['db']['freeze']) ? (bool)$di['config']['db']['freeze'] : true;
    $mapper->freeze($freeze);

    $db = new Fuse_Database();
    $db->setDi($di);
    $db->setDataMapper($mapper);
    return $db;
};
$di['pager'] = function() use($di) {
    $service = new Fuse_Pagination();
    $service->setDi($di);
    return $service;
};
$di['url'] = function() use ($di) {
    $url  = new Fuse_Url();
    $url->setDi($di);
    $url->setBaseUri(BF_URL);
    return $url;
};
$di['mod'] = $di->protect(function ($name) use($di) {
    $mod = new Fuse_Mod($name);
    $mod->setDi($di);
    return $mod;
});
$di['mod_service'] = $di->protect(function ($mod, $sub = '') use($di) {
    return $di['mod']($mod)->getService($sub);
});
$di['mod_config'] = $di->protect(function ($name) use($di) {
    return $di['mod']($name)->getConfig();
});
$di['events_manager'] = function() use ($di) {
    $service = new Fuse_EventManager();
    $service->setDi($di);
    return $service;
};
$di['session'] = function () use ($di) {
    $handler = new PdoSessionHandler($di['pdo']);
    return new Fuse_Session($handler);
};
$di['cookie'] = function () use ($di) {
    $service = new Fuse_Cookie();
    $service->setDi($di);
    return $service;
};
$di['request'] = function () use ($di) {
    $service = new Fuse_Request();
    $service->setDi($di);
    return $service;
};
$di['cache'] = function () use ($di) { return new FileCache();};
$di['auth'] = function () use ($di) { return new Fuse_Authorization($di);};
$di['twig'] = function () use ($di) {
    $config = $di['config'];
    $options = $config['twig'];

    $loader = new Twig_Loader_String();
    $twig = new Twig_Environment($loader, $options);

    $fuse_extensions = new Fuse_TwigExtensions();
    $fuse_extensions->setDi($di);

    $twig->addExtension(new Twig_Extension_Optimizer());
    $twig->addExtension(new Twig_Extensions_Extension_I18n());
    $twig->addExtension(new Twig_Extensions_Extension_Debug());
    $twig->addExtension($fuse_extensions);
    $twig->getExtension('core')->setDateFormat($config['locale_date_format']);
    $twig->getExtension('core')->setTimezone($config['timezone']);

    // add globals
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        $_GET['ajax'] = TRUE;
    }

    $twig->addGlobal('request', $_GET);
    $twig->addGlobal('guest', $di['api_guest']);

    return $twig;
};

$di['is_client_logged'] = function() use($di) {
    if(!$di['auth']->isClientLoggedIn()) {
        throw new Exception('Client is not logged in');
    }
    return true;
};

$di['is_admin_logged'] = function() use($di) {
    if(!$di['auth']->isAdminLoggedIn()) {
        throw new Exception('Admin is not logged in');
    }
    return true;
};

$di['loggedin_client'] = function() use ($di) {
    $di['is_client_logged'];
    $client_id = $di['session']->get('client_id');
    return $di['db']->getExistingModelById('Client', $client_id);
};
$di['loggedin_admin'] = function() use ($di) {
    if(php_sapi_name() == 'cli') {
        return $di['mod_service']('staff')->getCronAdmin();
    }

    $di['is_admin_logged'];
    $admin = $di['session']->get('admin');
    return $di['db']->getExistingModelById('Admin', $admin['id']);
};

$di['api'] = $di->protect(function($role) use($di) {
    switch ($role) {
        case 'guest':
            $identity = new \Model_Guest();
            break;

        case 'client':
            $identity = $di['loggedin_client'];
            break;

        case 'admin':
            $identity = $di['loggedin_admin'];
            break;

        case 'system':
            $identity = $di['mod_service']('staff')->getCronAdmin();
           break;

        default:
            throw new Exception('Unrecognized Handler type: '.$role);
    }
    $api = new Api_Handler($identity);
    $api->setDi($di);
    return $api;
});
$di['api_guest'] = function() use ($di) { return $di['api']('guest'); };
$di['api_client'] = function() use ($di) { return $di['api']('client');};
$di['api_admin'] = function() use ($di) { return $di['api']('admin'); };
$di['api_system'] = function() use ($di) { return $di['api']('system'); };
$di['license'] = function () use ($di) {
    $service = new Fuse_License();
    $service->setDi($di);
    return $service;
};

$di['tools'] = function () use ($di){
    $service = new Fuse_Tools();
    $service->setDi($di);
    return $service;
};

$di['validator'] = function () use ($di){
    $validator = new Fuse_Validate();
    $validator->setDi($di);
    return $validator;
};
$di['guzzle_client'] = function () {
    return new \Guzzle\Http\Client();
};
$di['mail'] = function () {
    return new Fuse_Mail();
};
$di['extension'] = function () use ($di) {
    $extension = new \Fuse_Extension();
    $extension->setDi($di);
    return $extension;
};
$di['updater'] = function () use ($di) {
    $updater = new \Fuse_Update();
    $updater->setDi($di);
    return $updater;
};
$di['curl'] = function ($url) use ($di) {
    $curl = new \Fuse_Curl($url);
    $curl->setDi($di);
    return $curl;

};
$di['zip_archive'] = function () use ($di) {return new ZipArchive();};

$di['server_package'] = function () use ($di) {return new Server_Package();};
$di['server_client'] = function () use ($di) {return new Server_Client();};
$di['server_account'] = function () use ($di) {return new Server_Account();};

$di['server_manager'] = $di->protect(function ($manager, $config) use($di) {
    $class = sprintf('Server_Manager_%s', ucfirst($manager));
    return new $class($config);
});

$di['requirements'] = function () use ($di) {
    $r = new Fuse_Requirements();
    $r->setDi($di);
    return $r;
};

$di['period'] = $di->protect(function($code) use($di){ return new \Fuse_Period($code); });

$di['theme'] = function() use($di) {
    $service = $di['mod_service']('theme');
    return $service->getCurrentClientAreaTheme();
};
$di['cart'] = function() use($di) {
    $service = $di['mod_service']('cart');
    return $service->getSessionCart();
};

$di['table'] = $di->protect(function ($name) use($di) {
    $tools = new Fuse_Tools();
    $tools->setDi($di);
    $table =  $tools->getTable($name);
    $table->setDi($di);
    return $table;
});

$di['license_server'] = function () use ($di) {
    $server = new \Fuse\Mod\Servicelicense\Server($di['logger']);
    $server->setDi($di);
    return $server;
};

$di['solusvm'] = $di->protect(function ($config) use($di) {
    $solusVM = new \Fuse\Mod\Servicesolusvm\SolusVM();
    $solusVM->setDi($di);
    $solusVM->setConfig($config);
    return $solusVM;
});

$di['service_billingfuse'] = $di->protect(function ($config) use($di) {
    $service = new \Fuse\Mod\Servicebillingfuselicense\ServiceBillingfuse($config);
    return $service;
});

$di['ftp'] = $di->protect(function($params) use($di){ return new \Fuse_Ftp($params); });

$di['pdf'] = function () use ($di) {
    include BF_PATH_LIBRARY . '/PDF_ImageAlpha.php';
    return new \PDF_ImageAlpha();
};

$di['geoip'] = function () use ($di) { return new \GeoIp2\Database\Reader(BF_PATH_LIBRARY . '/GeoLite2-Country.mmdb'); };

$di['password'] = function() use ($di) { return new Fuse_Password();};
$di['translate'] = $di->protect(function($textDomain = '') use ($di) {
    $tr = new Fuse_Translate();
    if (!empty($textDomain)){
        $tr->setDomain($textDomain);
    }
    $cookieBFlang = $di['cookie']->get('BFLANG');
    $locale = !empty($cookieBFlang) ? $cookieBFlang  : $di['config']['locale'];

    $tr->setDi($di);
    $tr->setLocale($locale);
    $tr->setup();
    return $tr;
});
$di['array_get'] = $di->protect(function (array $array, $key, $default = null) use ($di) {
    return isset ($array[$key]) ? $array[$key] : $default;
});
return $di;
