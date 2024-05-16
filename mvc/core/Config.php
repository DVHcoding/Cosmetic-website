<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$vnp_TmnCode = "YRJHXDMJ"; //Website ID in VNPAY System
$vnp_HashSecret = "MYRDNFUFAHKUUEYOHYFODMTSVZQNLEFN"; //Secret key
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/public/view/client/vnpay_php/vnpay_return.php";
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
//Config input format
//Expire
$startTime = date("YmdHis");
$expire = date('YmdHis', strtotime('+15 minutes', strtotime(date("YmdHis"))));


// APP ROOT
define('APP_ROOT', dirname(dirname(__FILE__)));

// URL ROOT (Liens dynamiques)
if (strpos($_SERVER['HTTP_HOST'], "localhost") !== false || strpos($_SERVER['HTTP_HOST'], "127.0.0.1") !== false) {
    define('URL_ROOT', "http://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php", "", $_SERVER['SCRIPT_NAME']));
} else {
    define('URL_ROOT', "https://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php", "", $_SERVER['SCRIPT_NAME']));
}
// Nom du site
define('SITE_NAME', str_replace("/public/index.php", "", $_SERVER['SCRIPT_NAME']));