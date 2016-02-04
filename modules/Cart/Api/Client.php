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


namespace Fuse\Mod\Cart\Api;

/**
 * Shopping cart management
 */
class Client extends \Api_Abstract
{
    /**
     * Checkout cart which has products
     *
     * @optional int $gateway_id - payment gateway id. Which payment gateway will be used to make payment
     *
     * @return string array
     */
    public function checkout($data)
    {
        $gateway_id =  $this->di['array_get']($data, 'gateway_id');
        $cart = $this->getService()->getSessionCart();
        $client = $this->getIdentity();
        return $this->getService()->checkoutCart($cart, $client, $gateway_id);
    }
}