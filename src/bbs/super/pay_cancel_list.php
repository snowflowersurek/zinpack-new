<?php
include_once("_common.php");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
	$db_conn = $connect_db;
}

$search = $_REQUEST['search'] ?? '';
$searchs = $_REQUEST['searchs'] ?? '';
$start_date = $_REQUEST['start_date'] ?? "20131201";
$end_date = $_REQUEST['end_date'] ?? date("Ymd");

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

$search_sql = " WHERE (a.lgdc_datetime >= ? AND a.lgdc_datetime <= ?) ";
$params = [$now_start, $now_end];
$types = "ss";

if(!empty($searchs)) {
	$keyword_param = "%{$searchs}%";
	if($search =="a"){
		$search_sql .= " AND a.ep_code LIKE ? ";
		$params[] = $keyword_param; $types .= "s";
	}else if($search =="b"){
		$search_sql .= " AND a.lgd_tid LIKE ? ";
		$params[] = $keyword_param; $types .= "s";
	}else if($search =="c"){
		$search_sql .= " AND a.lgdc_tid LIKE ? ";
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
		<li class="active">TOSS 취소내역</li>
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
			TOSS 취소내역
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
										<form name="date_form" id="date_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&search=<?php echo $search; ?>&searchs=<?php echo $searchs; ?>" method="post">
											<input type="text" name="start_date" maxlength="8" value="<?php echo $start_date; ?>">~
											<input type="text" name="end_date" maxlength="8" value="<?php echo $end_date; ?>">
											<input type="button" onclick="javascript:check_date();" value="조회">
										</form>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<form name="search_form" id="search_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?php if($search == "a"){ echo 'selected="selected"'; }?>>업체코드</option>
												<option value="b" <?php if($search == "b"){ echo 'selected="selected"'; }?>>거래번호</option>
												<option value="c" <?php if($search == "c"){ echo 'selected="selected"'; }?>>주문번호</option>
											</select></label><input type="text" name="searchs" value="<?php echo htmlspecialchars($searchs, ENT_QUOTES); ?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>업체명</th>
											<th>취소금액</th>
											<th>거래번호</th>
											<th>주문번호</th>
											<th>승인결과</th>
											<th>승인일시</th>
											<th>결제방식</th>
											<th>결제자</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql_count = "SELECT count(*) as cnt FROM {$iw['lgd_cancel_table']} a {$search_sql}";
										$stmt_count = mysqli_prepare($db_conn, $sql_count);
										if ($types) {
											mysqli_stmt_bind_param($stmt_count, $types, ...$params);
										}
										mysqli_stmt_execute($stmt_count);
										$result_count = mysqli_stmt_get_result($stmt_count);
										$row_count = mysqli_fetch_assoc($result_count);
										$total_line = $row_count['cnt'] ?? 0;
										mysqli_stmt_close($stmt_count);

										$max_line = 10;
										$max_page = 10;
											
										$page = $_GET["page"] ?? 1;
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

										$sql = "SELECT a.*, b.lgd_oid, b.lgd_amount, b.lgd_buyer, b.lgd_paytype, c.ep_corporate
												FROM {$iw['lgd_cancel_table']} a
												LEFT JOIN {$iw['lgd_table']} b ON a.lgdc_tid = b.lgd_tid
												LEFT JOIN {$iw['enterprise_table']} c ON a.ep_code = c.ep_code
												{$search_sql} 
												ORDER BY a.lgdc_no desc 
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
											$ep_code = $row["ep_code"] ?? '';
											$lgd_oid = $row["lgd_oid"] ?? '';
											$lgdc_tid = $row["lgdc_tid"] ?? '';
											$lgdc_respmsg = $row["lgdc_respmsg"] ?? '';
											$lgdc_datetime = $row["lgdc_datetime"] ? date( "Y-m-d H:i:s", strtotime($row["lgdc_datetime"])) : '';

											$sqls = "select * from {$iw['lgd_table']} where lgd_tid = '{$lgdc_tid}' and lgd_display = 2";
											$rows = sql_fetch($sqls);
											$lgd_amount = number_format($row["lgd_amount"] ?? 0);
											$lgd_financeauthnum = $rows["lgd_financeauthnum"] ?? '';
											$lgd_buyer = $row["lgd_buyer"] ?? '';
											$lgd_paytype = $row["lgd_paytype"] ?? '';

											$row2 = sql_fetch("select ep_corporate from {$iw['enterprise_table']} where ep_code = '$ep_code'");
											$ep_corporate = $row2["ep_corporate"] ?? '';
									?>
										<tr>
											<td data-title="업체명"><?php echo $ep_corporate; ?></td>
											<td data-title="취소금액"><?php echo $lgd_amount; ?>원</td>
											<td data-title="거래번호"><?php echo $lgdc_tid; ?></td>
											<td data-title="주문번호"><?php echo $lgd_oid; ?></td>
											<td data-title="승인결과"><?php echo $lgdc_respmsg; ?></td>
											<td data-title="승인일시"><?php echo $lgdc_datetime; ?></td>
											<td data-title="결제방식">
												<?php if($lgd_paytype=="SC0010"){?>
													신용카드
												<?php }else if($lgd_paytype=="SC0030"){?>
													계좌이체
												<?php }else if($lgd_paytype=="SC0040"){?>
													가상계좌
												<?php }else if($lgd_paytype=="SC0060"){?>
													휴대폰
												<?php }?>
											</td>
											<td data-title="결제자"><?php echo $lgd_buyer; ?></td>
										</tr>
									<?php
										$i++;
										}
										mysqli_stmt_close($stmt);
										if($i==0) echo "<tr><td colspan='8' align='center'>취소승인내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?php echo $iw['super_path']; ?>/pay_cancel_list_excel.php?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>'">
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
														echo "<li class='prev'><a href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&page=$pre&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&page=$i&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&page=$next&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'><i class='fa fa-angle-double-right'></i></a></li>";
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
		var event_returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event_returnValue = false;
		}
		if (!event_returnValue){
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
		event_returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event_returnValue = false;
		}
		if (!event_returnValue){
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