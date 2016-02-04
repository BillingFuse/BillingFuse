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


namespace Fuse\Mod\Invoice\Controller;

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
        $app->get('/invoice', 'get_invoices', array(), get_class($this));
        $app->post('/invoice', 'get_invoices', array(), get_class($this));
        $app->get('/invoice/:hash', 'get_invoice', array('hash'=>'[a-z0-9]+'), get_class($this));
        $app->post('/invoice/:hash', 'get_invoice', array('hash'=>'[a-z0-9]+'), get_class($this));
        $app->get('/invoice/print/:hash', 'get_invoice_print', array('hash'=>'[a-z0-9]+'), get_class($this));
        $app->post('/invoice/print/:hash', 'get_invoice_print', array('hash'=>'[a-z0-9]+'), get_class($this));
        $app->get('/invoice/banklink/:hash/:id', 'get_banklink', array('id'=>'[0-9]+', 'hash'=>'[a-z0-9]+'), get_class($this));
        $app->get('/invoice/thank-you/:hash', 'get_thankyoupage', array('hash'=>'[a-z0-9]+'), get_class($this));
        $app->get('/invoice/pdf/:hash', 'get_pdf', array('hash'=>'[a-z0-9]+'), get_class($this));
    }

    public function get_invoices(\Fuse_App $app)
    {
        $this->di['is_client_logged'];
        return $app->render('mod_invoice_index');
    }

    public function get_invoice(\Fuse_App $app, $hash)
    {
        $api = $this->di['api_guest'];
        $data = array(
            'hash' => $hash,
        );
        $invoice = $api->invoice_get($data);
        return $app->render('mod_invoice_invoice', array('invoice'=>$invoice));
    }

    public function get_invoice_print(\Fuse_App $app, $hash)
    {
        $api = $this->di['api_guest'];
        $data = array(
            'hash' => $hash,
        );
        $invoice = $api->invoice_get($data);
        return $app->render('mod_invoice_print', array('invoice'=>$invoice));
    }

    public function get_thankyoupage(\Fuse_App $app, $hash)
    {
        $api = $this->di['api_guest'];
        $data = array(
            'hash' => $hash,
        );
        $invoice = $api->invoice_get($data);
        return $app->render('mod_invoice_thankyou', array('invoice'=>$invoice));
    }
    
    public function get_banklink(\Fuse_App $app, $hash, $id)
    {
        $api = $this->di['api_guest'];
        $data = array(
            'subscription'  =>  $this->di['request']->getQuery('subscription'),
            'hash'          =>  $hash,
            'gateway_id'    =>  $id,
            'auto_redirect' =>  true,
        );

        $invoice = $api->invoice_get($data);
        $result = $api->invoice_payment($data);
        return $app->render('mod_invoice_banklink', array('payment'=>$result, 'invoice'=>$invoice));
    }

    public function get_pdf (\Fuse_App $app, $hash)
    {
        $api = $this->di['api_guest'];
        $data = array(
            'hash' => $hash,
        );
        $invoice = $api->invoice_pdf($data);
        return $app->render('mod_invoice_pdf', array('invoice'=>$invoice));
    }
}