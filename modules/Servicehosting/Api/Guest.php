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
class Guest extends \Api_Abstract
{
    /**
     * @param array $data
     * @return array
     * @throws \Fuse_Exception
     */
    public function free_tlds($data = array())
    {
        $required = array(
            'product_id'         => 'Product id is missing',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);

        $product_id = $this->di['array_get']($data, 'product_id', 0);
        $product = $this->di['db']->getExistingModelById('Product', $product_id, 'Product was not found');

        if ($product->type !== \Model_Product::HOSTING){
            throw new \Fuse_Exception('Product type is invalid');
        }

        return $this->getService()->getFreeTlds($product);

    }
}