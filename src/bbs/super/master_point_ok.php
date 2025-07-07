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
$change_point = (int)trim($_POST['change_point']);

if (!$ma_userid || !$ma_password || !$mb_code || !$ep_code) {
    alert("필수 정보가 누락되었습니다.", "");
}

$sql = "select * from {$iw['master_table']} where ma_no = 1";
$result = mysqli_query($db_conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row || !password_verify($ma_userid, $row['ma_userid']) || !password_verify($ma_password, $row['ma_password'])) {
    alert("아이디와 비밀번호를 확인하여 주십시오","");
} else {
    // 사용자 현재 포인트 조회
    $sql_select = "select mb_point from {$iw['member_table']} where ep_code = ? and mb_code = ?";
    $stmt_select = mysqli_prepare($db_conn, $sql_select);
    mysqli_stmt_bind_param($stmt_select, "ss", $ep_code, $mb_code);
    mysqli_stmt_execute($stmt_select);
    $result_select = mysqli_stmt_get_result($stmt_select);
    $row_point = mysqli_fetch_assoc($result_select);
    mysqli_stmt_close($stmt_select);
	$mb_point = (int)$row_point['mb_point'];

	if($mb_point == $change_point){
		alert("포인트를 변경할 내용이 없습니다.","");
	}

    $pt_deposit = 0;
    $pt_withdraw = 0;

	if($mb_point < $change_point){
		$pt_deposit = $change_point - $mb_point;
	} else {
		$pt_withdraw = $mb_point - $change_point;
	}

	$pt_datetime = date("Y-m-d H:i:s");
	$pt_content = "관리자 포인트 설정";

    // 사용자 포인트 업데이트
	$sql_update = "update {$iw['member_table']} set mb_point = ? where ep_code = ? and mb_code = ?";
    $stmt_update = mysqli_prepare($db_conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "iss", $change_point, $ep_code, $mb_code);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // 포인트 로그 삽입
	$sql_insert = "insert into {$iw['point_table']} set
			ep_code = ?, mb_code = ?, state_sort = 'doc', pt_deposit = ?, pt_withdraw = ?,
			pt_balance = ?, pt_content = ?, pt_datetime = ?";
    $stmt_insert = mysqli_prepare($db_conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssiiiss", $ep_code, $mb_code, $pt_deposit, $pt_withdraw, $change_point, $pt_content, $pt_datetime);
    mysqli_stmt_execute($stmt_insert);
    mysqli_stmt_close($stmt_insert);

	alert("포인트가 변경되었습니다.","{$iw['super_path']}/master_point.php?type={$iw['level']}&ep={$iw['store']}&gp={$iw['group']}&mep={$ep_code}&mgp={$gp_code}&mmb={$mb_code}");
}

?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />



