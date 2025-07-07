<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

$ma_userid = trim($_POST['ma_userid']);
$ma_password = trim($_POST['ma_password']);
$mb_code = trim($_POST['mb_code']); 
$ep_code = trim($_POST['ep_code']);
$gp_code = trim($_POST['gp_code']);

if (!$ma_userid || !$ma_password || !$mb_code || !$ep_code) {
    alert("필수 정보가 누락되었습니다.", "");
}

$sql = "select * from {$iw['master_table']} where ma_no = 1";
$result = mysqli_query($db_conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($row && $ma_userid === $row['ma_userid'] && password_verify($ma_password, $row['ma_password'])) {
    // 마스터 키 인증 성공
	set_cookie("iw_member", $mb_code, time()+3600);
	goto_url("{$iw['m_path']}/main.php?type=main&ep={$ep_code}&gp={$gp_code}");
}else{
    // 마스터 키 인증 실패
	alert("아이디와 비밀번호를 확인하여 주십시오","");
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />



