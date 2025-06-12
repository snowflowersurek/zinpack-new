<?php
include_once("_common.php");
if ($iw[type] != "bank" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$row = sql_fetch(" select count(*) as cnt from $iw[account_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and ac_display = 1");
if (!$row[cnt]) alert("계좌정보 등록후 환전하실 수 있습니다.","$iw[admin_path]/bank_account_write.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

$sql = "select * from $iw[account_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);

$ac_bank = $row["ac_bank"];
$ac_number = $row["ac_number"];
$ac_holder = $row["ac_holder"];

$sql = "select * from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
$mb_point = $row["mb_point"];
$mb_name = $row["mb_name"];

$sql = "select * from $iw[master_table] where ma_no = 1";
$row = sql_fetch($sql);
$ma_exchange_point = number_format($row["ma_exchange_point"]);
$ma_exchange_amount = number_format($row["ma_exchange_amount"]);

$next_month = date('m', strtotime('+1 month'));
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-money"></i>
			정산관리
		</li>
		<li class="active">환전</li>
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
			환전
			<small>
				<i class="fa fa-angle-double-right"></i>
				계좌입금
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ec_form" name="ec_form" action="<?=$iw['admin_path']?>/bank_exchange_list_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
					<div class="form-group">
						<label class="col-sm-1 control-label">보유 포인트</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_point?> Point</p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">환전하실 포인트</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ma_exchange_point?> Point</p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">환전될 금액</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ma_exchange_amount?> 원</p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">계좌정보</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ac_bank?> <?=$ac_number?> (예금주: <?=$ac_holder?>)</p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">입금예정일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$next_month?>월 15일</p>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								환전 신청
							</button>
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-xs-12">
						<h4 class="lighter"><!--제목--></h4>
						<section class="content-box">
							<div class="table-header">
								<!--게시판설명-->
							</div>
							<div class="table-set-mobile dataTable-wrapper">
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>신청일</th>
											<th>포인트(Point)</th>
											<th>금액(원)</th>
											<th>은행</th>
											<th>계좌번호</th>
											<th>예금주</th>
											<th>지급일</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select * from $iw[exchange_table] where ep_code = '$iw[store]' and mb_code='$iw[member]'";
										$result = sql_query($sql);
										$total_line = mysql_num_rows($result);

										$max_line = 10;
										$max_page = 10;
											
										$page = $_GET["page"];
										if(!$page) $page=1;
										$start_line = ($page-1)*$max_line;
										$total_page = ceil($total_line/$max_line);
										
										if($total_line < $max_line) {
											$end_line = $total_line;
										} else if($page==$total_page) {
											$end_line = $total_line - ($max_line*($total_page-1));
										} else {
											$end_line = $max_line;
										}

										$sql = "select * from $iw[exchange_table] where ep_code = '$iw[store]' and mb_code='$iw[member]' order by ec_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$ec_bank = $row["ec_bank"];
											$ec_number = $row["ec_number"];
											$ec_holder = $row["ec_holder"];
											$ec_point = number_format($row["ec_point"]);
											$ec_amount = number_format($row["ec_amount"]);
											$ec_display = $row["ec_display"];

											$ec_datetime = date("Y-m-d", strtotime($row["ec_datetime"]));
											$ec_give_datetime = date("Y-m-d", strtotime($row["ec_give_datetime"]));
									?>
										<tr>
											<td data-title="신청일"><?=$ec_datetime?></td>
											<td data-title="포인트(Point)"><?=$ec_point?></td>
											<td data-title="금액(원)"><?=$ec_amount?></td>
											<td data-title="은행"><?=$ec_bank?></td>
											<td data-title="계좌번호"><?=$ec_number?></td>
											<td data-title="예금주"><?=$ec_holder?></td>
											<td data-title="지급일"><?if($ec_display==1){?><?=$ec_give_datetime?><?}?></td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='7' align='center'>환전내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6"></div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<ul class="pagination">
											<?
												if($total_page!=0){
													if($page>$total_page) { $page=$total_page; }
													$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
													$end_page = $start_page+$max_page-1;
												 
													if($end_page>$total_page) {$end_page=$total_page;}
												 
													if($page>$max_page) {
														$pre = $start_page - 1;
														echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&search=$search&searchs=$searchs'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&search=$search&searchs=$searchs'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&search=$search&searchs=$searchs'><i class='fa fa-angle-double-right'></i></a></li>";
													} else {
														echo "<li class='next disabled'><a href='#'><i class='fa fa-angle-double-right'></i></a></li>";
													}
												}
											?>
											</ul>
										</div>
									</div>
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
	function check_form() {
		ec_form.submit();
	}
</script>
<?
include_once("_tail.php");
?>