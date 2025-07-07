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

// POST 데이터 안전하게 가져오기
$ma_policy_agreement = $_POST['contents1'] ?? '';
$ma_policy_private = $_POST['contents2'] ?? '';
$ma_policy_email = $_POST['contents3'] ?? '';

// 입력 검증
if (empty($ma_policy_agreement)) {
    alert("이용약관을 입력해주세요.", "");
}
if (empty($ma_policy_private)) {
    alert("개인정보 처리방침을 입력해주세요.", "");
}
if (empty($ma_policy_email)) {
    alert("이메일주소 무단수집거부를 입력해주세요.", "");
}

// Prepared Statement를 사용한 안전한 데이터베이스 업데이트
$sql = "UPDATE {$iw['master_table']} SET 
        ma_policy_agreement = ?,
        ma_policy_private = ?,
        ma_policy_email = ?
        WHERE ma_no = 1";

$stmt = mysqli_prepare($db_conn, $sql);
if (!$stmt) {
    alert("데이터베이스 오류: " . mysqli_error($db_conn), "");
}

mysqli_stmt_bind_param($stmt, "sss", $ma_policy_agreement, $ma_policy_private, $ma_policy_email);
$result = mysqli_stmt_execute($stmt);

if (!$result) {
    alert("이용약관 저장 중 오류가 발생했습니다: " . mysqli_error($db_conn), "");
}

mysqli_stmt_close($stmt);

alert("이용약관이 수정되었습니다.","{$iw['super_path']}/policy_edit.php?type={$iw['level']}&ep={$iw['store']}&gp={$iw['group']}");
?>



