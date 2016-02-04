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

namespace Fuse\Mod\Redirect\Controller;

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
        $app->get('/me', 'get_profile', array(), '\Fuse\Mod\Client\Controller\Client');
        $app->get('/balance', 'get_balance', array(), '\Fuse\Mod\Client\Controller\Client');
        $app->get('/reset-password-confirm/:hash', 'get_reset_password_confirm', array('hash'=>'[a-z0-9]+'), '\Fuse\Mod\Client\Controller\Client');
        $app->get('/emails', 'get_emails', array(), '\Fuse\Mod\Email\Controller\Client');
        $app->get('/banklink/:hash/:id', 'get_banklink', array('id'=>'[0-9]+', 'hash'=>'[a-z0-9]+'), '\Fuse\Mod\Invoice\Controller\Client');
        $app->get('/blog', 'get_news', array(), '\Fuse\Mod\News\Controller\Client');
        $app->get('/blog/:slug', 'get_news_item', array('slug' => '[a-z0-9-]+'), '\Fuse\Mod\News\Controller\Client');
        $app->get('/service', 'get_orders', array(), '\Fuse\Mod\Order\Controller\Client');
        $app->get('/service/manage/:id', 'get_order', array('id'=>'[0-9]+'), '\Fuse\Mod\Order\Controller\Client');
		$app->get('/contact-us', 'get_contact_us', array(), '\Fuse\Mod\Support\Controller\Client');
        $app->get('/contact-us/conversation/:hash', 'get_contact_us_conversation', array('hash'=>'[a-z0-9]+'), '\Fuse\Mod\Support\Controller\Client');
        
        $service = $this->di['mod_service']('redirect');
        $redirects = $service->getRedirects();
        foreach($redirects as $redirect) {
            $app->get('/'.$redirect['path'],             'do_redirect', array(), get_class($this));
        }
    }

    public function do_redirect(\Fuse_App $app)
    {
        $service = $this->di['mod_service']('redirect');
        $target = $service->getRedirectByPath($app->uri);
        Header( "HTTP/1.1 301 Moved Permanently" ); 
        Header( "Location: ".$target );
        exit;
    }
}