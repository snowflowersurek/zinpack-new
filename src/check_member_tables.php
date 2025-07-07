<?php
// 직접 데이터베이스 연결
$host = 'db';
$username = 'root';
$password = 'infoway@$db';
$database = 'infoway';

echo "<h1>멤버 테이블 전용 확인</h1>";

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("연결 실패: " . $conn->connect_error);
    }
    
    echo "<p style='color:green;'>✅ 데이터베이스 연결 성공!</p>";
    
    // 확인할 계정 정보
    $check_mail = "wizwindigital";
    $check_password = "!Wizwin1223!";
    
    // 멤버 테이블들 확인
    $member_tables = ['iw_member', 'iw_member_list'];
    
    foreach ($member_tables as $table) {
        echo "<h2>테이블: $table</h2>";
        
        // 테이블 구조 확인
        $structure = $conn->query("DESCRIBE $table");
        if ($structure) {
            echo "<h3>테이블 구조:</h3>";
            echo "<ul>";
            while ($field = $structure->fetch_assoc()) {
                echo "<li><strong>" . $field['Field'] . "</strong> (" . $field['Type'] . ")</li>";
            }
            echo "</ul>";
        }
        
        // 전체 데이터 확인
        $all_data = $conn->query("SELECT * FROM $table LIMIT 10");
        if ($all_data && $all_data->num_rows > 0) {
            echo "<h3>기존 데이터:</h3>";
            while ($row = $all_data->fetch_assoc()) {
                echo "<div style='border:1px solid #ccc; padding:10px; margin:10px; background:#f9f9f9;'>";
                foreach ($row as $key => $value) {
                    echo "<p><strong>$key:</strong> " . htmlspecialchars($value) . "</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<p style='color:gray;'>기존 데이터 없음</p>";
        }
        
        // 계정 검색 (다양한 필드명 시도)
        $search_fields = ['mb_mail', 'mb_id', 'ep_mail', 'ep_id', 'member_mail', 'member_id'];
        
        foreach ($search_fields as $field) {
            $search_query = "SELECT * FROM $table WHERE $field = '$check_mail' LIMIT 5";
            $search_result = $conn->query($search_query);
            
            if ($search_result && $search_result->num_rows > 0) {
                echo "<p style='color:blue;'><strong>🔍 계정 발견! (필드: $field)</strong></p>";
                
                while ($row = $search_result->fetch_assoc()) {
                    echo "<div style='border:2px solid blue; padding:10px; margin:10px; background:#e6f3ff;'>";
                    foreach ($row as $key => $value) {
                        echo "<p><strong>$key:</strong> " . htmlspecialchars($value) . "</p>";
                    }
                    echo "</div>";
                }
                break;
            }
        }
        
        echo "<hr>";
    }
    
    // 계정 생성 시도
    echo "<h2>계정 생성 시도:</h2>";
    
    // iw_member_list 테이블에 생성
    $target_table = 'iw_member_list';
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
        mb_datetime = '$datetime'
        ON DUPLICATE KEY UPDATE 
        mb_password = '$hashed_password',
        mb_level = 'admin',
        mb_display = 9";
    
    if ($conn->query($insert_query)) {
        echo "<p style='color:green;'>✅ 계정 생성/업데이트 완료!</p>";
        echo "<p><strong>아이디:</strong> $check_mail</p>";
        echo "<p><strong>비밀번호:</strong> $check_password</p>";
    } else {
        echo "<p style='color:red;'>계정 생성 실패: " . $conn->error . "</p>";
    }
    
    // 생성된 계정 확인
    echo "<h2>생성된 계정 확인:</h2>";
    $verify_query = "SELECT * FROM $target_table WHERE mb_mail = '$check_mail' OR mb_id = '$check_mail'";
    $verify_result = $conn->query($verify_query);
    
    if ($verify_result && $verify_result->num_rows > 0) {
        echo "<p style='color:green;'>✅ 계정 확인 완료!</p>";
        while ($row = $verify_result->fetch_assoc()) {
            echo "<div style='border:2px solid green; padding:10px; margin:10px; background:#e6ffe6;'>";
            foreach ($row as $key => $value) {
                echo "<p><strong>$key:</strong> " . htmlspecialchars($value) . "</p>";
            }
            echo "</div>";
        }
    } else {
        echo "<p style='color:red;'>계정 확인 실패</p>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color:red;'>오류: " . $e->getMessage() . "</p>";
}

echo "<p><strong>완료!</strong></p>";
echo "<hr>";
echo "<p><a href='/bbs/super/login.php' style='background:red; color:white; padding:15px; text-decoration:none;'>슈퍼관리자 로그인 테스트</a></p>";
?> 