<?php
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting(E_ALL ^ E_NOTICE);

// mysqli 전역 변수
$db_conn = null;

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

//
// Modern PHP SQL Injection protection
// magic_quotes_gpc가 PHP 7.4에서 제거되었으므로 현대적인 방식으로 변경
//
function array_map_recursive($callback, $array) {
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = array_map_recursive($callback, $value);
        } else {
            $array[$key] = call_user_func($callback, $value);
        }
    }
    return $array;
}

// addslashes 적용 (PHP 8 호환)
if (is_array($_GET)) {
    $_GET = array_map_recursive('addslashes', $_GET);
}

if (is_array($_POST)) {
    $_POST = array_map_recursive('addslashes', $_POST);
}

if (is_array($_COOKIE)) {
    $_COOKIE = array_map_recursive('addslashes', $_COOKIE);
}

if ((isset($_GET['payment_path']) && $_GET['payment_path']) || (isset($_POST['payment_path']) && $_POST['payment_path']) || (isset($_COOKIE['payment_path']) && $_COOKIE['payment_path'])) {
	if(isset($_GET['payment_path'])) unset($_GET['payment_path']);
	if(isset($_POST['payment_path'])) unset($_POST['payment_path']);
    unset($_COOKIE['payment_path']);
    unset($payment_path);
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

// index.php 가 있는곳의 상대경로
$payment['path'] = $payment_path;

// 경로의 오류를 없애기 위해 $payment_path 변수는 해제
unset($payment_path);

include_once("_config.php");  // 설정 파일

/*************************************************************************
**
**  SQL 관련 함수 모음
**
*************************************************************************/

// DB 연결
function sql_connect($host, $user, $pass, $db)
{
    global $db_conn;
    $db_conn = @mysqli_connect($host, $user, $pass, $db);
    if (mysqli_connect_errno()) {
        die("DB 연결 실패: " . mysqli_connect_error());
    }
    mysqli_set_charset($db_conn, "utf8");
    return $db_conn;
}


// DB 선택
function sql_select_db($db, $connect)
{
    // mysqli_connect 에서 DB를 선택하므로 이 함수는 더 이상 사용되지 않음
    // 하위 호환성을 위해 함수는 남겨둠
    return true;
}


// $result에 대한 메모리(memory)에 있는 내용을 모두 제거한다.
// sql_free_result()는 결과로부터 얻은 질의 값이 커서 많은 메모리를 사용할 염려가 있을 때 사용된다.
// 단, 결과 값은 스크립트(script) 실행부가 종료되면서 메모리에서 자동적으로 지워진다.
function sql_free_result($result)
{
    return mysqli_free_result($result);
}

function sql_real_escape_string($str)
{
    global $db_conn;
    return mysqli_real_escape_string($db_conn, $str);
}

function sql_password($value)
{
    // PHP 5.5.0 이상에서 지원되는 password_hash() 함수를 사용합니다.
    // BCRYPT는 현재 가장 강력한 알고리즘 중 하나입니다.
    return password_hash($value, PASSWORD_BCRYPT);
}


// PHPMyAdmin 참고
function get_table_define($table, $crlf="\n")
{
    global $payment, $db_conn;

    // 테이블 이름 이스케이프 처리
    $escaped_table = mysqli_real_escape_string($db_conn, $table);

    // For MySQL < 3.23.20
    $schema_create .= 'CREATE TABLE ' . $escaped_table . ' (' . $crlf;

    $sql = 'SHOW FIELDS FROM ' . $escaped_table;
    $result = mysqli_query($db_conn, $sql);
    while ($row = mysqli_fetch_assoc($result))
    {
        $schema_create .= '    ' . $row['Field'] . ' ' . $row['Type'];
        if (isset($row['Default']) && $row['Default'] != '')
        {
            $schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
        }
        if ($row['Null'] != 'YES')
        {
            $schema_create .= ' NOT NULL';
        }
        if ($row['Extra'] != '')
        {
            $schema_create .= ' ' . $row['Extra'];
        }
        $schema_create     .= ',' . $crlf;
    } // end while
    mysqli_free_result($result);

    $schema_create = preg_replace('/,' . $crlf . '$/', '', $schema_create);

    $sql = 'SHOW KEYS FROM ' . $escaped_table;
    $result = mysqli_query($db_conn, $sql);
    while ($row = mysqli_fetch_assoc($result))
    {
        $kname    = $row['Key_name'];
        $comment  = (isset($row['Comment'])) ? $row['Comment'] : '';
        $sub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';

        if ($kname != 'PRIMARY' && $row['Non_unique'] == 0) {
            $kname = "UNIQUE|$kname";
        }
        if ($comment == 'FULLTEXT') {
            $kname = 'FULLTEXT|$kname';
        }
        if (!isset($index[$kname])) {
            $index[$kname] = array();
        }
        if ($sub_part > 1) {
            $index[$kname][] = $row['Column_name'] . '(' . $sub_part . ')';
        } else {
            $index[$kname][] = $row['Column_name'];
        }
    } // end while
    mysqli_free_result($result);

    foreach($index as $x => $columns) {
        $schema_create     .= ',' . $crlf;
        if ($x == 'PRIMARY') {
            $schema_create .= '    PRIMARY KEY (';
        } else if (substr($x, 0, 6) == 'UNIQUE') {
            $schema_create .= '    UNIQUE ' . substr($x, 7) . ' (';
        } else if (substr($x, 0, 8) == 'FULLTEXT') {
            $schema_create .= '    FULLTEXT ' . substr($x, 9) . ' (';
        } else {
            $schema_create .= '    KEY ' . $x . ' (';
        }
        $schema_create     .= implode($columns, ', ') . ')';
    } // end while

    if (strtolower($payment['charset']) == "utf-8")
        $schema_create .= $crlf . ') DEFAULT CHARSET=utf8';
    else
        $schema_create .= $crlf . ')';

    return $schema_create;
} // end of the 'PMA_getTableDef()' function


// 메타태그를 이용한 URL 이동
// header("location:URL") 을 대체
function goto_url($url)
{
    echo "<script type='text/javascript'> location.replace('$url'); </script>";
    exit;
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
    global $payment;

    setcookie(md5($cookie_name), base64_encode($value), $expire, '/', $payment['cookie_domain']);
}


// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    $cookie_key = md5($cookie_name);
    return isset($_COOKIE[$cookie_key]) ? base64_decode($_COOKIE[$cookie_key]) : '';
}

