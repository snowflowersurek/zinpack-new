<?php
$payment_path = ".."; // common.php 의 상대 경로
include_once("$payment_path/_common.php");

include_once("{$payment['path']}/install/db_site_user_set.php");
include_once("{$payment['path']}/install/db_lgd_request_set.php");
include_once("{$payment['path']}/install/db_lgd_response_set.php");
include_once("{$payment['path']}/install/db_cancel_request_set.php");
include_once("{$payment['path']}/install/db_cancel_response_set.php");
?>



