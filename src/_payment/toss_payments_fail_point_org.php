<?php
include_once("_common.php");
include_once("_check_parameter.php");

$message = $_GET['message'];
$code = $_GET['code'];

$return_domain = get_cookie("payment_domain");

$protocol = "http://";

if ($PAYMENT_DOMAIN == "www.aviation.co.kr") {
	$protocol = "https://";
}

alert("결제승인 요청이 실패하였습니다.\n[오류코드] $code\n[오류메세지] $message",$protocol.$return_domain."/bbs/m/all_point_charge.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]");

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <title>결제 실패</title>
	<meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
</head>
<body>
<section>
    <h1>결제요청 실패</h1>
    <p><?php echo $message ?></p>
    <span>에러코드: <?php echo $code ?></span>
</section>
</body>
</html>




