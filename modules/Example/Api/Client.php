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
 * All public methods in this class are exposed to client using API.
 * Always think what kind of information you are exposing. 
 */

namespace Fuse\Mod\Example\Api;

class Client extends \Api_Abstract
{
    /**
     * From client API you can call any other module API
     * 
     * This method will collect data from all APIs and merge
     * into one result.
     * 
     * Be careful not to expose sensitive data from Admin API.
     */
    public function get_info($data)
    {
        // call custom event hook. All active modules will be notified
        $this->di['events_manager']->fire(array('event'=>'onAfterClientCalledExampleModule', 'params'=>array('key'=>'value')));
        
        // Log message
        $this->di['logger']->info('Log message to log file');

        $systemService = $this->di['mod_service']('System');
        $clientService = $this->di['mod_service']('Client');

        $type = $this->di['array_get']($data, 'type', 'info');

        return array(
            'data'      =>  $data,
            'version'   =>  $systemService->getVersion(),
            'profile'   =>  $clientService->toApiArray($this->di['loggedin_client']),
            'messages'  =>  $systemService->getMessages($type)
        );
    }
}