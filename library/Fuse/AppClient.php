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

class Fuse_AppClient extends Fuse_App
{
    protected function init()
    {
        $m = $this->di['mod']($this->mod);
        $m->registerClientRoutes($this);

        if($this->mod == 'api') {
            define ('BF_MODE_API', TRUE);
        } else {
            $extensionService = $this->di['mod_service']('extension');
            if ($extensionService->isExtensionActive('mod', 'redirect')) {
                $m = $this->di['mod']('redirect');
                $m->registerClientRoutes($this);
            }

            //init index module manually
            $this->get('', 'get_index');
            $this->get('/', 'get_index');


            //init custom methods for undefined pages
            $this->get('/:page', 'get_custom_page', array('page' => '[a-z0-9-/.//]+'));
            $this->post('/:page', 'get_custom_page', array('page' => '[a-z0-9-/.//]+'));
        }
    }

    public function get_index()
    {
        return $this->render('mod_index_dashboard');
    }

    public function get_custom_page($page)
    {
        $ext = $this->ext;
        if(strpos($page, '.') !== false) {
            $ext = substr($page, strpos($page, '.')+1);
            $page = substr($page, 0, strpos($page, '.'));
        }
        $page = str_replace('/', '_', $page);
        $tpl = 'mod_page_'.$page;
        try {
            return $this->render($tpl, array('post'=>$_POST), $ext);
        } catch(Exception $e) {
            if(BF_DEBUG) error_log($e);
        }
        throw new \Fuse_Exception('Page :url not found', array(':url'=>$page), 404);
    }

    /**
     * @param string $fileName
     */
    public function render($fileName, $variableArray = array(), $ext = 'phtml')
    {
        try {
            $template = $this->getTwig()->loadTemplate($fileName.'.'.$ext);
        } catch (\Twig_Error_Loader $e) {
            error_log($e->getMessage());
            throw new \Fuse_Exception('Page not found', null, 404);
        }
        return $template->render($variableArray);
    }

    protected function getTwig()
    {
        $service = $this->di['mod_service']('theme');
        $code = $service->getCurrentClientAreaThemeCode();
        $theme = $service->getTheme($code);
        $settings = $service->getThemeSettings($theme);

        $loader = new Fuse_TwigLoader(array(
                "mods" => BF_PATH_MODS,
                "theme" => BF_PATH_THEMES.DIRECTORY_SEPARATOR.$code,
                "type" => "client"
            )
        );

        $twig = $this->di['twig'];
        $twig->setLoader($loader);

        $twig->addGlobal('current_theme', $code);
        $twig->addGlobal('settings', $settings);

        if($this->di['auth']->isClientLoggedIn()) {
            $twig->addGlobal('client', $this->di['api_client']);
        }

        if($this->di['auth']->isAdminLoggedIn()) {
            $twig->addGlobal('admin', $this->di['api_admin']);
        }

        return $twig;
    }
}