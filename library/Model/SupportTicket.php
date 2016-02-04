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


class Model_SupportTicket extends RedBean_SimpleModel
{
    const OPENED = 'open';
    const ONHOLD = 'on_hold';
    const CLOSED = 'closed';

    const REL_TYPE_ORDER   = 'order';

    const REL_STATUS_PENDING        = 'pending';
    const REL_STATUS_COMPLETE       = 'complete';
    
    const REL_TASK_CANCEL   = 'cancel';
    const REL_TASK_UPGRADE  = 'upgrade';
}