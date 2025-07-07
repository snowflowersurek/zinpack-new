<?php
// 데이터베이스 초기 설정 스크립트

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

echo "<h2>데이터베이스 초기화 중...</h2>";

// 기업 테이블 생성
$sql = "CREATE TABLE IF NOT EXISTS iw_enterprise_list (
    ep_no int(11) NOT NULL auto_increment,
    ep_code varchar(30) NOT NULL,
    ep_nick varchar(50) NOT NULL,
    ep_name varchar(100) NOT NULL,
    ep_mail varchar(100) NOT NULL,
    ep_phone varchar(20),
    ep_address varchar(255),
    ep_jointype int(1) DEFAULT 1,
    ep_display int(1) DEFAULT 1,
    ep_datetime datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ep_no),
    UNIQUE KEY ep_code (ep_code)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

if (sql_query($sql)) {
    echo "✅ 기업 테이블 (iw_enterprise_list) 생성 완료<br>";
} else {
    echo "❌ 기업 테이블 생성 실패: " . mysqli_error($connect_db) . "<br>";
}

// 회원 테이블 생성
$sql = "CREATE TABLE IF NOT EXISTS iw_member_list (
    mb_no int(11) NOT NULL auto_increment,
    mb_code varchar(30) NOT NULL,
    ep_code varchar(30) NOT NULL,
    mb_mail varchar(100) NOT NULL,
    mb_password varchar(255) NOT NULL,
    mb_name varchar(50) NOT NULL,
    mb_nick varchar(50) NOT NULL,
    mb_phone varchar(20),
    mb_level varchar(20) DEFAULT 'member',
    mb_display int(1) DEFAULT 1,
    mb_datetime datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (mb_no),
    UNIQUE KEY mb_code (mb_code, ep_code),
    UNIQUE KEY mb_mail (mb_mail, ep_code)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

if (sql_query($sql)) {
    echo "✅ 회원 테이블 (iw_member_list) 생성 완료<br>";
} else {
    echo "❌ 회원 테이블 생성 실패: " . mysqli_error($connect_db) . "<br>";
}

// 그룹 테이블 생성
$sql = "CREATE TABLE IF NOT EXISTS iw_group_list (
    gp_no int(11) NOT NULL auto_increment,
    gp_code varchar(30) NOT NULL,
    ep_code varchar(30) NOT NULL,
    gp_nick varchar(50) NOT NULL,
    gp_name varchar(100) NOT NULL,
    gp_display int(1) DEFAULT 1,
    gp_datetime datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (gp_no),
    UNIQUE KEY gp_code (gp_code, ep_code)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

if (sql_query($sql)) {
    echo "✅ 그룹 테이블 (iw_group_list) 생성 완료<br>";
} else {
    echo "❌ 그룹 테이블 생성 실패: " . mysqli_error($connect_db) . "<br>";
}

// 설정 테이블 생성
$sql = "CREATE TABLE IF NOT EXISTS iw_setting_list (
    st_no int(11) NOT NULL auto_increment,
    ep_code varchar(30) NOT NULL,
    gp_code varchar(30) NOT NULL,
    st_title varchar(100) DEFAULT '사이트 제목',
    st_description varchar(255) DEFAULT '사이트 설명',
    st_favicon varchar(100),
    st_display int(1) DEFAULT 1,
    st_datetime datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (st_no),
    UNIQUE KEY setting_key (ep_code, gp_code)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

if (sql_query($sql)) {
    echo "✅ 설정 테이블 (iw_setting_list) 생성 완료<br>";
} else {
    echo "❌ 설정 테이블 생성 실패: " . mysqli_error($connect_db) . "<br>";
}

echo "<h3>기본 데이터 입력 중...</h3>";

// 기본 기업 데이터 입력
$enterprise_exists = sql_fetch("SELECT COUNT(*) as cnt FROM iw_enterprise_list WHERE ep_code = 'ep1822322763609cab5915c89'");
if ($enterprise_exists['cnt'] == 0) {
    $sql = "INSERT INTO iw_enterprise_list SET
            ep_code = 'ep1822322763609cab5915c89',
            ep_nick = 'dangcue',
            ep_name = '제 22대 서울시의원 김미희',
            ep_mail = 'admin@dangcue.com',
            ep_phone = '02-123-4567',
            ep_display = 1";
    
    if (sql_query($sql)) {
        echo "✅ 기본 기업 데이터 입력 완료<br>";
    } else {
        echo "❌ 기업 데이터 입력 실패: " . mysqli_error($connect_db) . "<br>";
    }
}

