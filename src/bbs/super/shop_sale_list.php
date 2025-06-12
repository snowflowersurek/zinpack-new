<?php
include_once("_common.php");
include_once("_head.php");

$ndate = date("Y-m-d");
if($_POST['monsel']){
	$chk_mon = $_POST['monsel'];
	$start_date = $_POST['monsel']."-01 00:00:00";
	$end_day = date('t', strtotime($start_date));
	$end_date = $_POST['monsel']."-".$end_day." 23:59:59";
}else if($_GET['monsel']){
	$chk_mon = $_POST['monsel'];
	$start_date = $_POST['monsel']."-01 00:00:00";
	$end_day = date('t', strtotime($start_date));
	$end_date = $_POST['monsel']."-".$end_day." 23:59:59";
}else{
	$start_mon = date("Y-m", strtotime($ndate.' -1 month'));
	$start_date = $start_mon."-01 00:00:00";
	$end_day = date('t', strtotime($start_date));
	$end_date = $start_mon."-".$end_day." 23:59:59";
	$chk_mon = $start_mon;
}
//$now_start = date("Y-m-d H:i:s", strtotime($start_date));
//$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-credit-card"></i>
			정산관리
		</li>
		<li class="active">업체별 정산내역</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			업체별 정산내역
			<small>
				<i class="fa fa-angle-double-right"></i>
				목록
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<div class="row">
					<div class="col-xs-12">
						<h4 class="lighter"><!--제목--></h4>
						<section class="content-box">
							<div class="table-header">
								<!--게시판설명-->
							</div>
							<div class="table-set-mobile dataTable-wrapper">
								<div class="row">
									<div class="col-sm-6">
										<form name="date_form" id="date_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&monsel=<?=$monsel?>" method="post">
											<select name="monsel" onchange="mon_refresh()">
											<?php
												$msql = "SELECT DISTINCT(SUBSTRING( lgd_paydate, 1, 6 )) AS mon FROM $iw[lgd_table] WHERE 1 ORDER BY lgd_no DESC";
												$mresult = sql_query($msql);
												while($mrow = sql_fetch_array($mresult)){
													if($mrow['mon']=="") continue;
													$mval = substr($mrow['mon'],0,4)."-".substr($mrow['mon'],4);
											?>
												<option value="<?=$mval?>" <?if($chk_mon == $mval){?>selected="selected"<?}?>><?=$mval?></option>
											<?php
												}
											?>
											</select>
										</form>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>업체명</th>
											<th>과세 매출액</th>
											<th>면세 매출액</th>
											<th>총 매출액</th>
											<th>정산 입금액</th>
											<th>수수료</th>
											<th>이북 매출</th>
											<th>지급액</th>
											<th>상세보기</th>
										</tr>
									</thead>
									<tbody>
									<?
										$epcode_ary = array();
										//$sql = "select * from $iw[lgd_table] where (pt_datetime >= '$now_start' and pt_datetime <= '$now_end') $search_sql";
										$sql = "SELECT DISTINCT(ep_code) AS epcode FROM $iw[lgd_table] WHERE (lgd_datetime >= '$start_date' AND lgd_datetime <= '$end_date') AND state_sort='shop' AND lgd_display=1";
										//echo "sql= ".$sql."<br>";
										$result = sql_query($sql);
										$ctotal = mysql_num_rows($result);
										if($ctotal > 0){
											while($row=@sql_fetch_array($result)){
												array_push($epcode_ary, $row['epcode']);
											}
										}
										$sql = "SELECT DISTINCT(ep_code) AS epcode FROM $iw[book_buy_table] WHERE bb_datetime >= '$start_date' AND bb_datetime <= '$end_date'";
										$result = sql_query($sql);
										$btotal = mysql_num_rows($result);
										if($btotal > 0){
											while($row=@sql_fetch_array($result)){
												if(in_array($row['epcode'], $epcode_ary)){
													continue;
												}
												array_push($epcode_ary, $row['epcode']);
											}
										}

										$sum_amt = 0;
										$sum_charge = 0;
										$sum_pay = 0;
										if(($ctotal+$btotal) > 0){
											//$epcode_ary = array_unique($epcode_ary);
											for($i=0; $i<sizeof($epcode_ary); $i++){
												$epcode = $epcode_ary[$i];

												$ssql = "SELECT c.ep_corporate, SUM(a.srs_price * a.srs_amount) AS sr_sum, SUM(a.srs_delivery_price) AS sr_delivery, SUM(aa.lgd_amount) AS lgd_sum FROM $iw[shop_order_sub_table] a LEFT JOIN $iw[lgd_table] aa ON a.sr_code = aa.lgd_oid LEFT JOIN $iw[shop_order_table] b ON a.sr_code = b.sr_code LEFT JOIN $iw[enterprise_table] c ON a.ep_code = c.ep_code WHERE aa.lgd_display = 1 AND b.sr_datetime >= '$start_date' AND b.sr_datetime <= '$end_date' AND b.ep_code = '$epcode' AND a.srs_taxfree = 1";
												$srow = sql_fetch($ssql);
												$ep_corporate = $srow['ep_corporate'];
												$taxfree_amt = $srow['sr_sum'] + $srow['sr_delivery'];

												$ssql = "SELECT c.ep_corporate, SUM(a.srs_price * a.srs_amount) AS sr_sum, SUM(a.srs_delivery_price) AS sr_delivery, SUM(aa.lgd_amount) AS lgd_sum FROM $iw[shop_order_sub_table] a LEFT JOIN $iw[lgd_table] aa ON a.sr_code = aa.lgd_oid LEFT JOIN $iw[shop_order_table] b ON a.sr_code = b.sr_code LEFT JOIN $iw[enterprise_table] c ON a.ep_code = c.ep_code WHERE aa.lgd_display = 1 AND b.sr_datetime >= '$start_date' AND b.sr_datetime <= '$end_date' AND b.ep_code = '$epcode' AND a.srs_taxfree = 0";
												$srow = sql_fetch($ssql);
												$tax_amt = $srow['sr_sum'] + $srow['sr_delivery'];

												$ssql = "SELECT c.ep_corporate, SUM(a.srs_price * a.srs_amount) AS sr_sum, SUM(a.srs_delivery_price) AS sr_delivery, SUM(aa.lgd_amount) AS lgd_sum FROM $iw[shop_order_sub_table] a LEFT JOIN $iw[lgd_table] aa ON a.sr_code = aa.lgd_oid LEFT JOIN $iw[shop_order_table] b ON a.sr_code = b.sr_code LEFT JOIN $iw[enterprise_table] c ON a.ep_code = c.ep_code WHERE aa.lgd_display = 1 AND b.sr_datetime >= '$start_date' AND b.sr_datetime <= '$end_date' AND b.ep_code = '$epcode' AND aa.lgd_in_date <> '0000-00-00'";
												$srow = sql_fetch($ssql);
												$in_amt = $srow['sr_sum'] + $srow['sr_delivery'];
												$tot_amt = $taxfree_amt + $tax_amt;
												$sum_amt += $tot_amt;

												// 여기부터 포인트(이북)매출액 추가
												$ssql = "SELECT SUM(bb_price_seller) AS sellerp , SUM(bb_price_site) AS sitep FROM iw_book_buy WHERE ep_code = '$epcode' AND bb_datetime >= '$start_date' AND bb_datetime <= '$end_date'";
												//echo $ssql . "<br>";
												$srow = sql_fetch($ssql);
												//echo "sellerp : ".$srow['sellerp'] .", sitep : ". $srow['sitep'] ."<br>";
												if($srow['sellerp'] == "" || $srow['sellerp'] == NULL || $srow['sellerp'] == "0"){
													$point_amt = 0;
													$tp_amt = 0;
												}else{
													$point_amt = $srow['sellerp'] + $srow['sitep'];
													$tp_amt = $point_amt * 15;
													$sum_amt += $tp_amt;
												}
												// 여기까지 포인트(이북)매출액 추가

												// 컨텐츠 매출은 필요없나 ? (2019년 이후로 컨텐츠를 무료로 제공되고 있음...)

												$charge_amt = $tot_amt * 0.1;
												$pay_amt = $tot_amt - $charge_amt + $tp_amt;
												$sum_charge += $charge_amt;
												$sum_pay += $pay_amt;

												if($ep_corporate==''){
													$tsql = "SELECT * FROM $iw[enterprise_table] WHERE ep_code LIKE '".$epcode."%'";
													$trow = sql_fetch($tsql);
													$epcode = $trow['ep_code'];
													$ep_corporate = $trow['ep_corporate'];
												}
									?>
										<tr>
											<td data-title="업체명"><?=$ep_corporate?></td>
											<td data-title="과세매출액" style="text-align:right;padding-right:20px;"><?=number_format($tax_amt)?> 원</td>
											<td data-title="면세매출액" style="text-align:right;padding-right:20px;"><?=number_format($taxfree_amt)?> 원</td>
											<td data-title="총매출액" style="text-align:right;padding-right:20px;"><?=number_format($tot_amt)?> 원</td>
											<td data-title="정산입금액" style="text-align:right;padding-right:20px;"><?=number_format($in_amt)?> 원</td>
											<td data-title="수수료" style="text-align:right;padding-right:20px;"><?=number_format($charge_amt)?> 원</td>
											<td data-title="이북매출" style="text-align:right;padding-right:20px;"><?=number_format($tp_amt)?> 원</td>
											<td data-title="지급액" style="text-align:right;padding-right:20px;"><?=number_format($pay_amt)?> 원</td>
											<td>
												<button onclick="javascript:view_details('<?=$chk_mon?>','<?=$epcode?>')">상세보기</button>
												<button onclick="javascript:show_receipt('<?=$chk_mon?>','<?=$epcode?>','<?=$tax_amt?>','<?=$taxfree_amt?>','<?=$tp_amt?>')">정산서</button>
											</td>
										</tr>
									<?php
											}
										}
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										총 매출 금액 : <?=number_format($sum_amt)?>원 - 총 수수료 : <?=number_format($sum_charge)?>원 = 총 지급액 : <?=number_format($sum_pay)?>원
								</div>
							</div><!-- /.table-responsive -->
						</section>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->


<script type="text/javascript">
function mon_refresh(){
	document.date_form.submit();
}
function view_details(mon,epcorp){
	document.location.href="shop_sale_details.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&mon=" + mon + "&epcorp=" + epcorp;
}
function show_receipt(mon,epcorp,taxsum,freesum,ebook){
	var url = "shop_receipt_win.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&mon=" + mon + "&epcorp=" + epcorp + "&taxsum=" + taxsum + "&freesum=" + freesum + "&ebook=" + ebook;
	var win = window.open(url, "PopupWin", "width=710,height=500");
}
</script>

<?
include_once("_tail.php");
?>