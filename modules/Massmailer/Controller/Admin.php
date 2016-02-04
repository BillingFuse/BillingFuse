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


namespace Fuse\Mod\Massmailer\Controller;

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
            'subpages'=>array(
                array(
                    'location'  => 'extensions',
                    'index'     => 4000,
                    'label' => 'Mass mailer',
                    'uri'   => $this->di['url']->adminLink('massmailer'),
                    'class' => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/massmailer', 'get_index', array(), get_class($this));
        $app->get('/massmailer/message/:id', 'get_edit', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_massmailer_index');
    }
    
    public function get_edit(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $model = $api->massmailer_get(array('id'=>$id));
        return $app->render('mod_massmailer_message', array('msg'=>$model));
    }
}