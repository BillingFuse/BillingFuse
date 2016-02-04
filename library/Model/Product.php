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


class Model_Product extends \RedBean_SimpleModel implements \Fuse\InjectionAwareInterface
{
    const STATUS_ENABLED    = 'enabled';
    const STATUS_DISABLED   = 'disabled';

    const CUSTOM            = 'custom';
    const LICENSE           = 'license';
    const ADDON             = 'addon';
    const DOMAIN            = 'domain';
    const DOWNLOADABLE      = 'downloadable';
    const HOSTING           = 'hosting';
    const MEMBERSHIP        = 'membership';
    const VPS               = 'vps';

    const SETUP_AFTER_ORDER     = 'after_order';
    const SETUP_AFTER_PAYMENT   = 'after_payment';
    const SETUP_MANUAL          = 'manual';

    protected $di;

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    public function getTable()
    {
        $tableName = 'Model_Product'. ucfirst($this->type). 'Table';
        if(!class_exists($tableName)) {
            $tableName = 'Model_ProductTable';
        }
        $productTable = new $tableName;
        $productTable->setDi($this->di);
        return $productTable;
    }

    public function getService()
    {
        return $this->di['mod_service']('service'.$this->type);
    }
}