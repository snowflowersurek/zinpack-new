<?php
include_once("_common.php");
if ($iw[level]=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");

$sql = "select * from $iw[shop_cart_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$result = sql_query($sql);
$a=0;
while($row = @sql_fetch_array($result)){
	$sc_no = $row["sc_no"];
	$sd_code = $row["sd_code"];
	$so_no = $row["so_no"];
	$sc_amount = $row["sc_amount"];

	$rowdata = sql_fetch(" select count(*) as cnt from $iw[shop_option_table] where ep_code = '$iw[store]' and sd_code = '$sd_code' and so_no = '$so_no' and so_amount > 0");
	if (!$rowdata[cnt]) {
		alert(national_language($iw[language],"a0222","품절되거나 삭제된 상품이 존재합니다. 해당상품은 장바구니에서 자동으로 삭제됩니다."),"$iw[m_path]/shop_cart_form.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	}else{
		$rowdata = sql_fetch(" select * from $iw[shop_option_table] where ep_code = '$iw[store]' and sd_code = '$sd_code' and so_no = '$so_no' and so_amount > 0");
		$so_amount = $rowdata["so_amount"];
		if ($so_amount < $sc_amount) {
			alert(national_language($iw[language],"a0223","선택한 수량보다 재고수량이 적은 상품이 존재합니다. 해당상품은 장바구니에서 최대수량으로 대체됩니다."),"$iw[m_path]/shop_cart_form.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
		}
	}
	$a++;
}
if($a == 0){
	alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"$iw[m_path]/shop_cart_form.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}

$sql = "delete from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and sr_display = 0";
sql_query($sql);
$sql = "delete from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and srs_display = 0";
sql_query($sql);

$microtimes = explode (" ", microtime());
$microsecond = explode (".", $microtimes[0]);
$sr_code = $microsecond[1];
$sr_code .= date("y");
$sr_code .= date("s");
$sr_code .= date("m");
$sr_code .= date("H");


$total_price = 0;
$total_taxfree = 0;
$total_delivery = 0;
$sd_code_check = "";
$mb_code_check = "";
$now_delivery = 0;
$now_sy_price = 0;
$product_count = 0;
$a = 0;

$sql = "select * from $iw[shop_cart_table] inner join $iw[shop_data_table] where $iw[shop_cart_table].sd_code = $iw[shop_data_table].sd_code and $iw[shop_cart_table].seller_mb_code = $iw[shop_data_table].mb_code and $iw[shop_cart_table].ep_code = '$iw[store]' and $iw[shop_cart_table].mb_code='$iw[member]' order by $iw[shop_cart_table].seller_mb_code asc, $iw[shop_data_table].sy_code asc, $iw[shop_cart_table].sd_code asc, $iw[shop_cart_table].so_no asc";
$result = sql_query($sql);

while($row = @sql_fetch_array($result)){
	$so_gp_code = $row["gp_code"];
	$sc_no = $row["sc_no"];
	$sd_code = $row["sd_code"];
	$seller_mb_code = $row["seller_mb_code"];
	$so_no = $row["so_no"];
	$sc_amount = $row["sc_amount"];
	$sd_delivery_type = $row["sd_delivery_type"];

	$rowdata = sql_fetch(" select * from $iw[shop_data_table] where ep_code = '$iw[store]' and sd_code = '$sd_code'");
	$sy_code = $rowdata["sy_code"];
	$sd_subject = $rowdata["sd_subject"];

	if($product_count == 0){
		$LGD_PRODUCTINFO = $sd_subject;
	}

	$rowdata = sql_fetch(" select * from $iw[shop_delivery_table] where ep_code = '$iw[store]' and mb_code = '$seller_mb_code' and sy_code='$sy_code' ");
	$sy_price = $rowdata["sy_price"];
	$sy_max = $rowdata["sy_max"];
	$sy_display = $rowdata["sy_display"];

	$rowdata = sql_fetch(" select * from $iw[shop_option_table] where ep_code = '$iw[store]' and sd_code = '$sd_code' and so_no = '$so_no'");
	$so_price = $rowdata["so_price"];
	$so_name = $rowdata["so_name"];
	$so_taxfree = $rowdata["so_taxfree"];

	if($sd_code != $sd_code_check){
		$sd_code_check = $sd_code;
		$rowcnt = sql_fetch(" select count(*) as cnt from $iw[shop_cart_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and sd_code = '$sd_code' ");
		$rowspan = $rowcnt[cnt];
		$a = 0;
	}

	if($seller_mb_code != $mb_code_check || $now_sy_code != $sy_code){//판매자나 배송코드 바뀌면
		$mb_code_check = $seller_mb_code;
		$now_delivery = $sc_amount; //현재 갯수
		$now_sy_code = $sy_code; //현재 배송코드
		$now_sy_price = $sy_price; //배송비 입력

		if($sy_display == 1){ //무료배송일경우
			$rowcnt = sql_fetch(" select SUM(so_price*sc_amount) as cnt from $iw[shop_cart_table] inner join $iw[shop_data_table] inner join $iw[shop_option_table] where $iw[shop_cart_table].sd_code = $iw[shop_data_table].sd_code and $iw[shop_cart_table].sd_code = $iw[shop_option_table].sd_code and $iw[shop_cart_table].so_no = $iw[shop_option_table].so_no and $iw[shop_cart_table].ep_code = '$iw[store]' and $iw[shop_cart_table].mb_code='$iw[member]' and $iw[shop_data_table].sy_code='$sy_code'");

			if($sy_max > $rowcnt[cnt]){ //상품갯수보다 얼마이상 무료가 많을경우
				$total_delivery += $now_sy_price; //배송비를 배송비합계에 추가
			}else{
				$now_sy_price = 0; //배송비 입력
			}
		}						
	}else{
		$now_delivery += $sc_amount; //현재 갯수에 갯수 추가
	}
	if($sy_display != 1){
		if($sy_max < $now_delivery){ //최대 개수보다 현재갯수가 많은 경우
			$now_sy_price += $sy_price * (floor($now_delivery / $sy_max));
			$now_delivery %= $sy_max;
		}
		$total_delivery += $now_sy_price; //배송비를 배송비합계에 추가
	}
	$total_price += $so_price*$sc_amount;

	if($so_taxfree == 1){
		$total_taxfree += $so_price*$sc_amount;
	}

	$sql2 = "insert into $iw[shop_order_sub_table] set
			ep_code = '$iw[store]',
			gp_code = '$so_gp_code',
			mb_code = '$iw[member]',
			sr_code = '$sr_code',
			sd_code = '$sd_code',
			seller_mb_code = '$seller_mb_code',
			so_no = '$so_no',
			srs_subject = '$sd_subject',
			srs_name = '$so_name',
			srs_amount  = '$sc_amount',
			srs_price  = '$so_price',
			srs_delivery_type  = '$sd_delivery_type',
			srs_bundle  = '$sy_max',
			srs_taxfree = '$so_taxfree'
			";
	sql_query($sql2);

	if($a == 0){
		$product_count++;
	}
	if($rowspan == $a+1){
		$sql2 = "update $iw[shop_order_sub_table] set
				srs_delivery_price  = '$now_sy_price'
				where ep_code = '$iw[store]' and gp_code='$iw[group]' and mb_code = '$iw[member]' and sr_code = '$sr_code' and sd_code = '$sd_code'";
		sql_query($sql2);
		$now_sy_price = 0;						
	}
	$a++;
}

$product_count--;
if($product_count > 0){
	$LGD_PRODUCTINFO = $LGD_PRODUCTINFO." + (".$product_count.national_language($iw[language],"a0215","개").")";
}

$sql = "select * from $iw[member_table] where mb_code = '$iw[member]'";
$row = sql_fetch($sql);

$mb_tel = explode("-", $row["mb_tel"]);
$mb_zip_code = $row["mb_zip_code"];

$sr_sum = $total_price+$total_delivery;
$sql = "insert into $iw[shop_order_table] set
		ep_code = '$iw[store]',
		mb_code = '$iw[member]',
		sr_code = '$sr_code',
		sr_sum  = '$sr_sum'
		";
sql_query($sql);

echo "<script>";
echo "var point_total = $row[mb_point];";
echo "var point_price = $sr_sum;";
echo "</script>";

if(preg_match('/(iPad|iPod|iPhone|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone)/i', $_SERVER['HTTP_USER_AGENT'])){
	$pay_device = "mobile";
}else{
	$pay_device = "pc";
}
if($iw[language]=="ko"){
	$form_action = "shop_pay_req.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];
	$rate_pv = 1;
}else if($iw[language]=="en"){
	$form_action = "shop_pay_road.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];
	$rate_pv = 1000;
}

$protocol = "http://";
if ($_SERVER["HTTP_HOST"] == "www.aviation.co.kr" || $_SERVER["HTTP_HOST"] == "www.info-way.co.kr") {
	$protocol = "https://";
}
$URLTORETURN		= $protocol.$_SERVER["SERVER_NAME"]."/bbs/m/shop_pay_res.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group];
$REQURL		= $protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
?>
<?if($iw[language]=="ko"){?><div id="LGD_ACTIVEX_DIV"></div><?}?> <!-- ActiveX 설치 안내 Layer 입니다. 수정하지 마세요. -->
<div class="content">
	<div class="row">
	    <div class="col-xs-12">
			<div class="breadcrumb-box input-group">
                <ol class="breadcrumb ">
					<li><a href="#"><?=$st_shop_name?> <?=national_language($iw[language],"a0245","주문/결제");?></a></li>
				</ol>
                <span class="input-group-btn">
					<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_cart_form.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0009","장바구니");?>"><i class="fa fa-shopping-cart fa-lg"></i></a>
					<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0008","구매내역");?>"><i class="fa fa-truck fa-lg"></i></a>
                </span>
            </div>
			
			<div class="masonry">
<?if($pay_device == "pc"){?>
				<!-- <div class="masonry-item w-full h-full">
					<div class="box br-theme text-center" style="background:#fbeeed;">
						<h3 style="margin-top:0;color:#dc3545;">모바일에서 결제해주시기 바랍니다.</h3>
						<span>
							현재 PC에서 결제 시 장애가 발생하여 안정화 작업 중에 있습니다.<br/>
							번거로우시겠지만 수정 및 안정화 작업이 완료될 때까지 모바일에서 결제해주시기 바랍니다.<br/>
							이용에 불편을 드려 죄송합니다.
						</span>
					</div>
				</div> -->
<?}?>
			<form id="LGD_PAYINFO" name="LGD_PAYINFO" action="<?=$form_action?>" method="post">
				<input type="hidden" name="PAYMENT_DEVICE" value="<?=$pay_device?>" />
				<input type="hidden" name="LGD_BUYER" value="<?=$row['mb_name']?>" />
				<input type="hidden" name="LGD_BUYERID" value="<?=$iw[member]?>" />
				<input type="hidden" name="LGD_PRODUCTINFO" value="<?=$LGD_PRODUCTINFO?>" />
				<input type="hidden" name="LGD_AMOUNT" value="<?=$sr_sum?>" />
				<input type="hidden" name="LGD_TAXFREEAMOUNT" value="<?=$total_taxfree?>" />
				<input type="hidden" name="LGD_BUYEREMAIL" value="<?=$row['mb_mail']?>" />
				<input type="hidden" name="LGD_OID" value="<?=$sr_code?>" />
				<input type="hidden" name="URL_TO_RETURN"	value="<?= $URLTORETURN ?>">
				<input type="hidden" name="REQUEST_URL"	value="<?= $REQURL ?>">

				<div class="grid-sizer"></div>

				<!-- 주문결제 -> 1. 구매자 정보 -->
				<div class="masonry-item w-full h-full">
					<div class="box br-theme">
						<h4 class="media-heading">1. <?=national_language($iw[language],"a0297","구매자 정보");?></h4>
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0070","이름");?></label>
							<input type="text" class="form-control" name="sr_buy_name" value="<?=$row['mb_name']?>" />
						</div>
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0076","휴대폰 번호");?></label>
							<div class="row">
								<div class="col-sm-4">
									<select class="form-control" name="sr_buy_phone_1">
										<option value="010" <?if($mb_tel[0] == "010"){?>checked<?}?>>010</option>
										<option value="011" <?if($mb_tel[0] == "011"){?>checked<?}?>>011</option>
										<option value="016" <?if($mb_tel[0] == "016"){?>checked<?}?>>016</option>
										<option value="017" <?if($mb_tel[0] == "017"){?>checked<?}?>>017</option>
										<option value="018" <?if($mb_tel[0] == "018"){?>checked<?}?>>018</option>
										<option value="019" <?if($mb_tel[0] == "019"){?>checked<?}?>>019</option>
									</select>
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="sr_buy_phone_2" maxlength="4" value="<?=$mb_tel[1]?>" />
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="sr_buy_phone_3" maxlength="4" value="<?=$mb_tel[2]?>" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0024","이메일");?></label>
							<input type="text" class="form-control" name="sr_buy_mail" value="<?=$row['mb_mail']?>" />
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
				
				<!-- 주문결제 -> 2. 받는사람 정보 -->
				<div class="masonry-item w-full h-full">
					<div class="box br-theme">
						<h4 class="media-heading">2. <?=national_language($iw[language],"a0298","받는사람 정보");?></h4>
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0070","이름");?></label>
							<input type="text" class="form-control" name="sr_name" value="<?=$row['mb_name']?>" />
						</div>
					<?if($iw[language]=="ko"){?>
						<div class="form-group">
							<label for="">휴대폰 번호</label>
							<div class="row">
								<div class="col-sm-4">
									<select class="form-control" name="sr_phone_1">
										<option value="010" <?if($mb_tel[0] == "010"){?>checked<?}?>>010</option>
										<option value="011" <?if($mb_tel[0] == "011"){?>checked<?}?>>011</option>
										<option value="016" <?if($mb_tel[0] == "016"){?>checked<?}?>>016</option>
										<option value="017" <?if($mb_tel[0] == "017"){?>checked<?}?>>017</option>
										<option value="018" <?if($mb_tel[0] == "018"){?>checked<?}?>>018</option>
										<option value="019" <?if($mb_tel[0] == "019"){?>checked<?}?>>019</option>
									</select>
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="sr_phone_2" maxlength="4" value="<?=$mb_tel[1]?>" />
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="sr_phone_3" maxlength="4" value="<?=$mb_tel[2]?>" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="">배송지 주소</label>
							<div class="row">
								<div class="col-sm-2">
									<label class="sr-only" for="mb_zip_code">우편번호</label>
									<input type="text" class="form-control" name="sr_zip_code" id="sr_zip_code" maxlength="7" placeholder="우편번호" readonly value="<?=$mb_zip_code?>" />
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-theme" onclick="fnFindAd('LGD_PAYINFO');">우편번호 검색</button>
								</div>
								<div class="col-sm-12">
									<label class="sr-only" for="sr_address">주소</label>
									<input type="text" class="form-control" name="sr_address" id="sr_address" placeholder="주소" readonly value="<?=$row['mb_address']?>" />
								</div>
								<div class="col-sm-12">
									<label class="sr-only" for="sr_address_sub">상세주소</label>
									<input type="text" class="form-control" name="sr_address_sub" id="sr_address_sub" placeholder="상세주소" value="<?=$row['mb_address_sub']?>" />
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label for="">추가 연락처</label>
							<div class="row">
								<div class="col-sm-4">
									<input type="text" class="form-control" name="sr_phone_sub_1" maxlength="3" value="<?=$mb_tel[0]?>" />
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="sr_phone_sub_2" maxlength="4" value="<?=$mb_tel[1]?>" />
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="sr_phone_sub_3" maxlength="4" value="<?=$mb_tel[2]?>" />
								</div>
							</div>
						</div>
					<?}else if($iw[language]=="en"){?>
						<div class="form-group">
							<label for="">Address Line1</label>
							<input type="text" class="form-control" name="sr_address" placeholder="Street address, P.O. box, company name, c/o" value="<?=$row['mb_address']?>" />
						</div>
						<div class="form-group">
							<label for="">Address Line2</label>
							<input type="text" class="form-control" name="sr_address_sub" placeholder="Apartment, suite, unit, building, floor, etc." value="<?=$row['mb_address_sub']?>" />
						</div>
						<div class="form-group">
							<label for="">City</label>
							<input type="text" class="form-control" name="sr_address_city" value="<?=$row['mb_address_city']?>" />
						</div>

						<div class="form-group">
							<label for="">County/Province/Region/State</label>
							<input type="text" class="form-control" name="sr_address_state" value="<?=$row['mb_address_state']?>" />
						</div>

						<div class="form-group">
							<label for="">Country</label>
							<select class="form-control" name="sr_address_country">
								<option value="">--</option>
							<?
								$sql2 = "select * from $iw[country_table] order by ct_no asc";
								$result2 = sql_query($sql2);

								while($row2 = @sql_fetch_array($result2)){
							?>
								<option value="<?=$row2["ct_code"];?>" <?if($row['mb_address_country']==$row2["ct_code"]){?>selected<?}?>><?=$row2["ct_name"];?></option>
							<?}?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="">Postal/Zip Code</label>
							<input type="text" class="form-control" name="sr_zip_code" value="<?=$row['mb_zip_code']?>" />
						</div>

						<div class="form-group">
							<label for="">Phone Number</label>
							<input type="text" class="form-control" name="sr_phone" value="<?=$row['mb_tel']?>" />
						</div>
						
						<div class="form-group">
							<label for="">Contact Number</label>
							<input type="text" class="form-control" name="sr_phone_sub" value="<?=$row['mb_tel']?>" />
						</div>
					<?}?>					
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0246","배송시 요청사항");?></label>
							<input type="text" class="form-control" name="sr_request" maxlength="100" />
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
				
				<!-- 주문결제 -> 3. 받는사람 정보 -->
				<div class="masonry-item w-full h-full">
					<div class="box br-theme">
						<h4 class="media-heading">3. <?=national_language($iw[language],"a0299","결제 정보");?></h4>
						
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0218","주문금액");?></label>
							<input type="text" class="form-control" value="<?=national_money($iw[language], $total_price+$total_delivery);?>" readonly />
						</div>
						
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0248","포인트로 사용할 현금 (최대");?> <?=national_money($iw[language], ($total_price+$total_delivery)*0.9);?>) - <?=national_language($iw[language],"a0131","보유 포인트");?> : <?=number_format($row['mb_point'])?>point = <?=national_money($iw[language], $row['mb_point']*$iw['shop_rate']);?></label>
							<input type="text" class="form-control" name="rate_point" maxlength="9" value="0" onKeyUp="onlyNum();" />
						</div>
						
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0249","차감될 포인트");?> ( <?=$rate_pv?>point = <?=national_money($iw[language], $iw[shop_rate]*1000);?>  )</label>
							<input type="hidden" name="exchange_rate" value="<?=$iw[shop_rate]?>" />
							<input type="hidden" name="sr_point" value="0" />
							<input type="text" class="form-control" name="rate_total" readonly value="- 0 point" />
						</div>
						
						<div class="form-group">
							<label for=""><?=national_language($iw[language],"a0134","결제금액");?></label>
							<input type="text" class="form-control" name="price_total" readonly value="<?=national_money($iw[language], $sr_sum);?>" />
						</div>
						

					<?if($iw[language]=="ko"){?>
						<label for="신용카드" class="radio-inline">
							<input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="SC0010" id="신용카드" checked> 신용카드
						</label>
						<!--
						<label for="휴대폰" class="radio-inline">
							<input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="SC0060" id="휴대폰"> 휴대폰
						</label>
						-->
						<? if(!preg_match('/(iPad|iPod|iPhone|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone)/i', $_SERVER['HTTP_USER_AGENT'])){?>
							<!-- <label for="계좌이체" class="radio-inline">
								<input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="SC0030" id="계좌이체"> 실시간 계좌이체
							</label> -->
						<?}?>
						<label for="가상계좌" class="radio-inline">
							<input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="SC0040" id="가상계좌"> 무통장(가상계좌)
							<span style="color:red;">※ 가상계좌 입금 후 취소 시, 3 영업일 이후에 환불이 완료됩니다.</span>
						</label>
					<?}else if($iw[language]=="en"){?>
						<label for="PAYPAL" class="radio-inline">
							<input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="PAYPAL" id="PAYPAL" checked /> Paypal <img src="<?=$iw[design_path]?>/img/pay_paypal.jpg">
						</label>
						
						<label for="ALIPAY" class="radio-inline">
							<input type="radio" name="LGD_CUSTOM_FIRSTPAY" value="ALIPAY" id="ALIPAY" /> Alipay <img src="<?=$iw[design_path]?>/img/pay_alipay.jpg">
						</label>
					<?}?>
					</div>
				</div>
			</form>
			
<?if($pay_device == "pc"){?>
				<!-- <div class="masonry-item w-full h-full">
					<div class="box br-theme text-center" style="background:#fbeeed;">
						<h3 style="margin-top:0;color:#dc3545;">모바일에서 결제해주시기 바랍니다.</h3>
						<span>
							현재 PC에서 결제 시 장애가 발생하여 안정화 작업 중에 있습니다.<br/>
							번거로우시겠지만 수정 및 안정화 작업이 완료될 때까지 모바일에서 결제해주시기 바랍니다.<br/>
							이용에 불편을 드려 죄송합니다.
						</span>
					</div>
				</div> -->
<?}?>
			</div> <!-- /.masonry -->
			<div class="col-sm-12">
				<div class="btn-list">
					<a href="javascript:check_form();" class="btn btn-theme"><?=national_language($iw[language],"a0251","바로 결제하기");?></a>
					<span style="color:red;">※ 결제를 완료하고 혹시라도 경고 페이지가 떴을 때, <strike>'뒤로가기'</strike> 절대 누르지 마시고, <strong>'무시하고 실행하기'</strong> 눌러야 정상적으로 주문이 이루어집니다.</span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div> <!-- .row -->
</div> <!-- .content -->

<script type="text/javascript">
	function check_form() {
		if (LGD_PAYINFO.sr_buy_name.value == ""){
			alert("<?=national_language($iw[language],'a0086','이름을 입력하여 주십시오.');?>");
			LGD_PAYINFO.sr_buy_name.focus();
			return;
		}
		if (LGD_PAYINFO.sr_buy_phone_2.value == ""){
			alert("구매자 휴대폰 번호를 입력하여 주십시오.");
			LGD_PAYINFO.sr_buy_phone_2.focus();
			return;
		}
		if (LGD_PAYINFO.sr_buy_phone_3.value == ""){
			alert("구매자 휴대폰 번호를 입력하여 주십시오.");
			LGD_PAYINFO.sr_buy_phone_3.focus();
			return;
		}
		if (LGD_PAYINFO.sr_buy_mail.value == ""){
			alert("<?=national_language($iw[language],'a0082','이메일을 입력하여 주십시오.');?>");
			LGD_PAYINFO.sr_buy_mail.focus();
			return;
		}
		if (LGD_PAYINFO.sr_buy_mail.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("<?=national_language($iw[language],'a0083','잘못된 이메일 주소입니다.');?>");
			LGD_PAYINFO.sr_buy_mail.focus();
			return;
		}

		if (LGD_PAYINFO.sr_name.value == ""){
			alert("<?=national_language($iw[language],'a0086','이름을 입력하여 주십시오.');?>");
			LGD_PAYINFO.sr_name.focus();
			return;
		}
		<?if($iw[language]=="ko"){?>
			if (LGD_PAYINFO.sr_phone_2.value == ""){
				alert("받는사람 휴대폰 번호를 입력하여 주십시오.");
				LGD_PAYINFO.sr_phone_2.focus();
				return;
			}
			if (LGD_PAYINFO.sr_phone_3.value == ""){
				alert("받는사람 휴대폰 번호를 입력하여 주십시오.");
				LGD_PAYINFO.sr_phone_3.focus();
				return;
			}
			if (LGD_PAYINFO.sr_address.value == ""){
				alert("배송지 주소를 입력하여 주십시오.");
				LGD_PAYINFO.sr_address.focus();
				return;
			}
			if (LGD_PAYINFO.sr_address_sub.value == ""){
				alert("배송지 상세주소를 입력하여 주십시오.");
				LGD_PAYINFO.sr_address_sub.focus();
				return;
			}
		<?}else if($iw[language]=="en"){?>
			if (LGD_PAYINFO.sr_address.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				LGD_PAYINFO.sr_address.focus();
				return;
			}
			if (LGD_PAYINFO.sr_address_sub.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				LGD_PAYINFO.sr_address_sub.focus();
				return;
			}
			if (LGD_PAYINFO.sr_address_city.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				LGD_PAYINFO.sr_address_city.focus();
				return;
			}
			if (LGD_PAYINFO.sr_address_state.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				LGD_PAYINFO.sr_address_state.focus();
				return;
			}
			if (LGD_PAYINFO.sr_address_country.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				LGD_PAYINFO.sr_address_country.focus();
				return;
			}
			if (LGD_PAYINFO.sr_zip_code.value == ""){
				alert("<?=national_language($iw[language],'a0089','우편번호를 입력하여 주십시오.');?>");
				LGD_PAYINFO.sr_zip_code.focus();
				return;
			}
			if (LGD_PAYINFO.sr_phone.value == ""){
				alert("<?=national_language($iw[language],'a0088','휴대폰 번호를 입력하여 주십시오.');?>");
				LGD_PAYINFO.sr_phone.focus();
				return;
			}
		<?}?>
		if (point_total < LGD_PAYINFO.sr_point.value){
			alert("<?=national_language($iw[language],'a0252','사용가능 포인트를 확인하여 주십시오.');?>");
			LGD_PAYINFO.sr_point.focus();
			return;
		}
		var e1 = LGD_PAYINFO.sr_point;
		var num ="0123456789";
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			e1.value="0";
		}

		LGD_PAYINFO.submit();
	}

	function onlyNum(){
		var e1 = event.srcElement;
		var num ="0123456789";
		event.returnValue = true;
	   
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
			if(i == 0 && e1.value.charAt(i) == "0")
			event.returnValue = false;
		}
		var e1_value = e1.value;
		<?php if($iw[language]=="en"){?>
			e1_value = e1.value*1000;
		<?php }?>
		var rate_value = Math.ceil(e1_value / Number(LGD_PAYINFO.exchange_rate.value));
		if (!event.returnValue || point_total < rate_value || Math.ceil(point_price*0.9) < e1_value ){
			e1_value="0";
			e1.value="0";
		}
		rate_value = Math.ceil(e1_value / Number(LGD_PAYINFO.exchange_rate.value));
		LGD_PAYINFO.sr_point.value = rate_value;
		LGD_PAYINFO.rate_total.value = "- "+commify(rate_value)+" PV";

		var total_return = point_price - e1_value;
		<?php if($iw[language]=="en"){?>
			total_return = total_return/1000;
		<?php }?>
		LGD_PAYINFO.price_total.value = <?php if($iw[language]=="en"){?>"US$ "+<?php }?>commify(total_return)<?php if($iw[language]=="ko"){?>+" 원"<?php }?>;
	}
	function commify(n){
    var reg = /(^[+-]?\d+)(\d{3})/;
    n += '';
    while (reg.test(n))   
        n = n.replace(reg, '$1' + ',' + '$2');
    return n;
	}

</script>
<!--  UTF-8 인코딩 사용 시는 xpay.js 대신 xpay_utf-8.js 을  호출하시기 바랍니다.-->
<script language="javascript" src="<?= $_SERVER['SERVER_PORT']!=443?"http":"https" ?>://xpay.uplus.co.kr<?=($CST_PLATFORM == "test")?($_SERVER['SERVER_PORT']!=443?":7080":":7443"):""?>/xpay/js/xpay_utf-8.js" type="text/javascript"></script>

<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function fnFindAd(f) {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
                var fullAddr = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수
                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
                }
                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if(data.userSelectedType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById("sr_zip_code").value = data.zonecode;
                document.getElementById("sr_address").value = fullAddr;

                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("sr_address_sub").focus();
            }
        }).open();
    }
</script>

<?
include_once("_tail.php");
?>