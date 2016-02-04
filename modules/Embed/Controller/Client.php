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

namespace Fuse\Mod\Embed\Controller;

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
        $app->get('/embed/:what',             'get_object', array('what' => '[a-z0-9-]+'), get_class($this));
    }

    public function get_object(\Fuse_App $app, $what)
    {
        $tpl = 'mod_embed_'.$what;
        return $app->render($tpl);
    }
}