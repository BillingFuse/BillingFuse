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


namespace Fuse\Mod\Cookieconsent\Controller;

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
            'subpages'=> array(
                array(
                    'location'  => 'extensions',
                    'label'     => 'Cookie consent',
                    'index'     => 2000,
                    'uri'       => $this->di['url']->adminLink('cookieconsent'),
                    'class'     => '',
                ),
            ),
        );
    }

    public function register(\Fuse_App &$app)
    {
        $app->get('/cookieconsent',           'get_index', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_cookieconsent_index');
    }
}