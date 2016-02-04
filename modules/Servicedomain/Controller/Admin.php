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

namespace Fuse\Mod\Servicedomain\Controller;

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
                    'index'     => 150,
                    'label' => 'Domain registration',
                    'uri'   => $this->di['url']->adminLink('servicedomain'),
                    'class' => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/servicedomain',      'get_index', null, get_class($this));
        $app->get('/servicedomain/tld/:tld',      'get_tld', array('tld'=>'[/.a-z0-9]+'), get_class($this));
        $app->get('/servicedomain/registrar/:id',      'get_registrar', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_servicedomain_index');
    }

    public function get_tld(\Fuse_App $app, $tld)
    {
        $api = $this->di['api_admin'];
        $m = $api->servicedomain_tld_get(array('tld'=>$tld));
        return $app->render('mod_servicedomain_tld', array('tld'=>$m));
    }

    public function get_registrar(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $registrar = $api->servicedomain_registrar_get(array('id'=>$id));
        return $app->render('mod_servicedomain_registrar', array('registrar'=>$registrar));
    }
}