// 경고메세지를 경고창으로
function alert($msg='', $url='')
{
	global $payment;

    if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	//header("Content-Type: text/html; charset=$g4[charset]");
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset={$payment['charset']}\">";
	echo "<script type='text/javascript'>alert('$msg');";
    if (!$url)
        echo "history.go(-1);";
    echo "</script>";
    if ($url)
        goto_url($url);
    exit;
}


// config.php 가 있는곳의 웹경로
if (!$payment['url'])
{
    $payment['url'] = 'http://' . $_SERVER['HTTP_HOST'];
    $dir = dirname($_SERVER["PHP_SELF"]);
    if (!file_exists("config.php"))
        $dir = dirname($dir);
    $cnt = substr_count($payment['path'], "..");
    for ($i=2; $i<=$cnt; $i++)
        $dir = dirname($dir);
    $payment['url'] .= $dir;
}
// \ 를 / 롤 변경
$payment['url'] = strtr($payment['url'], "\\", "/");
// url 의 끝에 있는 / 를 삭제한다.
$payment['url'] = preg_replace("/\/$/", "", $payment['url']);

//==============================================================================
// 공통DB
//==============================================================================

$connect_db = sql_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);

unset($my); // DB 설정값을 클리어 해줍니다.

$_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF']);
?>



