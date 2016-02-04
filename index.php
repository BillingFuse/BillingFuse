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
$url = $di['request']->getQuery('_url');
$admin_prefix = $di['config']['admin_area_prefix'];
if (strncasecmp($url,$admin_prefix, strlen($admin_prefix)) === 0) {
    $url = str_replace($admin_prefix, '', preg_replace('/\?.+/', '', $url));
    $app = new Fuse_AppAdmin();
    $app->setUrl($url);
} else {
    $app = new Fuse_AppClient();
    $app->setUrl($url);
}
$di['translate']();
$app->setDi($di);
print $app->run();
exit(); // disable auto_append_file directive