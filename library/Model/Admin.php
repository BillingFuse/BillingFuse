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


class Model_Admin extends \RedBean_SimpleModel
{
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_CRON = 'cron';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public function getFullName()
    {
        return $this->name;
    }

    public function getStatus($status = '')
    {
        $statusArray = array(
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE
        );
        if (in_array($status, $statusArray)){
            return strtolower($status);
        }
        return self::STATUS_INACTIVE;
    }
}