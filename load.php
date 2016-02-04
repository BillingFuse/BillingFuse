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

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
define('BF_PATH_ROOT',      dirname(__FILE__));
define('BF_PATH_VENDOR',    BF_PATH_ROOT . '/vendor');
define('BF_PATH_LIBRARY',   BF_PATH_ROOT . '/library');
define('BF_PATH_THEMES',    BF_PATH_ROOT . '/templates');
define('BF_PATH_MODS',      BF_PATH_ROOT . 'modules');
define('BF_PATH_LANGS',     BF_PATH_ROOT . '/locale');
define('BF_PATH_UPLOADS',   BF_PATH_ROOT . '/uploads');
define('BF_PATH_DATA',   BF_PATH_ROOT . '/data');

function handler_error($number, $message, $file, $line)
{
    if (E_RECOVERABLE_ERROR===$number) {
        handler_exception(new ErrorException($message, $number, 0, $file, $line));
    } else {
        error_log($number." ".$message." ".$file." ".$line);
    }
    return false;
}

function handler_exception(Exception $e)
{
    if(APPLICATION_ENV == 'testing') {
        print $e->getMessage() . PHP_EOL;
        return ;
    }
    error_log($e->getMessage());
    
    if(defined('BF_MODE_API')) {
        $code = $e->getCode() ? $e->getCode() : 9998;
        $result = array('result'=>NULL, 'error'=>array('message'=>$e->getMessage(), 'code'=>$code));
        print json_encode($result);
        return false;
    }

    $page = "<!DOCTYPE html>
    <html lang=en>
    <meta charset=utf-8>
    <title>Error</title>
    <style>
    *{margin:0;padding:0}html,code{font:15px/22px arial,sans-serif}html{background:#fff;color:#222;padding:15px}body{margin:7% auto 0;min-height:180px;padding:30px 0 15px}* > body{padding-right:205px}p{margin:11px 0 22px;overflow:hidden}ins{color:#777;text-decoration:none}a img{border:0} em{font-weight:bold}@media screen and (max-width:772px){body{background:none;margin-top:0;max-width:none;padding-right:0}}pre{ width: 100%; overflow:auto; }
    </style>
    <a href=//www.billingfuse.com/ target='_blank'><img src='http://billingfuse.com/images/logo.png' alt='BillingFuse' style='height:60px'></a>
    ";
    $page = str_replace(PHP_EOL, "", $page);
    print $page;
    if($e->getCode()) {
        print sprintf('<p>Code: <em>%s</em></p>', $e->getCode());
    }
    print sprintf('<p>%s</p>', $e->getMessage());
    print sprintf('<p><a href="http://docs.billingfuse.com/" target="_blank">Look for detailed error explanation</a></p>', urlencode($e->getMessage()));

    if(defined('BF_DEBUG') && BF_DEBUG) {
        print sprintf('<em>%s</em>', 'Set BF_DEBUG to FALSE, to hide the message below');
        print sprintf('<p>Class: "%s"</p>', get_class($e));
        print sprintf('<p>File: "%s"</p>', $e->getFile());
        print sprintf('<p>Line: "%s"</p>', $e->getLine());
        print sprintf('Trace: <pre>%s</pre>', $e->getTraceAsString());
    }
}

set_exception_handler("handler_exception");
set_error_handler('handler_error');

// multisite support. Load new config depending on current host
// if run from cli first param must be hostname
$configPath = BF_PATH_ROOT.'/config.php';
if((isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']) || (php_sapi_name() == 'cli' && isset($argv[1]) ) ) {
    if(php_sapi_name() == 'cli') {
        $host = $argv[1];
    } else {
        $host = $_SERVER['HTTP_HOST'];
    }
    
    $predictConfigPath = BF_PATH_ROOT.'/config-'.$host.'.php';
    if(file_exists($predictConfigPath)) {
        $configPath = $predictConfigPath;
    }
}

// check if config is available
if(!file_exists($configPath) || 0 == filesize( $configPath )) {
    
    //try create empty config file
    @file_put_contents($configPath, '');
    
    $base_url = "http://".$_SERVER['HTTP_HOST'];
    $base_url .= preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])).'/';
    $url = $base_url . 'install/index.php';
    $configFile = pathinfo($configPath, PATHINFO_BASENAME);
    $msg = sprintf("There doesn't seem to be a <em>$configFile</em> file or config.php file does not contain required configuration parameters. I need this before we can get started. Need more help? <a target='_blank' href='http://docs.billingfuse.com/'>We got it</a>. You can create a <em>$configFile</em> file through a web interface, but this doesn't work for all server setups. The safest way is to manually create the file.</p><p><a href='%s' class='button'>Continue with BillingFuse installation</a>", $url);
    throw new Exception($msg, 101);
}

$config = require_once $configPath;
require BF_PATH_VENDOR . '/autoload.php';

date_default_timezone_set($config['timezone']);

define('BF_DEBUG',          $config['debug']);
define('BF_URL',            $config['url']);
define('BF_SEF_URLS',       $config['sef_urls']);
define('BF_PATH_CACHE',     $config['path_data'] . '/cache');
define('BF_PATH_LOG',       $config['path_data'] . '/log');
define('BF_SSL',            (substr($config['url'], 0, 5) === 'https'));

if($config['sef_urls']) {
    define('BF_URL_API',    $config['url'] . 'api/');
} else {
    define('BF_URL_API',    $config['url'] . 'index.php?_url=/api/');
}

if($config['debug']) {
    error_reporting( E_ALL );
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting( E_RECOVERABLE_ERROR );
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

ini_set('log_errors', '1');
ini_set('html_errors', FALSE);
ini_set('error_log', BF_PATH_LOG . '/php_error.log');

// Strip magic quotes from request data.
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    // Create lamba style unescaping function (for portability)
    $quotes_sybase = strtolower(ini_get('magic_quotes_sybase'));
    $unescape_function = (empty($quotes_sybase) || $quotes_sybase === 'off') ? 'stripslashes($value)' : 'str_replace("\'\'","\'",$value)';
    $stripslashes_deep = create_function('&$value, $fn', '
        if (is_string($value)) {
            $value = ' . $unescape_function . ';
        } else if (is_array($value)) {
            foreach ($value as &$v) $fn($v, $fn);
        }
    ');

    // Unescape data
    $stripslashes_deep($_POST, $stripslashes_deep);
    $stripslashes_deep($_GET, $stripslashes_deep);
    $stripslashes_deep($_COOKIE, $stripslashes_deep);
    $stripslashes_deep($_REQUEST, $stripslashes_deep);
}
