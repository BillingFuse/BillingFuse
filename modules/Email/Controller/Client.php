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

class Client implements \Fuse\InjectionAwareInterface
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

    public function register(\Fuse_App &$app)
    {
        $app->get('/email', 'get_emails', array(), get_class($this));
        $app->get('/email/:id', 'get_email', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_emails(\Fuse_App $app)
    {
        $this->di['is_client_logged'];
        return $app->render('mod_email_index');
    }
    public function get_email(\Fuse_App $app, $id)
    {
        $api = $this->di['api_client'];
        $data = array('id'=>$id);
        $email = $api->email_get($data);
        return $app->render('mod_email_email', array('email'=>$email));
    }

}