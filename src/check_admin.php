<?php
// 관리자 계정 확인 및 생성 스크립트

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

echo "<h2>데이터베이스 회원 정보 확인</h2>";

// 회원 테이블 존재 확인
$tables = [];
$result = sql_query("SHOW TABLES LIKE '%member%'");
while ($row = sql_fetch_array($result)) {
    $tables[] = $row[0];
}

echo "<h3>회원 관련 테이블:</h3>";
foreach ($tables as $table) {
    echo "- $table<br>";
}

// iw_member_list 테이블이 있는지 확인
if (in_array('iw_member_list', $tables)) {
    echo "<h3>기존 회원 목록 (최대 10명):</h3>";
    $result = sql_query("SELECT mb_code, mb_mail, mb_name, mb_nick, ep_code, mb_level FROM iw_member_list LIMIT 10");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>mb_code</th><th>mb_mail</th><th>mb_name</th><th>mb_nick</th><th>ep_code</th><th>mb_level</th></tr>";
    while ($row = sql_fetch_array($result)) {
        echo "<tr>";
        echo "<td>{$row['mb_code']}</td>";
        echo "<td>{$row['mb_mail']}</td>";
        echo "<td>{$row['mb_name']}</td>";
        echo "<td>{$row['mb_nick']}</td>";
        echo "<td>{$row['ep_code']}</td>";
        echo "<td>{$row['mb_level']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 관리자 계정 확인
    $admin_check = sql_fetch("SELECT * FROM iw_member_list WHERE mb_level = 'admin' OR mb_level = 'super' OR mb_code = 'wizwindigital'");
    if ($admin_check) {
        echo "<h3>관리자 계정이 존재합니다!</h3>";
        echo "mb_code: {$admin_check['mb_code']}<br>";
        echo "mb_mail: {$admin_check['mb_mail']}<br>";
        echo "mb_name: {$admin_check['mb_name']}<br>";
        echo "mb_level: {$admin_check['mb_level']}<br>";
        echo "<strong>이 계정으로 로그인 시도해보세요!</strong><br>";
    } else {
        echo "<h3>관리자 계정이 없습니다. 새 관리자 계정을 생성하겠습니다.</h3>";
        
        // 관리자 계정 생성
        $password_hash = sql_password("admin123");
        $sql = "INSERT INTO iw_member_list SET
                mb_code = 'admin',
                ep_code = 'ep1822322763609cab5915c89',
                mb_mail = 'admin@example.com',
                mb_password = '$password_hash',
                mb_name = '관리자',
                mb_nick = '관리자',
                mb_level = 'admin',
                mb_display = 1,
                mb_datetime = NOW()";
        
        if (sql_query($sql)) {
            echo "<div style='background:green; color:white; padding:10px;'>";
            echo "✅ 관리자 계정이 성공적으로 생성되었습니다!<br>";
            echo "<strong>아이디: admin@example.com</strong><br>";
            echo "<strong>비밀번호: admin123</strong><br>";
            echo "</div>";
        } else {
            echo "계정 생성 중 오류가 발생했습니다: " . mysqli_error($connect_db) . "<br>";
        }
    }
} else {
    echo "<h3 style='color:red;'>iw_member_list 테이블이 없습니다!</h3>";
    echo "데이터베이스 설치가 필요할 수 있습니다.";
}

// 기업 정보 확인
echo "<h3>기업 정보 확인:</h3>";
try {
    $ep_result = sql_query("SELECT ep_code, ep_nick, ep_name FROM iw_enterprise_list LIMIT 5");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ep_code</th><th>ep_nick</th><th>ep_name</th></tr>";
    while ($row = sql_fetch_array($ep_result)) {
        echo "<tr>";
        echo "<td>{$row['ep_code']}</td>";
        echo "<td>{$row['ep_nick']}</td>";
        echo "<td>{$row['ep_name']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "기업 테이블을 읽을 수 없습니다: " . $e->getMessage();
}

echo "<br><br><a href='/bbs/m/all_login.php?ep=ep1822322763609cab5915c89&gp=all' style='background:blue; color:white; padding:10px; text-decoration:none;'>일반 로그인 페이지로 이동</a>";
echo "<br><br><a href='/bbs/super/login.php?ep=ep1822322763609cab5915c89&gp=all' style='background:red; color:white; padding:10px; text-decoration:none;'>Super 관리자 로그인으로 이동</a>";
?> 



