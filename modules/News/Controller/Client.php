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
        $app->get('/news', 'get_news', array(), get_class($this));
        $app->get('/news/:slug', 'get_news_item', array('slug' => '[a-z0-9-]+'), get_class($this));
    }

    public function get_news(\Fuse_App $app)
    {
        return $app->render('mod_news_index');
    }

    public function get_news_item(\Fuse_App $app, $slug)
    {
        $post = $this->di['api_guest']->news_get(array('slug'=>$slug));
        return $app->render('mod_news_post', array('post'=>$post));
    }

}