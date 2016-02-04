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


namespace Fuse\Mod\Forum\Controller;

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
        $app->get('/forum', 'get_index', array(), get_class($this));
        $app->get('/forum/members-list', 'get_members', array(), get_class($this));
        $app->get('/forum/topics.rss', 'get_rss', array(), get_class($this));
        $app->get('/forum/:forum', 'get_forum', array('forum' => '[a-z0-9-]+'), get_class($this));
        $app->get('/forum/:forum/:topic', 'get_forum_topic', array('forum' => '[a-z0-9-]+', 'topic' => '[a-z0-9-]+'), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        return $app->render('mod_forum_index');
    }

    public function get_rss(\Fuse_App $app)
    {
        header('Content-Type: application/rss+xml;');
        return $app->render('mod_forum_rss');
    }
    
    public function get_forum(\Fuse_App $app, $forum)
    {
        $api = $this->di['api_guest'];
        $data = array(
            'slug'  =>  $forum,
        );
        $f = $api->forum_get($data);
        return $app->render('mod_forum_forum', array('forum'=>$f));
    }

    public function get_forum_topic(\Fuse_App $app, $forum, $topic)
    {
        $api = $this->di['api_guest'];
        $data = array(
            'slug'  =>  $forum,
        );
        $f = $api->forum_get($data);

        $data = array(
            'slug'  =>  $topic,
        );
        $t = $api->forum_get_topic($data);
        return $app->render('mod_forum_topic', array('topic'=>$t, 'forum'=>$f));
    }
    
    public function get_members(\Fuse_App $app)
    {
        return $app->render('mod_forum_members_list', array());
    }
}