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
<?php
$ma_userid = trim($_POST['ma_userid'] ?? '');
$ma_password = trim($_POST['ma_password'] ?? '');

if (!$ma_userid || !$ma_password) {
    alert("아이디와 비밀번호를 모두 입력해주십시오.", "");
}

// 아이디 유효성 검사
if (strlen($ma_userid) < 4 || strlen($ma_userid) > 20) {
    alert("아이디는 4-20자 사이여야 합니다.", "");
}

// 비밀번호 유효성 검사
if (strlen($ma_password) < 6) {
    alert("비밀번호는 6자 이상이어야 합니다.", "");
}

$hashed_password = password_hash($ma_password, PASSWORD_BCRYPT);

// 레코드 존재 여부 확인
$check_sql = "select ma_no from {$iw['master_table']} where ma_no = 1";
$check_result = mysqli_query($db_conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    // 업데이트
    $sql = "update {$iw['master_table']} set
            ma_userid = ?,
            ma_password = ?
            where ma_no = 1";
} else {
    // 삽입
    $sql = "insert into {$iw['master_table']} (ma_no, ma_userid, ma_password, ma_buy_rate, ma_sell_rate, ma_shop_rate, ma_display) 
            values (1, ?, ?, 0, 0, 0, 0)";
}

$stmt = mysqli_prepare($db_conn, $sql);
if (!$stmt) {
    alert("데이터베이스 오류: " . mysqli_error($db_conn), "");
}

mysqli_stmt_bind_param($stmt, "ss", $ma_userid, $hashed_password);
$execute_result = mysqli_stmt_execute($stmt);

if (!$execute_result) {
    alert("마스터키 저장 중 오류가 발생했습니다: " . mysqli_error($db_conn), "");
}

mysqli_stmt_close($stmt);


alert("마스터키가 수정되었습니다.","{$iw['super_path']}/master_key.php?type={$iw['level']}&ep={$iw['store']}&gp={$iw['group']}");
?>



