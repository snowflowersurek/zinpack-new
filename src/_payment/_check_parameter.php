<?
if (!defined("_PAYMENT_")) exit; // 개별 페이지 접근 불가
if(!$_GET['type']) exit;
$payment['type'] = $_GET['type'];
if(!$_GET['ep']) exit;
$payment['store'] = $_GET['ep'];
if(!$_GET['gp']) exit;
$payment['group'] = $_GET['gp'];
?>