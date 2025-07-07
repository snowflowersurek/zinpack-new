<?php
include_once("_common.php");
if ($iw[level] != "admin") alert("잘못된 접근입니다!","");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/main/".$row[ep_nick]."/all/_images";

set_cookie("iw_upload",$upload_path,time()+36000);

include_once("_head.php");

$pl_no = $_GET["idx"];

$sql = "select * from $iw[popup_layer_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and pl_no = '$pl_no'";
$row = sql_fetch($sql);
if (!$row["pl_no"]) alert("잘못된 접근입니다!","");
$pl_subject = $row["pl_subject"];
$pl_subject = str_replace("\'", '&#039;', $pl_subject);
$pl_subject = str_replace('\"', '&quot;', $pl_subject);
$pl_stime = date("Y-m-d", strtotime($row["pl_stime"]));
$pl_etime = date("Y-m-d", strtotime($row["pl_etime"]));
$pl_width = $row["pl_width"];
$pl_height = $row["pl_height"];
$pl_top = $row["pl_top"];
$pl_left = $row["pl_left"];
$pl_state = $row["pl_state"];
$pl_dayback = $row["pl_dayback"];
$pl_dayfont = $row["pl_dayfont"];
$pl_line = $row["pl_line"];
$pl_content = stripslashes($row["pl_content"]);


?>
<script src="/include/ckeditor/ckeditor5.js"></script>
<script src="/include/ckeditor/ckeditor5-adapter.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-object-group"></i>
			디자인 설정
		</li>
		<li class="active">팝업창</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			팝업창
			<small>
				<i class="fa fa-angle-double-right"></i>
				수정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="pl_form" name="pl_form" action="<?=$iw['admin_path']?>/popup_layer_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="pl_no" value="<?=$pl_no?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">기간</label>
						<div class="col-sm-11">
							<input type="text" id="sc_reserve_date1" name="pl_stime" size="10" value="<?echo $pl_stime;?>" readonly> ~ 
							<input type="text" id="sc_reserve_date2" name="pl_etime" size="10" value="<?echo $pl_etime;?>" readonly> 까지 (ex 2013-02-02)
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="pl_subject" maxlength="100" value="<?=$pl_subject?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">내용</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents1" name="contents1" rows="10"><?=$pl_content?></textarea>
						</div>
					</div>

					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">창 크기</label>
						<div class="col-sm-11">
							가로 <input type="text" placeholder="입력" name="pl_width" size="6" maxlength="4" value="<?=$pl_width?>"> px &nbsp;
							세로 <input type="text" placeholder="입력" name="pl_height" size="6" maxlength="4" value="<?=$pl_height?>"> px
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">위치</label>
						<div class="col-sm-11">
							위에서 <input type="text" placeholder="입력" name="pl_top" size="6" maxlength="4" value="<?=$pl_top?>"> px &nbsp; 
							왼쪽에서 <input type="text" placeholder="입력" name="pl_left" size="6" maxlength="4" value="<?=$pl_left?>"> px
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">배경색</label>
						<div class="col-sm-3 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
							<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#ffffff" data-toggle="tooltip" name="pl_dayback" maxlength="7" value="<?=$pl_dayback?>"> 
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">글자색</label>
						<div class="col-sm-3 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
							<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#ffffff" data-toggle="tooltip" name="pl_dayfont" maxlength="7" value="<?=$pl_dayfont?>"> 
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">테두리색</label>
						<div class="col-sm-3 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
							<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#ffffff" data-toggle="tooltip" name="pl_line" maxlength="7" value="<?=$pl_line?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사용 설정</label>
						<div class="col-sm-11">
							<select name="pl_state" id="pl_state">
								<option value="1" <?php if{?>selected<?php }?>>사용</option>
								<option value="0" <?php if{?>selected<?php }?>>미사용</option>
							</select>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								수정
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/popup_layer_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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

<script type="text/javascript">
	function check_form() {
		if (pl_form.pl_subject.value == "") {
			alert('제목을 입력하여 주십시오.');
			pl_form.pl_subject.focus();
			return;
		}
		if (pl_form.pl_width.value == "") {
			alert('창크기를 입력하여 주십시오.');
			pl_form.pl_width.focus();
			return;
		}
		if (pl_form.pl_height.value == "") {
			alert('창크기를 입력하여 주십시오.');
			pl_form.pl_height.focus();
			return;
		}
		if (pl_form.pl_top.value == "") {
			alert('위치를 입력하여 주십시오.');
			pl_form.pl_top.focus();
			return;
		}
		if (pl_form.pl_left.value == "") {
			alert('위치를 입력하여 주십시오.');
			pl_form.pl_left.focus();
			return;
		}
		if (CKEDITOR.instances.contents1.getData() == "") {
			alert("내용을 입력하여 주십시오.");
			CKEDITOR.instances.contents1.focus();
			return;
		}
		pl_form.submit();
	}
</script>
 
<?php
include_once("_tail.php");
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
	$(function() {
		<?php for($a=1; $a <= 2; $a++){?>
			$("#sc_reserve_date<?echo $a;?>").datepicker({
				monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
				dayNamesMin: ['일','월','화','수','목','금','토'],
				weekHeader: 'Wk',
				dateFormat: 'yy-mm-dd', //형식(2012-03-03)
				autoSize: false, //오토리사이즈(body등 상위태그의 설정에 따른다)
				changeMonth: true, //월변경가능
				changeYear: true, //년변경가능
				showMonthAfterYear: true, //년 뒤에 월 표시
				buttonImageOnly: false, //이미지표시
				showOn: "focus", //엘리먼트와 이미지 동시 사용
				yearRange: '2015:<?echo date("Y") + 2;?>' //2005년부터 내년까지(php)
			});
		<?php }?>
	});
</script>



