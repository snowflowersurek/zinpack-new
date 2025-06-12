<?php
include_once("_common.php");
if ($iw[level] != "seller") alert("잘못된 접근입니다!","");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>관리페이지</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="summary here">
    <meta name="author" content="">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<script language="JavaScript"> //링크 클릭시 테두리 제거.
		function bluring(){
			if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus();
		}
		document.onfocusin=bluring;
	</script>

	<style>
		html { overflow:scroll;overflow-x:auto;height:100%;}
		body { background-color:#ffffff; background-repeat:repeat; background-attachment:scroll; background-position:top left; font-family:굴림,gulim,굴림체; font-size:12px; color:#000000; margin:0px; word-break:break-word; height:100%; width:700px; line-height:150%;}
		table { border-collapse:collapse; width:100%;}
		div	{ width:100%; margin-bottom:5px;}
		.pageTitle		{ width:100%; text-align:center; font-size:20px; font-weight:bold; border:1px solid #000000; padding:8px 0;}
		.tdTitle		{ font-weight:bold;}
		.table1 td		{ padding:5px 0; border-top:1px dotted #000000; border-bottom:1px dotted #000000;}
		.table2 td		{ padding:7px 0; text-align:center;}
		.table3			{ border-top:3px solid #000000; border-bottom:3px solid #000000;}
		.table3 td		{ padding:6px 0; text-align:center;}
		.table4 td		{ padding:5px 0; text-align:center;}
		.table5 th		{ padding:5px 0; text-align:center; border-top:2px solid #000000; border-bottom:1px solid #000000;}
		.table5 td		{ padding:5px 5px; border-bottom:1px dotted #000000; text-align:center;}
	</style>

	<script language="javascript">
		function print_click(){
			window.print();
		}
	</script>
</head>
<body>
<div><input type="button" onclick="javascript:print_click();" value="인쇄"></div>
<?
$sr_code = $_GET["idx"];

$sql = "select * from $iw[shop_order_table] where ep_code = '$iw[store]' and sr_code = '$sr_code' and sr_display <> 0";
$row = sql_fetch($sql);
if (!$row["sr_no"]) alert("잘못된 접근입니다!","");

$sr_no = $row["sr_no"];
$mb_code = $row["mb_code"];
$sr_buy_name = $row["sr_buy_name"];
$sr_name = $row["sr_name"];
$sr_phone = $row["sr_phone"];
$sr_phone_sub = $row["sr_phone_sub"];
$sr_zip_code = $row["sr_zip_code"];
$sr_address = $row["sr_address"];
$sr_address_sub = $row["sr_address_sub"];
$sr_address_city = $row["sr_address_city"];
$sr_address_state = $row["sr_address_state"];
$sr_address_country = $row["sr_address_country"];
$sr_datetime = $row["sr_datetime"];
$sr_sum = $row["sr_sum"];
$sr_request = $row["sr_request"];
$sr_pay = $row["sr_pay"];

$sql = "select ct_name from $iw[country_table] where ct_code = '$sr_address_country'";
$row = sql_fetch($sql);
$sr_address_country = $row["ct_name"];

$address = "[".$sr_zip_code."] ".$sr_address." ".$sr_address_sub;
if($sr_pay == "lguplus"){
	$pay_county = "ko";
	$sql = "select * from $iw[lgd_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$mb_code' and lgd_oid = '$sr_code' and lgd_display <> 0";
	$row = sql_fetch($sql);
	$lgd_amount = $row["lgd_amount"];
	$lgd_paytype = $row["lgd_paytype"];
	$lgd_financename = $row["lgd_financename"];
	$lgd_cardnum = $row["lgd_cardnum"];
	$lgd_telno = $row["lgd_telno"];

}else if($sr_pay == "paypal"){
	$pay_county = "en";
	$address = "[".$sr_zip_code."] ".$sr_address_sub.", ".$sr_address.", ".$sr_address_city.", ".$sr_address_state.", ".$sr_address_country;
	$sql = "select * from $iw[paypal_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$iw[member]' and pp_invoice = '$sr_code' and pp_display <> 0";
	$row = sql_fetch($sql);
	$lgd_amount = $row["pp_mc_gross"];
}else if($sr_pay == "alipay"){
	$pay_county = "en";
	$address = "[".$sr_zip_code."] ".$sr_address_sub.", ".$sr_address.", ".$sr_address_city.", ".$sr_address_state.", ".$sr_address_country;
	$sql = "select * from $iw[alipay_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$iw[member]' and ap_out_trade_no = '$sr_code' and ap_display <> 0";
	$row = sql_fetch($sql);
	$lgd_amount = $row["ap_total_fee"];
}

$sql = "select * from $iw[shop_seller_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
$ss_name = $row["ss_name"];
$ss_tel = $row["ss_tel"];
?>
<div class="pageTitle">거&nbsp;&nbsp;&nbsp;래&nbsp;&nbsp;&nbsp;명&nbsp;&nbsp;&nbsp;세&nbsp;&nbsp;&nbsp;표 (판매자용)</div>
<div>
	<table>
		<tr>
			<td style="width:300px;">
				<table class="table1">
					<tr>
						<td>- 주문번호 :&nbsp;&nbsp;&nbsp;<?=$sr_code?></td>
					</tr>
					<tr>
						<td>- 주문자명 :&nbsp;&nbsp;&nbsp;<?=$sr_buy_name?></td>
					</tr>
					<tr>
						<td>- 결제방법 :&nbsp;&nbsp;&nbsp;<?if($lgd_paytype=="SC0010"){?>신용카드<?}else if($lgd_paytype=="SC0030"){?>계좌이체<?}else if($lgd_paytype=="SC0060"){?>휴대폰<?}else if($sr_pay=="paypal"){?>PAYPAL<?}else if($sr_pay=="alipay"){?>ALIPAY<?}?></td>
					</tr>
					<tr>
						<td>- 결제내용 :&nbsp;&nbsp;&nbsp;<?=$lgd_financename?><?if($lgd_paytype=="SC0010"){?><?=$lgd_cardnum?><?}else if($lgd_paytype=="SC0060"){?><?=$lgd_telno?><?}?></td>
					</tr>
					<tr>
						<td>- 주문시간 :&nbsp;&nbsp;&nbsp;<?=$sr_datetime?></td>
					</tr>
				</table>
			</td>
			<td style="width:400px;">
				<table class="table2">
					<tr>
						<td rowspan="4" style="width:50px;">공<br/>급<br/>자</td>
						<td style="width:80px;">사업자번호</td>
						<td colspan="3">119-81-93821</td>
					</tr>
					<tr>
						<td>상호</td>
						<td>(주)위즈윈디지털</td>
						<td>대표</td>
						<td>박비안네</td>
					</tr>
					<tr>
						<td>업태</td>
						<td>서비스</td>
						<td>종목</td>
						<td>통신판매업 외</td>
					</tr>
					<tr>
						<td>사업장소재지</td>
						<td colspan="3">경기도 파주시 송학2길 5-1</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<div>
	<table class="table3">
		<tr>
			<td rowspan="4" class="tdTitle" style="width:120px;">수령지 정보</td>
			<td class="tdTitle" style="width:150px;">수령인성명</td>
			<td style="text-align:left;"><?=$sr_name?></td>
		</tr>
		<tr>
			<td class="tdTitle">수령인전화</td>
			<td style="text-align:left;"><?=$sr_phone?> / <?=$sr_phone_sub?></td>
		</tr>
		<tr>
			<td class="tdTitle">도착지주소</td>
			<td style="text-align:left;"><?=$address?></td>
		</tr>
		<tr>
			<td class="tdTitle">운송방법</td>
			<td style="text-align:left;">CJ대한통운 택배 (고객센터 : 1588-1255)</td>
		</tr>
	</table>
</div>
<div>
	<table class="table4">
		<tr>
			<td class="tdTitle" style="width:120px;">세금계산서</td>
			<td style="text-align:left;">카드전표로 사용하세요.</td>
		</tr>
	</table>
</div>
<div>
	<table class="table5">
		<tr>
			<th style="width:100px;">제품번호</th>
			<th>제품명</th>
			<th style="width:60px;">수량</th>
			<th style="width:100px;">단가</th>
			<th style="width:100px;">합계</th>
			<th style="width:80px;">운송비</th>
		</tr>
	<?
		$total_price = 0;
		$total_delivery = 0;
		$sd_code_check = "";
		$mb_code_check = "";
		$i = 0;
		$sql = "select * from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$mb_code' and sr_code = '$sr_code' order by srs_bundle asc, srs_delivery_price desc, sd_code asc, so_no asc";
		$result = sql_query($sql);

		while($row = @sql_fetch_array($result)){
			$srs_no = $row["srs_no"];
			$sd_code = $row["sd_code"];
			$seller_mb_code = $row["seller_mb_code"];
			$so_no = $row["so_no"];
			$srs_amount = $row["srs_amount"];
			$srs_delivery_type = $row["srs_delivery_type"];
			$srs_price = $row["srs_price"];
			$srs_delivery_price = $row["srs_delivery_price"];
			$srs_bundle = $row["srs_bundle"];
			$srs_name = stripslashes($row["srs_name"]);
			$srs_subject = stripslashes($row["srs_subject"]);
			$srs_delivery_num = $row["srs_delivery_num"];
			$srs_display = $row["srs_display"];

			if($seller_mb_code != $mb_code_check){
				$mb_code_check = $seller_mb_code;
			}

			if($sd_code != $sd_code_check){
				$sd_code_check = $sd_code;
				$rowcnt = sql_fetch(" select count(*) as cnt from $iw[shop_order_sub_table] where  mb_code = '$mb_code' and sr_code = '$sr_code' and sd_code = '$sd_code'");
				$rowspan = $rowcnt[cnt];
			}

			$total_price += $srs_price*$srs_amount;
	?>
		<tr>
			<td>
				<?=$sd_code?>
			</td>
			<td>
				<b><?=$srs_subject?></b><br />
				<?=$srs_name?>
			</td>
			<td>
				<?=number_format($srs_amount)?>
			</td>
			<td>
				<?=national_money($pay_county, $srs_price);?>
			</td>
			<td>
				<?=national_money($pay_county, $srs_price*$srs_amount);?>
			</td>
			<?if($rowspan != 0){?>
			<td align="center" rowspan="<?=$rowspan?>">
				<?
					$total_delivery += $srs_delivery_price;
					echo national_money($pay_county, $srs_delivery_price);
				?>
			</td>
			<?
					$rowspan = 0;
				}
			?>
		</tr>
	<?
		$i++;
		}
	?>
		<tr>
			<td colspan="6" style="text-align:right; font-size:15px;">총 합계금액 : <b><?=national_money($pay_county, $sr_sum);?></b></td>
		</tr>
	</table>
</div>
<div>
	<b>[배송시 요청사항]</b><br/>
	<?=$sr_request?>
</div>
<div style="padding:10px 0;">
	[판매담당자] <?=$ss_name?> / <?=$ss_tel?>
</div>
<div>
	<b>[판매자 메모]</b><br/>
	<?
		$sql = "select * from $iw[shop_order_memo_table] where ep_code = '$iw[store]' and sr_code = '$sr_code' and seller_mb_code = '$iw[member]'";
		$row = sql_fetch($sql);
		echo str_replace("\r\n", "<br/>", $row["srm_content"]);
	?>
</div>



<!--고객용-->
<div class="pageTitle" style="page-break-before:always;">거&nbsp;&nbsp;&nbsp;래&nbsp;&nbsp;&nbsp;명&nbsp;&nbsp;&nbsp;세&nbsp;&nbsp;&nbsp;표 (고객용)</div>
<div>
	<table>
		<tr>
			<td style="width:300px;">
				<table class="table1">
					<tr>
						<td>- 주문번호 :&nbsp;&nbsp;&nbsp;<?=$sr_code?></td>
					</tr>
					<tr>
						<td>- 주문자명 :&nbsp;&nbsp;&nbsp;<?=$sr_buy_name?></td>
					</tr>
					<tr>
						<td>- 결제방법 :&nbsp;&nbsp;&nbsp;<?if($lgd_paytype=="SC0010"){?>신용카드<?}else if($lgd_paytype=="SC0030"){?>계좌이체<?}else if($lgd_paytype=="SC0060"){?>휴대폰<?}else if($sr_pay=="paypal"){?>PAYPAL<?}else if($sr_pay=="alipay"){?>ALIPAY<?}?></td>
					</tr>
					<tr>
						<td>- 결제내용 :&nbsp;&nbsp;&nbsp;<?=$lgd_financename?><?if($lgd_paytype=="SC0010"){?><?=$lgd_cardnum?><?}else if($lgd_paytype=="SC0060"){?><?=$lgd_telno?><?}?></td>
					</tr>
					<tr>
						<td>- 주문시간 :&nbsp;&nbsp;&nbsp;<?=$sr_datetime?></td>
					</tr>
				</table>
			</td>
			<td style="width:400px;">
				<table class="table2">
					<tr>
						<td rowspan="4" style="width:50px;">공<br/>급<br/>자</td>
						<td style="width:80px;">사업자번호</td>
						<td colspan="3">119-81-93821</td>
					</tr>
					<tr>
						<td>상호</td>
						<td>(주)위즈윈디지털</td>
						<td>대표</td>
						<td>박비안네</td>
					</tr>
					<tr>
						<td>업태</td>
						<td>서비스</td>
						<td>종목</td>
						<td>통신판매업 외</td>
					</tr>
					<tr>
						<td>사업장소재지</td>
						<td colspan="3">경기도 파주시 송학2길 5-1</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<div>
	<table class="table3">
		<tr>
			<td rowspan="4" class="tdTitle" style="width:120px;">수령지 정보</td>
			<td class="tdTitle" style="width:150px;">수령인성명</td>
			<td style="text-align:left;"><?=$sr_name?></td>
		</tr>
		<tr>
			<td class="tdTitle">수령인전화</td>
			<td style="text-align:left;"><?=$sr_phone?> / <?=$sr_phone_sub?></td>
		</tr>
		<tr>
			<td class="tdTitle">도착지주소</td>
			<td style="text-align:left;"><?=$address?></td>
		</tr>
		<tr>
			<td class="tdTitle">운송방법</td>
			<td style="text-align:left;">CJ대한통운 택배 (고객센터 : 1588-1255)</td>
		</tr>
	</table>
</div>
<div>
	<table class="table4">
		<tr>
			<td class="tdTitle" style="width:120px;">세금계산서</td>
			<td style="text-align:left;">카드전표로 사용하세요.</td>
		</tr>
	</table>
</div>
<div>
	<table class="table5">
		<tr>
			<th style="width:100px;">제품번호</th>
			<th>제품명</th>
			<th style="width:60px;">수량</th>
			<th style="width:100px;">단가</th>
			<th style="width:100px;">합계</th>
			<th style="width:80px;">운송비</th>
		</tr>
	<?
		$total_price = 0;
		$total_delivery = 0;
		$sd_code_check = "";
		$mb_code_check = "";
		$i = 0;
		$sql = "select * from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$mb_code' and sr_code = '$sr_code' order by srs_bundle asc, srs_delivery_price desc, sd_code asc, so_no asc";
		$result = sql_query($sql);

		while($row = @sql_fetch_array($result)){
			$srs_no = $row["srs_no"];
			$sd_code = $row["sd_code"];
			$seller_mb_code = $row["seller_mb_code"];
			$so_no = $row["so_no"];
			$srs_amount = $row["srs_amount"];
			$srs_delivery_type = $row["srs_delivery_type"];
			$srs_price = $row["srs_price"];
			$srs_delivery_price = $row["srs_delivery_price"];
			$srs_bundle = $row["srs_bundle"];
			$srs_name = $row["srs_name"];
			$srs_subject = $row["srs_subject"];
			$srs_delivery_num = $row["srs_delivery_num"];
			$srs_display = $row["srs_display"];

			if($seller_mb_code != $mb_code_check){
				$mb_code_check = $seller_mb_code;
			}

			if($sd_code != $sd_code_check){
				$sd_code_check = $sd_code;
				$rowcnt = sql_fetch(" select count(*) as cnt from $iw[shop_order_sub_table] where  mb_code = '$mb_code' and sr_code = '$sr_code' and sd_code = '$sd_code'");
				$rowspan = $rowcnt[cnt];
			}

			$total_price += $srs_price*$srs_amount;
	?>
		<tr>
			<td>
				<?=$sd_code?>
			</td>
			<td>
				<b><?=$srs_subject?></b><br />
				<?=$srs_name?>
			</td>
			<td>
				<?=number_format($srs_amount)?>
			</td>
			<td>
				<?=national_money($pay_county, $srs_price);?>
			</td>
			<td>
				<?=national_money($pay_county, $srs_price*$srs_amount);?>
			</td>
			<?if($rowspan != 0){?>
			<td align="center" rowspan="<?=$rowspan?>">
				<?
					$total_delivery += $srs_delivery_price;
					echo national_money($pay_county, $srs_delivery_price);
				?>
			</td>
			<?
					$rowspan = 0;
				}
			?>
		</tr>
	<?
		$i++;
		}
	?>
		<tr>
			<td colspan="6" style="text-align:right; font-size:15px;">총 합계금액 : <b><?=national_money($pay_county, $sr_sum);?></b></td>
		</tr>
	</table>
</div>
<div>
	<b>[배송시 요청사항]</b><br/>
	<?=$sr_request?>
</div>
<div style="padding:10px 0;">
	[판매담당자] <?=$ss_name?> / <?=$ss_tel?>
</div>
<div>
	<b>아래의 경우에는 환불,교환,반품이 되지 않습니다.</b><br/>
	- 교환 및 반품 가능 기간은 고객수령일로부터 7일입니다.<br/>
	- 고객의 부주의로 인한 내용물분실<br/>
	- 이상이 없는 제품의 비닐포장이 개봉되어 있는경우 제품외관손상등 발생시<br/>
	- 반송비용 : 제품의 하자등으로 인한 경우는 판매담당자가 부담합니다. 변심에 의한 교환, 반품을 하실 경우 왕복택배 비용을 고객님께서 부담하셔야 합니다.(색상 교환, 사이즈 교환, 모델 변경 등 포함)
</div>
</body>
</html>