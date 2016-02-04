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


namespace Fuse\Mod\Cookieconsent;

use Fuse\InjectionAwareInterface;

class Service implements InjectionAwareInterface
{
    protected $di;

    public function setDi($di)
    {
        $this->di = $di;
    }

    public function getDi()
    {
        return $this->di;
    }

    public function getMessage()
    {
        $config = $this->di['mod_config']('cookieconsent');

        return $this->di['array_get']($config, 'message', 'This website uses cookies. By continuing to use this website, you consent to our use of these cookies.');
    }
}