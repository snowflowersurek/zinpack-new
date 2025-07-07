<?php
// 직접 데이터베이스 연결
$host = 'db';
$username = 'root';
$password = 'infoway@$db';
$database = 'infoway';

echo "<h1>데이터베이스 직접 확인</h1>";

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("연결 실패: " . $conn->connect_error);
    }
    
    echo "<p style='color:green;'>✅ 데이터베이스 연결 성공!</p>";
    
    // 테이블 목록 확인
    echo "<h2>테이블 목록:</h2>";
    $result = $conn->query("SHOW TABLES");
    
    $tables = [];
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
        echo "<li>" . $row[0] . "</li>";
    }
    
    echo "<br><p>총 " . count($tables) . "개 테이블</p>";
    
    // 계정 정보 확인
    $check_mail = "wizwindigital";
    $check_password = "!Wizwin1223!";
    
    echo "<h2>계정 검색:</h2>";
    
    // 가능한 멤버 테이블들 확인
    $member_tables = [];
    foreach ($tables as $table) {
        if (strpos($table, 'member') !== false || strpos($table, 'enterprise') !== false) {
            $member_tables[] = $table;
        }
    }
    
    echo "<h3>멤버 관련 테이블:</h3>";
    foreach ($member_tables as $table) {
        echo "<li>$table</li>";
        
        // 테이블 구조 확인
        $structure = $conn->query("DESCRIBE $table");
        if ($structure) {
            echo "<ul>";
            while ($field = $structure->fetch_assoc()) {
                echo "<li>" . $field['Field'] . " (" . $field['Type'] . ")</li>";
            }
            echo "</ul>";
        }
        
        // 계정 검색
        $search_query = "SELECT * FROM $table WHERE 
            (mb_mail = '$check_mail' OR mb_id = '$check_mail' OR ep_mail = '$check_mail')
            LIMIT 5";
        
        $search_result = $conn->query($search_query);
        
        if ($search_result && $search_result->num_rows > 0) {
            echo "<p style='color:blue;'><strong>🔍 계정 발견!</strong></p>";
            
            while ($row = $search_result->fetch_assoc()) {
                echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
                foreach ($row as $key => $value) {
                    echo "<p><strong>$key:</strong> " . htmlspecialchars($value) . "</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<p style='color:gray;'>계정 없음</p>";
        }
        
        echo "<hr>";
    }
    
    // 계정 생성 시도
    if (count($member_tables) > 0) {
        echo "<h2>계정 생성 시도:</h2>";
        $target_table = $member_tables[0];
        
        $hashed_password = password_hash($check_password, PASSWORD_DEFAULT);
        $datetime = date('Y-m-d H:i:s');
        
        $insert_query = "INSERT INTO $target_table SET
            ep_code = 'ep1822322763609cab5915c89',
            mb_code = 'mb" . time() . rand(1000, 9999) . "',
            mb_mail = '$check_mail',
            mb_id = '$check_mail',
            mb_name = 'WIZWIN 관리자',
            mb_password = '$hashed_password',
            mb_level = 'admin',
            mb_display = 9,
            mb_datetime = '$datetime'";
        
        if ($conn->query($insert_query)) {
            echo "<p style='color:green;'>✅ 계정 생성 완료!</p>";
        } else {
            echo "<p style='color:red;'>계정 생성 실패: " . $conn->error . "</p>";
        }
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color:red;'>오류: " . $e->getMessage() . "</p>";
}

echo "<p><strong>완료!</strong></p>";
?> 