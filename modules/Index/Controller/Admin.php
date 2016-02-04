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


namespace Fuse\Mod\Index\Controller;

use Fuse\InjectionAwareInterface;

class Admin implements InjectionAwareInterface
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
        $app->get('', 'get_index', array(), get_class($this));
        $app->get('/', 'get_index', array(), get_class($this));
        $app->get('/index', 'get_index', array(), get_class($this));
        $app->get('/index/', 'get_index', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        if($this->di['auth']->isAdminLoggedIn()) {
            return $app->render('mod_index_dashboard');
        } else {
            return $app->redirect('/staff/login');
        }
    }
}