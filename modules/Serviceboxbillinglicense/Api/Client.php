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

namespace Fuse\Mod\Servicebillingfuselicense\Api;

/**
 * BillingFuse license management
 */
class Client extends \Api_Abstract
{
    private function _getService($data)
    {
        if (!isset($data['order_id'])) {
            throw new \Fuse_Exception('Order id is required');
        }

        $order = $this->di['db']->findOne('client_order',
            "id=:id
                 AND client_id = :cid
                 AND service_type = 'billingfuselicense'
                ",
            array(':id' => $data['order_id'], ':cid' => $this->getIdentity()->id));

        if (!$order) {
            throw new \Fuse_Exception('BillingFuse license order not found');
        }

        $s = $this->di['db']->findOne('service_billingfuselicense',
            'id=:id AND client_id = :cid',
            array('id' => $order->service_id, 'cid' => $this->getIdentity()->id));
        if (!$s) {
            throw new \Fuse_Exception('Order is not activated');
        }

        return array($order, $s);
    }

    /**
     * Reset license information. Usually used when moving BillingFuse to
     * new server.
     *
     * @param int $order_id - order id
     * @return bool
     */
    public function reset($data)
    {
        list($order, $service) = $this->_getService($data);
        $this->getService()->reset($order, $service, $data);
        $this->di['logger']->info('Reset license information. Order ID #%s', $order->id);

        return true;
    }
}