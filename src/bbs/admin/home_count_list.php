<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

if($_POST['search']){
	$search = $_POST['search'];
}else{
	$search = $_GET['search'];
}

if($search){
	$search_sql = "and ep_code = '$search'";
}
if($iw[group] != "all"){
	$search_sql = "and gp_code = '$iw[group]'";
}

if($_POST['start_date']){
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
}else if($_GET['start_date']){
	$start_date = $_GET['start_date'];
	$end_date = $_GET['end_date'];
}else{
	$start_date = date("Ymd", strtotime(date("Ymd").' - 6 days')); 
	$end_date = date("Ymd");
}
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-bar-chart-o"></i>
			방문자수
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
									<?if($iw[group] == "all"){?>
										<div class="dataTable-option">
											<label>그룹<select size="1" onchange="javascript:select_search('<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>&search=',this.value)">
												<option value="">전체</option>
												<?
													$sql = "select * from $iw[group_table] where ep_code = '$iw[store]' order by gp_no asc";
													$result = sql_query($sql);

													$i=0;
													while($row = @sql_fetch_array($result)){
												?>
													<option value="<?=$row["gp_code"]?>" <?if($search == $row["gp_code"]){?>selected="selected"<?}?>><?=$row["gp_subject"]?></option>
												<?}?>
											</select></label>
										</div>
									<?}?>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<form name="date_form" id="date_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=$search?>" method="post">
												<input type="text" name="start_date" maxlength="8" value="<?=$start_date?>">~
												<input type="text" name="end_date" maxlength="8" value="<?=$end_date?>">
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
									<?
										$row = sql_fetch("select sum(ac_page_count) as page_c,sum(ac_ip_count) as ip_c from $iw[access_count_table] where ep_code = '$iw[store]' $search_sql");
										$ac_page_count = $row["page_c"];
										$ac_ip_count = $row["ip_c"];
									?>
										<tr>
											<td data-title="날짜"><b>누적합계</b></td>
											<td data-title="페이지뷰"><b><?=number_format($ac_page_count)?></b></td>
											<td data-title="방문자"><b><?=number_format($ac_ip_count)?></b></td>
										</tr>
									<?
										$date_count = round(abs(strtotime($end_date)-strtotime($start_date))/86400);
										for($i=0; $i<=$date_count; $i++){
											$now_date = date("Y-m-d H:i:s", strtotime(date($start_date).' + '.$i.' days'));
											$row = sql_fetch("select sum(ac_page_count) as page_c,sum(ac_ip_count) as ip_c from $iw[access_count_table] where ep_code = '$iw[store]' and ac_date = '$now_date' $search_sql");
											$ac_page_count = 0;
											$ac_ip_count = 0;
											$ac_page_count += $row["page_c"];
											$ac_ip_count += $row["ip_c"];
											$ac_date = date("Y-m-d", strtotime(date($start_date).' + '.$i.' days'));
											$all_page_count += $ac_page_count;
											$all_ip_count += $ac_ip_count;
											$chart_data .= "['".$ac_date."', ".$ac_page_count.", ".$ac_ip_count."],";
									?>
										<tr>
											<td data-title="날짜"><?=$ac_date?></td>
											<td data-title="페이지뷰"><?=number_format($ac_page_count)?></td>
											<td data-title="방문자"><?=number_format($ac_ip_count)?></td>
										</tr>
									<?
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


	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
	var data = google.visualization.arrayToDataTable([
		['날짜', '페이지뷰', '방문자'],
		<?=$chart_data?>
	]);

	var options = {
		title: '<?=$chart_title?>',
		legend: { position: 'bottom', maxLines: 3 }
	};

	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
</script>
<?
include_once("_tail.php");
?>