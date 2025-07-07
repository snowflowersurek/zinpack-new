<?php
// mysqli 전역 변수
$db_conn = null;

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


// mysql_query 와 mysql_error 를 한꺼번에 처리
function sql_query($sql, $error=TRUE)
{
    global $db_conn;
    if ($error)
        $result = @mysqli_query($db_conn, $sql) or die("<p>$sql<p>" . mysqli_errno($db_conn) . " : " .  mysqli_error($db_conn) . "<p>error file : $_SERVER[PHP_SELF]");
    else
        $result = @mysqli_query($db_conn, $sql);
    return $result;
}


// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql, $error=TRUE)
{
    $result = sql_query($sql, $error);
    //$row = @sql_fetch_array($result) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : $_SERVER[PHP_SELF]");
    $row = sql_fetch_array($result);
    return $row;
}


// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    $row = @mysqli_fetch_assoc($result);
    return $row;
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
    // mysql 4.0x 이하 버전에서는 password() 함수의 결과가 16bytes
    // mysql 4.1x 이상 버전에서는 password() 함수의 결과가 41bytes
    $row = sql_fetch(" select password('$value') as pass ");
    return $row['pass'];
}


// PHPMyAdmin 참고
function get_table_define($table, $crlf="\n")
{
    global $iw;

    // For MySQL < 3.23.20
    $schema_create .= 'CREATE TABLE ' . $table . ' (' . $crlf;

    $sql = 'SHOW FIELDS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
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
    sql_free_result($result);

    $schema_create = preg_replace('/,' . $crlf . '$/', '', $schema_create);

    $sql = 'SHOW KEYS FROM ' . $table;
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
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
    sql_free_result($result);

    while (list($x, $columns) = @each($index)) {
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

    if (strtolower($iw['charset']) == "utf-8")
        $schema_create .= $crlf . ') DEFAULT CHARSET=utf8';
    else
        $schema_create .= $crlf . ')';

    return $schema_create;
} // end of the 'PMA_getTableDef()' function


// 한글 요일
function get_yoil($date, $full=0)
{
    $arr_yoil = array ("일", "월", "화", "수", "목", "금", "토");

    $yoil = date("w", strtotime($date));
    $str = $arr_yoil[$yoil];
    if ($full) {
        $str .= "요일";
    }
    return $str;
}


// 날짜를 select 박스 형식으로 얻는다
function date_select($date, $name="")
{
    global $iw;

    $s = "";
    if (substr($date, 0, 4) == "0000") {
        $date = $iw['time_ymdhis'];
    }
    preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

    // 년
    $s .= "<select name='{$name}_y'>";
    for ($i=$m[0]-3; $i<=$m[0]+3; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[0]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>년 \n";

    // 월
    $s .= "<select name='{$name}_m'>";
    for ($i=1; $i<=12; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[2]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>월 \n";

    // 일
    $s .= "<select name='{$name}_d'>";
    for ($i=1; $i<=31; $i++) {
        $s .= "<option value='$i'";
        if ($i == $m[3]) {
            $s .= " selected";
        }
        $s .= ">$i";
    }
    $s .= "</select>일 \n";

    return $s;
}

// 한글 한글자(2byte, 유니코드 3byte)는 길이 2, 공란.영숫자.특수문자는 길이 1
// 유니코드는 http://g4uni.winnwe.net/bbs/board.php?bo_table=g4uni_faq&wr_id=7 의 Mr.Learn님의 글을 참고하였습니다.
function cut_str($str, $len, $suffix="…")
{
    global $iw;

    if (strtoupper($iw['charset']) == 'UTF-8') {
        $c = substr(str_pad(decbin(ord($str[$len])),8,'0',STR_PAD_LEFT),0,2); 
        if ($c == '10') 
            for (;$c != '11' && $c[0] == 1;$c = substr(str_pad(decbin(ord($str[--$len])),8,'0',STR_PAD_LEFT),0,2)); 
        return substr($str,0,$len) . (strlen($str)-strlen($suffix) >= $len ? $suffix : ''); 
    } else {
        $s = substr($str, 0, $len);
        $cnt = 0;
        for ($i=0; $i<strlen($s); $i++)
            if (ord($s[$i]) > 127)
                $cnt++;
        $s = substr($s, 0, $len - ($cnt % 2));
        if (strlen($s) >= strlen($str))
            $suffix = "";
        return $s . $suffix;
    }
}

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
    global $iw;

    setcookie(md5($cookie_name), base64_encode($value), $expire, '/', $iw['cookie_domain']);
}


// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    $cookie = md5($cookie_name);
    if (isset($_COOKIE[$cookie])) {
        return base64_decode($_COOKIE[$cookie]);
    }
    return '';
}

// 경고메세지를 경고창으로
function alert($msg='', $url='')
{
	global $iw;

    if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	//header("Content-Type: text/html; charset=$g4[charset]");
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset={$iw['charset']}\">";
	echo "<script type='text/javascript'>alert('$msg');";
    if (!$url)
        echo "history.go(-1);";
    echo "</script>";
    if ($url)
        // 4.06.00 : 불여우의 경우 아래의 코드를 제대로 인식하지 못함
        //echo "<meta http-equiv='refresh' content='0;url=$url'>";
        goto_url($url);
    exit;
}


// 국가별 언어설정
function national_language($country, $search)
{
	if (!$country) {
		$country = "ko";
	}
	$fp = fopen("../../include/language/$country.txt", "r"); //읽기 모드로 text문서 열기
	while(!feof($fp)) {//텍스트 문서에서 한줄한줄 읽어와 배열에 저장
		$txt_buffer = fgets($fp, 4096);
		$txt_divide = explode(" ",$txt_buffer);
		if($search === $txt_divide[0]){
			$txt_return = str_replace($txt_divide[0], "", $txt_buffer);
			break;
		}
	}
	fclose($fp);//파일 닫기

	return trim($txt_return);
}

// 국가별 화폐설정
function national_money($country, $price)
{
	if($country=="ko"){
		$price_return = number_format($price)." 원";
	}else if($country=="en"){
		$price = $price/1000;
		$price = sprintf("%1.2f", $price);
		$price = explode(".", $price); 
		$price_return = "US$ ".number_format($price[0]).".".$price[1];
	}
	
	return $price_return;
}

function get_shortURL($longURL) {
	$chBit_ID = "infowayglobal"; 
	$chBit_APIKey = "R_8bd24707280741c6a7bf3c890361e0fb";
	$chBit_LongUrl = $longURL; //단축URL로 만들 주소
	$chBit_Request_Url = "http://api.bit.ly/v3/shorten?login=".$chBit_ID."&apiKey=".$chBit_APIKey."&longUrl=".$chBit_LongUrl;

	$cURL = curl_init();
	curl_setopt($cURL, CURLOPT_HEADER, 0);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_URL, $chBit_Request_Url);
	$data = curl_exec($cURL);
	curl_close($cURL);
	$aResult = json_decode($data);
	if ($aResult->status_code == 200) {
		$chShort_URL = $aResult->data->url;
	} else {
		$chErrMsg = $aResult->status_txt;
		$chShort_URL = $chBit_LongtUrl;
	}
	return $chShort_URL;
}

