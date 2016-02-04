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
 * Static Pages management
 */

namespace Fuse\Mod\Page\Api;

class Admin extends \Api_Abstract
{
    /**
     * Return page pairs. Includes module and currently selected client area
     * pages
     *
     * @return array
     */
    public function get_pairs()
    {
        $service = $this->getService();
        return $service->getPairs();
    }
}