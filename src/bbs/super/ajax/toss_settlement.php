<?php
header('Content-Type: text/html; charset=utf-8');
$iw_path = "../../.."; // common.php 의 상대 경로
include_once("$iw_path/include/common.php");

$sk = base64_encode("live_sk_dP9BRQmyarYOK0y0Og93J07KzLNk:");
$from_date = date('Y-m-d', time()-86400);
$to_date = date("Y-m-d");
$ch = curl_init(); 
$url = "https://api.tosspayments.com/v1/settlements?dateType=paidOutDate&startDate=$from_date&endDate=$to_date";
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
$headers[] = "Authorization: Basic ".$sk;
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_ENCODING, "");
curl_setopt ($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
curl_setopt ($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
	//echo $response;
	$res_ary = json_decode($response);
	foreach($res_ary as $row){
		$oid = $row->orderId;
		$sql = "UPDATE iw_lgd SET lgd_in_date = '".$row->paidOutDate."', lgd_telno='1' WHERE lgd_oid = '".$oid."'";
		sql_query($sql);
	}
	echo "OK";
}
?>
