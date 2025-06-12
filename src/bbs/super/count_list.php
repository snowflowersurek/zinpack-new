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

$start_date = $_REQUEST['start_date'] ?? date("Ymd", strtotime(date("Ymd").' - 6 days')); 
$end_date = $_REQUEST['end_date'] ?? date("Ymd");
?>
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
											<label>사이트<select size="1" onchange="javascript:select_search('<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&search=',this.value)">
												<option value="">전체</option>
												<?php
													$sql = "select * from {$iw['enterprise_table']} where ep_code<>'{$iw['store']}' order by ep_corporate asc";
													$result = sql_query($sql);

													$i=0;
													while($row = @sql_fetch_array($result)){
												?>
													<option value="<?php echo $row["ep_code"]; ?>" <?php if($search == $row["ep_code"]){ echo 'selected="selected"'; }?>><?php echo $row["ep_corporate"]; ?></option>
												<?php }?>
											</select></label>

											<label>그룹<select size="1" onchange="javascript:select_search('<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&search=<?php echo $search; ?>&searchs=',this.value)">
												<option value="">전체</option>
												<?php
													$sql = "select * from {$iw['group_table']} where ep_code = '$search' order by gp_no asc";
													$result = sql_query($sql);

													$i=0;
													while($row = @sql_fetch_array($result)){
												?>
													<option value="<?php echo $row["gp_code"]; ?>" <?php if($searchs == $row["gp_code"]){ echo 'selected="selected"'; }?>><?php echo $row["gp_subject"]; ?></option>
												<?php }?>
											</select></label>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<form name="date_form" id="date_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&search=<?php echo $search; ?>&searchs=<?php echo $searchs; ?>" method="post">
												<input type="text" name="start_date" maxlength="8" value="<?php echo $start_date; ?>">~
												<input type="text" name="end_date" maxlength="8" value="<?php echo $end_date; ?>">
												<input type="button" onclick="javascript:check_date();" value="조회">
											</form>
										</div>
									</div>
								</div><div id="chart_div" style="width: 100%; height: 500px;"></div>
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
										$row = sql_fetch("select sum(ac_page_count) as page_c,sum(ac_ip_count) as ip_c from {$iw['access_count_table']} where ac_date <> '' $search_sql");
										$ac_page_count = $row["page_c"] ?? 0;
										$ac_ip_count = $row["ip_c"] ?? 0;
									?>
										<tr>
											<td data-title="날짜"><b>누적합계</b></td>
											<td data-title="페이지뷰"><b><?php echo number_format($ac_page_count); ?></b></td>
											<td data-title="방문자"><b><?php echo number_format($ac_ip_count); ?></b></td>
										</tr>
									<?php
										$date_count = round(abs(strtotime($end_date)-strtotime($start_date))/86400);
                                        $all_page_count = 0;
                                        $all_ip_count = 0;
                                        $chart_data = '';
										for($i=0; $i<=$date_count; $i++){
											$now_date = date("Y-m-d H:i:s", strtotime(date($start_date).' + '.$i.' days'));
											$row = sql_fetch("select sum(ac_page_count) as page_c,sum(ac_ip_count) as ip_c from {$iw['access_count_table']} where ac_date = '$now_date' $search_sql");
											$ac_page_count = 0;
											$ac_ip_count = 0;
											$ac_page_count += ($row["page_c"] ?? 0);
											$ac_ip_count += ($row["ip_c"] ?? 0);
											$ac_date = date("Y-m-d", strtotime(date($start_date).' + '.$i.' days'));
											$all_page_count += $ac_page_count;
											$all_ip_count += $ac_ip_count;
											$chart_data .= "['".$ac_date."', ".$ac_page_count.", ".$ac_ip_count."],";
									?>
										<tr>
											<td data-title="날짜"><?php echo $ac_date; ?></td>
											<td data-title="페이지뷰"><?php echo number_format($ac_page_count); ?></td>
											<td data-title="방문자"><?php echo number_format($ac_ip_count); ?></td>
										</tr>
									<?php
										}
										$chart_title = date("Y-m-d", strtotime(date($start_date)))." ~ ".date("Y-m-d", strtotime(date($end_date)))." ( 페이지뷰 : ".number_format($all_page_count).", 방문자 : ".number_format($all_ip_count)." )";
									?>
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

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
<?php
include_once("_tail.php");
?>