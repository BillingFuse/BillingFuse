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


namespace Fuse\Mod\Orderbutton\Controller;

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
        $app->get('/orderbutton', 'get_index', array(), get_class($this));
        $app->get('/orderbutton/js', 'get_js', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        return $app->render('mod_orderbutton_index');
    }

    public function get_js(\Fuse_App $app)
    {
        header("Content-Type: application/javascript");
        return $app->render('mod_orderbutton_js');
    }
}