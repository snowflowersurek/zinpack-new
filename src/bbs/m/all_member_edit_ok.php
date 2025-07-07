<?php
include_once("_common_guest.php");
if ($iw['level']=="guest") alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$mb_password = trim($_POST['mb_password']);
$mb_name = trim($_POST['mb_name']);
$mb_nick = trim($_POST['mb_nick']);
$mb_address = trim($_POST['mb_address']);
$mb_address_sub = trim($_POST['mb_address_sub']);
$mb_sub_mail = trim($_POST['mb_sub_mail']);
$confirm_password = trim($_POST['confirm_password']);

if($iw['language']=="ko"){
	$mb_tel_1 = trim($_POST['mb_tel_1']);
	$mb_tel_2 = trim($_POST['mb_tel_2']);
	$mb_tel_3 = trim($_POST['mb_tel_3']);
	$mb_tel = $mb_tel_1."-".$mb_tel_2."-".$mb_tel_3;
	$mb_zip_code = trim($_POST['mb_zip_code']);
}else if($iw['language']=="en"){
	$mb_tel = trim($_POST['mb_tel']);
	$mb_zip_code = trim($_POST['mb_zip_code']);
	$mb_address_city = trim($_POST['mb_address_city']);
	$mb_address_state = trim($_POST['mb_address_state']);
	$mb_address_country = trim($_POST['mb_address_country']);
}

$sql_user = "select mb_password from {$iw['member_table']} where ep_code = ? and mb_code = ?";
$stmt_user = mysqli_prepare($db_conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "ss", $iw['store'], $iw['member']);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$row_user = mysqli_fetch_assoc($result_user);
mysqli_stmt_close($stmt_user);

if ($row_user && password_verify($confirm_password, $row_user['mb_password'])) {
	$sql_nick = "select count(*) as cnt from {$iw['member_table']} where mb_nick = ? and ep_code = ? and mb_code <> ?";
	$stmt_nick = mysqli_prepare($db_conn, $sql_nick);
	mysqli_stmt_bind_param($stmt_nick, "sss", $mb_nick, $iw['store'], $iw['member']);
	mysqli_stmt_execute($stmt_nick);
	$result_nick = mysqli_stmt_get_result($stmt_nick);
	$rownick = mysqli_fetch_assoc($result_nick);
	mysqli_stmt_close($stmt_nick);

	if ($rownick['cnt']) {
		alert(national_language($iw['language'],"a0100","닉네임이 이미 존재합니다."),"");
	}else{
		$sql_update = "update {$iw['member_table']} set
				mb_name = ?, mb_nick = ?, mb_tel = ?, mb_sub_mail = ?, mb_zip_code = ?,
				mb_address = ?, mb_address_sub = ?, mb_address_city = ?, mb_address_state = ?, mb_address_country = ?
				where mb_code = ?";
		$stmt_update = mysqli_prepare($db_conn, $sql_update);
		mysqli_stmt_bind_param($stmt_update, "sssssssssss", $mb_name, $mb_nick, $mb_tel, $mb_sub_mail, $mb_zip_code, $mb_address, $mb_address_sub, $mb_address_city, $mb_address_state, $mb_address_country, $iw['member']);
		mysqli_stmt_execute($stmt_update);
		mysqli_stmt_close($stmt_update);

		if ($mb_password != ""){
			$result_check = passwordCheck($mb_password);
			if ($result_check[0] == false){
				alert(national_language($iw['language'],"a0084","비밀번호를 특수문자 포함해서 8글자 이상 입력하십시오."),"");
			}
			$hashed_password = password_hash($mb_password, PASSWORD_BCRYPT);
			$sql_pass = "update {$iw['member_table']} set mb_password = ? where mb_code = ?";
			$stmt_pass = mysqli_prepare($db_conn, $sql_pass);
			mysqli_stmt_bind_param($stmt_pass, "ss", $hashed_password, $iw['member']);
			mysqli_stmt_execute($stmt_pass);
			mysqli_stmt_close($stmt_pass);
		}

		alert(national_language($iw['language'],"a0115","회원정보가 수정되었습니다."),"{$iw['m_path']}/all_member_edit.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
	}
}else{
	alert(national_language($iw['language'],"a0116","비밀번호를 확인하여 주십시오."),"");
}
?>



