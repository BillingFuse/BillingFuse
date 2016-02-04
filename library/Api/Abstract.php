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

use Fuse\InjectionAwareInterface;

class Api_Abstract implements InjectionAwareInterface
{
    /**
     * @var string - request ip
     */
    protected $ip           = null;

    /**
     * @var \Fuse_Mod
     */
    protected $mod          = null;

    /**
     * @var \Fuse\Mod\X\Service
     */
    protected $service      = null;

    /**
     * @var Model_Admin | Model_Client | Model_Guest
     */
    protected $identity     = null;

    /**
     * @var \Fuse_Di
     */
    protected $di           = null;

    /**
     * @param \Fuse_Di $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return \Fuse_Di
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param null $mod
     */
    public function setMod($mod)
    {
        $this->mod = $mod;
    }

    /**
     * @return Fuse_Mod
     */
    public function getMod()
    {
        if(!$this->mod) {
            throw new Fuse_Exception('Mod object is not set for the service');
        }
        return $this->mod;
    }

    /**
     * @param null $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return Model_Admin
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param null $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return null
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param null $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @deprecated use DI
     */
    public function getApiAdmin()
    {
        if($this->identity instanceof \Model_Admin) {
            return $this->di['api_admin'];
        }
        return $this->di['api_system'];
    }

    /**
     * @deprecated use DI
     */
    public function getApiGuest()
    {
        return $this->di['api_guest'];
    }

    /**
     * @deprecated use DI
     */
    public function getApiClient()
    {
        return $this->di['api_client'];
    }
}