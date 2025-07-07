<?php
// 관리자 계정 생성 스크립트

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

echo "<h2>관리자 계정 생성</h2>";

// 기존 관리자 계정 확인
$admin_check = sql_fetch("SELECT * FROM iw_member_list WHERE ep_code = 'ep1822322763609cab5915c89' AND (mb_level = 'admin' OR mb_level = 'super')");

if ($admin_check) {
    echo "<div style='background:blue; color:white; padding:15px;'>";
    echo "✅ <strong>관리자 계정이 이미 존재합니다!</strong><br><br>";
    echo "<strong>기존 계정 정보:</strong><br>";
    echo "📧 아이디: {$admin_check['mb_mail']}<br>";
    echo "👤 이름: {$admin_check['mb_name']}<br>";
    echo "🎖️ 권한: {$admin_check['mb_level']}<br>";
    echo "</div>";
} else {
    // 비밀번호를 직접 해시화
    $password = "admin123";
    $password_hash = md5($password); // 간단한 MD5 해시 사용
    
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
        echo "<div style='background:green; color:white; padding:15px;'>";
        echo "🎉 <strong>관리자 계정이 성공적으로 생성되었습니다!</strong><br><br>";
        echo "<strong>로그인 정보:</strong><br>";
        echo "📧 아이디: admin@example.com<br>";
        echo "🔑 비밀번호: admin123<br>";
        echo "🎖️ 권한: admin<br>";
        echo "</div>";
    } else {
        echo "<div style='background:red; color:white; padding:15px;'>";
        echo "❌ 관리자 계정 생성 실패: " . mysqli_error($connect_db);
        echo "</div>";
    }
}

// Super 관리자도 생성 (wizwindigital)
echo "<h3>Super 관리자 계정 생성</h3>";
$super_check = sql_fetch("SELECT * FROM iw_member_list WHERE mb_code = 'wizwindigital'");

if ($super_check) {
    echo "ℹ️ wizwindigital 계정이 이미 존재합니다.<br>";
} else {
    $password_hash = md5("wizwin00");
    $sql = "INSERT INTO iw_member_list SET
            mb_code = 'wizwindigital',
            ep_code = 'ep1822322763609cab5915c89',
            mb_mail = 'wizwindigital@wizwin.co.kr',
            mb_password = '$password_hash',
            mb_name = '위즈윈디지털',
            mb_nick = '최고관리자',
            mb_level = 'super',
            mb_display = 1,
            mb_datetime = NOW()";
    
    if (sql_query($sql)) {
        echo "<div style='background:purple; color:white; padding:15px;'>";
        echo "🎉 <strong>Super 관리자 계정이 생성되었습니다!</strong><br><br>";
        echo "<strong>로그인 정보:</strong><br>";
        echo "📧 아이디: wizwindigital@wizwin.co.kr<br>";
        echo "🔑 비밀번호: wizwin00<br>";
        echo "🎖️ 권한: super<br>";
        echo "</div>";
    } else {
        echo "❌ Super 관리자 계정 생성 실패: " . mysqli_error($connect_db) . "<br>";
    }
}

echo "<h3>로그인 테스트</h3>";
echo "<a href='/bbs/m/all_login.php?ep=ep1822322763609cab5915c89&gp=all' style='background:blue; color:white; padding:15px; text-decoration:none; margin:5px;'>일반 로그인 (admin@example.com / admin123)</a><br><br>";
echo "<a href='/bbs/super/login.php?ep=ep1822322763609cab5915c89&gp=all' style='background:red; color:white; padding:15px; text-decoration:none; margin:5px;'>Super 로그인 (wizwindigital@wizwin.co.kr / wizwin00)</a>";

echo "<hr>";
echo "<p style='color:red;'><strong>보안을 위해 이 파일을 삭제하세요: fix_admin.php</strong></p>";
?> 



