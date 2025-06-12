<?php
include_once("_common.php");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
	$db_conn = $connect_db;
}

$search = $_REQUEST['search'];
$searchs = $_REQUEST['searchs'];
$start_date = $_REQUEST['start_date'] ?: date("Ymd", strtotime(date("Ymd").' -2 months'));
$end_date = $_REQUEST['end_date'] ?: date("Ymd");

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

$search_sql = " WHERE (a.pt_datetime >= ? AND a.pt_datetime <= ?) ";
$params = [$now_start, $now_end];
$types = "ss";

if($searchs) {
	$keyword_param = "%{$searchs}%";
	if($search =="a"){
		$search_sql .= " AND a.ep_code LIKE ? ";
		$params[] = $keyword_param; $types .= "s";
	}else if($search =="b"){
		$search_sql .= " AND a.ogd_oid LIKE ? ";
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
		<li class="active">관리비 결제내역</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			관리비 결제내역
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
												<option value="a" <?if($search == "a"){?>selected="selected"<?}?>>업체코드</option>
												<option value="b" <?if($search == "b"){?>selected="selected"<?}?>>거래번호</option>
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
											<th>입금액</th>
											<th>거래번호</th>
											<th>승인결과</th>
											<th>승인일시</th>
											<th>결제방식</th>
											<th>결제자</th>
											<th>만료일자</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql_count = "SELECT count(*) as cnt FROM {$iw['charge_table']} a {$search_sql}";
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

										$sql = "SELECT a.*, b.lgd_respmsg, b.lgd_buyer, b.lgd_castamount, c.ep_corporate, c.ep_expiry_date
												FROM {$iw['charge_table']} a
												LEFT JOIN {$iw['lgd_table']} b ON a.ogd_oid = b.lgd_oid
												LEFT JOIN {$iw['enterprise_table']} c ON a.ep_code = c.ep_code
												{$search_sql} ORDER BY a.ch_no desc LIMIT ?, ?";
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
											$ogd_oid = $row["ogd_oid"];
											$ch_amount = number_format($row["ch_amount"]);
											$lgd_paydate = date( "Y-m-d H:i:s", strtotime($row["pt_datetime"]) );
											$ch_paytype = $row["ch_paytype"];

											$row1 = sql_fetch("select * from $iw[lgd_table] where lgd_oid = '$ogd_oid'");
											if($row1['lgd_oid']==""){
												$lgd_respmsg = "미승인";
												$lgd_buyer = "";
												$lgd_castamount = "0";
											}else{
												$lgd_respmsg = $row1["lgd_respmsg"];
												$lgd_buyer = $row1["lgd_buyer"];
												$lgd_castamount = $row1["lgd_castamount"];
											}

											$row2 = sql_fetch("select ep_corporate, ep_expiry_date from $iw[enterprise_table] where ep_code = '$ep_code'");
											$ep_corporate = $row2["ep_corporate"];
											$ep_expiry = $row2["ep_expiry_date"];
									?>
										<tr>
											<td data-title="업체명"><?=$ep_corporate?></td>
											<td data-title="승인금액"><?=$ch_amount?>원</td>
											<td data-title="입금액"><?if($ch_paytype=="SC0040"){?><?=$lgd_castamount?>원<?}?></td>
											<td data-title="거래번호"><?=$ogd_oid?></td>
											<td data-title="승인결과"><?=$lgd_respmsg?></td>
											<td data-title="승인일시"><?=$lgd_paydate?></td>
											<td data-title="결제방식">
												<?if($ch_paytype=="SC0010"){?>
													신용카드
												<?}else if($ch_paytype=="SC0030"){?>
													계좌이체
												<?}else if($ch_paytype=="SC0060"){?>
													휴대폰
												<?}else if($ch_paytype=="SC0040"){?>
													가상계좌
												<?}?>
											</td>
											<td data-title="결제자"><?=$lgd_buyer?></td>
											<td data-title="만료일자"><?=$ep_expiry?></td>
										</tr>
									<?
										$i++;
										}
										mysqli_stmt_close($stmt);
										if($i==0) echo "<tr><td colspan='9' align='center'>결제승인내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<!-- <div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['super_path']?>/pay_list_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>'">
												<i class="fa fa-check"></i>
												엑셀 출력
											</button>
										</div>
									</div> -->
									<div class="col-sm-12">
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