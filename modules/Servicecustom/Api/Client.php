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

namespace Fuse\Mod\Servicecustom\Api;

/**
 * Custom product management
 */
class Client extends \Api_Abstract
{
    /**
     * Universal method to call method from plugin
     * Pass any other params and they will be passed to plugin
     *
     * @param int $order_id - ID of the order
     *
     * @throws Fuse_Exception
     */
    public function __call($name, $arguments)
    {
        if (!isset($arguments[0])) {
            throw new \Fuse_Exception('API call is missing arguments', null, 7103);
        }

        $data = $arguments[0];

        if (!isset($data['order_id'])) {
            throw new \Fuse_Exception('Order id is required');
        }
        $model = $this->getService()->getServiceCustomByOrderId($data['order_id']);

        return $this->getService()->customCall($model, $name, $data);
    }
}