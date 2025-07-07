<?php
$iw_path = "."; // common.php 의 상대 경로

// ep 파라미터 미리 설정
$_GET['ep'] = 'ep1822322763609cab5915c89';
$_GET['gp'] = 'all';
$_GET['type'] = 'admin';

include_once("include/common.php");

echo "<h1>계정 확인 및 생성</h1>";

// 확인할 계정 정보
$check_mail = "wizwindigital";
$check_password = "!Wizwin1223!";
$ep_code = "ep1822322763609cab5915c89";

echo "<h2>1. 계정 검색 결과:</h2>";

// 계정 검색 (이메일로)
$sql = "SELECT mb_no, mb_mail, mb_id, mb_name, mb_display, mb_level, mb_password 
        FROM iw_member_$ep_code 
        WHERE mb_mail = '$check_mail' OR mb_id = '$check_mail'";
        
$result = sql_query($sql);
$found = false;

while($row = sql_fetch_array($result)) {
    $found = true;
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
    echo "<p><strong>계정 발견!</strong></p>";
    echo "<p>번호: " . $row['mb_no'] . "</p>";
    echo "<p>이메일: " . $row['mb_mail'] . "</p>";
    echo "<p>아이디: " . $row['mb_id'] . "</p>";
    echo "<p>이름: " . $row['mb_name'] . "</p>";
    echo "<p>권한(mb_display): " . $row['mb_display'] . "</p>";
    echo "<p>레벨: " . $row['mb_level'] . "</p>";
    echo "<p>비밀번호(암호화): " . substr($row['mb_password'], 0, 20) . "...</p>";
    
    // 비밀번호 확인
    if (password_verify($check_password, $row['mb_password'])) {
        echo "<p style='color: green;'><strong>✅ 비밀번호 일치!</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>❌ 비밀번호 불일치!</strong></p>";
        echo "<p>확인용 해시: " . password_hash($check_password, PASSWORD_DEFAULT) . "</p>";
    }
    
    // 권한 확인
    if ($row['mb_display'] == 9) {
        echo "<p style='color: blue;'><strong>🔥 슈퍼관리자 권한!</strong></p>";
    } else if ($row['mb_display'] == 7) {
        echo "<p style='color: orange;'><strong>⚡ 일반관리자 권한!</strong></p>";
    } else {
        echo "<p style='color: gray;'>일반 사용자 권한 (mb_display: {$row['mb_display']})</p>";
    }
    echo "</div>";
}

if (!$found) {
    echo "<p style='color: red;'><strong>❌ 계정을 찾을 수 없습니다!</strong></p>";
    
    echo "<h2>2. 슈퍼관리자 계정 생성:</h2>";
    
    // 슈퍼관리자 계정 생성
    $hashed_password = password_hash($check_password, PASSWORD_DEFAULT);
    $datetime = date('Y-m-d H:i:s');
    
    $insert_sql = "INSERT INTO iw_member_$ep_code SET
        ep_code = '$ep_code',
        mb_code = 'mb" . time() . rand(1000, 9999) . "',
        mb_mail = '$check_mail',
        mb_id = '$check_mail',
        mb_name = 'WIZWIN 슈퍼관리자',
        mb_nick = 'wizwin',
        mb_password = '$hashed_password',
        mb_level = 'super',
        mb_display = 9,
        mb_datetime = '$datetime',
        mb_point = 0,
        mb_post_count = 0,
        mb_comment_count = 0";
    
    if (sql_query($insert_sql)) {
        echo "<p style='color: green;'><strong>✅ 슈퍼관리자 계정 생성 완료!</strong></p>";
        echo "<p>아이디: $check_mail</p>";
        echo "<p>비밀번호: $check_password</p>";
        echo "<p>권한: 슈퍼관리자 (mb_display = 9)</p>";
    } else {
        echo "<p style='color: red;'><strong>❌ 계정 생성 실패!</strong></p>";
        echo "<p>SQL 오류: " . mysqli_error($connect) . "</p>";
    }
}

echo "<h2>3. 전체 관리자 계정 목록:</h2>";
$admin_sql = "SELECT mb_mail, mb_id, mb_name, mb_display, mb_level 
              FROM iw_member_$ep_code 
              WHERE mb_display >= 7 
              ORDER BY mb_display DESC";
              
$admin_result = sql_query($admin_sql);
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>이메일</th><th>아이디</th><th>이름</th><th>권한</th><th>레벨</th></tr>";

while($admin_row = sql_fetch_array($admin_result)) {
    echo "<tr>";
    echo "<td>" . $admin_row['mb_mail'] . "</td>";
    echo "<td>" . $admin_row['mb_id'] . "</td>";
    echo "<td>" . $admin_row['mb_name'] . "</td>";
    echo "<td>" . ($admin_row['mb_display'] == 9 ? '슈퍼관리자' : '일반관리자') . " ({$admin_row['mb_display']})</td>";
    echo "<td>" . $admin_row['mb_level'] . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<p><strong>완료!</strong></p>";
?> 