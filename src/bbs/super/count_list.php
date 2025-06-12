<?php
include_once("_common.php");
include_once("_head.php");

$search = $_REQUEST['search'] ?? '';
$searchs = $_REQUEST['searchs'] ?? '';
$search_sql = '';

if($search){
	$search_sql = "and ep_code = '$search'";
}
if($searchs){
	$search_sql .= "and gp_code = '$searchs'";
}

// 날짜 형식 변경 (Ymd → Y-m-d)
$start_date = $_REQUEST['start_date'] ?? date("Y-m-d", strtotime(date("Y-m-d").' - 6 days')); 
$end_date = $_REQUEST['end_date'] ?? date("Y-m-d");
?>

<!-- jQuery UI CSS 추가 -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<!-- 커스텀 스타일 -->
<style>
/* 입력창 높이 줄이기 및 중앙정렬 */
.form-select-custom {
    font-size: 1.0rem !important;
    height: 2.8rem !important; /* 10% 줄임 */
    min-height: 2.8rem !important;
    padding: 0.3rem 0.75rem !important;
}

.form-control-custom {
    font-size: 1.0rem !important;
    height: 2.3rem !important; /* 10% 줄임 */
    min-height: 2.3rem !important;
    text-align: center !important; /* 날짜 중앙정렬 */
    padding-right: 30px !important; /* 아이콘 공간 확보 */
}

.btn-custom {
    font-size: 1.0rem !important;
    height: 2.3rem !important; /* 10% 줄임 */
    min-height: 2.3rem !important;
    padding: 0 2rem !important;
}

/* datepicker 입력창 내부 아이콘 스타일 */
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

/* jQuery UI datepicker 크기 조정 */
.ui-datepicker {
    font-size: 0.9rem;
}
.ui-datepicker select.ui-datepicker-month,
.ui-datepicker select.ui-datepicker-year {
    font-size: 0.9rem;
}

.dataTable th,
.dataTable td {
    text-align: center !important;
    vertical-align: middle !important;
}

