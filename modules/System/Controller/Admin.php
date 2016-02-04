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


namespace Fuse\Mod\System\Controller;

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
            'group'  =>  array(
                'index'     => 600,
                'location'  =>  'system',
                'label'     => 'Configuration',
                'class'     => 'settings',
                'sprite_class' => 'dark-sprite-icon sprite-cog3',
            ),
            'subpages'=> array(
                array(
                    'location'  => 'system',
                    'label' => 'Settings',
                    'index'     => 100,
                    'uri' => $this->di['url']->adminLink('system'),
                    'class'     => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/system',           'get_index', array(), get_class($this));
        $app->get('/system/',           'get_index', array(), get_class($this));
        $app->get('/system/index',           'get_index', array(), get_class($this));
        $app->get('/system/activity',           'get_activity', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_system_index');
    }
    
    public function get_activity(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_system_activity');
    }
}