<?php
include_once("_common.php");

global $db_conn;
if (!$db_conn) {
	$db_conn = $connect_db;
}

$mb_mail = trim($_POST['mb_mail']);
$mb_password = trim($_POST['mb_password']);

if (!$mb_mail || !$mb_password) {
	alert("아이디와 비밀번호를 모두 입력해주십시오.", "");
}

$sql = "select * from {$payment['site_user_table']} where ps_domain = ? and ps_display = 9";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $mb_mail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($row && password_verify($mb_password, $row['ps_corporate'])) {
	set_cookie("p_member", $row['ps_no'], time()+3600*8);
	goto_url("pay_list.php?type={$payment['type']}&ep={$payment['store']}&gp={$payment['group']}");
}else{
	alert("아이디와 비밀번호를 확인하여 주십시오","");
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />