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

namespace Fuse\Mod\Servicehosting\Api;
/**
 * Hosting service management 
 */
class Client extends \Api_Abstract
{
    /**
     * Change hosting account username
     * 
     * @param int $order_id - Hosting account order id
     * @param string $username - New username
     * 
     * @return boolean 
     */
    public function change_username($data)
    {
        list($order, $s) = $this->_getService($data);
        return $this->getService()->changeAccountUsername($order, $s, $data);
    }

    /**
     * Change hosting account domain
     * 
     * @param int $order_id - Hosting account order id
     * @param string $password - New second level domain name, ie: mydomain
     * @param string $password_confirm - New top level domain, ie: .com
     * 
     * @return boolean 
     */
    public function change_domain($data)
    {
        list($order, $s) = $this->_getService($data);
        return $this->getService()->changeAccountDomain($order, $s, $data);
    }

    /**
     * Change hosting account password
     * 
     * @param int $order_id - Hosting account order id
     * @param string $password          - New account password
     * @param string $password_confirm  - Repeat new password
     * 
     * @return boolean 
     */
    public function change_password($data)
    {
        list($order, $s) = $this->_getService($data);
        return $this->getService()->changeAccountPassword($order, $s, $data);
    }

    /**
     * Get hosting plans pairs. Usually for select box
     * @return array
     */
    public function hp_get_pairs($data)
    {
        return $this->getService()->getHpPairs();
    }

    public function _getService($data)
    {
        if(!isset($data['order_id'])) {
            throw new \Fuse_Exception('Order id is required');
        }
        $identity = $this->getIdentity();
        $order = $this->di['db']->findOne('ClientOrder', 'id = ? and client_id = ?', array($data['order_id'], $identity->id));
        if(!$order instanceof \Model_ClientOrder ) {
            throw new \Fuse_Exception('Order not found');
        }

        $orderSerivce = $this->di['mod_service']('order');
        $s = $orderSerivce->getOrderService($order);
        if(!$s instanceof \Model_ServiceHosting) {
            throw new \Fuse_Exception('Order is not activated');
        }

        return array($order, $s);
    }
}