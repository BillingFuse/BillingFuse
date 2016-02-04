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

class Fuse_AppAdmin extends Fuse_App
{
    public function init()
    {
        $m = $this->di['mod']($this->mod);
        $controller = $m->getAdminController();
        $controller->register($this);
    }

    public function render($fileName, $variableArray = array())
    {
        $template = $this->getTwig()->loadTemplate($fileName.'.phtml');
        return $template->render($variableArray);
    }

    public function redirect($path)
    {
        $location = $this->di['url']->adminLink($path);
        header("Location: $location");
        exit;
    }

    protected function getTwig()
    {
        $service = $this->di['mod_service']('theme');
        $theme = $service->getCurrentAdminAreaTheme();

        $loader = new Fuse_TwigLoader(array(
                "mods"  => BF_PATH_MODS,
                "theme" => BF_PATH_THEMES . DIRECTORY_SEPARATOR . $theme['code'],
                "type"  => "admin"
            )
        );

        $twig = $this->di['twig'];
        $twig->setLoader($loader);

        $twig->addGlobal('theme', $theme);

        if($this->di['auth']->isAdminLoggedIn()) {
            $twig->addGlobal('admin', $this->di['api_admin']);
        }

        return $twig;
    }
}
