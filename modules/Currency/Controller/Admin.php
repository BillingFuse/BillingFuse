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


namespace Fuse\Mod\Currency\Controller;

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

    public function register(\Fuse_App &$app)
    {
        $app->get('/currency/manage/:code', 'get_manage', array('code'=>'[a-zA-Z]+'), get_class($this));
    }

    public function get_manage(\Fuse_App $app, $code)
    {
        $this->di['is_admin_logged'];
        $guest_api = $this->di['api_guest'];
        $currency = $guest_api->currency_get(array('code'=>$code));
        return $app->render('mod_currency_manage', array('currency'=>$currency));
    }
}