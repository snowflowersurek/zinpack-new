<?php
include_once("_common.php");
if ($iw[level] != "seller") alert("잘못된 접근입니다!","");

$list_display = $_GET['display'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=주문내역_".date('Ymd_His')."(".$start_date."~".$end_date.").xls" );

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr>
<td>주문일자</td>
<td>주문번호</td>
<td>회원코드</td>
<td>이름</td>
<td>연락처</td>
<td>상품명/옵션</td>
<td>결제방식</td>
<td>상품금액</td>
<td>수량</td>
<td>주문금액</td>
<td>부가세</td>
<td>배송비</td>
<td>상태</td>
</tr>
";

$sr_code_check = "";
$sd_code_check = "";
$mb_code_check = "";

$sql = "select * from $iw[shop_order_sub_table] inner join $iw[shop_order_table] where $iw[shop_order_sub_table].sr_code = $iw[shop_order_table].sr_code and $iw[shop_order_sub_table].ep_code = '$iw[store]' and $iw[shop_order_sub_table].seller_mb_code = '$iw[member]' and $iw[shop_order_sub_table].srs_display <> 0 and ($iw[shop_order_table].sr_datetime >= '$now_start' and $iw[shop_order_table].sr_datetime <= '$now_end') order by $iw[shop_order_table].sr_datetime desc, seller_mb_code asc, srs_bundle asc, srs_delivery_price desc, sd_code asc, so_no asc";
$result = sql_query($sql);

$i=0;
while($row = @sql_fetch_array($result)){
	$srs_no = $row["srs_no"];
	$so_no = $row["so_no"];
	$mb_code = $row["mb_code"];
	$sr_code = $row["sr_code"];
	$sd_code = $row["sd_code"];
	$seller_mb_code = $row["seller_mb_code"];
	$srs_subject = stripslashes($row["srs_subject"]);
	$srs_name = stripslashes($row["srs_name"]);
	$srs_amount = $row["srs_amount"];
	$srs_price = $row["srs_price"];
	$srs_delivery_type = $row["srs_delivery_type"];
	$srs_delivery_price = $row["srs_delivery_price"];
	$srs_bundle = $row["srs_bundle"];
	$srs_display = $row["srs_display"];
	$srs_taxfree = $row["srs_taxfree"];

	if($srs_display=="1"){
		$displays = "주문";
	}else if($srs_display=="2"){
		$displays = "준비";
	}else if($srs_display=="3"){
		$displays = "배송";
	}else if($srs_display=="4"){
		$displays = "완료";
	}else if($srs_display=="8"){
		$displays = "취소";
	}else if($srs_display=="9"){
		$displays = "반품";
	}else if($srs_display=="7"){
		$displays = "결제취소";
	}else if($srs_display=="5"){
		$displays = "입금대기";
	}
	$sr_pay = $row["sr_pay"];
	if($sr_pay == "paypal"){
		$srs_price = $srs_price/1000;
		$srs_delivery_price = $srs_delivery_price/1000;
	}

	$sr_buy_name = $row["sr_buy_name"];
	$sr_phone = $row["sr_phone"];
	$sr_datetime = $row["sr_datetime"];
	$sr_total = $srs_price*$srs_amount;

	if($srs_taxfree==1){
		$srs_taxfree = "면세";
	}else{
		$srs_taxfree = "포함";
	}

	echo "
		<tr>
		<td style='mso-number-format:\@;'>$sr_datetime</td>
		<td style='mso-number-format:\@;'>$sr_code</td>
		<td style='mso-number-format:\@;'>$mb_code</td>
		<td style='mso-number-format:\@;'>$sr_buy_name</td>
		<td style='mso-number-format:\@;'>$sr_phone</td>
		<td style='mso-number-format:\@;'>$srs_subject / $srs_name</td>
		<td>$sr_pay</td>
		<td>$srs_price</td>
		<td>$srs_amount</td>
		<td>$sr_total</td>
		<td style='mso-number-format:\@;'>$srs_taxfree</td>
		<td style='mso-number-format:\@;'>
		";
	
	if($mb_code_check != $mb_code || $sr_code_check != $sr_code || $sd_code_check != $sd_code){
		echo $srs_delivery_price;
		if($mb_code_check != $mb_code) $mb_code_check = $mb_code;
		if($sr_code_check != $sr_code) $sr_code_check = $sr_code;
		if($sd_code_check != $sd_code) $sd_code_check = $sd_code;
	}

	echo "
		</td>
		<td style='mso-number-format:\@;'>$displays</td>
		</tr>
		";
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>