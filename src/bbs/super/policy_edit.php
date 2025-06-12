<?php
include_once("_common.php");
set_cookie("iw_upload","/super",time()+36000);
include_once("_head.php");

$sql = "select * from $iw[master_table] where ma_no = 1";
$row = sql_fetch($sql);
?>
<script language="Javascript" src="/include/ckeditor/ckeditor.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">이용약관</li>
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
			이용약관
			<small>
				<i class="fa fa-angle-double-right"></i>
				설정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ma_form" name="ma_form" action="<?=$iw['super_path']?>/policy_edit_ok.php?ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label class="col-sm-1 control-label">이용약관</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents1" name="contents1" rows="10"><?=stripslashes($row["ma_policy_agreement"])?></textarea>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">개인정보 처리방침</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents2" name="contents2" rows="10"><?=stripslashes($row["ma_policy_private"])?></textarea>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이메일주소 무단수집거부</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents3" name="contents3" rows="10"><?=stripslashes($row["ma_policy_email"])?></textarea>
						</div>
					</div>
					
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								확인
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
		if (CKEDITOR.instances.contents1.getData() == "") {
			alert("이용약관을 입력하여 주십시오.");
			CKEDITOR.instances.contents1.focus();
			return;
		}
		if (CKEDITOR.instances.contents2.getData() == "") {
			alert("개인정보 처리방침을 입력하여 주십시오.");
			CKEDITOR.instances.contents2.focus();
			return;
		}
		if (CKEDITOR.instances.contents3.getData() == "") {
			alert("이메일주소 무단수집거부를 입력하여 주십시오.");
			CKEDITOR.instances.contents3.focus();
			return;
		}
		ma_form.submit();
	}
</script>
 
<?
include_once("_tail.php");
?>