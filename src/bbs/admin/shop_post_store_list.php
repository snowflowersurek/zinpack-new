<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

if($_POST['search']){
	$search = $_POST['search'];
	$searchs = $_POST['searchs'];
}else{
	$search = $_GET['search'];
	$searchs = $_GET['searchs'];
}
if($search =="g"){
	$search_sql = "and ($iw[shop_order_sub_table].sr_code like '%$searchs%' or $iw[shop_order_sub_table].sr_code like '%$searchs%' or $iw[shop_order_sub_table].mb_code like '%$searchs%' or $iw[shop_order_sub_table].sd_code like '%$searchs%' or $iw[shop_order_sub_table].so_no like '%$searchs%' or $iw[shop_order_table].sr_buy_name like '%$searchs%' or $iw[shop_order_table].sr_phone like '%$searchs%')";
}else if($search =="a"){
	$search_sql = "and $iw[shop_order_sub_table].sr_code like '%$searchs%'";
}else if($search =="b"){
	$search_sql = "and $iw[shop_order_sub_table].mb_code like '%$searchs%'";
}else if($search =="c"){
	$search_sql = "and $iw[shop_order_sub_table].sd_code like '%$searchs%'";
}else if($search =="d"){
	$search_sql = "and $iw[shop_order_sub_table].so_no like '%$searchs%'";
}else if($search =="f"){
	$search_sql = "and $iw[shop_order_table].sr_buy_name like '%$searchs%'";
}else if($search =="e"){
	if($searchs=="주문"){
		$list_display = "1";
	}else if($searchs=="준비"){
		$list_display = "2";
	}else if($searchs=="배송"){
		$list_display = "3";
	}else if($searchs=="완료"){
		$list_display = "4";
	}else if($searchs=="취소"){
		$list_display = "8";
	}else if($searchs=="반품"){
		$list_display = "9";
	}else if($searchs=="결제취소"){
		$list_display = "7";
	}else if($searchs=="입금대기"){
		$list_display = "5";
	}else{
		$list_display = "";
	}
	$search_sql = "and srs_display IN (1,2,3)";
	//$search_sql = "and srs_display like '%$list_display%'";
}else if($search =="h"){
	$search_sql = "and $iw[shop_order_table].sr_phone like '%$searchs%'";
}

