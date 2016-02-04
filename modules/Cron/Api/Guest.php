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
 * Cron checker
 */

namespace Fuse\Mod\Cron\Api;

class Guest extends \Api_Abstract
{
    /**
     * Run cron if is late and web based cron is enabled
     *
     * @return bool
     */
    public function check()
    {
        return false;
    }

    /**
     * Get cron settings
     *
     * @return array
     */
    public function settings()
    {
        return $this->getMod()->getConfig();
    }

    /**
     * Tells if cron is late
     *
     * @return bool
     */
    public function is_late()
    {
        return $this->getService()->isLate();
    }
}