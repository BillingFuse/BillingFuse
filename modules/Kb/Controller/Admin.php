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


namespace Fuse\Mod\Kb\Controller;

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
            'group'  =>  array(
                'index' => 501,
                'location' => 'kb',
                'label' => 'Knowledge Base',
                'uri' => $this->di['url']->adminLink('kb'),
                'class' => 'support',
            ),
            'subpages'  =>  array(
                array(
                    'location'  => 'kb',
                    'index'     => 800,
                    'label' => 'Knowledge Base',
                    'uri'   => $this->di['url']->adminLink('kb'),
                    'class' => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/kb',           'get_index', array(), get_class($this));
        $app->get('/kb/article/:id',  'get_post', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/kb/category/:id',  'get_cat', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_kb_index');
    }
    
    public function get_post(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $post = $api->kb_article_get(array('id'=>$id));
        return $app->render('mod_kb_article', array('post'=>$post));
    }

    public function get_cat(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $cat = $api->kb_category_get(array('id'=>$id));
        return $app->render('mod_kb_category', array('category'=>$cat));
    }

}