if($_POST['start_date']){
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
}else if($_GET['start_date']){
	$start_date = $_GET['start_date'];
	$end_date = $_GET['end_date'];
}else{
	$start_date = "20131201"; 
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
											<label>상태<select size="1" onchange="javascript:select_search('<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>&search=e&searchs=',this.value)">
												<option value="">전체</option>
												<option value="주문" <?if($searchs == "주문"){?>selected="selected"<?}?>>주문</option>
												<option value="준비" <?if($searchs == "준비"){?>selected="selected"<?}?>>준비</option>
												<option value="배송" <?if($searchs == "배송"){?>selected="selected"<?}?>>배송</option>
												<option value="완료" <?if($searchs == "완료"){?>selected="selected"<?}?>>완료</option>
												<option value="취소" <?if($searchs == "취소"){?>selected="selected"<?}?>>취소</option>
												<option value="반품" <?if($searchs == "반품"){?>selected="selected"<?}?>>반품</option>
												<option value="결제취소" <?if($searchs == "결제취소"){?>selected="selected"<?}?>>결제취소</option>
												<option value="입금대기" <?if($searchs == "입금대기"){?>selected="selected"<?}?>>입금대기</option>
											</select></label>
										</div>
										<form name="date_form" id="date_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=$search?>&searchs=<?=$searchs?>" method="post">
											<input type="text" name="start_date" maxlength="8" value="<?=$start_date?>">~
											<input type="text" name="end_date" maxlength="8" value="<?=$end_date?>">
											<input type="button" onclick="javascript:check_date();" value="조회">
										</form>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" method="post">
											<label>검색: <select name="search">
												<option value="g" <?if($search == "g"){?>selected="selected"<?}?>>전체</option>
												<option value="a" <?if($search == "a"){?>selected="selected"<?}?>>주문번호</option>
												<option value="b" <?if($search == "b"){?>selected="selected"<?}?>>회원코드</option>
												<option value="f" <?if($search == "f"){?>selected="selected"<?}?>>주문자</option>
												<option value="h" <?if($search == "h"){?>selected="selected"<?}?>>연락처</option>
												<option value="c" <?if($search == "c"){?>selected="selected"<?}?>>상품코드</option>
												<option value="d" <?if($search == "d"){?>selected="selected"<?}?>>옵션번호</option>
												<option value="e" <?if($search == "e"){?>selected="selected"<?}?>>상태</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<div class="col-sm-12" style="padding:15px;">
									<span style="color:red;font-weight:bold">반드시 배송정보(송장번호 포함)를 입력하셔야 정상적인 정산이 되오니 관리 부탁드립니다!!!</span>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>주문일자</th>
											<th>주문번호</th>
											<th>회원코드</th>
											<th>주문자</th>
											<th>연락처</th>
											<th>상품명/옵션</th>
											<th>상품금액</th>
											<th>수량</th>
											<th>주문문액</th>
											<th>부가세</th>
											<th>상태</th>
											<th>관리</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select * from $iw[shop_order_sub_table] inner join $iw[shop_order_table] where $iw[shop_order_sub_table].sr_code = $iw[shop_order_table].sr_code and $iw[shop_order_sub_table].ep_code = '$iw[store]' and $iw[shop_order_sub_table].seller_mb_code = '$iw[member]' and $iw[shop_order_sub_table].srs_display <> 0 $search_sql and ($iw[shop_order_table].sr_datetime >= '$now_start' and $iw[shop_order_table].sr_datetime <= '$now_end')";

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

										$sql = "select * from $iw[shop_order_sub_table] inner join $iw[shop_order_table] where $iw[shop_order_sub_table].sr_code = $iw[shop_order_table].sr_code and $iw[shop_order_sub_table].ep_code = '$iw[store]' and $iw[shop_order_sub_table].seller_mb_code = '$iw[member]' and $iw[shop_order_sub_table].srs_display <> 0 $search_sql and ($iw[shop_order_table].sr_datetime >= '$now_start' and $iw[shop_order_table].sr_datetime <= '$now_end') order by $iw[shop_order_table].sr_datetime desc, $iw[shop_order_sub_table].srs_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$srs_no = $row["srs_no"];
											$so_no = $row["so_no"];
											$mb_code = $row["mb_code"];
											$sr_code = $row["sr_code"];
											$sd_code = $row["sd_code"];
											$seller_mb_code = $row["seller_mb_code"];
											$srs_subject = stripslashes($row["srs_subject"]);
											$srs_name = stripslashes($row["srs_name"]);
											$srs_amount = $row["srs_amount"];
											$srs_price = $row["srs_price"];
											$srs_delivery_type = $row["srs_delivery_type"];
											$srs_delivery_price = $row["srs_delivery_price"];
											$srs_bundle = $row["srs_bundle"];
											$srs_display = $row["srs_display"];
											$srs_taxfree = $row["srs_taxfree"];

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

											$sr_buy_name = $row["sr_buy_name"];
											$sr_phone = $row["sr_phone"];
											$sr_datetime = $row["sr_datetime"];
											$sr_pay = $row["sr_pay"];

											if($sr_pay == "lguplus"){
												$pay_county = "ko";
											}else if($sr_pay == "paypal" || $sr_pay == "alipay"){
												$pay_county = "en";
											}
									?>
										<tr>
											<td data-title="주문일자"><?=$sr_datetime?></td>
											<td data-title="주문번호"><a href="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=a&searchs=<?=$sr_code?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" ><?=$sr_code?></a></td>
											<td data-title="회원코드"><a href="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=b&searchs=<?=$mb_code?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" ><?=$mb_code?></a></td>
											<td data-title="주문자"><?=$sr_buy_name?></td>
											<td data-title="연락처"><?=$sr_phone?></td>
											<td data-title="상품명/옵션">
												<b><a href="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=c&searchs=<?=$sd_code?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" ><?=$srs_subject?></a></b><br />
												<span class="cartOption" <?if($srs_display==7){?>style="text-decoration:line-through;"<?}?>><a href="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=d&searchs=<?=$so_no?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>" ><?=$srs_name?></a></span>
											</td>
											<td data-title="상품금액"><?=national_money($pay_county, $srs_price);?></td>
											<td data-title="수량"><?=$srs_amount?></td>
											<td data-title="주문문액"><?=national_money($pay_county, $srs_price*$srs_amount);?></td>
											<td data-title="부가세"><?if($srs_taxfree==1){?>면세<?}else{?>포함<?}?></td>
											<td data-title="상태"><a href="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=e&searchs=<?=$displays?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>"><?=$displays?></a></td>
											<td data-title="관리"><a href="<?=$iw['admin_path']?>/shop_post_store_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$sr_code?>"><span class="label label-sm label-success">관리</span></a></td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='11' align='center'>주문 내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/shop_post_store_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&display=<?=$list_display?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>'">
												<i class="fa fa-check"></i>
												송장용 엑셀
											</button>
											<button class="btn btn-warning" type="button" onclick="location.href='<?=$iw['admin_path']?>/shop_post_store_excel2.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&display=<?=$list_display?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>'">
												<i class="fa fa-check"></i>
												매출 엑셀
											</button>
										</div>
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
														echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'><i class='fa fa-angle-double-right'></i></a></li>";
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
		location.href=url+search;
	}
	function check_date(){
		if (date_form.start_date.value.length < 8){
			alert("날짜를 20130703 형식으로 입력하여 주십시오.");
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
			alert("날짜를 20130703 형식으로 입력하여 주십시오.");
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