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
 * Cron management 
 */

namespace Fuse\Mod\Cron\Api;

class Admin extends \Api_Abstract
{
    /**
     * Returns cron job information. When it was last executed, where cron job
     * file is located.
     * 
     * @return array
     */
    public function info($data)
    {
        return $this->getService()->getCronInfo();
    }
    
    /**
     * Run cron
     * 
     * @return bool
     */
    public function run($data)
    {
        return $this->getService()->runCrons();
    }
}