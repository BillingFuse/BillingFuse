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


class Fuse_LogDb
{
    /**
     * $service - module service class
     *
     * @var object $service
     */
    protected $service = null;

    /**
     * Class constructor
     *
     * @param object $service - module service class object
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    public function write($event)
    {
        try {
            if(method_exists($this->service, 'logEvent')) {
                $this->service->logEvent($event);
            }
        } catch(Exception $e) {
            error_log($e);
        }
    }

}
