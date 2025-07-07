<?php
include_once("_common.php");
if ($iw[type] != "shop" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$sr_code = $_GET["idx"];

$sql = "select * from $iw[shop_order_table] where ep_code = '$iw[store]' and sr_code = '$sr_code' and sr_display <> 0";
$row = sql_fetch($sql);
if (!$row["sr_no"]) alert("잘못된 접근입니다!","");

$sr_no = $row["sr_no"];
$mb_code = $row["mb_code"];
$sr_buy_name = $row["sr_buy_name"];
$sr_buy_phone = $row["sr_buy_phone"];
$sr_buy_mail = $row["sr_buy_mail"];
$sr_request = $row["sr_request"];
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
$sr_sum = $row["sr_sum"];
$sr_point = number_format($row["sr_point"]);
$sr_datetime = $row["sr_datetime"];
$sr_pay = $row["sr_pay"];

$sql = "select ct_name from $iw[country_table] where ct_code = '$sr_address_country'";
$row = sql_fetch($sql);
$sr_address_country = $row["ct_name"];

$address = "[".$sr_zip_code."] ".$sr_address." ".$sr_address_sub;
if($sr_pay == "lguplus"){
	$pay_county = "ko";
	$sql = "select * from $iw[lgd_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$mb_code' and lgd_oid = '$sr_code' and lgd_display <> 0";
	//echo "sql=".$sql;
	$row = sql_fetch($sql);
	$lgd_amount = $row["lgd_amount"];
	$lgd_paytype = $row["lgd_paytype"];
	$lgd_financename = $row["lgd_financename"];
	$lgd_cardnointyn = $row["lgd_cardnointyn"];
	$lgd_accountowner = $row["lgd_accountowner"];
	$lgd_display = $row["lgd_display"];
	$lgd_payer = $row["lgd_payer"];
	$lgd_accountnum = $row["lgd_accountnum"];
	$lgd_mid = "PC/모바일";

}else if($sr_pay == "paypal"){
	$pay_county = "en";
	$address = "[".$sr_zip_code."] ".$sr_address_sub.", ".$sr_address.", ".$sr_address_city.", ".$sr_address_state.", ".$sr_address_country;
	$sql = "select * from $iw[paypal_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$mb_code' and pp_invoice = '$sr_code' and pp_display <> 0";
	$row = sql_fetch($sql);
	$lgd_amount = $row["pp_mc_gross"];
	$lgd_mid = "PAYPAL";
}else if($sr_pay == "alipay"){
	$pay_county = "en";
	$address = "[".$sr_zip_code."] ".$sr_address_sub.", ".$sr_address.", ".$sr_address_city.", ".$sr_address_state.", ".$sr_address_country;
	$sql = "select * from $iw[alipay_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$mb_code' and ap_out_trade_no = '$sr_code' and ap_display <> 0";
	$row = sql_fetch($sql);
	$lgd_amount = $row["ap_total_fee"];
	$lgd_mid = "ALIPAY";
}

?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-shopping-cart"></i>
			쇼핑몰
		</li>
		<li class="active">전체 주문조회</li>
	</ul><!-- .breadcrumb -->

	<!--<div class="nav-search" id="nav-search">
		<form class="form-search">
			<span class="input-icon">
				<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off">
				<i class="fa fa-search"></i>
			</span>
		</form>
	</div>--><!-- #nav-search -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			전체 주문조회
			<small>
				<i class="fa fa-angle-double-right"></i>
				상세
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<div class="row">
					<div class="col-xs-12">
						<h4 class="lighter"></h4>
						<section class="content-box">
							<div class="table-header">
								주문상품
							</div>
							<div class="table-set-mobile dataTable-wrapper">
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th style="text-align:center;width:15%">판매자코드</th>
											<th>상품명/옵션정보</th>
											<th style="text-align:center;width:8%">상품금액</th>
											<th style="text-align:center;width:5%">수량</th>
											<th style="text-align:center;width:8%">주문금액</th>
											<th style="text-align:center;width:5%">부가세</th>
											<th style="text-align:center;width:8%">배송비</th>
											<th style="text-align:center;width:5%">진행상태</th>
											<th style="text-align:center;width:25%">송장번호</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$total_price = 0;
										$total_delivery = 0;
										$sd_code_check = "";
										$mb_code_check = "";

										$sql = "select * from $iw[shop_order_sub_table] where mb_code = '$mb_code' and sr_code = '$sr_code' order by seller_mb_code asc, srs_bundle asc, srs_delivery_price desc, sd_code asc, so_no asc";
										$result = sql_query($sql);

										while($row = @sql_fetch_array($result)){
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
												$srs_delivery = $row["srs_delivery"];
												$dlvcomp = sql_fetch("SELECT * FROM $iw[delivery_info_table] WHERE de_code='$srs_delivery'");
											$srs_dlvcomp = $dlvcomp['de_name'];
											$srs_delivery_dt = substr($row["srs_delivery_dt"],0,10);
											$srs_display = $row["srs_display"];
											$srs_taxfree = $row["srs_taxfree"];

											if($srs_display==1){
												$qa_display = "주문";
											}else if($srs_display==2){
												$qa_display = "준비";
											}else if($srs_display==3){
												$qa_display = "배송";
											}else if($srs_display==4){
												$qa_display = "완료";
											}else if($srs_display==8){
												$qa_display = "취소";
											}else if($srs_display==9){
												$qa_display = "반품";
											}else if($srs_display==7){
												$qa_display = "결제취소";
											}else if($srs_display==5){
												$qa_display = "입금대기";
											}

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
											<td data-title="판매자코드" style="text-align:center;"><?=$seller_mb_code?></td>
											<td data-title="상품명/옵션정보">
												<a href="<?=$iw['admin_path']?>/shop_all_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$sd_code?>"><b><?=$srs_subject?></b></a><br />
												<span <?php if{?>style="text-decoration:line-through;"<?php }?>><?=$srs_name?></span>
											</td>
											<td data-title="상품금액" style="text-align:right;"><?=national_money($pay_county, $srs_price);?></td>
											<td data-title="수량" style="text-align:center;"><?=number_format($srs_amount)?></td>
											<td data-title="주문금액" style="text-align:right;"><b><?=national_money($pay_county, $srs_price*$srs_amount);?></b></td>
											<td data-title="부가세" style="text-align:center;"><?php if{?>면세<?php }else{?>포함<?php }?></td>
											<?php if($rowspan != 0){?>
											<td data-title="배송비" rowspan="<?=$rowspan?>" style="text-align:right;">
												<?php
													$total_delivery += $srs_delivery_price;
													echo national_money($pay_county, $srs_delivery_price);
												?>
											</td>
											<?php
													$rowspan = 0;
												}
											?>
											<td data-title="진행상태" style="text-align:center;"><?=$qa_display?></td>
											<td data-title="송장번호" style="text-align:center;">
												<?php // if($srs_delivery_num != "" && ($srs_display==3 || $srs_display==4)){ if($srs_display==3 || $srs_display==4){?>
													<?=$srs_dlvcomp?> (<?=$srs_delivery_num?>) / <?=$srs_delivery_dt?> 발송
												<?php }?>
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>주문 내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
							</div><!-- /.table-responsive -->
						</section>
					</div>
				</div>

				<div class="table-header">
					주문자정보
				</div>
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">주문번호</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$sr_code?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">주문일자</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$sr_datetime?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">주문자</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$sr_buy_name?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">연락처</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$sr_buy_phone?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이메일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$sr_buy_mail?></p>
						</div>
					</div>
				</form>

				<div class="table-header">
					받는사람 정보
				</div>
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">받는사람</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$sr_name?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">배송주소</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$address?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">연락처</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$sr_phone?> / <?=$sr_phone_sub?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">배송시 요청사항</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$sr_request?></p>
						</div>
					</div>
				</form>

				<div class="table-header">
					결제 정보
				</div>
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">주문금액</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><b><?=national_money($pay_county, $sr_sum);?></b></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">결제금액</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<b style="color:#e73535;"><?=national_money($pay_county, $lgd_amount);?></b> <?php if{?>(포인트 : <?=$sr_point?>Point)<?php }?><br/>
								<?php if($lgd_paytype=="SC0010"){?>
									신용카드 ( <?=$lgd_financename?> / <?php if{?>일시불<?php }else{?><?=$lgd_cardnointyn?>개월<?php }?> )
								<?php }else if($lgd_paytype=="SC0030"){?>
									계좌이체 ( <?=$lgd_financename?> / 계좌주 : <?=$lgd_accountowner?> )
								<?php }else if($lgd_paytype=="SC0060"){?>
									휴대폰 ( <?=$lgd_financename?> )
								<?php }else if($lgd_paytype=="SC0040"){?>
									가상계좌 ( <?=$lgd_financename?> <?=$lgd_accountnum?> / 입금자명 : <?=$lgd_payer?> )
								<?php }?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">결제기기</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$lgd_mid?></p>
						</div>
					</div>
				</form>

				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/shop_post_all_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
							<i class="fa fa-undo"></i>
							목록
						</button>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<script type="text/javascript">
	function win_open(url, name, option)
    {
        var popup = window.open(url, name, option);
        popup.focus();
    }
    function delivery_check(num)
    {
        url = "http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?ems_gubun=E&sid1="+num+"&POST_CODE=&mgbn=trace&traceselect=1&postNum="+num+"&x=27&y=1";
        win_open(url, "배송조회", "left=50,top=50,width=616,height=460,scrollbars=1");
    }
</script>

<?php
include_once("_tail.php");
?>



