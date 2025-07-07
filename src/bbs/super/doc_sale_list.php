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
		$search_sql = " AND a.dd_code LIKE '%".$searchs."%'";
	}else if($search == "f"){
		$search_sql = " AND a.db_subject LIKE '%".$searchs."%'";
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
		<li class="active">컨텐츠몰 판매내역</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			컨텐츠몰 판매내역
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
												<option value="e" <?php if($search == "e"){echo "selected";}?>>컨텐츠코드</option>
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
											<th class="text-center">컨텐츠</th>
											<th class="text-center">판매</th>
											<th class="text-center">위즈윈디지털</th>
											<th class="text-center">사이트</th>
											<th class="text-center">판매자</th>
											<th class="text-center">유효기한</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "SELECT a.*,b.ep_corporate,c.gp_subject,d.mb_name,e.mb_name as member FROM {$iw['doc_buy_table']} a LEFT JOIN {$iw['enterprise_table']} b ON a.ep_code = b.ep_code LEFT JOIN {$iw['group_table']} c ON a.gp_code = c.gp_code LEFT JOIN {$iw['member_table']} d ON a.seller_mb_code = d.mb_code LEFT JOIN {$iw['member_table']} e ON a.mb_code = e.mb_code WHERE a.db_datetime >= '{$now_start}' AND a.db_datetime <= '{$now_end}'{$search_sql}";
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

										$sql .= " ORDER BY db_no DESC LIMIT {$start_line}, {$end_line}";
										$result = sql_query($sql);

										$i = 0;
										while($row = @sql_fetch_array($result)){
											$db_subject = $row['db_subject'] ?? '';
											$dd_code = $row['dd_code'] ?? '';
											$db_datetime = $row['db_datetime'] ?? '';
											$db_end_datetime = $row['db_end_datetime'] ?? '';
											$db_price = $row['db_price'] ?? 0;
											$db_price_seller = $row['db_price_seller'] ?? 0;
											$db_price_site = $row['db_price_site'] ?? 0;
											$db_price_super = $row['db_price_super'] ?? 0;
											$ep_code = $row['ep_code'] ?? '';
											$ep_corporate = $row['ep_corporate'] ?? '';
											$gp_code = $row['gp_code'] ?? '';
											$gp_subject = $row['gp_subject'] ?? '';
											$seller_mb_code = $row['seller_mb_code'] ?? '';
											$mb_name = $row['mb_name'] ?? '';
											$mb_code = $row['mb_code'] ?? '';
											$member_name = $row['member'] ?? '';

											if($db_datetime == $db_end_datetime){
												$db_date = "제한없음";
											}else{
												$db_date = date("Y.m.d (H:i)", strtotime($db_end_datetime));
											}
									?>
<tr>
    <td class="text-center" data-title="판매일자" style="white-space:nowrap;"><?php echo $db_datetime;?></td>
	<td class="text-center" data-title="사이트"><?php echo $ep_corporate;  echo $ep_code; ?></td>
<td class="text-center" data-title="그룹"><?php echo $gp_subject;  echo $gp_code; ?></td>
<td class="text-center" data-title="판매자"><?php echo $mb_name;  echo $seller_mb_code; ?></td>
<td class="text-center" data-title="구매자"><?php echo $member_name;  echo $mb_code; ?></td>
    <td class="text-center" data-title="컨텐츠"><?php echo $db_subject;?><br><?php echo $dd_code;?></td>
    <td class="text-end" data-title="판매"><?php echo number_format($db_price); ?></td>
    <td class="text-end" data-title="위즈윈디지털"><?php echo $db_price_super; ?></td>
    <td class="text-end" data-title="사이트"><?php echo $db_price_site; ?></td>
    <td class="text-end" data-title="판매자"><?php echo $db_price_seller; ?></td>
    <td class="text-center" data-title="유효기한"><?php echo $db_date; ?></td>
</tr>
									<?php
										$i ++;
										}
										if($i == 0) echo "<tr><td colspan='11' class='text-center'>판매내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?php echo $iw['super_path']."/doc_sale_excel.php?type=".$iw['type']."&ep=".$iw['store']."&gp=".$iw['group']."&start_date=".$start_date."&end_date=".$end_date."&search=".$search."&searchs=".$searchs_encoded; ?>'">
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

<!-- jQuery UI CSS 추가 -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<!-- 커스텀 스타일 (방문자수와 동일) -->
<style>
.form-control-custom {
    font-size: 1.0rem !important;
    height: 2.3rem !important;
    min-height: 2.3rem !important;
    text-align: center !important;
    padding-right: 30px !important;
}
.btn-custom {
    font-size: 1.0rem !important;
    height: 2.3rem !important;
    min-height: 2.3rem !important;
    padding: 0 2rem !important;
}
.datepicker-wrapper {
    position: relative;
    display: inline-block;
}
.datepicker-icon {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #666;
    font-size: 1rem;
}

/* 테이블 모든 셀 세로 중앙 정렬 */
.table.dataTable th,
.table.dataTable td {
    vertical-align: middle !important;
}
</style>

<!-- jQuery UI JS 추가 -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
$(function() {
    // jQuery UI datepicker 한글 설정
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년'
    });
    // datepicker 적용
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: "c-10:c+10",
        showOn: "focus"
    });
    // 아이콘 클릭시 datepicker 열기
    $(".datepicker-icon").parent().on("click", function() {
        $(this).find(".datepicker").datepicker("show");
    });
});
function check_date(){
    var start_date = date_form.start_date.value.replace(/-/g, '');
    var end_date = date_form.end_date.value.replace(/-/g, '');
    if (start_date.length < 8){
        alert("시작일을 선택해 주세요.");
        date_form.start_date.focus();
        return;
    }
    if (end_date.length < 8){
        alert("종료일을 선택해 주세요.");
        date_form.end_date.focus();
        return;
    }
    date_form.submit();
}
</script>

<?php
include_once("_tail.php");
?>