// 기본 그룹 데이터 입력
$group_exists = sql_fetch("SELECT COUNT(*) as cnt FROM iw_group_list WHERE gp_code = 'all' AND ep_code = 'ep1822322763609cab5915c89'");
if ($group_exists['cnt'] == 0) {
    $sql = "INSERT INTO iw_group_list SET
            gp_code = 'all',
            ep_code = 'ep1822322763609cab5915c89',
            gp_nick = 'all',
            gp_name = '전체 그룹',
            gp_display = 1";
    
    if (sql_query($sql)) {
        echo "✅ 기본 그룹 데이터 입력 완료<br>";
    } else {
        echo "❌ 그룹 데이터 입력 실패: " . mysqli_error($connect_db) . "<br>";
    }
}

// 기본 설정 데이터 입력
$setting_exists = sql_fetch("SELECT COUNT(*) as cnt FROM iw_setting_list WHERE ep_code = 'ep1822322763609cab5915c89' AND gp_code = 'all'");
if ($setting_exists['cnt'] == 0) {
    $sql = "INSERT INTO iw_setting_list SET
            ep_code = 'ep1822322763609cab5915c89',
            gp_code = 'all',
            st_title = '제 22대 서울시의원 김미희',
            st_description = '제 22대 서울시의원 김미희 공식홈페이지입니다.',
            st_display = 1";
    
    if (sql_query($sql)) {
        echo "✅ 기본 설정 데이터 입력 완료<br>";
    } else {
        echo "❌ 설정 데이터 입력 실패: " . mysqli_error($connect_db) . "<br>";
    }
}

echo "<h3>관리자 계정 생성 중...</h3>";

// 관리자 계정 확인 및 생성
$admin_exists = sql_fetch("SELECT COUNT(*) as cnt FROM iw_member_list WHERE mb_level = 'admin' AND ep_code = 'ep1822322763609cab5915c89'");
if ($admin_exists['cnt'] == 0) {
    $password_hash = sql_password("admin123");
    $sql = "INSERT INTO iw_member_list SET
            mb_code = 'admin',
            ep_code = 'ep1822322763609cab5915c89',
            mb_mail = 'admin@example.com',
            mb_password = '$password_hash',
            mb_name = '관리자',
            mb_nick = '관리자',
            mb_level = 'admin',
            mb_display = 1";
    
    if (sql_query($sql)) {
        echo "<div style='background:green; color:white; padding:15px; margin:10px 0;'>";
        echo "🎉 <strong>관리자 계정이 성공적으로 생성되었습니다!</strong><br><br>";
        echo "<strong>로그인 정보:</strong><br>";
        echo "📧 아이디: admin@example.com<br>";
        echo "🔑 비밀번호: admin123<br>";
        echo "</div>";
    } else {
        echo "❌ 관리자 계정 생성 실패: " . mysqli_error($connect_db) . "<br>";
    }
} else {
    echo "ℹ️ 관리자 계정이 이미 존재합니다.<br>";
}

echo "<h3>완료!</h3>";
echo "<p>데이터베이스 초기화가 완료되었습니다.</p>";
echo "<br><a href='/bbs/m/all_login.php?ep=ep1822322763609cab5915c89&gp=all' style='background:blue; color:white; padding:15px; text-decoration:none; margin:5px;'>일반 로그인</a>";
echo "<a href='/bbs/super/login.php?ep=ep1822322763609cab5915c89&gp=all' style='background:red; color:white; padding:15px; text-decoration:none; margin:5px;'>Super 로그인</a>";
echo "<br><br><a href='/check_admin.php' style='background:gray; color:white; padding:10px; text-decoration:none;'>관리자 계정 확인</a>";

// 임시 파일 삭제 안내
echo "<hr>";
echo "<p style='color:orange;'><strong>보안을 위해 다음 파일들을 삭제하세요:</strong></p>";
echo "<ul>";
echo "<li>setup_database.php (이 파일)</li>";
echo "<li>check_admin.php</li>";
echo "<li>check_tables.php</li>";
echo "</ul>";
?> 



