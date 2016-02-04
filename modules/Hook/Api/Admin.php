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
 * Hooks management module
 */

namespace Fuse\Mod\Hook\Api;

class Admin extends \Api_Abstract
{
    /**
     * Get paginated list of hooks
     *
     * @return array
     */
    public function get_list($data)
    {
        $service = $this->getService();
        list($sql, $params) = $service->getSearchQuery($data);
        $per_page = $this->di['array_get']($data, 'per_page', $this->di['pager']->getPer_page());
        return $this->di['pager']->getSimpleResultSet($sql, $params, $per_page);
    }
    
    /**
     * Invoke hook with params
     * 
     * @param string $event - event name, ie: onEventBeforeInvoiceIsDue
     * @optional array $params - what params are passed to event method $event->getParams()
     * 
     * @return mixed - event return value
     */
    public function call($data)
    {
        if(!isset($data['event']) || empty($data['event'])) {
            error_log('Invoked event call without providing event name');
            return false;
        }
        
        $event = $data['event'];
        $params = $this->di['array_get']($data, 'params', null);
        if($this->di['config']['debug']) {
            try {
                $this->di['logger']->info($event. ': '. var_export($params, 1));
            } catch(\Exception $e) {
                error_log($e->getMessage());
            }
        }

        return $this->di['events_manager']->fire($data);
    }
    
    /**
     * Reinstall and activate all existing hooks from module or all 
     * activated modules. Does not connect already connected event
     * 
     * @optional string $mod - module name to connect hooks
     * 
     * @return bool
     */
    public function batch_connect($data)
    {
        $mod = $this->di['array_get']($data, 'mod', null);
        $service = $this->getService();
        return $service->batchConnect($mod);
    }

}