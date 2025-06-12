<?php
include_once("_common.php");
if (get_cookie("payment_member")!="payment") alert("잘못된 접근입니다!","index.php");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$ps_corporate = trim(sql_real_escape_string($_POST[ps_corporate]));
$ps_domain = trim(sql_real_escape_string($_POST[ps_domain]));
$ps_display = trim(sql_real_escape_string($_POST[ps_display]));

$row = sql_fetch(" select count(*) as cnt from $payment[site_user_table] where ps_domain = '$ps_domain' ");
if ($row[cnt]) {
	alert("도메인이 이미 존재합니다.","");
}else{

	$ps_datetime = date("Y-m-d H:i:s");

	$sql = "insert into $payment[site_user_table] set
			ps_corporate = '$ps_corporate',
			ps_domain = '$ps_domain',
			ps_datetime = '$ps_datetime',
			ps_display = '$ps_display'
			";

	sql_query($sql);

	alert("신규 사이트가 등록되었습니다.","payment_site_list.php");
}
?>