//RGB색상값
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

function validateDate($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function get_receipt($orderid, $type) {
	$secretKey = "live_sk_dP9BRQmyarYOK0y0Og93J07KzLNk";
	$credential = base64_encode($secretKey . ':');
	$ret = array();

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.tosspayments.com/v1/payments/orders/".$orderid,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "UTF-8",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			"Authorization: Basic ".$credential
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);
	$responseJson = json_decode($response);

	if($type=="1"){
		$ret['url'] = $responseJson->card->receiptUrl;
		$ret['txt'] = "매출전표 출력";
	}else if($type=="2"){
		$ret['url'] = $responseJson->cashReceipt->receiptUrl;
		$ret['txt'] = "현금영수증 출력";
	}else{
		$ret = NULL;
	}
	return $ret;
}

function passwordCheck($_str) {
    $pw = $_str;
    //$num = preg_match('/[0-9]/u', $pw);
    $eng = preg_match('/[a-z]/u', $pw);
    $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u",$pw);
 
    if(strlen($pw) < 8){
        return array(false,"비밀번호는 영문, 특수문자를 혼합하여 최소 8자리 이상으로 입력해주세요.");
        exit;
    }
 
    if(preg_match("/\s/u", $pw) == true){
        return array(false, "비밀번호는 공백없이 입력해주세요.");
        exit;
    }
 
    if( $eng == 0 || $spe == 0){
        return array(false, "영문, 숫자, 특수문자를 혼합하여 입력해주세요.");
        exit;
    }
    return array(true);
}