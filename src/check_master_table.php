<?php
// 직접 데이터베이스 연결
$host = 'db';
$username = 'root';
$password = 'infoway@$db';
$database = 'infoway';

echo "<h1>마스터 테이블 확인 및 생성</h1>";

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("연결 실패: " . $conn->connect_error);
    }
    
    echo "<p style='color:green;'>✅ 데이터베이스 연결 성공!</p>";
    
    // 1. iw_master 테이블 존재 확인
    echo "<h2>1. iw_master 테이블 확인:</h2>";
    $check_table_sql = "SHOW TABLES LIKE 'iw_master'";
    $result = $conn->query($check_table_sql);
    
    if ($result->num_rows > 0) {
        echo "<p style='color:green;'>✅ iw_master 테이블 존재</p>";
        
        // 기존 데이터 확인
        $data_sql = "SELECT * FROM iw_master WHERE ma_no = 1";
        $data_result = $conn->query($data_sql);
        
        if ($data_result->num_rows > 0) {
            $row = $data_result->fetch_assoc();
            echo "<h3>기존 마스터 데이터:</h3>";
            echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
            foreach ($row as $key => $value) {
                echo "<p><strong>$key:</strong> " . htmlspecialchars($value) . "</p>";
            }
            echo "</div>";
        } else {
            echo "<p style='color:orange;'>⚠️ 마스터 데이터가 없습니다.</p>";
        }
        
    } else {
        echo "<p style='color:red;'>❌ iw_master 테이블이 없습니다. 생성중...</p>";
        
        // 테이블 생성
        $create_table_sql = "CREATE TABLE iw_master (
            ma_no int(11) NOT NULL AUTO_INCREMENT,
            ma_userid varchar(255) NOT NULL DEFAULT '',
            ma_password varchar(255) NOT NULL DEFAULT '',
            ma_buy_rate int(11) NOT NULL DEFAULT 100,
            ma_sell_rate int(11) NOT NULL DEFAULT 100,
            ma_shop_rate int(11) NOT NULL DEFAULT 100,
            PRIMARY KEY (ma_no)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        
        if ($conn->query($create_table_sql)) {
            echo "<p style='color:green;'>✅ iw_master 테이블 생성 완료!</p>";
        } else {
            echo "<p style='color:red;'>❌ 테이블 생성 실패: " . $conn->error . "</p>";
        }
    }
    
    // 2. 마스터 데이터 생성/업데이트
    echo "<h2>2. 마스터 데이터 설정:</h2>";
    
    $default_userid = "wizwindigital";
    $default_password = "!Wizwin1223!";
    
    $upsert_sql = "INSERT INTO iw_master SET
        ma_no = 1,
        ma_userid = '$default_userid',
        ma_password = '$default_password',
        ma_buy_rate = 100,
        ma_sell_rate = 100,
        ma_shop_rate = 100
        ON DUPLICATE KEY UPDATE
        ma_userid = '$default_userid',
        ma_password = '$default_password'";
    
    if ($conn->query($upsert_sql)) {
        echo "<p style='color:green;'>✅ 마스터 데이터 설정 완료!</p>";
        echo "<div style='border:2px solid green; padding:15px; margin:10px; background:#e6ffe6;'>";
        echo "<h3>마스터키 정보:</h3>";
        echo "<p><strong>아이디:</strong> $default_userid</p>";
        echo "<p><strong>비밀번호:</strong> $default_password</p>";
        echo "</div>";
    } else {
        echo "<p style='color:red;'>❌ 마스터 데이터 설정 실패: " . $conn->error . "</p>";
    }
    
    // 3. 최종 확인
    echo "<h2>3. 최종 확인:</h2>";
    $final_check_sql = "SELECT * FROM iw_master WHERE ma_no = 1";
    $final_result = $conn->query($final_check_sql);
    
    if ($final_result->num_rows > 0) {
        $final_row = $final_result->fetch_assoc();
        echo "<p style='color:green;'>✅ 마스터 설정 완료!</p>";
        echo "<div style='border:1px solid blue; padding:10px; margin:10px; background:#e6f3ff;'>";
        echo "<h4>현재 설정:</h4>";
        echo "<p><strong>아이디:</strong> " . $final_row['ma_userid'] . "</p>";
        echo "<p><strong>비밀번호:</strong> " . $final_row['ma_password'] . "</p>";
        echo "<p><strong>구매율:</strong> " . $final_row['ma_buy_rate'] . "%</p>";
        echo "<p><strong>판매율:</strong> " . $final_row['ma_sell_rate'] . "%</p>";
        echo "<p><strong>쇼핑률:</strong> " . $final_row['ma_shop_rate'] . "%</p>";
        echo "</div>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color:red;'>오류: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>마스터키 설정 페이지 접근:</h2>";
echo "<p><a href='/bbs/super/master_key.php?type=site&ep=ep1822322763609cab5915c89&gp=all' style='background:purple; color:white; padding:15px; text-decoration:none; margin:10px;'>🔑 마스터키 설정</a></p>";
echo "<p><strong>이 파일을 삭제하세요.</strong></p>"; 