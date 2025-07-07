<?php
include_once("_common.php");

if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

$ps_no = $_POST['ps_no'];
$ps_corporate = trim(sql_real_escape_string($_POST['ps_corporate']));
$ps_domain = trim(sql_real_escape_string($_POST['ps_domain']));
$ps_display = trim(sql_real_escape_string($_POST['ps_display']));

$row = sql_fetch(" select count(*) as cnt from $payment[site_user_table] where ps_domain = '$ps_domain' and ps_no <> '$ps_no' ");
if ($row['cnt']) {
	alert("이미 등록된 도메인입니다.");
}

$sql = "update $payment[site_user_table] set
		ps_corporate = '$ps_corporate',
		ps_domain = '$ps_domain',
		ps_display = '$ps_display'
		where ps_no = '$ps_no'
		";
sql_query($sql);

goto_url("payment_site_list.php");
?>



