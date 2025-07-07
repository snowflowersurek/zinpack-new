<?php
// 데이터베이스 테이블 확인 스크립트

// 기본 설정
$iw_path = ".";
require_once("config/app.php");
require_once("config/database.php");
require_once("include/lib/lib_common.php");

// DB 연결
$connect_db = sql_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$connect_db) {
    die("데이터베이스 연결 실패: " . mysqli_connect_error());
}

echo "<h1>데이터베이스 테이블 확인</h1>";

// ep 파라미터 미리 설정
$_GET['ep'] = 'ep1822322763609cab5915c89';
$_GET['gp'] = 'all';
$_GET['type'] = 'admin';

include_once("include/common.php");

// 확인할 계정 정보
$check_mail = "wizwindigital";
$check_password = "!Wizwin1223!";

echo "<h2>1. 생성된 테이블 목록:</h2>";
$tables_sql = "SHOW TABLES";
$tables_result = sql_query($tables_sql);

echo "<ul>";
while($table_row = sql_fetch_array($tables_result)) {
    $table_name = $table_row[0];
    echo "<li style='margin: 5px; padding: 5px; background: #f0f0f0;'>$table_name</li>";
}
echo "</ul>";

echo "<h2>2. 모든 멤버 테이블에서 계정 검색:</h2>";

// 모든 멤버 테이블에서 검색
$member_tables = [
    'iw_member',
    'iw_member_list',
    'iw_member_ep1822322763609cab5915c89',
    'iw_member_all',
    'iw_enterprise_list',
    'iw_master'
];

foreach ($member_tables as $table) {
    echo "<h3>테이블: $table</h3>";
    
    // 테이블 존재 확인
    $check_table_sql = "SHOW TABLES LIKE '$table'";
    $check_result = sql_query($check_table_sql);
    
    if (sql_num_rows($check_result) > 0) {
        echo "<p style='color: green;'>✅ 테이블 존재</p>";
        
        // 계정 검색
        $search_sql = "SELECT * FROM $table WHERE mb_mail = '$check_mail' OR mb_id = '$check_mail' OR ep_mail = '$check_mail' OR master_mail = '$check_mail' LIMIT 5";
        
        try {
            $search_result = sql_query($search_sql);
            
            if (sql_num_rows($search_result) > 0) {
                echo "<p style='color: blue;'><strong>🔍 계정 발견!</strong></p>";
                
                while($row = sql_fetch_array($search_result)) {
                    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px; background: #f9f9f9;'>";
                    
                    foreach ($row as $key => $value) {
                        if (!is_numeric($key)) {
                            echo "<p><strong>$key:</strong> " . htmlspecialchars($value) . "</p>";
                        }
                    }
                    echo "</div>";
                }
            } else {
                echo "<p style='color: gray;'>계정 없음</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>오류: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ 테이블 없음</p>";
    }
    
    echo "<hr>";
}

echo "<h2>3. 계정 생성 (필요시):</h2>";
// 가장 적합한 테이블에 계정 생성
$target_table = 'iw_member_list';
$hashed_password = password_hash($check_password, PASSWORD_DEFAULT);
$datetime = date('Y-m-d H:i:s');

$create_sql = "INSERT INTO $target_table SET
    ep_code = 'ep1822322763609cab5915c89',
    mb_code = 'mb" . time() . rand(1000, 9999) . "',
    mb_mail = '$check_mail',
    mb_id = '$check_mail',
    mb_name = 'WIZWIN 관리자',
    mb_password = '$hashed_password',
    mb_level = 'admin',
    mb_display = 9,
    mb_datetime = '$datetime'
    ON DUPLICATE KEY UPDATE mb_password = '$hashed_password'";

try {
    sql_query($create_sql);
    echo "<p style='color: green;'>✅ 계정 생성/업데이트 완료!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>계정 생성 실패: " . $e->getMessage() . "</p>";
}

echo "<p><strong>완료!</strong></p>";
?> 



