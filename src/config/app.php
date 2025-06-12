<?php
// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define("_INFOWAY_", TRUE);

if (function_exists("date_default_timezone_set")) {
    date_default_timezone_set("Asia/Seoul");
}

// 기본 도메인 및 URL 설정
$iw['default_domain'] = "www.info-way.co.kr";
$iw['pay_site'] = "www.info-way.co.kr";
$iw['pay_platform'] = "service"; // 'test' or 'service'

// URL 자동 계산
if (isset($_SERVER['HTTP_HOST'])) {
    $iw['url'] = 'http://' . $_SERVER['HTTP_HOST'];
    $dir = dirname($_SERVER["PHP_SELF"]);
    
    // 이 설정 파일이 'config' 디렉토리에 있으므로, 상위 디렉토리로 조정
    $dir = dirname($dir); 
    
    // $iw['path'] 변수가 다른 곳에서 설정된다는 가정 하에, 그 값을 사용하여 URL을 완성할 수 있습니다.
    // 하지만 여기서는 독립적으로 작동하도록 단순화된 경로를 사용하거나,
    // $iw['path']가 설정된 후에 이 로직이 실행되도록 순서를 조정해야 합니다.
    // 지금은 common.php에서 $iw['path']가 설정된 후 이 값을 재계산하는 로직이 있으므로
    // 여기서는 기본값만 설정하고, 필요 시 common.php에서 덮어쓰도록 둡니다.
}


// 기본 Charset
$iw['charset'] = "utf-8";

// 서버 시간
$iw['server_time'] = time();
$iw['time_ymd']    = date("Y-m-d", $iw['server_time']);
$iw['time_his']    = date("H:i:s", $iw['server_time']);
$iw['time_ymdhis'] = date("Y-m-d H:i:s", $iw['server_time']);

// 기타 설정
$iw['re_url'] = isset($_SERVER['REQUEST_URI']) ? urlencode($_SERVER['REQUEST_URI']) : '';
$iw['server_path'] = "/www/infoway/_infoway"; // 이 값은 환경에 따라 달라질 수 있으므로 주의 