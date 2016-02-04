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


final class Fuse_Version
{
    const VERSION = '4.20';

    const TYPE_FREE = 'free';
    const TYPE_PRO  = 'pro';

    /**
     * Compare the specified BillingFuse version string $version
     * with the current Fuse_Version::VERSION of BillingFuse.
     *
     * @param  string  $version  A version string (e.g. "0.7.1").
     * @return integer           -1 if the $version is older,
     *                           0 if they are the same,
     *                           and +1 if $version is newer.
     *
     */
    public static function compareVersion($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
        return version_compare($version, strtolower(self::VERSION));
    }
}
