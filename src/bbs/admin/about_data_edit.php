<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

$ad_code = $_GET["idx"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/about/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$ad_code;

set_cookie("iw_upload",$upload_path,time()+36000);

include_once("_head.php");

$sql = "select * from $iw[about_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and ad_code = '$ad_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["ad_no"]) alert("잘못된 접근입니다!","");

$content = stripslashes($row["ad_content"]);

$ad_subject = $row["ad_subject"];
$ad_subject = str_replace("\'", '&#039;', $ad_subject);
$ad_subject = str_replace('\"', '&quot;', $ad_subject);

$ad_navigation = $row["ad_navigation"];
$ad_navigation = str_replace("\'", '&#039;', $ad_navigation);
$ad_navigation = str_replace('\"', '&quot;', $ad_navigation);
?>
<script src="/include/ckeditor/ckeditor5.js"></script>
<script src="/include/ckeditor/ckeditor5-adapter.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-object-group"></i>
			디자인 설정
		</li>
		<li class="active">독립페이지 관리</li>
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
			독립페이지 관리
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
				<form class="form-horizontal" id="ad_form" name="ad_form" action="<?=$iw['admin_path']?>/about_data_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="ad_code" value="<?=$ad_code?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">네비게이션</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ad_navigation" maxlength="100" value="<?=$ad_navigation?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ad_subject" maxlength="100" value="<?=$ad_subject?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">내용</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents1" name="contents1" rows="10"><?=$content?></textarea>
						</div>
					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/about_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
		if (ad_form.ad_subject.value == "") {
			alert('제목을 입력하여 주십시오.');
			ad_form.ad_subject.focus();
			return;
		}
		if (CKEDITOR.instances.contents1.getData() == "") {
			alert("내용을 입력하여 주십시오.");
			CKEDITOR.instances.contents1.focus();
			return;
		}
		ad_form.submit();
	}
</script>
 
<?php
include_once("_tail.php");
?>



