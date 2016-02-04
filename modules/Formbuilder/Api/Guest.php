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
 * Custom forms
 */

namespace Fuse\Mod\Formbuilder\Api;

class Guest extends \Api_Abstract
{
    /**
     * Get custom order form details for product
     * 
     * @param int $product_id - Product id
     * 
     * @return array
     * @throws Fuse_Exception 
     */
    public function get($data)
    {
        $required = array(
            'id' => 'Form id was not passed',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
       
        $service = $this->getService();
        return $service->getForm($data['id']);
    }
}