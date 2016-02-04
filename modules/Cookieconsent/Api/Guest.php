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
 * Cookies consent notification bar.
 *
 * Show cookie consent message to comply with European Cookie Law
 *
 */

namespace Fuse\Mod\Cookieconsent\Api;

class Guest extends \Api_Abstract
{
    /**
     * Get message which should be shown in notification bar
     *
     * @return boolean
     * @throws \Fuse_Exception
     */
    public function message()
    {
        return $this->getService()->getMessage();
    }
}