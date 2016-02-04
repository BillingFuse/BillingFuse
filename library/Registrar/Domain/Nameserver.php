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

class Registrar_Domain_Nameserver
{
    private $host = null;
    private $ip = null;

    public function setHost($param)
    {
        $this->host = $param;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setIp($param)
    {
        $this->ip = $param;
        return $this;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function __toString() {
        $c = '';
        $c .= sprintf("Host: %s", $this->getHost()).PHP_EOL;
        $c .= sprintf("Ip: %s", $this->getIp()).PHP_EOL;
        return $c;
    }
}