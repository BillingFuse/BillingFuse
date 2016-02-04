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

abstract class Registrar_AdapterAbstract
{
    protected $_log = null;

    /**
     * Are we in test mode ?
     *
     * @var boolean
     */
    protected $_testMode = false;

    /**
     * Return array with configuration
     * Must be overriden in adapter class
     * @return array
     */
    public static function getConfig()
    {
        throw new Registrar_Exception('Domain registrar class did not implement configuration options method', 749);
    }

    /**
     * Return array of TLDs current Registar is capable to register
     * If function returns empty array, this registrar can register any TLD
     * @return array
     */
    abstract public function getTlds();

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function isDomainAvailable(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function isDomainCanBeTransfered(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function modifyNs(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function modifyContact(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function transferDomain(Registrar_Domain $domain);

    /**
     * Should return details of registered domain
     * If domain is not registered should throw Registrar_Exception
     * @return Registrar_Domain
     * @throws Registrar_Exception
     */
    abstract public function getDomainDetails(Registrar_Domain $domain);

    /**
     * Should return domain transfer code
     *
     * @return string
     * @throws Registrar_Exception
     */
    abstract public function getEpp(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function registerDomain(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function renewDomain(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function deleteDomain(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function enablePrivacyProtection(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function disablePrivacyProtection(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function lock(Registrar_Domain $domain);

    /**
     * @return bool
     * @throws Registrar_Exception
     */
    abstract public function unlock(Registrar_Domain $domain);

    /**
     * Set Log
     *
     * @param Fuse_Log $log
     * @return Registrar_AdapterAbstract
     */
    public function setLog(Fuse_Log $log)
    {
        $this->_log = $log;
        return $this;
    }

    public function getLog()
    {
        $log = $this->_log;
        if(!$log instanceof Fuse_Log) {
            $log = new Fuse_Log();
            $log->addWriter(new Fuse_LogDb('Model_ActivitySystem'));
        }
        return $log;
    }

    public function enableTestMode()
    {
        $this->_testMode = true;
        return $this;
    }
}
