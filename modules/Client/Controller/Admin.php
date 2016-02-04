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


namespace Fuse\Mod\Client\Controller;

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
                'index' => 200,
                'location' => 'client',
                'label' => 'Clients',
                'uri' => $this->di['url']->adminLink('client'),
                'class'     => 'contacts',
                'sprite_class' => 'dark-sprite-icon sprite-users',
            ),
            'subpages' => array(
                array(
                    'location' => 'client',
                    'label' => 'Overview',
                    'uri' => $this->di['url']->adminLink('client'),
                    'index' => 100,
                    'class'     => '',
                ),
                array(
                    'location' => 'client',
                    'label' => 'Advanced search',
                    'uri' => $this->di['url']->adminLink('client', array('show_filter' => 1)),
                    'index' => 200,
                    'class'     => '',
                ),
                array(
                    'location' => 'activity',
                    'index' =>  900,
                    'label' => 'Client logins history',
                    'uri' => $this->di['url']->adminLink('client/logins'),
                    'class'     => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/client',            'get_index', array(), get_class($this));
        $app->get('/client/',           'get_index', array(), get_class($this));
        $app->get('/client/index',      'get_index', array(), get_class($this));
        $app->get('/client/login/:id',  'get_login', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/client/manage/:id', 'get_manage', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/client/group/:id',  'get_group', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/client/create',     'get_create', array(), get_class($this));
        $app->get('/client/logins',     'get_history', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_client_index');
    }

    public function get_manage(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $client = $api->client_get(array('id'=>$id));
        return $app->render('mod_client_manage', array('client'=>$client));
    }

    public function get_group(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $model = $api->client_group_get(array('id'=>$id));
        return $app->render('mod_client_group', array('group'=>$model));
    }
    
    public function get_history(\Fuse_App $app)
    {
        $api = $this->di['api_admin'];
        return $app->render('mod_client_login_history');
    }

    public function get_login(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $api->client_login(array('id'=>$id));

        $redirect_to = '/';
        if ($this->di['request']->getQuery('r')) {
            $r           = $this->di['request']->getQuery('r');
            $redirect_to = '/' . trim($r, '/');
        }

        Header( "HTTP/1.1 301 Moved Permanently" );
        Header( "Location: ".$this->di['tools']->url($redirect_to) );
        exit;
    }
}