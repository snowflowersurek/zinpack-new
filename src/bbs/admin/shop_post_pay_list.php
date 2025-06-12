<?php
include_once("_common.php");
if ($iw[type] != "shop" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");
include_once("_head.php");

if($_POST[search]){
	$search = $_POST[search];
	$searchs = $_POST[searchs];
}else{
	$search = $_GET[search];
	$searchs = $_GET[searchs];
}

if($search =="g"){
	$search_sql = "AND (a.sr_code LIKE '%".$searchs."%' OR a.sr_code LIKE '%".$searchs."%' OR a.mb_code LIKE '%".$searchs."%' OR a.sd_code LIKE '%".$searchs."%' OR a.so_no LIKE '%".$searchs."%' OR b.sr_buy_name LIKE '%".$searchs."%' OR b.sr_phone LIKE '%$searchs%')";
}else if($search =="a"){
	$search_sql = "AND a.sr_code LIKE '%".$searchs."%'";
}else if($search =="b"){
	$search_sql = "AND a.mb_code LIKE '%".$searchs."%'";
}else if($search =="c"){
	$search_sql = "AND a.sd_code LIKE '%".$searchs."%'";
}else if($search =="d"){
	$search_sql = "AND a.so_no LIKE '%".$searchs."%'";
}else if($search =="f"){
	$search_sql = "AND b.sr_buy_name LIKE '%".$searchs."%'";
}else if($search =="h"){
	$search_sql = "AND b.sr_phone LIKE '%".$searchs."%'";
}else if($search =="j"){
	$search_sql = "AND a.seller_mb_code LIKE '%".$searchs."%'";
}
$searchs = urlencode($searchs);

if($_GET[display]){
	$display = $_GET[display];
	$search_sql .= " AND a.srs_display = '".$display."'";
}

if($_POST[start_date]){
	$start_date = $_POST[start_date];
	$end_date = $_POST[end_date];
}else if($_GET[start_date]){
	$start_date = $_GET[start_date];
	$end_date = $_GET[end_date];
}else{
	$start_date = date("Ym")."01";
	$end_date = date("Ymd");
}
$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-shopping-cart"></i>
			쇼핑몰
		</li>
		<li class="active">전체 결제조회</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			전체 결제조회
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
										<div class="dataTable-option">
											<label>상태<select size="1" onchange="javascript:select_search('<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&start_date=".$start_date."&end_date=".$end_date."&search=".$search."&searchs=".$searchs?>',this.value)">
												<option value="">전체</option>
												<option value="1" <?if($display == "1"){echo "selected";}?>>주문</option>
												<option value="2" <?if($display == "2"){echo "selected";}?>>준비</option>
												<option value="3" <?if($display == "3"){echo "selected";}?>>배송</option>
												<option value="4" <?if($display == "4"){echo "selected";}?>>완료</option>
												<option value="8" <?if($display == "8"){echo "selected";}?>>취소</option>
												<option value="9" <?if($display == "9"){echo "selected";}?>>반품</option>
												<option value="7" <?if($display == "7"){echo "selected";}?>>결제취소</option>
												<option value="5" <?if($display == "5"){echo "selected";}?>>입금대기</option>
											</select></label>
										</div>
										<form name="date_form" id="date_form" action="<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&search=".$search."&searchs=".$searchs?>" method="post">
											<input type="text" name="start_date" maxlength="8" value="<?echo $start_date;?>">~
											<input type="text" name="end_date" maxlength="8" value="<?echo $end_date;?>">
											<input type="button" onclick="javascript:check_date();" value="조회">
										</form>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<form name="search_form" id="search_form" action="<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date?>" method="post">
											<label>검색: <select name="search">
												<option value="g" <?if($search == "g"){echo "selected";}?>>전체</option>
												<option value="a" <?if($search == "a"){echo "selected";}?>>주문번호</option>
												<option value="j" <?if($search == "j"){echo "selected";}?>>판매자코드</option>
												<option value="b" <?if($search == "b"){echo "selected";}?>>회원코드</option>
												<option value="f" <?if($search == "f"){echo "selected";}?>>주문자</option>
												<option value="h" <?if($search == "h"){echo "selected";}?>>연락처</option>
												<option value="c" <?if($search == "c"){echo "selected";}?>>상품코드</option>
												<option value="d" <?if($search == "d"){echo "selected";}?>>옵션번호</option>
											</select></label><input type="text" name="searchs" value="<?echo urldecode($searchs);?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>주문일자</th>
											<th>주문번호</th>
											<th>판매자</th>
											<th>주문회원</th>
											<th>주문자</th>
											<th>연락처</th>
											<th>상품명/옵션</th>
											<th>상품금액</th>
											<th>수량</th>
											<th>주문문액</th>
											<th>부가세</th>
											<th>상태</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "
											SELECT 
												a.*, b.*, c.ep_corporate, d.ss_name, e.mb_name 
											FROM ".$iw[shop_order_sub_table]." a 
												LEFT JOIN ".$iw[shop_order_table]." b ON a.sr_code = b.sr_code 
												LEFT JOIN ".$iw[enterprise_table]." c ON a.ep_code = c.ep_code 
												LEFT JOIN ".$iw[shop_seller_table]." d ON a.seller_mb_code = d.mb_code 
												LEFT JOIN ".$iw[member_table]." e ON a.mb_code = e.mb_code 
											WHERE a.srs_display <> 0 AND b.ep_code = '$iw[store]' AND b.sr_datetime >= '".$now_start."' AND b.sr_datetime <= '".$now_end."' ".$search_sql;

										$result = sql_query($sql);
										$total_line = mysql_num_rows($result);

										$max_line = 10;
										$max_page = 10;
											
										$page = $_GET[page];
										if(!$page) $page = 1;
										$start_line = ($page-1) * $max_line;
										$total_page = ceil($total_line / $max_line);
										
										if($total_line < $max_line) {
											$end_line = $total_line;
										} else if($page == $total_page) {
											$end_line = $total_line - ($max_line * ($total_page - 1));
										} else {
											$end_line = $max_line;
										}

										$sql .= " ORDER BY b.sr_datetime DESC, a.srs_no DESC LIMIT ".$start_line.", ".$end_line;
										$result = sql_query($sql);

										$i = 0;
										while($row = @sql_fetch_array($result)){
											$srs_no = $row[srs_no];
											$so_no = $row[so_no];
											$mb_code = $row[mb_code];
											$sr_code = $row[sr_code];
											$sd_code = $row[sd_code];
											$seller_mb_code = $row[seller_mb_code];
											$srs_subject = $row[srs_subject];
											$srs_name = $row[srs_name];
											$srs_amount = $row[srs_amount];
											$srs_price = $row[srs_price];
											$srs_delivery_type = $row[srs_delivery_type];
											$srs_delivery_price = $row[srs_delivery_price];
											$srs_bundle = $row[srs_bundle];
											$srs_display = $row[srs_display];
											$srs_taxfree = $row[srs_taxfree];


											$ep_code = $row[ep_code];
											$ep_corporate = $row[ep_corporate];
											
											$ss_name = $row[ss_name];
											$mb_name = $row[mb_name];
											
											

											if($srs_display=="1"){
												$displays = "주문";
											}else if($srs_display=="2"){
												$displays = "준비";
											}else if($srs_display=="3"){
												$displays = "배송";
											}else if($srs_display=="4"){
												$displays = "완료";
											}else if($srs_display=="8"){
												$displays = "취소";
											}else if($srs_display=="9"){
												$displays = "반품";
											}else if($srs_display=="7"){
												$displays = "결제취소";
											}else if($srs_display=="5"){
												$displays = "입금대기";
											}

											$sr_buy_name = $row[sr_buy_name];
											$sr_phone = $row[sr_phone];
											$sr_datetime = $row[sr_datetime];
											$sr_pay = $row[sr_pay];

											if($sr_pay == "lguplus"){
												$pay_county = "ko";
											}else if($sr_pay == "paypal"){
												$pay_county = "en";
											}
									?>
										<tr>
											<td data-title="주문일자">
												<?echo $sr_datetime;?>
											</td>
											<td data-title="주문번호">
												<a href="<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=a&searchs=".$sr_code?>"><?echo $sr_code;?></a>
											</td>
											<td data-title="판매자">
												<a href="<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=j&searchs=".$seller_mb_code?>"><?echo $ss_name;?><br><?echo $seller_mb_code;?></a>
											</td>
											<td data-title="회원">
												<a href="<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=b&searchs=".$mb_code?>"><?echo $mb_name;?><br><?echo $mb_code;?></a></td>
											<td data-title="주문자">
												<?echo $sr_buy_name;?>
											</td>
											<td data-title="연락처">
												<?echo $sr_phone;?>
											</td>
											<td data-title="상품명/옵션">
												<b><a href="<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=c&searchs=".$sd_code?>"><?echo $srs_subject;?></a></b><br />
												<span class="cartOption" <?if($srs_display == 7){ echo "style=\"text-decoration:line-through;\""; }?>><a href="<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=d&searchs=".$so_no?>"><?echo $srs_name;?></a></span>
											</td>
											<td data-title="상품금액">
												<?echo national_money($pay_county, $srs_price);?>
											</td>
											<td data-title="수량">
												<?echo $srs_amount;?></td>
											<td data-title="주문문액">
												<?echo national_money($pay_county, $srs_price * $srs_amount);?>
											</td>
											<td data-title="부가세">
												<?
													if($srs_taxfree==1){
														echo "면세";
													}else{
														echo "포함";
													}
												?>
											</td>
											<td data-title="상태">
												<a href="<?echo $PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$srs_display."&start_date=".$start_date."&end_date=".$end_date."&search=".$search."&searchs=".$searchs?>"><?echo $displays;?></a>
											</td>
										</tr>
									<?
										$i ++;
										}
										if($i == 0) echo "<tr><td colspan=\"13\" align=\"center\">주문 내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?echo $iw[admin_path]."/shop_post_pay_excel.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=".$search."&searchs=".$searchs?>'">
												<i class="fa fa-check"></i>
												엑셀 출력
											</button>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<ul class="pagination">
											<?
												if($total_page != 0){
													if($page > $total_page) { $page = $total_page; }
													$start_page = ((ceil($page / $max_page) - 1) * $max_page) + 1;
													$end_page = $start_page + $max_page - 1;
												 
													if($end_page > $total_page) {$end_page = $total_page;}
												 
													if($page > $max_page) {
														$pre = $start_page - 1;
														echo "<li class='prev'><a href='".$PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=".$search."&searchs=".$searchs."&page=".$pre."'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>".$i."</a></li>";
														else          echo "<li><a href='".$PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=".$search."&searchs=".$searchs."&page=".$i."'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='".$PHP_SELF."?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&display=".$display."&start_date=".$start_date."&end_date=".$end_date."&search=".$search."&searchs=".$searchs."&page=".$next."'><i class='fa fa-angle-double-right'></i></a></li>";
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
	function select_search(url,search){
		location.href=url+"&display="+search;
	}
	function check_date(){
		if (date_form.start_date.value.length < 8){
			alert("날짜를 <?php echo date("Ymd");?> 형식으로 입력하여 주십시오.");
			date_form.start_date.focus();
			return;
		}
		var e1 = date_form.start_date;
		var num ="0123456789";
		event.returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			date_form.start_date.focus();
			return;
		}
		if (date_form.end_date.value.length < 8){
			alert("날짜를 <?php echo date("Ymd");?> 형식으로 입력하여 주십시오.");
			date_form.end_date.focus();
			return;
		}
		e1 = date_form.end_date;
		event.returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			date_form.end_date.focus();
			return;
		}
		date_form.submit();
	}
</script>

<?
include_once("_tail.php");
?>