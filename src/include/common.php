<?php
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting(E_ALL ^ E_NOTICE);

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);

// 짧은 환경변수를 지원하지 않는다면
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

if (isset($_GET['iw_path']) || isset($_POST['iw_path']) || isset($_COOKIE['iw_path'])) {
    unset($_GET['iw_path'], $_POST['iw_path'], $_COOKIE['iw_path'], $iw_path);
}


//==========================================================================================================================
// XSS(Cross Site Scripting) 공격에 의한 데이터 검증 및 차단
//--------------------------------------------------------------------------------------------------------------------------
function xss_clean($data) 
{ 
    // If its empty there is no point cleaning it :\ 
    if(empty($data)) 
        return $data; 
         
    // Recursive loop for arrays 
    if(is_array($data)) 
    { 
        foreach($data as $key => $value) 
        { 
            $data[$key] = xss_clean($value); 
        } 
         
        return $data; 
    } 
     
    // http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php 
    // +----------------------------------------------------------------------+ 
    // | Copyright (c) 2001-2006 Bitflux GmbH                                 | 
    // +----------------------------------------------------------------------+ 
    // | Licensed under the Apache License, Version 2.0 (the "License");      | 
    // | you may not use this file except in compliance with the License.     | 
    // | You may obtain a copy of the License at                              | 
    // | http://www.apache.org/licenses/LICENSE-2.0                           | 
    // | Unless required by applicable law or agreed to in writing, software  | 
    // | distributed under the License is distributed on an "AS IS" BASIS,    | 
    // | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      | 
    // | implied. See the License for the specific language governing         | 
    // | permissions and limitations under the License.                       | 
    // +----------------------------------------------------------------------+ 
    // | Author: Christian Stocker <chregu@bitflux.ch>                        | 
    // +----------------------------------------------------------------------+ 
     
    // Fix &entity\n; 
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data); 
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/', '$1;', $data); 
    $data = preg_replace('/(&#x*[0-9A-F]+);*/i', '$1;', $data); 

    if (function_exists("html_entity_decode"))
    {
        $data = html_entity_decode($data); 
    }
    else
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        $data = strtr($data, $trans_tbl);
    }

    // Remove any attribute starting with "on" or xmlns 
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#i', '$1>', $data); 

    // Remove javascript: and vbscript: protocols 
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2nojavascript...', $data); 
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2novbscript...', $data); 
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#', '$1=$2nomozbinding...', $data); 

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span> 
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data); 
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data); 
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#i', '$1>', $data); 

    // Remove namespaced elements (we do not need them) 
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data); 

    do 
    { 
        // Remove really unwanted tags 
        $old_data = $data; 
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data); 
    } 
    while ($old_data !== $data); 
     
    return $data; 
} 

$_GET = xss_clean($_GET);
//==========================================================================================================================


//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}
//==========================================================================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

// --- 설정 파일 로드 ---
if (!isset($iw_path)) {
    die('Critical error: `$iw_path` is not defined.');
}
$iw['path'] = $iw_path;
unset($iw_path);

require_once("{$iw['path']}/config/app.php");
require_once("{$iw['path']}/config/database.php");
require_once("{$iw['path']}/config/paths.php");
require_once("{$iw['path']}/config/constants.php");

$iw['type'] = $_GET['type'] ?? '';
if (empty($_GET['ep'])) exit('Error: ep is missing.');
$iw['store'] = $_GET['ep'];
if (empty($_GET['gp'])) exit('Error: gp is missing.');
$iw['group'] = $_GET['gp'];

$main_domain = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']);
$iw['cookie_domain'] = "." . $main_domain;

require_once("{$iw['path']}/include/lib/lib_common.php"); 

//==============================================================================
// 공통DB 연결
//==============================================================================
$db_conn = sql_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$db_conn) {
	die("<meta http-equiv='content-type' content='text/html; charset={$iw['charset']}'><script type='text/javascript'> alert('DB 접속 오류'); </script>");
}
unset($mysql_host, $mysql_user, $mysql_password, $mysql_db);

$_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF']);

//포인트 환율
$sql_master = "select ma_buy_rate, ma_sell_rate, ma_shop_rate from {$iw['master_table']} where ma_no = 1";
$result_master = mysqli_query($db_conn, $sql_master);
$row_master = mysqli_fetch_assoc($result_master);
$iw['buy_rate'] = ($row_master["ma_buy_rate"] ?? 100)/100;
$iw['sell_rate'] = ($row_master["ma_sell_rate"] ?? 100)/100;
$iw['shop_rate'] = ($row_master["ma_shop_rate"] ?? 100)/100;

// 언어설정
$sql_ent = "select ep_language, ep_anonymity, ep_domain, ep_expiry_date from {$iw['enterprise_table']} where ep_code = ?";
$stmt_ent = mysqli_prepare($db_conn, $sql_ent);
mysqli_stmt_bind_param($stmt_ent, "s", $iw['store']);
mysqli_stmt_execute($stmt_ent);
$result_ent = mysqli_stmt_get_result($stmt_ent);
$row_ent = mysqli_fetch_assoc($result_ent);
mysqli_stmt_close($stmt_ent);
$iw['language'] = $row_ent["ep_language"] ?? "korean";
$iw['anonymity'] = $row_ent["ep_anonymity"] ?? "N";
$iw['expiry_date'] = $row_ent["ep_expiry_date"] ?? "";
$ep_domain = $row_ent["ep_domain"] ?? "";

if ($iw['group'] != "all") {
	$sql_group = "select gp_language from {$iw['group_table']} where ep_code = ? and gp_code = ?";
    $stmt_group = mysqli_prepare($db_conn, $sql_group);
    mysqli_stmt_bind_param($stmt_group, "ss", $iw['store'], $iw['group']);
    mysqli_stmt_execute($stmt_group);
    $result_group = mysqli_stmt_get_result($stmt_group);
	$gp_row = mysqli_fetch_assoc($result_group);
    mysqli_stmt_close($stmt_group);
	$iw['language'] = $gp_row["gp_language"];
}

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://";

/*
if("www.".$ep_domain != $_SERVER["HTTP_HOST"] && $iw['default_domain'] != $_SERVER["HTTP_HOST"]){
	header("location: ".$protocol.$iw['default_domain'].$_SERVER['REQUEST_URI']);
    exit;
}
*/