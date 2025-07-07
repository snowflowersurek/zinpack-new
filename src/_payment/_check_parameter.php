<?php
if (!defined("_PAYMENT_")) exit; // 개별 페이지 접근 불가
if(empty($_GET['type'])) exit;
$payment['type'] = $_GET['type'];
if(empty($_GET['ep'])) exit;
$payment['store'] = $_GET['ep'];
if(empty($_GET['gp'])) exit;
$payment['group'] = $_GET['gp'];
?>



