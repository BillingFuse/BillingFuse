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


namespace Fuse\Mod\Extension\Api;

/**
 * Extensions
 */
class Guest extends \Api_Abstract
{
    /**
     * Checks if extensions is available
     * 
     * @param string $mod - module name to be checked
     * @return bool
     */
    public function is_on($data)
    {
        $service = $this->getService();
        if(isset($data['mod']) && !empty($data['mod'])) {
            return $service->isExtensionActive('mod', $data['mod']);
        }
        
        if(isset($data['id']) && !empty($data['type'])) {
            return $service->isExtensionActive($data['type'], $data['id']);
        }
        return true;
    }

    /**
     * Return active theme info
     * @return array
     */
    public function theme()
    {
        $systemService = $this->di['mod_service']('theme');
        return $systemService->getThemeConfig(true, null);
    }

    /**
     * Retrieve extension public settings
     *
     * @param string $ext - extension name
     * @throws Fuse_Exception
     * @return array
     */
    public function settings($data)
    {
        if(!isset($data['ext'])) {
            throw new \Fuse_Exception('Parameter ext is missing');
        }
        $service = $this->getService();
        $config = $service->getConfig($data['ext']);
        return (isset($config['public']) && is_array($config['public'])) ? $config['public'] : array();
    }

    /**
     * Retrieve list of available languages
     * @return array
     */
    public function languages()
    {
        $service = $this->di['mod_service']('system');
        return $service->getLanguages();
    }
}