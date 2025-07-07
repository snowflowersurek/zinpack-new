<?php
include_once("_common.php");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

$search = $_REQUEST['search'];
$searchs = $_REQUEST['searchs'];
$start_date = $_REQUEST['start_date'] ?: "20131201";
$end_date = $_REQUEST['end_date'] ?: date("Ymd");

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

$search_sql = " WHERE (a.ap_datetime >= ? AND a.ap_datetime <= ?) ";
$params = [$now_start, $now_end];
$types = "ss";

if($searchs) {
    $keyword_param = "%{$searchs}%";
    if($search =="a"){
        $search_sql .= " AND a.ep_code LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search =="b"){
        $search_sql .= " AND a.ap_trade_no LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search =="c"){
        $search_sql .= " AND a.ap_buyer_email LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }
}
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-credit-card"></i>
			결제내역
		</li>
		<li class="active">ALIPAY결제내역</li>
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
			ALIPAY결제내역
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
											<label>상태<select size="1" onchange="javascript:select_search('<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>&search=d&searchs=',this.value)">
												<option value="">전체</option>
												<option value="shop" <?php if{?>selected="selected"<?php }?>>쇼핑몰</option>
												<option value="point" <?php if{?>selected="selected"<?php }?>>포인트</option>
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
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?php if{?>selected="selected"<?php }?>>업체코드</option>
												<option value="b" <?php if{?>selected="selected"<?php }?>>거래번호</option>
												<option value="c" <?php if{?>selected="selected"<?php }?>>결제자</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>업체명</th>
											<th>승인금액</th>
											<th>거래번호</th>
											<th>내용</th>
											<th>승인결과</th>
											<th>승인일시</th>
											<th>결제자</th>
										</tr>
									</thead>
									<tbody>
									<?php
                                        $sql_count = "SELECT count(*) as cnt FROM {$iw['alipay_table']} a {$search_sql}";
                                        $stmt_count = mysqli_prepare($db_conn, $sql_count);
                                        mysqli_stmt_bind_param($stmt_count, $types, ...$params);
                                        mysqli_stmt_execute($stmt_count);
                                        $result_count = mysqli_stmt_get_result($stmt_count);
                                        $row_count = mysqli_fetch_assoc($result_count);
										$total_line = $row_count['cnt'];
                                        mysqli_stmt_close($stmt_count);

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

										$sql = "SELECT a.*, b.ep_corporate 
                                                FROM {$iw['alipay_table']} a
                                                LEFT JOIN {$iw['enterprise_table']} b ON a.ep_code = b.ep_code
                                                {$search_sql} 
                                                ORDER BY a.ap_no desc 
                                                LIMIT ?, ?";
										$stmt = mysqli_prepare($db_conn, $sql);
                                        $page_params = $params;
                                        $page_params[] = $start_line;
                                        $page_params[] = $max_line;
                                        $page_types = $types . "ii";
                                        mysqli_stmt_bind_param($stmt, $page_types, ...$page_params);
                                        mysqli_stmt_execute($stmt);
										$result = mysqli_stmt_get_result($stmt);

										$i=0;
										while($row = mysqli_fetch_assoc($result)){
											$ep_code = $row["ep_code"];
											$mb_code = $row["mb_code"];
											$pp_invoice = $row["ap_out_trade_no"];
											$pp_txn_id = $row["ap_trade_no"];
											$pp_mc_gross = $row["ap_total_fee"];
											$pp_payment_status = $row["ap_trade_status"];
											$pp_datetime = date( "Y-m-d H:i:s", strtotime($row["ap_datetime"]) );
											$pp_item_name = $row["ap_item_name"];

											$row2 = mysqli_fetch_assoc(mysqli_query($db_conn, "SELECT ep_corporate FROM {$iw['enterprise_table']} WHERE ep_code = '$ep_code'"));
											$ep_corporate = $row2["ep_corporate"];
									?>
										<tr>
											<td data-title="업체명"><?=$ep_corporate?></td>
											<td data-title="승인금액"><?=national_money("en", $pp_mc_gross);?></td>
											<td data-title="거래번호"><?=$pp_txn_id?></td>
											<td data-title="내용"><?=$pp_item_name?></td>
											<td data-title="승인결과"><?=$pp_payment_status?></td>
											<td data-title="승인일시"><?=$pp_datetime?></td>
											<td data-title="결제자"><?=$mb_code?></td>
										</tr>
									<?php
										$i++;
										}
                                        mysqli_stmt_close($stmt);
										if($i==0) echo "<tr><td colspan='8' align='center'>결제승인내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['super_path']?>/pay_alipay_list_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>'">
												<i class="fa fa-check"></i>
												엑셀 출력
											</button>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<ul class="pagination">
											<?php
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

<?php
include_once("_tail.php");
?>



