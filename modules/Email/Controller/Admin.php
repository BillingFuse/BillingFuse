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


namespace Fuse\Mod\Email\Controller;

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
            'subpages' => array(
                array(
                    'location'  => 'activity',
                    'index'     => 200,
                    'label' => 'Email history',
                    'uri'   => $this->di['url']->adminLink('email/history'),
                    'class' => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/email/history/', 'get_history', array(), get_class($this));
        $app->get('/email/history', 'get_history', array(), get_class($this));
        $app->get('/email/templates', 'get_index', array(), get_class($this));
        $app->get('/email/template/:id', 'get_template', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/email/:id', 'get_email', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_history(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_email_history');
    }
    
    public function get_template(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $template = $api->email_template_get(array('id'=>$id));
        return $app->render('mod_email_template', array('template'=>$template));
    }
    
    public function get_email(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $template = $api->email_email_get(array('id'=>$id));
        return $app->render('mod_email_details', array('email'=>$template));
    }
}