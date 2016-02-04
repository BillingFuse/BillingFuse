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


namespace Fuse\Mod\News\Controller;

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
            'subpages'  =>  array(
                array(
                    'location'  => 'support',
                    'index'     => 900,
                    'label' => 'Announcements',
                    'uri'   => $this->di['url']->adminLink('news'),
                    'class' => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/news',           'get_index', array(), get_class($this));
        $app->get('/news/',          'get_index', array(), get_class($this));
        $app->get('/news/index',     'get_index', array(), get_class($this));
        $app->get('/news/index/',    'get_index', array(), get_class($this));
        $app->get('/news/post/:id',  'get_post', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_news_index');
    }
    
    public function get_post(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $post = $api->news_get(array('id'=>$id));
        return $app->render('mod_news_post', array('post'=>$post));
    }
}