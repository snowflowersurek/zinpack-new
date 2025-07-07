<?php
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting(E_ALL ^ E_NOTICE);

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);

// 짧은 환경변수를 지원하지 않는다면 (PHP 구버전용, 제거 가능)
if (isset($HTTP_POST_VARS) && !isset($_POST)) {
	$_POST   = &$HTTP_POST_VARS;
	$_GET    = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_ENV    = &$HTTP_ENV_VARS;
	$_FILES  = &$HTTP_POST_FILES;

    if (!isset($_SESSION))
		$_SESSION = &$HTTP_SESSION_VARS;
}

// magic_quotes_gpc 관련 코드는 PHP 5.4부터 제거되었으므로 삭제합니다.

if (isset($_GET['iw_path']) || isset($_POST['iw_path']) || isset($_COOKIE['iw_path'])) {
    unset($_GET['iw_path'], $_POST['iw_path'], $_COOKIE['iw_path'], $iw_path);
}

//==========================================================================================================================
// XSS(Cross Site Scripting) 공격에 의한 데이터 검증 및 차단
// (이 함수는 lib/lib_common.php 로 옮기는 것을 고려해볼 수 있습니다)
//--------------------------------------------------------------------------------------------------------------------------
function xss_clean($data) { 
    // ... 함수 내용 ...
} 

$_GET = xss_clean($_GET);

//==========================================================================================================================
// 전역 변수 관련 처리 (extract)
// (보안에 취약하므로 점진적으로 제거하는 것이 좋습니다)
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    if (isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
//==========================================================================================================================


// --- 설정 파일 로드 ---
// $iw_path 변수는 index.php 와 같은 진입점에서 정의되어야 합니다.
if (!isset($iw_path)) {
    die('Critical error: `$iw_path` is not defined.');
}
$iw['path'] = $iw_path;
unset($iw_path);

// 새로운 설정 파일 로드
require_once("{$iw['path']}/config/app.php");
require_once("{$iw['path']}/config/database.php");
require_once("{$iw['path']}/config/paths.php");
require_once("{$iw['path']}/config/constants.php");

// 공통 라이브러리 로드
require_once("{$iw['path']}/include/lib/lib_common.php");


// URL 경로 재계산 (app.php의 기본값을 덮어쓸 수 있음)
if (!isset($iw['url'])) {
    $iw['url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}";
    $dir = dirname($_SERVER["PHP_SELF"]);
    
    // config.php 위치에 따라 경로를 조정했던 로직을 현재 구조에 맞게 수정
    if (strpos(__FILE__, 'include') !== false) {
        $dir = dirname($dir);
    }
    
    $cnt = substr_count($iw['path'], "..");
    for ($i=2; $i<=$cnt; $i++) {
        $dir = dirname($dir);
    }
    $iw['url'] .= $dir;
}
$iw['url'] = rtrim(strtr($iw['url'], "\\", "/"), "/");

//==============================================================================
// 공통DB 연결
//==============================================================================

$db_conn = sql_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$db_conn) {
	die("<meta http-equiv='content-type' content='text/html; charset={$iw['charset']}'><script type='text/javascript'> alert('DB 접속 오류'); </script>");
}

unset($mysql_host, $mysql_user, $mysql_password, $mysql_db); // 민감한 정보 변수 해제

//==============================================================================
// 도메인 검사 및 리디렉션
//==============================================================================
$_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF']);

if($iw['default_domain'] != $_SERVER["HTTP_HOST"]) {
	$dmn = explode(".", $_SERVER['HTTP_HOST']);
	$sub_domain = $dmn[0];
	$main_domain = $dmn[1];
	if (count($dmn) > 2) {
		for ($i=2; $i<count($dmn); $i++) {
			$main_domain .= ".".$dmn[$i];
		}
	} else {
		$sub_domain = "www";
		$main_domain = $_SERVER['HTTP_HOST'];
	}

    $row = null;
    $sql = "select ep_code, ep_nick from {$iw['enterprise_table']} where ep_domain = ?";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $main_domain);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

	if($row['ep_nick']){
		$ep_code = $row['ep_code'];
		$return_domain = "www.".$main_domain."/main/".$row['ep_nick'];
	}

	if($sub_domain != "www"){
        $row = null;
        $sql = "select gp_nick from {$iw['group_table']} where ep_code = ? and gp_nick = ?";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $ep_code, $sub_domain);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

		if($row['gp_nick']){
			$return_domain .= "/".$sub_domain;
		}else{
			exit;
		}
	}

	$protocol = "http://";
	if ($_SERVER["HTTP_HOST"] == "www.aviation.co.kr" || $_SERVER["HTTP_HOST"] == "www.info-way.co.kr") {
		$protocol = "https://";
	}

	header("location: ".$protocol.$return_domain);
	//header("location: http://".$return_domain);
}


