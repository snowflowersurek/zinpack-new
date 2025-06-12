<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/main/$row[ep_nick]";

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= "/_board";

set_cookie("iw_upload",$upload_path,time()+36000);

include_once("_head.php");

$nt_no = $_GET["idx"];

$sql = "select * from $iw[notice_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and nt_display = 1 and nt_no = '$nt_no'";
$row = sql_fetch($sql);
if (!$row["nt_no"]) alert("잘못된 접근입니다!","");

$nt_content = stripslashes($row["nt_content"]);

$nt_subject = $row["nt_subject"];
$nt_subject = str_replace("\'", '&#039;', $nt_subject);
$nt_subject = str_replace('\"', '&quot;', $nt_subject);
?>
<script language="Javascript" src="/include/ckeditor/ckeditor.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-bullhorn"></i>
			공지사항
		</li>
		<li class="active">공지사항</li>
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
			공지사항
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
				<form class="form-horizontal" id="nt_form" name="nt_form" action="<?=$iw['admin_path']?>/home_notice_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="nt_no" value="<?=$nt_no?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="nt_subject" maxlength="100" value="<?=$nt_subject?>">
							<span class="help-inline col-xs-12 col-sm-4">
								<label class="middle">
									<input type="checkbox" name="nt_type" value="1" <?if($row["nt_type"]==1){?>checked<?}?>>
									<span class="lbl"> 상단노출</span>
								</label>
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">내용</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents1" name="contents1" height="300px"><?=$nt_content?></textarea>
						</div>
					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								확인
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/home_notice_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$nt_no?>'">
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
		if (nt_form.nt_subject.value == ""){
			alert("제목을 입력하여 주십시오.");
			nt_form.nt_subject.focus();
			return;
		}
		if (CKEDITOR.instances.contents1.getData() == "") {
			alert("본문내용을 입력하여 주십시오.");
			CKEDITOR.instances.contents1.focus();
			return;
		}
		nt_form.submit();
	}
</script>

<?
include_once("_tail.php");
?>