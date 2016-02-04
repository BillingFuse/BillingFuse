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

namespace Fuse\Mod\Servicesolusvm\Controller;

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
        $app->get('/servicesolusvm', 'get_index', array(), get_class($this));
        $app->get('/servicesolusvm/import/clients', 'get_import_clients', array(), get_class($this));
        $app->get('/servicesolusvm/import/servers', 'get_import_servers', array(), get_class($this));
    }
    
    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        $app->redirect('/extension/settings/servicesolusvm');
    }

    public function get_import_clients(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_servicesolusvm_import_clients');
    }

    public function get_import_servers(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_servicesolusvm_import_servers');
    }
}