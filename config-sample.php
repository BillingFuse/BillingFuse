<?php
/*
*
* FILE : THE BASE CONFIGURATION FILE FOR BillingFuse
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

return array(

    /**
     * Set BillingFuse license key. Get license key at http://www.billingfuse.com
     */
    'license'     => 'PRO-0000-0000-0000-0000',

    'salt'        => '',

    /**
     * Full URL where BillingFuse is installed with trailing slash
     */
    'url'     => 'http://www.example.com/',

    'admin_area_prefix' =>  '/admin',

    /**
     * Enable or Disable the display of notices
     */
    'debug'     => false,

    /**
     * Enable or Disable search engine friendly urls.
     * Configure .htaccess file before enabling this feature
     * Set to TRUE if using nginx
     */
    'sef_urls'  => false,

    /**
     * Application timezone
     */
    'timezone'    =>  'UTC',

    /**
     * Set BillingFuse locale
     */
    'locale'    =>  'en_US',

    /**
     * Set default date format for localized strings
     * Format information: http://php.net/manual/en/function.strftime.php
     */
    'locale_date_format'    =>  '%A, %d %B %G',

    /**
     * Set default time format for localized strings
     * Format information: http://php.net/manual/en/function.strftime.php
     */
    'locale_time_format'    =>  ' %T',

    /**
     * Set location to store sensitive data
     */
    'path_data'  => dirname(__FILE__) . '/data',

    'path_logs'  => dirname(__FILE__) . '/data/log/application.log',

    'log_to_db'  => true,

    'db'    =>  array(
        /**
         * Database type. Don't change this if in doubt.
         */
        'type'   =>'mysql',

        /**
         * Database hostname. Don't change this if in doubt.
         */
        'host'   =>'127.0.0.1',

        /**
         * The name of the database for BillingFuse
         */
        'name'   =>'BillingFuse',

        /**
         * Database username
         */
        'user'   =>'foo',

        /**
         * Database password
         */
        'password'   =>'foo',
    ),

    'twig'   =>  array(
        'debug'         =>  false,
        'auto_reload'   =>  false,
        'cache'         =>  dirname(__FILE__) . '/data/cache',
    ),

    'api'   =>  array(
        // all requests made to API must have referrer request header with the same url as BillingFuse installation
        'require_referrer_header'   =>  false,

        // empty array will allow all IPs to access API
        'allowed_ips'       =>  array(),

        // Time span for limit in seconds
        'rate_span'         =>  60 * 60,

        // How many requests allowed per time span
        'rate_limit'        =>  1000,
    ),
);