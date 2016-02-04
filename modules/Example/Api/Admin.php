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
 * Example module Admin API
 * 
 * API can be access only by admins
 */

namespace Fuse\Mod\Example\Api;

class Admin extends \Api_Abstract
{
    /**
     * Return list of example objects
     * 
     * @return string[]
     */
    public function get_something($data)
    {
        $result = array(
            'apple',
            'google',
            'facebook',
        );

        if(isset($data['microsoft'])) {
            $result[] = 'microsoft';
        }
        
        return $result;
    }
}