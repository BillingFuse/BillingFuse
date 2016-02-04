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

class Model_Transaction extends RedBean_SimpleModel
{
    const STATUS_RECEIVED        = 'received';
    const STATUS_APPROVED        = 'approved';
    const STATUS_PROCESSED       = 'processed';
    const STATUS_ERROR           = 'error';
}