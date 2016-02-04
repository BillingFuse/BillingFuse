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


class Model_ServiceLicense extends \RedBean_SimpleModel
{
    private function _decodeJson($j)
    {
        $config = json_decode($j, true);
        return is_array($config) ? $config : array();
    }

    public function getAllowedIps()
    {
        return $this->_decodeJson($this->ips);
    }

    public function getAllowedVersions()
    {
        return $this->_decodeJson($this->versions);
    }

    public function getAllowedHosts()
    {
        return $this->_decodeJson($this->hosts);
    }

    public function getAllowedPaths()
    {
        return $this->_decodeJson($this->paths);
    }

}