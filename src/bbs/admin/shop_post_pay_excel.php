<?php
include_once("_common.php");
if ($iw[type] != "shop" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=shop_sale_list_".date("Y_m_d_His").".xls" );

$search = $_GET[search];
$searchs = $_GET[searchs];
$start_date = $_GET[start_date];
$end_date = $_GET[end_date];

if($search =="g"){
	$search_sql = " AND (a.sr_code LIKE '%".$searchs."%' OR a.sr_code LIKE '%".$searchs."%' OR a.mb_code LIKE '%".$searchs."%' OR a.sd_code LIKE '%".$searchs."%' OR a.so_no LIKE '%".$searchs."%' OR b.sr_buy_name LIKE '%".$searchs."%' OR b.sr_phone LIKE '%$searchs%')";
}else if($search =="a"){
	$search_sql = " AND a.sr_code LIKE '%".$searchs."%'";
}else if($search =="b"){
	$search_sql = " AND a.mb_code LIKE '%".$searchs."%'";
}else if($search =="c"){
	$search_sql = " AND a.sd_code LIKE '%".$searchs."%'";
}else if($search =="d"){
	$search_sql = " AND a.so_no LIKE '%".$searchs."%'";
}else if($search =="f"){
	$search_sql = " AND b.sr_buy_name LIKE '%".$searchs."%'";
}else if($search =="h"){
	$search_sql = " AND b.sr_phone LIKE '%".$searchs."%'";
}else if($search =="j"){
	$search_sql = " AND a.seller_mb_code LIKE '%".$searchs."%'";
}

if($_GET[display]){
	$search_sql .= " AND a.srs_display = '".$_GET[display]."'";
}

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr style='background-color:#ccc; font-weight:bold;'>
	<td>번호</td>
	<td>그룹</td>
	<td>그룹코드</td>
	<td>판매자</td>
	<td>판매자코드</td>
	<td>구매자</td>
	<td>구매자코드</td>
	<td>주문일자</td>
	<td>주문번호</td>
	<td>주문자</td>
	<td>연락처</td>
	<td>상품명</td>
	<td>옵션</td>
	<td>금액</td>
	<td>수량</td>
	<td>주문금액</td>
	<td>배송비</td>
	<td>유효결제금액</td>
	<td>부가세</td>
	<td>상태</td>
	<td>결제방식</td>
	<td>결제일자</td>
	<td>결제응답</td>
	<td>결제금액</td>
	<td>입금액</td>
	<td>결제수단</td>
	<td>구매자명</td>
	<td>구매내역</td>
	<td>결제기관명</td>
	<td>신용카드번호</td>
	<td>휴대폰번호</td>
</tr>
";

$sr_code_check = "";
$sd_code_check = "";
$mb_code_check = "";

$sql = "
		SELECT 
			a.*,b.*,c.ep_corporate,d.gp_subject,e.ss_name,f.mb_name,g.* 
		FROM ".$iw[shop_order_sub_table]." a 
			LEFT JOIN ".$iw[shop_order_table]." b ON a.sr_code = b.sr_code 
			LEFT JOIN ".$iw[enterprise_table]." c ON a.ep_code = c.ep_code 
			LEFT JOIN ".$iw[group_table]." d ON a.gp_code = d.gp_code 
			LEFT JOIN ".$iw[shop_seller_table]." e ON a.seller_mb_code = e.mb_code 
			LEFT JOIN ".$iw[member_table]." f ON a.mb_code = f.mb_code 
			LEFT JOIN ".$iw[lgd_table]." g ON a.sr_code = g.lgd_oid 
		WHERE 
			a.srs_display <> 0 AND b.ep_code = '$iw[store]' AND b.sr_datetime >= '".$now_start."' AND b.sr_datetime <= '".$now_end."'".$search_sql." 
		ORDER BY 
			c.ep_corporate ASC, a.srs_taxfree ASC, b.sr_datetime DESC, a.seller_mb_code ASC, a.srs_bundle ASC, a.srs_delivery_price DESC, a.sd_code ASC, a.so_no ASC
";
$result = sql_query($sql);

$i = 1;
while($row = @sql_fetch_array($result)){
	$mb_code = $row[mb_code];
	$sr_code = $row[sr_code];
	$sd_code = $row[sd_code];
	$seller_mb_code = $row[seller_mb_code];
	$srs_subject = stripslashes($row[srs_subject]);
	$srs_name = stripslashes($row[srs_name]);
	$srs_amount = $row[srs_amount];
	$srs_price = $row[srs_price];
	$srs_delivery_price = $row[srs_delivery_price];
	$srs_display = $row[srs_display];
	$srs_taxfree = $row[srs_taxfree];
	$ep_code = $row[ep_code];
	$ep_corporate = $row[ep_corporate];
	$gp_code = $row[gp_code];
	$gp_subject = $row[gp_subject];
	$ss_name = $row[ss_name];
	$mb_name = $row[mb_name];
	$sr_buy_name = $row[sr_buy_name];
	$sr_phone = $row[sr_phone];
	$sr_datetime = $row[sr_datetime];
	$sr_pay = $row[sr_pay];
	$lgd_tid = $row[lgd_tid];
	$lgd_mid = $row[lgd_mid];
	$lgd_amount = $row[lgd_amount];
	$lgd_paytype = $row[lgd_paytype];
	$lgd_buyer = $row[lgd_buyer];
	$lgd_productinfo = $row[lgd_productinfo];
	$lgd_financename = $row[lgd_financename];
	$lgd_cardnum = $row[lgd_cardnum];
	$lgd_telno = $row[lgd_telno];
	$lgd_datetime = $row[lgd_datetime];
	$lgd_respmsg = $row[lgd_respmsg];
	$lgd_castamount = $row[lgd_castamount];

	if($lgd_paytype=="SC0010"){
		$lgd_paytype = "신용카드";
	}else if($lgd_paytype=="SC0030"){
		$lgd_paytype = "계좌이체";
	}else if($lgd_paytype=="SC0060"){
		$lgd_paytype = "휴대폰";
	}else if($lgd_paytype=="SC0040"){
		$lgd_paytype = "가상계좌";
	}

	if($srs_display == "1"){
		$displays = "주문";
	}else if($srs_display == "2"){
		$displays = "준비";
	}else if($srs_display == "3"){
		$displays = "배송";
	}else if($srs_display == "4"){
		$displays = "완료";
	}else if($srs_display == "8"){
		$displays = "<span style='color:red;'>취소</span>";
	}else if($srs_display == "9"){
		$displays = "<span style='color:red;'>반품</span>";
	}else if($srs_display == "7"){
		$displays = "<span style='color:red;'>결제취소</span>";
	}else if($srs_display == "5"){
		$displays = "<span style='color:red;'>입금대기</span>";
	}
	
	if($sr_pay == "paypal"){
		$srs_price = $srs_price / 1000;
		$srs_delivery_price = $srs_delivery_price / 1000;
	}
	$sr_total = $srs_price * $srs_amount;
	
	if($mb_code_check != $mb_code || $sr_code_check != $sr_code || $sd_code_check != $sd_code){
		if($mb_code_check != $mb_code) $mb_code_check = $mb_code;
		if($sr_code_check != $sr_code) $sr_code_check = $sr_code;
		if($sd_code_check != $sd_code) $sd_code_check = $sd_code;
	} else {
		$srs_delivery_price = "0";
	}

	if($srs_taxfree == 1){
		$srs_taxfree = "면세";
	}else{
		$srs_taxfree = "포함";
	}

	if($lgd_paytype == "SC0010"){
		$lgd_paytype = "신용카드";
	}else if($lgd_paytype == "SC0030"){
		$lgd_paytype = "계좌이체";
	}else if($lgd_paytype == "SC0060"){
		$lgd_paytype = "휴대폰";
	}else if($lgd_paytype == "SC0040"){
		$lgd_paytype = "가상계좌";
	}

	if ($srs_display == "5" || $srs_display == "7" || $srs_display == "8" || $srs_display == "9") {
		$srs_availableMoney = "0";
	} else {
		if ($lgd_paytype == "SC0040" && $lgd_castamount == "0") {
			$srs_availableMoney = "0";
		} else {
			$srs_availableMoney = $sr_total + $srs_delivery_price;
		}
	}

	echo "
		<tr>
		<td>".$i."</td>
		<td style='mso-number-format:\@;'>".$gp_subject."</td>
		<td style='mso-number-format:\@;'>".$gp_code."</td>
		<td style='mso-number-format:\@;'>".$ss_name."</td>
		<td style='mso-number-format:\@;'>".$seller_mb_code."</td>
		<td style='mso-number-format:\@;'>".$mb_name."</td>
		<td style='mso-number-format:\@;'>".$mb_code."</td>
		<td style='mso-number-format:\@;'>".$sr_datetime."</td>
		<td style='mso-number-format:\@;'>".$sr_code."</td>
		<td style='mso-number-format:\@;'>".$sr_buy_name."</td>
		<td style='mso-number-format:\@;'>".$sr_phone."</td>
		<td style='mso-number-format:\@;'>".$srs_subject."</td>
		<td style='mso-number-format:\@;'>".$srs_name."</td>
		<td style='mso-number-format:\#\,\#\#0;'>".$srs_price."</td>
		<td style='mso-number-format:\#\,\#\#0;'>".$srs_amount."</td>
		<td style='mso-number-format:\#\,\#\#0;'>".$sr_total."</td>
		<td style='mso-number-format:\#\,\#\#0;'>$srs_delivery_price</td>
		<td style='mso-number-format:\#\,\#\#0; background-color:#ff0;'>".$srs_availableMoney."</td>
		<td style='mso-number-format:\@;'>".$srs_taxfree."</td>
		<td style='mso-number-format:\@;'>".$displays."</td>
		<td style='mso-number-format:\@;'>".$sr_pay."</td>
		<td style='mso-number-format:\@;'>".$lgd_datetime."</td>
		<td style='mso-number-format:\@;'>".$lgd_respmsg."</td>
		<td style='mso-number-format:\#\,\#\#0;'>".$lgd_amount."</td>
		<td style='mso-number-format:\#\,\#\#0;'>".$lgd_castamount."</td>
		<td style='mso-number-format:\@;'>".$lgd_paytype."</td>
		<td style='mso-number-format:\@;'>".$lgd_buyer."</td>
		<td style='mso-number-format:\@;'>".$lgd_productinfo."</td>
		<td style='mso-number-format:\@;'>".$lgd_financename."</td>
		<td style='mso-number-format:\@;'>".$lgd_cardnum."</td>
		<td style='mso-number-format:\@;'>".$lgd_telno."</td>
		</tr>
		";
	$i ++;
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>