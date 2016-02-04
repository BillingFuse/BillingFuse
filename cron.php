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

require_once dirname(__FILE__) . '/load.php';
$di = include dirname(__FILE__) . '/di.php';
$di['translate']();

$interval = isset($argv[1]) ? $argv[1] : null;
$service = $di['mod_service']('cron');
$service->runCrons($interval);

unset($service, $interval, $di);