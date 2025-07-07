<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");

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
$sr_datetime = $row["sr_datetime"];
$sr_sum = $row["sr_sum"];
$sr_point = number_format($row["sr_point"]);
$sr_pay = $row["sr_pay"];

$sql = "select * from $iw[shop_order_memo_table] where ep_code = '$iw[store]' and sr_code = '$sr_code' and seller_mb_code = '$iw[member]'";
$row = sql_fetch($sql);
$srm_content = $row["srm_content"];

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
		<li class="active">주문관리</li>
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
			주문관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				수정
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
								<form id="frmorderform" name="frmorderform" action="<?=$iw['admin_path']?>/shop_post_store_display.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th style="width:3%;text-align:center">선택</th>
											<th style="width:20%;text-align:center">상품명/옵션정보</th>
											<th style="width:8%;text-align:center">상품금액</th>
											<th style="width:5%;text-align:center">수량</th>
											<th style="width:8%;text-align:center">주문금액</th>
											<th style="width:8%;text-align:center">부가세</th>
											<th style="width:8%;text-align:center">배송비</th>
											<th style="width:5%;text-align:center">진행상태</th>
											<th>송장번호</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$total_price = 0;
										$total_delivery = 0;
										$sd_code_check = "";
										$mb_code_check = "";
										$i = 0;
										$sql = "select * from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$mb_code' and sr_code = '$sr_code' and seller_mb_code = '$iw[member]' order by srs_bundle asc,  sd_code asc, so_no asc";
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
											$srs_del_code = $row["srs_delivery"];
											$srs_delivery_num = $row["srs_delivery_num"];
											$srs_delivery_dt = $row["srs_delivery_dt"];
											if($srs_delivery_dt=="" || $srs_delivery_dt=="0000-00-00 00:00:00"){
												$srs_delivery_dt = date("Y-m-d");
											}else{
												$srs_delivery_dt = substr($srs_delivery_dt,0,10);
											}
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
											}else if($srs_display=="7"){
												$qa_display = "결제취소";
											}else if($srs_display=="5"){
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
											<td data-title="선택"><input type="checkbox" id='ct_chk_<?=$i?>' name='ct_chk[<?=$i?>]' value='1'></td>
											<td data-title="상품명/옵션정보">
												<a href="<?=$iw['admin_path']?>/shop_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$sd_code?>"><b><?=$srs_subject?></b></a><br />
												<span <?php if{?>style="text-decoration:line-through;"<?php }?>><?=$srs_name?></span>
											</td>
											<td data-title="상품금액" style="text-align:right"><?=national_money($pay_county, $srs_price);?></td>
											<td data-title="수량" style="text-align:center"><?=number_format($srs_amount)?></td>
											<td data-title="주문금액" style="text-align:right"><b><?=national_money($pay_county, $srs_price*$srs_amount);?></b></td>
											<td data-title="부가세" style="text-align:right"><?php if{?>면세<?php }else{?>포함<?php }?></td>
											<?php if($rowspan != 0){?>
											<td data-title="배송비" rowspan="<?=$rowspan?>" style="text-align:right">
												<?php
													$total_delivery += $srs_delivery_price;
													echo national_money($pay_county, $srs_delivery_price);
												?>
											</td>
											<?php
													$rowspan = 0;
												}
											?>
											<td data-title="진행상태" style="text-align:center"><?=$qa_display?></td>
											<td data-title="송장번호">
												<input type="hidden" name="srs_no[]" value="<?=$srs_no?>" />
												<select name="dlv_comp_code[]" class="plain_sel">
													<option value="">택배사를 선택하세요</option>
													<?php
														$desql = "SELECT * FROM $iw[delivery_info_table] where de_active=1";
														$deresult = sql_query($desql);
														while($derow = sql_fetch_array($deresult)){
													?>
													<option value="<?=$derow['de_code']?>" <?php echo ($srs_del_code==$derow['de_code'])?"selected":""; ?>><?=$derow['de_name']?></option>
													<?php
														}
													?>
												</select>
												<input type="text" name="srs_delivery_num[]" maxlength="20" value="<?=$srs_delivery_num?>" />
												<input type="date" name="dlv_datetime[]" value="<?=$srs_delivery_dt?>" />
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>주문 내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="col-sm-12" style="padding:15px;">
									<span style="color:red;font-weight:bold">반드시 배송정보(송장번호 포함)를 입력하셔야 정상적인 정산이 되오니 관리 부탁드립니다!!!</span>
								</div>
								<input type="hidden" name="sr_code" value="<?=$sr_code?>" />
								<input type="hidden" name="ct_status" value="" />
								<input type="hidden" name="chk_cnt" value="<?=$i?>" />
								</form>
								<div class="row">
									<div class="col-sm-9">
										<button class="btn btn-default" type="button" onclick="form_submit(5);">
											<i class="fa fa-check"></i>
											입금대기
										</button>
										<button class="btn btn-primary" type="button" onclick="form_submit(1);">
											<i class="fa fa-check"></i>
											주문
										</button>
										<button class="btn btn-primary" type="button" onclick="form_submit(2);">
											<i class="fa fa-check"></i>
											준비
										</button>
										<button class="btn btn-info" type="button" onclick="form_submit(3);">
											<i class="fa fa-check"></i>
											배송
										</button>
										<button class="btn btn-success" type="button" onclick="form_submit(4);">
											<i class="fa fa-check"></i>
											완료
										</button>
										<button class="btn btn-warning" type="button" onclick="form_submit(8);">
											<i class="fa fa-check"></i>
											취소
										</button>
										<button class="btn btn-danger" type="button" onclick="form_submit(9);">
											<i class="fa fa-check"></i>
											반품
										</button>
									</div>
									<!-- <div class="col-sm-3">
										<div class="dataTable-option-right">
											<button class="btn btn-default" type="button" onclick="form_submit(0);">
												<i class="fa fa-undo"></i>
												운송장 일괄
											</button>
										</div>
									</div> -->
								</div>
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
				<form class="form-horizontal no-input-detail" id="srm_form" name="srm_form" action="<?=$iw['admin_path']?>/shop_post_store_memo.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
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

					<div class="form-group">
						<label class="col-sm-1 control-label">판매자 메모</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<input type="hidden" name="memo_sr_code" value="<?=$sr_code?>" />
								<textarea name="srm_content" class="col-xs-12 col-sm-8" style="height:100px;"><?=$srm_content?></textarea>
								<button class="btn btn-primary" type="button" onclick="javascript:srm_form.submit();">
									<i class="fa fa-check"></i>
									메모 저장
								</button>
							</p>
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
							<p class="col-xs-12 col-sm-8 form-control-static"><b><?=national_money($pay_county, $total_price+$total_delivery);?></b></p>
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
						<button class="btn btn-primary" type="button" onclick="javascript:print_page('<?=$iw[store]?>', '<?=$iw[group]?>', '<?=$sr_code?>');">
							<i class="fa fa-check"></i>
							거래명세표
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/shop_post_store_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
	var srs_display = '<?=$srs_display?>';
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
	function print_page(ep, gp, sr_code)
    {
        url = "shop_post_store_print.php?ep="+ep+"&gp="+gp+"&idx="+sr_code;
        win_open(url, "거래명세표", "left=50,top=50,width=720,height=800,scrollbars=1");
    }

	var select_all_sw = false;
	var visible_sw = false;
	// 전체선택, 전체해제
	function select_all()
	{
		var f = document.frmorderform;
	 
		for (i=0; i<f.chk_cnt.value; i++)
		{
			if (select_all_sw == false)
				document.getElementById('ct_chk_'+i).checked = true;
			else
				document.getElementById('ct_chk_'+i).checked = false;
		}
	 
		if (select_all_sw == false)
			select_all_sw = true;
		else
			select_all_sw = false;
	}

	function form_submit(status)
	{
		var f = document.frmorderform;

		if (status != 0) {
			var check = false;
			for (i=0; i<f.chk_cnt.value; i++) {
				if (document.getElementById('ct_chk_'+i).checked == true){
					if(status == 3){
						var comp_input = document.getElementsByName('dlv_comp_code[]');
						if(comp_input[i].value==""){
							alert("택배사를 선택해주세요.");
							return;
						}
						var dlv_input = document.getElementsByName('srs_delivery_num[]');
						if(dlv_input[i].value.trim()=="" && comp_input[i].value!="OT"){
							alert("송장번호를 입력해주세요.");
							return;
						}
					}
					check = true;
				}
			}
			
			if (check == false) {
				alert("처리할 자료를 하나 이상 선택해 주십시오.");
				return;
			}
		}

		var status_text = "";

		if (status == 1){
			status_text = "주문";
		}else if (status == 2){
			status_text = "준비";
		}else if (status == 3){
			status_text = "배송";
		}else if (status == 4){
			if(srs_display != '3'){
				alert("완료 전에 먼저 [배송] 처리가 되어야 합니다.");
				return;
			}
			status_text = "완료";
		}else if (status == 5){
			status_text = "입금대기";
		}else if (status == 8){
			status_text = "취소";
		}else if (status == 9){
			status_text = "반품";
		}else if (status == 0){
			status_text = "운송장";
			alert("현재 작업중입니다..");
			return;
		}

		if (confirm("\'" + status_text + "\'을(를) 선택하셨습니다.\n\n이대로 처리 하시겠습니까?") == true) {
			f.ct_status.value = status;
			f.submit();
		}
	 
		return;
	}
	function partial_cancel(ep,gp,sr_code)
	{
		if (confirm("부분취소를 진행 하시겠습니까?\n확인을 누르시면 부분취소가 진행됩니다.") == true) {
			location.href = "shop_pay_partial.php?type=shop&ep="+ep+"&gp="+gp+"&idx="+sr_code;
		}
	}
</script>

<?php
include_once("_tail.php");
?>



