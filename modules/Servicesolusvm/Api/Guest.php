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

namespace Fuse\Mod\Servicesolusvm\Api;
/**
 * Solusvm service management
 */
class Guest extends \Api_Abstract
{
    /**
     * Return operating system templates available on solusvm master server
     * @param string $type - virtualization type
     * @return array 
     */
    public function get_templates($data)
    {
        try {
            $type = $this->di['array_get']($data, 'type', 'openvz');
            $templates = $this->getService()->getTemplates($type);
        } catch (\Exception $exc) {
            $templates = array();
            if(BF_DEBUG) error_log($exc);
        }
        
        return $templates;
    }
}