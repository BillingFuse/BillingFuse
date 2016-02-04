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
class Admin extends \Api_Abstract
{
    private function _getService($data)
    {
        if(!isset($data['order_id'])) {
            throw new \Fuse_Exception('Order id is required');
        }
        
        $order = $this->di['db']->findOne('client_order',
                "id=:id 
                 AND service_type = 'billingfuselicense'
                ", 
                array('id'=>$data['order_id']));
        
        if(!$order) {
            throw new \Fuse_Exception('BillingFuse license order not found');
        }
        
        $s = $this->di['db']->findOne('service_billingfuselicense',
                'id=:id',
                array('id'=>$order->service_id));
        if(!$s) {
            throw new \Fuse_Exception('Order is not activated');
        }
        return array($order, $s);
    }
    
    /**
     * Update module configuration
     * 
     * @param int $order_id - order id
     * @return bool 
     */
    public function config_update($data)
    {
        $this->getService()->setModuleConfig($data);
        $this->di['logger']->info('Updated configuration information.');
        return true;
    }
    
    /**
     * Get module configuration
     * 
     * @param int $order_id - order id
     * @return bool 
     */
    public function config_get($data)
    {
        return $this->getService()->getModuleConfig($data);
    }
    
    /**
     * Get detailed license order info 
     * 
     * @param int $order_id - order id
     * 
     * @return array
     */
    public function order_info($data)
    {
        list(, $service) = $this->_getService($data);
        return $this->getService()->licenseDetails($service);
    }
    
    /**
     * Reset license information. Usually used when moving BillingFuse to
     * new server.
     * 
     * @param int $order_id - order id
     * 
     * @return bool
     */
    public function order_reset($data)
    {
        list(, $service) = $this->_getService($data);
        $this->getService()->licenseReset($service);
        return true;
    }
    
    /**
     * Convenience method to become partner. After you become BillingFuse
     * partner you are able to sell licenses.
     * 
     * @return bool
     */
    public function become_partner()
    {
        return $this->getService()->becomePartner();
    }
    
    /**
     * Test connection to BillingFuse server. Used to test your configuration.
     * 
     * @return bool
     */
    public function test_connection()
    {
        $this->getService()->testApiConnection();
        return true;
    }
    
    /**
     * Update existing order service
     * This method used to change service data if order setup fails 
     * or have changed on remote server
     * 
     * @param int $order_id - order id
     * 
     * @return boolean 
     */
    public function update($data)
    {
        list(, $service) = $this->_getService($data);
        
        $service->oid = $this->di['array_get']($data, 'oid', $service->oid);
        $service->updated_at = date('Y-m-d H:i:s');
        $this->di['db']->store($service);
        
        $this->di['logger']->info('Updated BillingFuse license %s details', $service->id);
        return true;
    }
}