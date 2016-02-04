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


namespace Fuse\Mod\Extension\Controller;

class Admin implements \Fuse\InjectionAwareInterface
{
    protected $di;

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    public function fetchNavigation()
    {
        return array(
            'group' => array(
                'location'  => 'extensions',
                'index'     => 1000,
                'label' => 'Extensions',
                'class' => 'iPlugin',
                'sprite_class' => 'dark-sprite-icon sprite-electroPlug',
            ),
            'subpages' => array(
                array(
                    'location'  => 'extensions',
                    'label' => 'Overview',
                    'uri' => $this->di['url']->adminLink('extension'),
                    'index'     => 100,
                    'class'     => '',
                ),
                array(
                    'location'  => 'extensions',
                    'label' => 'Languages',
                    'index'     => 200,
                    'uri' => $this->di['url']->adminLink('extension/languages'),
                    'class'     => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/extension', 'get_index', array(), get_class($this));
        $app->get('/extension/settings/:mod',           'get_settings', array('mod' => '[a-z0-9-]+'), get_class($this));
        $app->get('/extension/languages',           'get_langs', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_extension_index');
    }

    public function get_langs(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_extension_languages');
    }
    
    public function get_settings(\Fuse_App $app, $mod)
    {
        $this->di['is_admin_logged'];
        $file = 'mod_'.$mod.'_settings';
        return $app->render($file);
    }
}