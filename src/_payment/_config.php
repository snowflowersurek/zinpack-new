<?php
// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define("_PAYMENT_", TRUE);

if (function_exists("date_default_timezone_set"))
    date_default_timezone_set("Asia/Seoul");


// 서버의 시간과 실제 사용하는 시간이 틀린 경우 수정하세요.
// 하루는 86400 초입니다. 1시간은 3600초
// 6시간이 빠른 경우 time() + (3600 * 6);
// 6시간이 느린 경우 time() - (3600 * 6);
$payment['server_time'] = time();
$payment['time_ymd']    = date("Y-m-d", $payment['server_time']);
$payment['time_his']    = date("H:i:s", $payment['server_time']);
$payment['time_ymdhis'] = date("Y-m-d H:i:s", $payment['server_time']);

$payment['charset'] = "utf-8";

//==============================================================================
// DB_INFO
//==============================================================================
// 메인 애플리케이션과 동일한 환경 변수를 사용하도록 수정
$mysql_host = getenv('DB_HOST') ?: 'db';
$mysql_user = getenv('DB_USER') ?: 'root';
$mysql_password = getenv('DB_PASSWORD') ?: 'changeme';
$mysql_db = 'payment'; // _payment 모듈은 별도의 DB를 사용할 수 있습니다.

// 테이블명 접두사
$payment['table_prefix'] = "pay_";

$payment['site_user_table'] = $payment['table_prefix']."site_user";
$payment['lgd_request_table'] = $payment['table_prefix']."lgd_request";
$payment['lgd_response_table'] = $payment['table_prefix']."lgd_response";
$payment['cancel_request_table'] = $payment['table_prefix']."cancel_request";
$payment['cancel_response_table'] = $payment['table_prefix']."cancel_response";

$payment['cookie_domain'] = ".info-way.co.kr";
$payment['url'] = "http://www.info-way.co.kr";
$payment['https_url'] = "https://www.info-way.co.kr";
?>



