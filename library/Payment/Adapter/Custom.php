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

class Payment_Adapter_Custom
{
    private $config = array();
    protected $di;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param Fuse_Di $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    public static function getConfig()
    {
        return array(
            'can_load_in_iframe'   =>  true,
            'supports_one_time_payments'   =>  true,
            'supports_subscriptions'       =>  true,
            'description'     =>  'Custom payment gateway allows you to give instructions how can your client pay invoice. All system, client, order and invoice details can be printed. HTML and JavaScript code is supported.',
            'form'  => array(
                'single' => array('textarea', array(
                            'label' => 'Enter your text for single payment information',
                    ),
                ),
                'recurrent' => array('textarea', array(
                            'label' => 'Enter your text for subscription information',
                    ),
                ),
            ),
        );
    }

    /**
     * Generate payment text
     * 
     * @param Api_Admin $api_admin
     * @param int $invoice_id
     * @param bool $subscription
     * 
     * 
     * @return string - html form with auto submit javascript
     */
    public function getHtml($api_admin, $invoice_id, $subscription)
    {
        $invoiceModel = $this->di['db']->load('Invoice', $invoice_id);
        $invoiceService = $this->di['mod_service']("Invoice");
        $invoice = $invoiceService->toApiArray($invoiceModel, true);

        $vars = array(
            'client'    =>  $invoice['buyer'],
            'invoice'   =>  $invoice,
            '_tpl'      =>  ($subscription) ? $this->config['recurrent'] : $this->config['single'],
        );
        $systemService = $this->di['mod_service']('System');
        return $systemService->renderString($vars['_tpl'], true, $vars);
    }

    public function process($tx)
    {
        //do processing
        return true;
    }
}