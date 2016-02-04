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


namespace Fuse\Mod\Page;

use Fuse\InjectionAwareInterface;

class Service implements InjectionAwareInterface
{
    /**
     * @var \Fuse_Di
     */
    protected $di = null;

    /**
     * @param \Fuse_Di $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return \Fuse_Di
     */
    public function getDi()
    {
        return $this->di;
    }

    public function getPairs()
    {
        $themeService = $this->di['mod_service']('theme');
        $code = $themeService->getCurrentClientAreaThemeCode();
        $paths = array(
            BF_PATH_THEMES . DIRECTORY_SEPARATOR . $code . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR,
            BF_PATH_MODS . DIRECTORY_SEPARATOR . 'mod_page' . DIRECTORY_SEPARATOR . 'html_client' . DIRECTORY_SEPARATOR,
        );

        $list = array();
        foreach($paths as $path) {
            foreach(glob($path.'mod_page_*.phtml') as $file) {
                $file = str_replace('mod_page_', '', pathinfo($file, PATHINFO_FILENAME));
                $list[$file] = ucwords(strtr($file, array('-'=>' ', '_'=>' ')));
            }
        }

        return $list;
    }
}