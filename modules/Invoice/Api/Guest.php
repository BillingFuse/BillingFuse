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

/**
 * Invoice processing
 */

namespace Fuse\Mod\Invoice\Api;

class Guest extends \Api_Abstract
{
    /**
     * Get invoice details
     *
     * @param string $hash - invoice hash
     * @return array
     * @throws Exception
     */
    public function get($data)
    {
        $required = array(
            'hash' => 'Invoice hash not passed',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $model = $this->di['db']->findOne('Invoice', 'hash = :hash', array('hash' => $data['hash']));
        if (!$model) {
            throw new \Fuse_Exception('Invoice was not found');
        }

        return $this->getService()->toApiArray($model, true, $this->getIdentity());
    }

    /**
     * Update Invoice details. Only unpaid invoice details can be updated.
     *
     * @param string $hash - invoice hash
     *
     * @optional int $gateway_id - selected payment gateway id
     *
     * @return bool
     * @throws Exception
     */
    public function update($data)
    {
        $required = array(
            'hash' => 'Invoice hash not passed',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $invoice = $this->di['db']->findOne('Invoice', 'hash = :hash', array('hash' => $data['hash']));
        if (!$invoice) {
            throw new \Fuse_Exception('Invoice was not found');
        }
        if ($invoice->status == 'paid') {
            throw new \Fuse_Exception('Paid Invoice can not be modified');
        }

        $updateParams               = array();
        $updateParams['gateway_id'] = $this->di['array_get']($data, 'gateway_id', null);

        return $this->getService()->updateInvoice($invoice, $updateParams);
    }

    /**
     * Get list of available payment gateways to pay for invoices
     *
     * @optional string $format - if format is "pairs" then id=>name values are returned
     *
     * @return array
     */
    public function gateways($data)
    {
        $gatewayService = $this->di['mod_service']('Invoice', 'PayGateway');

        return $gatewayService->getActive($data);
    }

    /**
     * Process invoice for selected gateway. Returned result can be processed
     * to redirect or to show required information. Returned result depends
     * on payment gateway.
     *
     * Tries to detect if invoice can be subscribed and if payment gateway supports subscriptions
     * uses subscription payment.
     *
     * @param string $hash - invoice hash
     * @param int $gateway_id - payment gateway id
     *
     * @optional bool $auto_redirect - should payment adapter automatically redirect client or just print pay now button
     *
     * @return array
     * @throws Exception
     * @throws LogicException
     */
    public function payment($data)
    {
        if (!isset($data['hash'])) {
            throw new \Fuse_Exception('Invoice hash not passed. Missing param hash', null, 810);
        }

        if (!isset($data['gateway_id'])) {
            throw new \Fuse_Exception('Payment method not found. Missing param gateway_id', null, 811);
        }

        return $this->getService()->processInvoice($data);
    }


    /**
     * Generates PDF for given invoice
     *
     * @param string $hash - invoice hash
     *
     *
     *
     * @throws Exception
     */
    public function pdf($data)
    {
        $required = array(
            'hash' => 'Invoice hash is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        return $this->getService()->generatePDF($data['hash'], $this->getIdentity());
    }
}