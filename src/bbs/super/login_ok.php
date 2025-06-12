<?php
include_once("_common.php");

global $db_conn;
if (!$db_conn) {
	// _common.php 에서 $connect_db 변수에 할당된 연결을 사용합니다.
	$db_conn = $connect_db;
}

$mb_mail = trim($_POST['mb_mail']);
$mb_password = trim($_POST['mb_password']);

if (!$mb_mail || !$mb_password) {
	alert("아이디와 비밀번호를 모두 입력해주십시오.", "");
}

$row = null;
$sql = "SELECT mb_code, mb_password FROM {$iw['member_table']} WHERE ep_code = ? AND mb_mail = ? AND mb_display = 9";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $iw['store'], $mb_mail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// 비밀번호 검증 로직을 오래된 PASSWORD() 함수 방식과 호환되도록 수정
$sql_password = "SELECT PASSWORD(?) as pass";
$stmt_password = mysqli_prepare($db_conn, $sql_password);
mysqli_stmt_bind_param($stmt_password, "s", $mb_password);
mysqli_stmt_execute($stmt_password);
$result_password = mysqli_stmt_get_result($stmt_password);
$row_password = mysqli_fetch_assoc($result_password);
mysqli_stmt_close($stmt_password);
$encrypted_password = $row_password['pass'];

if ($row && $row['mb_password'] === $encrypted_password) {
	// 로그인 성공
	set_cookie("iw_member", $row['mb_code'], time()+3600*8);
	goto_url("{$iw['super_path']}/main.php?type=dashboard&ep={$iw['store']}&gp={$iw['group']}");
} else {
	// 로그인 실패
	alert("아이디와 비밀번호를 확인하여 주십시오", "");
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />