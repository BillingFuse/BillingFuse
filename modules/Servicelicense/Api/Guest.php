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

namespace Fuse\Mod\Servicelicense\Api;
/**
 * Licensing server
 */
class Guest extends \Api_Abstract
{
    /**
     * Check license details callback. Request IP is detected automatically
     * You can pass any other parameters to be validated by license plugin.
     *
     * @param string $license - license key
     * @param string $host - hostname where license is installed
     * @param string $version - software version
     * @param string $path - software install path
     * 
     * @optional string $legacy - deprecated parameter. Returns result in non consistent API result
     * 
     * @return array - bool
     */
    public function check($data)
    {
        return $this->getService()->checkLicenseDetails($data);
    }
}