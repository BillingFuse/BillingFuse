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


class Model_ClientOrder extends RedBean_SimpleModel
{
	const STATUS_PENDING_SETUP = "pending_setup";
	const STATUS_FAILED_SETUP = "failed_setup";
	const STATUS_ACTIVE = "active";
	const STATUS_CANCELED = "canceled";
	const STATUS_SUSPENDED = "suspended";

    const ACTION_CREATE     = 'create';
    const ACTION_ACTIVATE   = 'activate';
    const ACTION_RENEW      = 'renew';
    const ACTION_SUSPEND    = 'suspend';
    const ACTION_UNSUSPEND  = 'unsuspend';
    const ACTION_CANCEL     = 'cancel';
    const ACTION_UNCANCEL   = 'uncancel';
    const ACTION_DELETE     = 'delete';
}