/* 누적합계 행 스타일 */
.total-row {
    background-color: #f0f0f0;
    font-weight: bold;
}
</style>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">방문자수</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			방문자수
			<small>
				<i class="fa fa-angle-double-right"></i>
				조회
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
											<label style="font-size:1.1rem; font-weight:500;">사이트
												<select size="1" class="form-select form-select-lg form-select-custom" style="width:11rem; min-width:160px; display:inline-block; vertical-align:middle;" onchange="javascript:select_search('<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&search=',this.value)">
													<option value="">전체</option>
													<?php
														$sql = "select * from {$iw['enterprise_table']} where ep_code<>'{$iw['store']}' order by ep_corporate asc";
														$result = sql_query($sql);
														$i=0;
														while($row = @sql_fetch_array($result)){
													?>
														<option value="<?php echo $row["ep_code"]; ?>" <?php if($search == $row["ep_code"]){ echo 'selected="selected"'; }?>><?php echo $row["ep_corporate"]; ?></option>
													<?php }?>
												</select>
											</label>
											<label style="font-size:1.1rem; font-weight:500; margin-left:8px;">그룹
												<select size="1" class="form-select form-select-lg form-select-custom" style="width:11rem; min-width:160px; display:inline-block; vertical-align:middle;" onchange="javascript:select_search('<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&search=<?php echo $search; ?>&searchs=',this.value)">
													<option value="">전체</option>
													<?php
														$sql = "select * from {$iw['group_table']} where ep_code = '$search' order by gp_no asc";
														$result = sql_query($sql);
														$i=0;
														while($row = @sql_fetch_array($result)){
													?>
														<option value="<?php echo $row["gp_code"]; ?>" <?php if($searchs == $row["gp_code"]){ echo 'selected="selected"'; }?>><?php echo $row["gp_subject"]; ?></option>
													<?php }?>
												</select>
											</label>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right text-end">
											<form name="date_form" id="date_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&search=<?php echo $search; ?>&searchs=<?php echo $searchs; ?>" method="post" style="display:inline-block;">
												<div class="datepicker-wrapper">
													<input type="text" name="start_date" maxlength="10" value="<?php echo $start_date; ?>" class="form-control form-control-lg form-control-custom datepicker" style="width:9.5rem; display:inline-block; vertical-align:middle; cursor:pointer;" placeholder="시작일">
													<i class="fas fa-calendar datepicker-icon"></i>
												</div>
												<span style="font-size:1.1rem; font-weight:500; margin:0 6px;">~</span>
												<div class="datepicker-wrapper">
													<input type="text" name="end_date" maxlength="10" value="<?php echo $end_date; ?>" class="form-control form-control-lg form-control-custom datepicker" style="width:9.5rem; display:inline-block; vertical-align:middle; cursor:pointer;" placeholder="종료일">
													<i class="fas fa-calendar datepicker-icon"></i>
												</div>
												<input type="button" onclick="javascript:check_date();" value="조회" class="btn btn-success btn-lg btn-custom" style="margin-left:6px;">
											</form>
										</div>
									</div>
								</div>
								<div id="chart_div" style="width: 100%; height: 500px; margin-top: 20px;"></div>
								<table class="table table-striped table-bordered table-hover dataTable">
    <thead>
        <tr>
            <th>날짜</th>
            <th>페이지뷰</th>
            <th>방문자</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $date_count = round(abs(strtotime($end_date)-strtotime($start_date))/86400);
        $all_page_count = 0;
        $all_ip_count = 0;
        $chart_data = '';
        $table_rows = array(); // 테이블 행 데이터 저장용 배열
        
        // 데이터 수집 (역순을 위해 먼저 배열에 저장)
        for($i=0; $i<=$date_count; $i++){
            $now_date = date("Y-m-d H:i:s", strtotime($start_date.' + '.$i.' days'));
            $row = sql_fetch("select sum(ac_page_count) as page_c,sum(ac_ip_count) as ip_c from {$iw['access_count_table']} where ac_date = '$now_date' $search_sql");
            $ac_page_count = 0;
            $ac_ip_count = 0;
            $ac_page_count += ($row["page_c"] ?? 0);
            $ac_ip_count += ($row["ip_c"] ?? 0);
            $ac_date = date("Y-m-d", strtotime($start_date.' + '.$i.' days'));
            $all_page_count += $ac_page_count;
            $all_ip_count += $ac_ip_count;
            
            // 차트 데이터는 정순으로
            $chart_data .= "['".$ac_date."', ".$ac_page_count.", ".$ac_ip_count."],";
            
            // 테이블 행 데이터 저장
            $table_rows[] = array(
                'date' => $ac_date,
                'page_count' => $ac_page_count,
                'ip_count' => $ac_ip_count
            );
        }
        
        // 역순으로 출력
        for($i = count($table_rows) - 1; $i >= 0; $i--){
    ?>
        <tr>
            <td data-title="날짜"><?php echo $table_rows[$i]['date']; ?></td>
            <td data-title="페이지뷰"><?php echo number_format($table_rows[$i]['page_count']); ?></td>
            <td data-title="방문자"><?php echo number_format($table_rows[$i]['ip_count']); ?></td>
        </tr>
    <?php
        }
        
        // 누적합계 가져오기
        $row_total = sql_fetch("select sum(ac_page_count) as page_c,sum(ac_ip_count) as ip_c from {$iw['access_count_table']} where ac_date <> '' $search_sql");
        $total_page_count = $row_total["page_c"] ?? 0;
        $total_ip_count = $row_total["ip_c"] ?? 0;
        
        $chart_title = $start_date." ~ ".$end_date." ( 페이지뷰 : ".number_format($all_page_count).", 방문자 : ".number_format($all_ip_count)." )";
    ?>
        <!-- 누적합계를 맨 아래로 -->
        <tr class="total-row">
            <td data-title="날짜">누적합계</td>
            <td data-title="페이지뷰"><?php echo number_format($total_page_count); ?></td>
            <td data-title="방문자"><?php echo number_format($total_ip_count); ?></td>
        </tr>
    </tbody>
</table>
							</div><!-- /.table-responsive -->
						</section>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<!-- jQuery UI JS 추가 -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	function select_search(url,search){
		location.href=url+search;
	}
	function check_date(){
		// 날짜 형식 체크 수정 (yyyy-mm-dd)
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

	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
	var data = google.visualization.arrayToDataTable([
		['날짜', '페이지뷰', '방문자'],
		<?php echo $chart_data; ?>
	]);

	var options = {
		title: '<?php echo $chart_title; ?>',
		legend: { position: 'bottom', maxLines: 3 }
	};

	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
</script>
<script>
$(function() {
	// jQuery UI datepicker 한글 설정
	$.datepicker.setDefaults({
		dateFormat: 'yy-mm-dd', // yyyy-mm-dd 형식
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
		showOn: "focus" // 입력창 클릭시만 표시
	});
	
	// 아이콘 클릭시 datepicker 열기
	$(".datepicker-icon").parent().on("click", function() {
		$(this).find(".datepicker").datepicker("show");
	});
});
</script>

<?php
include_once("_tail.php");
?>