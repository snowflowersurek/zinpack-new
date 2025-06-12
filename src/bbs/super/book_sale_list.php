<?php
include_once("_common.php");
include_once("_head.php");

$search = $_REQUEST['search'] ?? '';
$searchs = $_REQUEST['searchs'] ?? '';
$search_sql = '';

if(!empty($searchs)) {
	if($search == "a"){
		$search_sql = " AND a.ep_code LIKE '%".$searchs."%'";
	}else if($search == "b"){
		$search_sql = " AND a.gp_code LIKE '%".$searchs."%'";
	}else if($search == "c"){
		$search_sql = " AND a.seller_mb_code LIKE '%".$searchs."%'";
	}else if($search == "d"){
		$search_sql = " AND a.mb_code LIKE '%".$searchs."%'";
	}else if($search == "e"){
		$search_sql = " AND a.bd_code LIKE '%".$searchs."%'";
	}else if($search == "f"){
		$search_sql = " AND a.bb_subject LIKE '%".$searchs."%'";
	}
}
$searchs_encoded = urlencode($searchs);

$start_date = $_REQUEST['start_date'] ?? '20131201';
$end_date = $_REQUEST['end_date'] ?? date("Ymd");

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-line-chart"></i>
			매출내역
		</li>
		<li class="active">이북몰 판매내역</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			이북몰 판매내역
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
										<form name="date_form" id="date_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&search=<?php echo $search; ?>&searchs=<?php echo $searchs_encoded; ?>" method="post" style="display:inline-block;">
											<div class="datepicker-wrapper">
												<input type="text" name="start_date" maxlength="10" value="<?php echo date('Y-m-d', strtotime($start_date)); ?>" class="form-control form-control-lg form-control-custom datepicker" style="width:9.5rem; display:inline-block; vertical-align:middle; cursor:pointer;" placeholder="시작일" readonly>
												<i class="fas fa-calendar datepicker-icon"></i>
											</div>
											<span style="font-size:1.1rem; font-weight:500; margin:0 6px;">~</span>
											<div class="datepicker-wrapper">
												<input type="text" name="end_date" maxlength="10" value="<?php echo date('Y-m-d', strtotime($end_date)); ?>" class="form-control form-control-lg form-control-custom datepicker" style="width:9.5rem; display:inline-block; vertical-align:middle; cursor:pointer;" placeholder="종료일" readonly>
												<i class="fas fa-calendar datepicker-icon"></i>
											</div>
											<input type="button" onclick="javascript:check_date();" value="조회" class="btn btn-success btn-lg btn-custom" style="margin-left:6px;">
										</form>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<form name="search_form" id="search_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" method="post">
											<label>검색: <select name="search" class="form-select form-select-lg" style="font-size:1.1rem; display:inline-block; width:12rem; min-width:180px; height:120%; min-height:3.2rem; vertical-align:middle;">
												<option value="a" <?php if($search == "a"){echo "selected";}?>>업체코드</option>
												<option value="b" <?php if($search == "b"){echo "selected";}?>>그룹코드</option>
												<option value="c" <?php if($search == "c"){echo "selected";}?>>판매자코드</option>
												<option value="d" <?php if($search == "d"){echo "selected";}?>>구매자코드</option>
												<option value="e" <?php if($search == "e"){echo "selected";}?>>이북코드</option>
												<option value="f" <?php if($search == "f"){echo "selected";}?>>제목</option>
											</select></label><input type="text" name="searchs" value="<?php echo htmlspecialchars($searchs, ENT_QUOTES); ?>" class="form-control form-control-lg" style="font-size:1.1rem; width:auto; display:inline-block; vertical-align:middle; margin-left:8px; height:120%; min-height:2.5rem;">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th class="text-center">판매일자</th>
											<th class="text-center">사이트</th>
											<th class="text-center">그룹</th>
											<th class="text-center">판매자</th>
											<th class="text-center">구매자</th>
											<th class="text-center">이북</th>
											<th class="text-center">판매</th>
											<th class="text-center">위즈윈디지털</th>
											<th class="text-center">사이트</th>
											<th class="text-center">판매자</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "SELECT a.*,b.ep_corporate,c.gp_subject,d.mb_name,e.mb_name as member FROM {$iw['book_buy_table']} a LEFT JOIN {$iw['enterprise_table']} b ON a.ep_code = b.ep_code LEFT JOIN {$iw['group_table']} c ON a.gp_code = c.gp_code LEFT JOIN {$iw['member_table']} d ON a.seller_mb_code = d.mb_code LEFT JOIN {$iw['member_table']} e ON a.mb_code = e.mb_code WHERE a.bb_datetime >= '{$now_start}' AND a.bb_datetime <= '{$now_end}'{$search_sql}";
										$result = sql_query($sql);
										$total_line = mysqli_num_rows($result);

										$max_line = 10;
										$max_page = 10;
											
										$page = $_GET['page'] ?? 1;
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

										$sql .= " ORDER BY bb_no DESC LIMIT {$start_line}, {$end_line}";
										$result = sql_query($sql);

										$i = 0;
										while($row = @sql_fetch_array($result)){
											$bb_subject = $row['bb_subject'] ?? '';
											$bd_code = $row['bd_code'] ?? '';
											$bb_datetime = $row['bb_datetime'] ?? '';
											$bb_price = $row['bb_price'] ?? 0;
											$bb_price_seller = $row['bb_price_seller'] ?? 0;
											$bb_price_site = $row['bb_price_site'] ?? 0;
											$bb_price_super = $row['bb_price_super'] ?? 0;
											$ep_code = $row['ep_code'] ?? '';
											$ep_corporate = $row['ep_corporate'] ?? '';
											$gp_code = $row['gp_code'] ?? '';
											$gp_subject = $row['gp_subject'] ?? '';
											$seller_mb_code = $row['seller_mb_code'] ?? '';
											$mb_name = $row['mb_name'] ?? '';
											$mb_code = $row['mb_code'] ?? '';
											$member_name = $row['member'] ?? '';
									?>
										<tr>
											<td class="text-center" data-title="판매일자" style="white-space:nowrap;"><?php echo $bb_datetime;?></td>
											<td class="text-center" data-title="사이트"><?php echo $ep_corporate; ?> <?php echo $ep_code;?></td>
											<td class="text-center" data-title="그룹"><?php echo $gp_subject; ?> <?php echo $gp_code;?></td>
											<td class="text-center" data-title="판매자"><?php echo $mb_name; ?> <?php echo $seller_mb_code;?></td>
											<td class="text-center" data-title="구매자"><?php echo $member_name; ?> <?php echo $mb_code;?></td>
											<td class="text-center" data-title="이북"><?php echo $bb_subject;?><br><?php echo $bd_code;?></td>
											<td class="text-end" data-title="판매(point)"><?php echo number_format($bb_price); ?></td>
											<td class="text-end" data-title="위즈윈디지털(point)"><?php echo $bb_price_super; ?></td>
											<td class="text-end" data-title="사이트(point)"><?php echo $bb_price_site; ?></td>
											<td class="text-end" data-title="판매자(point)"><?php echo $bb_price_seller; ?></td>
										</tr>
									<?php
										$i ++;
										}
										if($i == 0) echo "<tr><td colspan='10' class='text-center'>판매내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?php echo $iw['super_path']."/book_sale_excel.php?type=".$iw['type']."&ep=".$iw['store']."&gp=".$iw['group']."&start_date=".$start_date."&end_date=".$end_date."&search=".$search."&searchs=".$searchs_encoded; ?>'">
												<i class="fa fa-check"></i>
												엑셀 출력
											</button>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right text-end">
											<ul class="pagination justify-content-end" style="gap:4px;">
											<?php
												if($total_page != 0){
													if($page > $total_page) { $page = $total_page; }
													$start_page = ((ceil($page / $max_page) - 1) * $max_page) + 1;
													$end_page = $start_page + $max_page - 1;
												 
													if($end_page > $total_page) {$end_page = $total_page;}
												 
													if($page > $max_page) {
														$pre = $start_page - 1;
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm' href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&start_date={$start_date}&end_date={$end_date}&search={$search}&searchs={$searchs_encoded}&page={$pre}'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm disabled' href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='page-item'><a class='btn btn-secondary btn-sm active' href='#'>$i</a></li>";
														else          echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm' href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&start_date={$start_date}&end_date={$end_date}&search={$search}&searchs={$searchs_encoded}&page={$i}'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm' href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&start_date={$start_date}&end_date={$end_date}&search={$search}&searchs={$searchs_encoded}&page={$next}'><i class='fa fa-angle-double-right'></i></a></li>";
													} else {
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm disabled' href='#'><i class='fa fa-angle-double-right'></i></a></li>";
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