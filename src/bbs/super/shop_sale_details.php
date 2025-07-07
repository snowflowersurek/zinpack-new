<?php
include_once("_common.php");
include_once("_head.php");

if($_REQUEST['mon']){
	$chk_mon = $_REQUEST['mon'];
	$start_date = $chk_mon."-01 00:00:00";
	$end_day = date('t', strtotime($start_date));
	$end_date = $chk_mon."-".$end_day." 23:59:59";
}else{
	echo "<script>alert('잘못된 접근입니다.');history.go(-1);</script>";
}

$tepcode = $_REQUEST['epcorp'];
$sql = "SELECT * FROM {$iw['enterprise_table']} WHERE ep_code='$tepcode'";
//echo "tsql : ".$sql."<br>";
$row = sql_fetch($sql);
$corp = $row['ep_corporate'];
$epcode = str_replace(".del","",$tepcode);
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-credit-card"></i>
			정산관리
		</li>
		<li class="active">업체별 매출내역</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			<?=$corp?> 매출내역
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
										<form name="date_form" id="date_form" action="<?=$PHP_SELF?>?type=<?=$iw['type']?>&ep=<?=$iw['store']?>&gp=<?=$iw['group']?>&mon=<?=$mon?>&epcorp=<?=$tepcode?>" method="post">
											<select name="mon" onchange="mon_refresh()">
											<?php
												$msql = "SELECT DISTINCT(SUBSTRING( lgd_paydate, 1, 6 )) AS mon FROM {$iw['lgd_table']} WHERE ep_code = '$epcode' ORDER BY lgd_no DESC";
												$mresult = sql_query($msql);
												while($mrow = sql_fetch_array($mresult)){
													$mval = substr($mrow['mon'],0,4)."-".substr($mrow['mon'],4);
											?>
												<option value="<?=$mval?>" <?php if($mval==$chk_mon){?>selected="selected"<?php }?>><?=$mval?></option>
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
											<th style="text-align:center;">승인금액</th>
											<th style="text-align:center;">정산 입금액</th>
											<th style="text-align:center;">거래번호</th>
											<th style="text-align:center;">면/과세</th>
											<th style="text-align:center;">내용</th>
											<th style="text-align:center;">승인결과</th>
											<th style="text-align:center;">승인일시</th>
											<th style="text-align:center;">결제방식</th>
											<th style="text-align:center;">정산입금일자</th>
											<th style="text-align:center;">결제자</th>
										</tr>
									</thead>
									<tbody>
									<?php
										//$sql = "select * from {$iw['lgd_table']} where (pt_datetime >= '$now_start' and pt_datetime <= '$now_end') $search_sql";
										$sql = "SELECT a.*, aa.* FROM {$iw['shop_order_sub_table']} a LEFT JOIN {$iw['lgd_table']} aa ON a.sr_code = aa.lgd_oid LEFT JOIN {$iw['shop_order_table']} b ON a.sr_code = b.sr_code WHERE aa.lgd_display = 1 AND b.sr_datetime >= '$start_date' AND b.sr_datetime <= '$end_date' AND b.ep_code = '$epcode'";
										//echo "sql= ".$sql."<br>";
										$result = sql_query($sql);
										$total = mysqli_num_rows($result);
										$i=0;
										$tot_amount = 0;
										$sum_tax_amt = 0;
										$sum_taxfree_amt = 0;
										if($total > 0){
											while($row = @sql_fetch_array($result)){
											$ep_code = $row["ep_code"];
											$lgd_tid = $row["lgd_tid"];
											$lgd_productinfo = $row["lgd_productinfo"];
											$tax_free = $row["srs_taxfree"]=="0"?"과세":"면세";
											$srsamt = ($row["srs_amount"] * $row["srs_price"]) + $row["srs_delivery_price"];
											$lgd_amount = number_format($srsamt);
											if($row["srs_taxfree"]=="0"){
												$sum_tax_amt += $srsamt;
											}else{
												$sum_taxfree_amt += $srsamt;
											}
											$tot_amount += $srsamt;
											$lgd_respmsg = $row["lgd_respmsg"];
											$lgd_paydate = date( "Y-m-d H:i:s", strtotime($row["lgd_paydate"]) );
											$lgd_in_date = $row["lgd_in_date"];
											$lgd_buyer = $row["lgd_buyer"];
											$lgd_paytype = $row["lgd_paytype"];
											if($lgd_in_date=="0000-00-00"){
												$lgd_castamount = "";
												$lgd_in_date = "";
											}else{
												$lgd_castamount = $lgd_amount . "원";
											}

											$row2 = sql_fetch("select ep_corporate from {$iw['enterprise_table']} where ep_code = '$ep_code'");
											$ep_corporate = $row2["ep_corporate"];
									?>
										<tr>
											<td data-title="승인금액" style="text-align:right;"><?=$lgd_amount?>원</td>
											<td data-title="정산입금액" style="text-align:right;"><?=$lgd_castamount?></td>
											<td data-title="거래번호" style="text-align:center;"><?=$lgd_tid?></td>
											<td data-title="면과세" style="text-align:center;"><?=$tax_free?></td>
											<td data-title="내용" style="text-align:center;"><?=$lgd_productinfo?></td>
											<td data-title="승인결과" style="text-align:center;"><?=$lgd_respmsg?></td>
											<td data-title="승인일시" style="text-align:center;"><?=$lgd_paydate?></td>
											<td data-title="결제방식" style="text-align:center;">
												<?php if($lgd_paytype=="SC0010"){?>
													신용카드
												<?php }else if($lgd_paytype=="SC0030"){?>
													계좌이체
												<?php }else if($lgd_paytype=="SC0060"){?>
													휴대폰
												<?php }else if($lgd_paytype=="SC0040"){?>
													가상계좌
												<?php }?>
											</td>
											<td data-title="정산입금일자" style="text-align:center;"><input type="text" name="in_date" id="in_<?=$row["lgd_no"]?>" value="<?=$lgd_in_date?>"  style="width:120px;border-color:beige;" disabled></td>
											<td data-title="결제자" style="text-align:center;"><?=$lgd_buyer?></td>
										</tr>
									<?php
												$i++;
											}
										}
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-12">
										총 결제 금액 : <?=number_format($tot_amount)?> 원 (<?=$i?> 건) / 과세상품 총매출액 : <?=number_format($sum_tax_amt)?> 원 / 면세상품 총매출액 : <?=number_format($sum_taxfree_amt)?> 원
								</div>
							</div><!-- /.table-responsive -->
						</section>
						<?php
							$sql = "SELECT a.*, b.mb_name, b.mb_nick FROM {$iw['book_buy_table']} a LEFT JOIN {$iw['member_table']} b ON a.mb_code = b.mb_code WHERE a.bb_datetime >= '$start_date' AND a.bb_datetime <= '$end_date' AND a.ep_code = '$epcode' ORDER BY a.bb_no DESC";
							//echo "sql= ".$sql."<br>";
							$result = sql_query($sql);
							$ptotal = mysqli_num_rows($result);
							$sum_point = 0;
							if($ptotal > 0){
						?>
						<div class="page-header" style="margin-top:20px;">
							<h1>
								<?=$corp?> 이북 매출내역
								<small>
									<i class="fa fa-angle-double-right"></i>
									목록
								</small>
							</h1>
						</div>
						<section><!-- 여기에 포인트 상세 -->
							<div class="table-set-mobile dataTable-wrapper">
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th style="text-align:center;">날짜</th>
											<th style="text-align:center;">회원코드</th>
											<th style="text-align:center;">회원명</th>
											<th style="text-align:center;">닉네임</th>
											<th style="text-align:center;">내용</th>
											<th style="text-align:center;">사이트</th>
											<th style="text-align:center;">판매자</th>
										</tr>
									</thead>
									<tbody>
									<?php
											while($row = @sql_fetch_array($result)){
												$bbdatetime = $row["bb_datetime"];
												$mbcode = $row["mb_code"];
												$mbname = $row["mb_name"];
												$mbnick = $row["mb_nick"];
												$bbsubject = $row["bb_subject"];
												$bbpricesite = $row["bb_price_site"];
												$bbpriceseller = $row["bb_price_seller"];

												$sum_point += ($bbpricesite + $bbpriceseller);
									?>
										<tr>
											<td data-title="날짜" style="text-align:center;"><?=$bbdatetime?></td>
											<td data-title="회원코드" style="text-align:center;"><?=$mbcode?></td>
											<td data-title="회원명" style="text-align:center;"><?=$mbname?></td>
											<td data-title="닉네임" style="text-align:center;"><?=$mbnick?></td>
											<td data-title="내용" style="text-align:left;"><?=$bbsubject?></td>
											<td data-title="사용포인트" style="text-align:right;"><?=number_format($bbpricesite)?></td>
											<td data-title="잔여포인트" style="text-align:right;"><?=number_format($bbpriceseller)?></td>
										</tr>
									<?php
											}
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-12">
										총 매출 포인트 : <?=number_format($sum_point)?> (<?=number_format($sum_point * 15)?> 원)
								</div>
							</div>
						</section>
						<?php } ?>
						<div class="col-xs-12" style="text-align:center;padding:15px;"><button onclick="view_list()">목록으로 가기</button></div>
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
function view_list(){
	document.location.href="shop_sale_list.php?type=<?=$iw['type']?>&ep=<?=$iw['store']?>&gp=<?=$iw['group']?>";
}
</script>

<?php
include_once("_tail.php");
?>



