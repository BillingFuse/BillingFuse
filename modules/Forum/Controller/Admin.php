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
            'subpages'=>array(
                array(
                    'location'  => 'support',
                    'index'     => 700,
                    'label' => 'Forum',
                    'uri'   => $this->di['url']->adminLink('forum'),
                    'class' => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/forum', 'get_index', array(), get_class($this));
        $app->get('/forum/profile/:id', 'get_profile', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/forum/:id', 'get_forum', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/forum/topic/:id', 'get_topic', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_forum_index');
    }
    
    public function get_forum(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $forum = $api->forum_get(array('id'=>$id));
        return $app->render('mod_forum_forum', array('forum'=>$forum));
    }
    
    public function get_topic(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $topic = $api->forum_topic_get(array('id'=>$id));
        return $app->render('mod_forum_topic', array('topic'=>$topic));
    }
    
    public function get_profile(\Fuse_App $app, $id)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_forum_profile', array('client_id'=>$id));
    }
}