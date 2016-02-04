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

class Model_InvoiceItem extends RedBean_SimpleModel
{
    const TYPE_DEPOSIT  = 'deposit'; // this type of item can not be charged with credits
    const TYPE_CUSTOM   = 'custom';
    const TYPE_ORDER    = 'order';
    const TYPE_HOOK_CALL= 'hook_call';
    
    const TASK_VOID     = 'void';
    const TASK_ACTIVATE = 'activate';
    const TASK_RENEW    = 'renew';

    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_PENDING_SETUP = 'pending_setup';
    const STATUS_EXECUTED = 'executed';
}