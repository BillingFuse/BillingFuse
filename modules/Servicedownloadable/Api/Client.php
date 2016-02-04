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

namespace Fuse\Mod\Servicedownloadable\Api;
/**
 * Downloadable service management
 */
class Client extends \Api_Abstract
{
    /**
     * Use GET to call this method. Sends file attached to order.
     * Sends file as attachment.
     * 
     * @param int $order_id - downloadable service order id
     * @return bool
     */
    public function send_file($data)
    {
        if(!isset($data['order_id'])) {
            throw new \Fuse_Exception('Order id is required');
        }
        $identity = $this->getIdentity();
        $order = $this->di['db']->findOne('ClientOrder', 'id = :id AND client_id = :client_id', array(':id' => $data['order_id'], ':client_id' => $identity->id));
        if(!$order instanceof \Model_ClientOrder ) {
            throw new \Fuse_Exception('Order not found');
        }

        $orderService = $this->di['mod_service']('order');
        $s = $orderService->getOrderService($order);
        if(!$s instanceof \Model_ServiceDownloadable) {
            throw new \Fuse_Exception('Order is not activated');
        }

        $service = $this->getService();
        return (bool) $service->sendFile($s);
    }
}