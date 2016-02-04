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

/**
 * This file connects BillingFuse client area interface and API
 * Class does not extend any other class
 */

namespace Fuse\Mod\Example\Controller;

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

    /**
     * Methods maps client areas urls to corresponding methods
     * Always use your module prefix to avoid conflicts with other modules
     * in future
     *
     * @param \Fuse_App $app - returned by reference
     */
    public function register(\Fuse_App &$app)
    {
        $app->get('/example',             'get_index', array(), get_class($this));
        $app->get('/example/protected',   'get_protected', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        return $app->render('mod_example_index');
    }

    public function get_protected(\Fuse_App $app)
    {
        // call $this->di['is_client_logged'] method to validate if client is logged in
        $this->di['is_client_logged'];
        return $app->render('mod_example_index', array('show_protected'=>true));
    }
}