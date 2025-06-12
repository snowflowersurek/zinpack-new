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
$ma_userid = trim($_POST['ma_userid']);
$ma_password = trim($_POST['ma_password']);

if (!$ma_userid || !$ma_password) {
    alert("아이디와 비밀번호를 모두 입력해주십시오.", "");
}

$hashed_password = password_hash($ma_password, PASSWORD_BCRYPT);

$sql = "update {$iw['master_table']} set
		ma_userid = ?,
		ma_password = ?
		where ma_no = 1";

$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $ma_userid, $hashed_password);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);


alert("마스터키가 수정되었습니다.","{$iw['super_path']}/master_key.php?type={$iw['level']}&ep={$iw['store']}&gp={$iw['group']}");
?>