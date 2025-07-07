<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once($iw['admin_path']."/_cg_head.php");

$bd_code = $_GET["idx"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("잘못된 접근입니다!","");
?>
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="bg_form" name="bg_form" action="<?=$iw['admin_path']?>/blog/blog_page_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="bd_code" value="<?=$row['bd_code']?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
					<div class="form-group">
						<label class="col-sm-2 control-label">썸네일</label>
						<div class="col-sm-8">
							<input type="file" class="col-xs-12 col-sm-12" name="bg_image">
							<span class="help-block col-xs-12">
								사이즈(pixel) 240 X 240
							</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">PDF 페이지</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-4" name="bg_page" maxlength="3">
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
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
		if (bg_form.bg_image.value == ""){
			alert("썸네일을 입력하여 주십시오.");
			bg_form.bg_image.focus();
			return;
		}
		if (bg_form.bg_image.value && !bg_form.bg_image.value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
			alert('이미지 파일만 업로드 가능합니다.');
			bg_form.bg_image.focus();
			return;
		}
		if (bg_form.bg_page.value == ""){
			alert("PDF 페이지를 입력하여 주십시오.");
			bg_form.bg_page.focus();
			return;
		}
		var e1 = bg_form.bg_page;
		var num ="0123456789";
		event.returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			bg_form.bg_page.focus();
			return;
		}
		bg_form.submit();
	}
</script>

<?php
include_once($iw['admin_path']."/_cg_tail.php");
?>



