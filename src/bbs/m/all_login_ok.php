<?php
include_once("_common_guest.php");

global $db_conn;
if (!$db_conn) {
	$db_conn = $connect_db;
}

$re_url = $_POST['re_url'] ?? '';
$mb_mail = trim($_POST['mb_mail']);
$mb_password = trim($_POST['mb_password']);

if (!$mb_mail || !$mb_password) {
	alert(national_language($iw['language'],"a0110","아이디와 비밀번호를 확인하여 주십시오."),"");
}

$sql = "select mb_display, mb_code, mb_password from {$iw['member_table']} where ep_code = ? and mb_mail = ?";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $iw['store'], $mb_mail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($row && password_verify($mb_password, $row['mb_password'])) {
	if ($row['mb_display'] == 0) {
		alert(national_language($iw['language'],"a0108","이메일 인증 후 로그인이 가능합니다."),"");
	}else if($row['mb_display'] == 3) {
		alert(national_language($iw['language'],"a0109","가입승인 후 로그인이 가능합니다."),"");
	}else{
		$login_time = date("Y-m-d H:i:s");
		$sql_update = "update {$iw['member_table']} set mb_login_datetime = ? where ep_code = ? and mb_code = ?";
		$stmt_update = mysqli_prepare($db_conn, $sql_update);
		mysqli_stmt_bind_param($stmt_update, "sss", $login_time, $iw['store'], $row['mb_code']);
		mysqli_stmt_execute($stmt_update);
		mysqli_stmt_close($stmt_update);

		set_cookie("iw_member", $row['mb_code'], time()+3600*24*7);

		goto_url("{$iw['m_path']}/main.php?type=main&ep={$iw['store']}&gp={$iw['group']}");
	}
}else{
	alert(national_language($iw['language'],"a0110","아이디와 비밀번호를 확인하여 주십시오."),"");
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />



