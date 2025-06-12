<?php
include_once("_common.php");
if ($iw[level] != "seller") alert("잘못된 접근입니다!","");

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=".date('Ymd His').".xls" );

$list_display = $_GET['display'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr>
<td>고객주문번호</td>
<td>받는분성명</td>
<td>받는분전화번호</td>
<td>받는분기타연락처</td>
<td>받는분우편번호</td>
<td>받는분주소(전체, 분할)</td>
<td>받는분상세주소(분할)</td>
<td>품목명</td>
<td>배달메세지1</td>
<td>주문일자</td>
<td>준비</td>
<td>주문</td>
<td>기타</td>
</tr>
";

$sql = "select * from $iw[shop_order_table] inner join $iw[shop_order_sub_table] where $iw[shop_order_table].sr_code = $iw[shop_order_sub_table].sr_code and $iw[shop_order_sub_table].ep_code = '$iw[store]' and $iw[shop_order_sub_table].seller_mb_code = '$iw[member]' and $iw[shop_order_sub_table].srs_display <> 0 and $iw[shop_order_sub_table].srs_display like '%$list_display%' and ($iw[shop_order_table].sr_datetime >= '$now_start' and $iw[shop_order_table].sr_datetime <= '$now_end') group by $iw[shop_order_table].sr_code order by $iw[shop_order_table].sr_datetime desc, $iw[shop_order_sub_table].srs_no desc";
$result = sql_query($sql);

$sr_code_check = "";
while($row = @sql_fetch_array($result)){
	$sr_code = $row["sr_code"];
	$sr_datetime = $row["sr_datetime"];
	$sr_name = $row["sr_name"];
	$sr_phone = $row["sr_phone"];
	$sr_phone_sub = $row["sr_phone_sub"];
	$sr_zip_code = $row["sr_zip_code"];
	$sr_address = $row["sr_address"];
	$sr_address_sub = $row["sr_address_sub"];
	$sr_address_city = $row["sr_address_city"];
	$sr_address_state = $row["sr_address_state"];
	$sr_address_country = $row["sr_address_country"];
	$sr_request = $row["sr_request"];
	$mb_code = $row["mb_code"];

	$sr_pay = $row["sr_pay"];

	$address = $sr_address;
	if($sr_pay == "lguplus"){
		$pay_county = "ko";
	}else if($sr_pay == "paypal"){
		$address = $sr_address.", ".$sr_address_city.", ".$sr_address_state.", ".$sr_address_country;
		$pay_county = "en";
	}

	echo "
	<tr>
	<td style='mso-number-format:\@;'>$sr_code</td>
	<td style='mso-number-format:\@;'>$sr_name</td>
	<td style='mso-number-format:\@;'>$sr_phone</td>
	<td style='mso-number-format:\@;'>$sr_phone_sub</td>
	<td style='mso-number-format:\@;'>$sr_zip_code</td>
	<td style='mso-number-format:\@;'>$address</td>
	<td style='mso-number-format:\@;'>$sr_address_sub</td>
	<td style='mso-number-format:\@;'>
	";
	
	$sql2 = "select * from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$mb_code' and sr_code = '$sr_code' and seller_mb_code = '$iw[member]' order by srs_bundle asc, srs_delivery_type desc, srs_delivery_price desc, sd_code asc, so_no asc";
	$result2 = sql_query($sql2);
	
	$srs_display_1 = 0;
	$srs_display_2 = 0;
	$srs_display_3 = 0;
	while($row2 = @sql_fetch_array($result2)){
		$srs_subject = stripslashes($row2["srs_subject"]);
		$srs_name = stripslashes($row2["srs_name"]);
		$srs_amount = $row2["srs_amount"];
		$srs_display = $row2["srs_display"];

		if($srs_display=="2"){
			$srs_display_1++;
		}else if($srs_display=="1"){
			$srs_display_2++;
		}else if($srs_display > $srs_display_3){
			$srs_display_3 = $srs_display;
		}
		echo "$srs_subject / $srs_name - $srs_amount 개";
	}
	
	echo "
	</td>
	<td style='mso-number-format:\@;'>$sr_request</td>
	<td style='mso-number-format:\@;'>$sr_datetime</td>
	<td style='mso-number-format:\@;'>
	";
	if($srs_display_1>0){
		echo "준비";
	}
	echo "</td><td style='mso-number-format:\@;'>";
	if($srs_display_2>0){
		echo "주문";
	}
	echo "</td><td style='mso-number-format:\@;'>";
	if($srs_display_3=="3"){
		echo "배송";
	}else if($srs_display_3=="4"){
		echo "완료";
	}else if($srs_display_3=="8"){
		echo "취소";
	}else if($srs_display_3=="9"){
		echo "반품";
	}else if($srs_display_3=="7"){
		echo "결제취소";
	}

	echo "
	</td>
	</tr>";
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>