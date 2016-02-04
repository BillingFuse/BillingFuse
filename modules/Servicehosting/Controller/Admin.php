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


namespace Fuse\Mod\Servicehosting\Controller;

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
            'subpages'  =>  array(
                array(
                    'location'  => 'system',
                    'index'     => 140,
                    'label' => 'Hosting plans and servers',
                    'uri'   => $this->di['url']->adminLink('servicehosting'),
                    'class' => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/servicehosting',          'get_index', null, get_class($this));
        $app->get('/servicehosting/plan/:id',       'get_plan', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/servicehosting/server/:id',     'get_server', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_servicehosting_index');
    }

    public function get_plan(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $hp = $api->servicehosting_hp_get(array('id'=>$id));
        return $app->render('mod_servicehosting_hp', array('hp'=>$hp));
    }

    public function get_server(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $server = $api->servicehosting_server_get(array('id'=>$id));
        return $app->render('mod_servicehosting_server', array('server'=>$server));
    }
}