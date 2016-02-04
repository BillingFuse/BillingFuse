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

$bf_invoice_id = null;
if(isset($_GET['bf_invoice_id'])) {
    $bf_invoice_id = $_GET['bf_invoice_id'];
}
if(isset($_POST['bf_invoice_id'])) {
    $bf_invoice_id = $_POST['bf_invoice_id'];
}

$bf_gateway_id = null;
if(isset($_GET['bf_gateway_id'])) {
    $bf_gateway_id = $_GET['bf_gateway_id'];
}
if(isset($_POST['bf_gateway_id'])) {
    $bf_gateway_id = $_POST['bf_gateway_id'];
}

$ipn = array(
    'skip_validation'       =>  true,
    'bf_invoice_id'         =>  $bf_invoice_id,
    'bf_gateway_id'         =>  $bf_gateway_id,
    'get'                   =>  $_GET,
    'post'                  =>  $_POST,
    'server'                =>  $_SERVER,
    'http_raw_post_data'    =>  file_get_contents("php://input"),
);

try {
    $service = $di['mod_service']('invoice', 'transaction');
    $output = $service->createAndProcess($ipn);
    $res = array('result'=>$output, 'error'=>null);
} catch(Exception $e) {
    $res = array('result'=>null, 'error'=> array('message' => $e->getMessage()));
    $output = false;
}

// redirect to invoice if gateways requires
if(isset($_GET['bf_redirect']) && isset($_GET['bf_invoice_hash'])) {
    $url = $di['url']->link('invoice/'.$_GET['bf_invoice_hash']);
    header("Location: $url");
    exit;
} else {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json; charset=utf-8');
    print json_encode($res);
    exit;
}