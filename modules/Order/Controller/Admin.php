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


namespace Fuse\Mod\Order\Controller;

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
            'group' => array(
                'index' => 300,
                'location' => 'order',
                'label' => 'Orders',
                'uri' => $this->di['url']->adminLink('order'),
                'class'     => 'orders',
                'sprite_class' => 'dark-sprite-icon sprite-basket',
                ),
            'subpages' => array(
                array(
                    'location'  => 'order',
                    'index' =>  100,
                    'label' => 'Overview',
                    'uri' => $this->di['url']->adminLink('order'),
                    'class'     => '',
                ),
                array(
                    'location'  => 'order',
                    'index' =>  200,
                    'label' => 'Advanced search',
                    'uri' => $this->di['url']->adminLink('order', array('show_filter' => 1)),
                    'class'     => '',
                ),
            ),
        );
    }
    
    public function register(\Fuse_App &$app)
    {
        $app->get('/order',            'get_index', array(), get_class($this));
        $app->get('/order/',           'get_index', array(), get_class($this));
        $app->get('/order/index',      'get_index', array(), get_class($this));
        $app->get('/order/manage/:id', 'get_order', array('id'=>'[0-9]+'), get_class($this));
        $app->post('/order/new', 'get_new', array(), get_class($this));
    }

    public function get_index(\Fuse_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_order_index');
    }

    public function get_new(\Fuse_App $app)
    {
        $api = $this->di['api_admin'];
        $product = $api->product_get(array('id' => $this->di['request']->getPost('product_id')));
        $client = $api->client_get(array('id' => $this->di['request']->getPost('client_id')));
        return $app->render('mod_order_new', array('product' => $product, 'client' => $client));
    }
    
    public function get_order(\Fuse_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $data = array(
            'id'    =>  $id,
        );
        $order = $api->order_get($data);
        $set = array('order'=>$order);
        
        if(isset($order['plugin']) && !empty($order['plugin'])) {
            $set['plugin'] = 'plugin_'.$order['plugin'].'_manage.phtml';
        }
        
        return $app->render('mod_order_manage', $set);
    }
}