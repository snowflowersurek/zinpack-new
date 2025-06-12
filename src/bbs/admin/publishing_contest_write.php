<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

$iw[CKeditor] = "on";

$row = sql_fetch("select ep_nick,ep_upload,ep_upload_size from $iw[enterprise_table] where ep_code = '$iw[store]'");
$ep_upload = $row[ep_upload];
$ep_upload_size = $row[ep_upload_size];
$contest_code = "ct".uniqid(rand());
$upload_path = "/publishing/".$row[ep_nick]."/contest/".$contest_code;

set_cookie("iw_upload",$upload_path,time()+36000);

include_once("_head.php");

if(preg_match('/(iPod|iPhone|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone)/i', $_SERVER['HTTP_USER_AGENT'])){
	$mobile_check = "ok";
}
?>

<script type="text/javascript" src="/include/ckeditor/ckeditor.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">공모전관리</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			공모전관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				상세정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="contest_form" name="contest_form" action="<?=$iw['admin_path']?>/publishing_contest_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="contest_code" value="<?=$contest_code?>" />
					<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
					<input type="hidden" name="ep_upload_size" value="<?=$ep_upload_size?>" />

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">분류</label>
						<div class="col-sm-8">
							<select name="cg_code">
								<option value="">선택</option>
								<?php
								$category_result = sql_query("select cg_code, cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'publishing_contest' order by cg_name asc");

								while($row = @sql_fetch_array($category_result)){
									echo "<option value='$row[cg_code]'>$row[cg_name]</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" name="subject" value="" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">공모기간</label>
						<div class="col-sm-8">
							<input type="text" name="start_date" class="contest_date" value="" maxlength="10" readonly style="width:90px;">
							~
							<input type="text" name="end_date" class="contest_date" value="" maxlength="10" readonly style="width:90px;">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">첨부파일</label>
						<div class="col-sm-8">
							<input type="file" name="attach_file" id="attach_file">
						</div>
					</div>

					<div class="form-group" id="web_editor">
						<label class="col-sm-1 control-label">상세내용</label>
						<div class="col-sm-8">
							<textarea class="ckeditor" id="content" name="content"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">게시상태</label>
						<div class="col-sm-8">
							<input type="radio" name="display" id="display1" value="1" checked>
							<label for="display1"> 노출</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" name="display" id="display2" value="2">
							<label for="display2"> 숨김</label>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/publishing_contest_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
								<i class="fa fa-undo"></i>
								취소
							</button>
						</div>
					</div>
				</form>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
$(function() {
	$(".contest_date").datepicker({
		dateFormat: 'yy-mm-dd', //형식(2012-03-03)
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		autoSize: false, //오토리사이즈(body등 상위태그의 설정에 따른다)
		changeMonth: true, //월변경가능
		changeYear: true, //년변경가능
		showMonthAfterYear: true, //년 뒤에 월 표시
		buttonImageOnly: false, //이미지표시
		showOn: "focus" //엘리먼트와 이미지 동시 사용
	});
});

function check_form() {
	if (contest_form.cg_code.selectedIndex == 0){
		alert("분류를 선택하세요.");
		contest_form.cg_code.focus();
		return;
	}
	if (contest_form.subject.value == ""){
		alert("공모전 제목을 입력하세요.");
		contest_form.subject.focus();
		return;
	}
	if (contest_form.start_date.value == "" || contest_form.end_date.value == ""){
		alert("공모기간을 입력하세요.");
		return;
	}

	var start_date = new Date(contest_form.start_date.value);
	var end_date = new Date(contest_form.end_date.value);

	if (start_date > end_date) {
		alert("공모 마감일이 시작일보다 이전입니다.");
		return;
	}
	if (CKEDITOR.instances.content.getData() == "") {
		alert("공모전 상세내용을 입력하세요.");
		CKEDITOR.instances.content.focus();
		return;
	}
	contest_form.submit();
}
</script>

<?
include_once("_tail.php");
?>