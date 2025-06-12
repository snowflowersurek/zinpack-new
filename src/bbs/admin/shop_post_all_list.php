<?php
include_once("_common.php");
if ($iw[type] != "shop" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");
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
										<!--<div class="dataTable-option">
											<label>Display <select size="1">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select> records</label>
										</div>-->
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<?
												if($_POST['search']){
													$search = $_POST['search'];
													$searchs = $_POST['searchs'];
												}else{
													$search = $_GET['search'];
													$searchs = $_GET['searchs'];
												}
												if($search =="a"){
													$search_sql = "and sr_code like '%$searchs%'";
												}else if($search =="b"){
													$search_sql = "and sr_buy_name like '%$searchs%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?if($search == "a"){?>selected="selected"<?}?>>주문번호</option>
												<option value="b" <?if($search == "b"){?>selected="selected"<?}?>>주문자</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th style="text-align:center;">주문일자</th>
											<th style="text-align:center;">주문번호</th>
											<th style="text-align:center;">주문자</th>
											<th style="text-align:center;">주문내용</th>
											<th style="text-align:center;">상품</th>
											<th style="text-align:center;">주문합계</th>
											<th style="text-align:center;">포인트</th>
											<th style="text-align:center;">결제수단</th>
											<th style="text-align:center;">주문상태</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select * from $iw[shop_order_table] where ep_code = '$iw[store]' and sr_display <> 0 $search_sql";
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

										$sql = "select * from $iw[shop_order_table] where ep_code = '$iw[store]' and sr_display <> 0 $search_sql order by sr_datetime desc, sr_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$sr_code = $row["sr_code"];
											$md_code = $row["md_code"];
											$sr_buy_name = $row["sr_buy_name"];
											$sr_sum = $row["sr_sum"];
											$sr_point = number_format($row["sr_point"]);
											$sr_datetime = $row["sr_datetime"];
											$sr_pay = $row["sr_pay"];
											$srs_display = $row["sr_display"];
											$order_status = $ORDSTATUS[$srs_display];

											$rowcnt = sql_fetch(" select count(*) as cnt from $iw[shop_order_sub_table] where sr_code = '$sr_code'");
											$sr_option = $rowcnt[cnt];
											
											if($sr_pay == "lguplus"){
												$pay_county = "ko";
												$sqllgd = "select * from $iw[lgd_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and lgd_oid = '$sr_code'";
												$rowlgd = sql_fetch($sqllgd);
												$lgd_amount = $rowlgd["lgd_amount"];
												$lgd_paytype = $rowlgd["lgd_paytype"];
												$lgd_productinfo = $rowlgd["lgd_productinfo"];
											}else if($sr_pay == "paypal"){
												$pay_county = "en";
												$sqllgd = "select * from $iw[paypal_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and pp_invoice = '$sr_code'";
												$rowlgd = sql_fetch($sqllgd);
												$lgd_amount = $rowlgd["pp_mc_gross"];
												$lgd_productinfo = $rowlgd["pp_item_name"];
											}else if($sr_pay == "alipay"){
												$pay_county = "en";
												$sqllgd = "select * from $iw[alipay_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and ap_out_trade_no = '$sr_code'";
												$rowlgd = sql_fetch($sqllgd);
												$lgd_amount = $rowlgd["ap_total_fee"];
												$lgd_productinfo = $rowlgd["ap_item_name"];
											}										
									?>
										<tr>
											<td data-title="주문일자" style="text-align:center;"><?=$sr_datetime?></td>
											<td data-title="주문번호" style="text-align:center;"><a href="<?=$iw['admin_path']?>/shop_post_all_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$sr_code?>"><?=$sr_code?></a></td>
											<td data-title="주문자" style="text-align:center;"><?=$sr_buy_name?></td>
											<td data-title="주문내용"><?=$lgd_productinfo?></td>
											<td data-title="상품" style="text-align:center;"><?=$sr_option?></td>
											<td data-title="주문합계" style="text-align:right;"><?=national_money($pay_county, $sr_sum);?></td>
											<td data-title="포인트" style="text-align:right;"><?=$sr_point?> Point</td>
											<td data-title="결제수단" style="text-align:center;">
												<?if($lgd_paytype=="SC0010"){?>
													신용카드
												<?}else if($lgd_paytype=="SC0030"){?>
													계좌이체
												<?}else if($lgd_paytype=="SC0060"){?>
													휴대폰
												<?}else if($lgd_paytype=="SC0040"){?>
													가상계좌
												<?}else if($sr_pay == "paypal"){?>
													PAYPAL
												<?}else if($sr_pay == "alipay"){?>
													ALIPAY
												<?}?>
											</td>
											<td data-title="주문상태" style="text-align:center;"><?=$order_status?></td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>검색된 주문이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info"><!--페이지/전체--></div>
									</div>
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

<?
include_once("_tail.php");
?>