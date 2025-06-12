<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$mb_mail = trim($_POST['mb_mail']);
$mb_password = trim($_POST['mb_password']);
$mb_code = trim($_POST['mb_code']);
$mb_name = trim($_POST['mb_name']);
$mb_nick = trim($_POST['mb_nick']);
$mb_address = trim($_POST['mb_address']);
$mb_address_sub = trim($_POST['mb_address_sub']);
$mb_sub_mail = trim($_POST['mb_sub_mail']);
$ep_code = trim($_POST['ep_code']);
$language = trim($_POST['language']);
$mb_tel = '';
$mb_zip_code = '';
$mb_address_city = '';
$mb_address_state = '';
$mb_address_country = '';

if($language=="ko"){
	$mb_tel_1 = trim($_POST['mb_tel_1']);
	$mb_tel_2 = trim($_POST['mb_tel_2']);
	$mb_tel_3 = trim($_POST['mb_tel_3']);
	$mb_tel = $mb_tel_1."-".$mb_tel_2."-".$mb_tel_3;
	$mb_zip_code = trim($_POST['mb_zip_code']);
}else if($language=="en"){
	$mb_tel = trim($_POST['mb_tel']);
	$mb_zip_code = trim($_POST['mb_zip_code']);
	$mb_address_city = trim($_POST['mb_address_city']);
	$mb_address_state = trim($_POST['mb_address_state']);
	$mb_address_country = trim($_POST['mb_address_country']);
}

// 이메일 중복 확인
$sql = "select count(*) as cnt from {$iw['member_table']} where mb_mail = ? and ep_code = ? and mb_code <> ?";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $mb_mail, $ep_code, $mb_code);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rowmail = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// 닉네임 중복 확인
$sql = "select count(*) as cnt from {$iw['member_table']} where mb_nick = ? and ep_code = ? and mb_code <> ?";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $mb_nick, $ep_code, $mb_code);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rownick = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);


if ($rowmail['cnt']) {
	alert("이메일주소가 이미 존재합니다.","");
}else if ($rownick['cnt']) {
	alert("닉네임이 이미 존재합니다.","");
}else{
    // 비밀번호를 제외한 정보 업데이트
	$sql = "update {$iw['member_table']} set
			mb_mail = ?,
			mb_name = ?,
			mb_nick = ?,
			mb_tel = ?,
			mb_sub_mail = ?,
			mb_zip_code = ?,
			mb_address = ?,
			mb_address_sub = ?,
			mb_address_city = ?,
			mb_address_state = ?,
			mb_address_country = ?
			where mb_code = ? and ep_code = ?
			";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssss", $mb_mail, $mb_name, $mb_nick, $mb_tel, $mb_sub_mail, $mb_zip_code, $mb_address, $mb_address_sub, $mb_address_city, $mb_address_state, $mb_address_country, $mb_code, $ep_code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 비밀번호가 입력된 경우에만 업데이트
    if ($mb_password != ""){
        $hashed_password = password_hash($mb_password, PASSWORD_BCRYPT);
        $sql_pass = "update {$iw['member_table']} set mb_password = ? where mb_code = ? and ep_code = ?";
        $stmt_pass = mysqli_prepare($db_conn, $sql_pass);
        mysqli_stmt_bind_param($stmt_pass, "sss", $hashed_password, $mb_code, $ep_code);
        mysqli_stmt_execute($stmt_pass);
        mysqli_stmt_close($stmt_pass);
    }

	alert("회원정보가 수정되었습니다.","{$iw['super_path']}/member_data_view.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&idx={$mb_code}");
}
?>