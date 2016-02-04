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

class Fuse_Cookie implements \Fuse\InjectionAwareInterface
{
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

    public function delete($key)
    {
        unset($_COOKIE[$key]);
    }

    public function has($key)
    {
        return isset($_COOKIE[$key]);
    }

    public function get($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
    
    public function set($key, $value)
    {
        $_COOKIE[$key] = $value;
    }

    public function reset()
    {
        $_COOKIE = null;
    }
}