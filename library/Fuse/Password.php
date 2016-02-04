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

class Fuse_Password {

    private $algo = PASSWORD_DEFAULT;
    private $options = array();

    public function setAlgo ($algo)
    {
        $this->algo = $algo;
    }

    public function getAlgo()
    {
        return $this->algo;
    }

    public function setOptions($options = array())
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function hashIt($password)
    {
        return password_hash($password, $this->algo, $this->options);
    }

    public function verify ($password, $hash)
    {
        return password_verify((string) $password, $hash);
    }

    public function needsRehash($hash)
    {
        return password_needs_rehash($hash, $this->algo, $this->options);
    }
}