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
 * Spam cheking module management 
 */
namespace Fuse\Mod\Spamchecker\Api;
class Guest extends \Api_Abstract
{
    /**
     * Returns recaptcha public key
     * 
     * @return string
     */
    public function recaptcha($data)
    {
        $config = $this->di['mod_config']('Spamchecker');
        $result = array(
            'publickey' =>  $this->di['array_get']($config, 'captcha_recaptcha_publickey', null),
            'enabled'   =>  $this->di['array_get']($config, 'captcha_enabled', false),
            'version'   =>  $this->di['array_get']($config, 'captcha_version', null),
        );
        return $result;
    }
}