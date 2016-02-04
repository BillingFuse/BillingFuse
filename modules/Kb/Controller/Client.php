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
        $app->get('/kb', 'get_kb', array(), get_class($this));
        $app->get('/kb/:category', 'get_kb_category', array('category' => '[a-z0-9-]+'), get_class($this));
        $app->get('/kb/:category/:slug', 'get_kb_article', array('category' => '[a-z0-9-]+', 'slug' => '[a-z0-9-]+'), get_class($this));
    }

    public function get_kb(\Fuse_App $app)
    {
        return $app->render('mod_kb_index');
    }

    public function get_kb_category(\Fuse_App $app, $category)
    {
        $api = $this->di['api_guest'];
        $data = array('slug'=>$category);
        $model = $api->kb_category_get($data);
        return $app->render('mod_kb_category', array('category'=>$model));
    }

    public function get_kb_article(\Fuse_App $app, $category, $slug)
    {
        $api = $this->di['api_guest'];
        $data = array('slug'=>$slug);
        $article = $api->kb_article_get($data);
        return $app->render('mod_kb_article', array('article'=>$article));
    }
}