<?php
include_once("_common.php");
if ($iw[type] != "publishing" || ($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

$iw[CKeditor] = "on";

$row = sql_fetch("select ep_nick,ep_upload,ep_upload_size from $iw[enterprise_table] where ep_code = '$iw[store]'");
$ep_upload = $row[ep_upload];
$ep_upload_size = $row[ep_upload_size];
$upload_path = "/$iw[type]/$row[ep_nick]/author";
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
		<li class="active">저자관리</li>
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
			저자관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				작가정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="author_form" name="author_form" action="<?=$iw['admin_path']?>/publishing_author_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">

					<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
					<input type="hidden" name="ep_upload_size" value="<?=$ep_upload_size?>" />

					<div class="form-group">
						<label class="col-sm-1 control-label">작가명(한글)</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" name="Author" value="" maxlength="30">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">작가명(원어)</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" name="original_name" value="" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">글쓰기 방식</label>
						<div class="col-sm-8">
							<input type="radio" name="content_type" value="1" id="content_type1" onclick="javascript:type_change(this.value);" checked/>
							<label for="content_type1">간편모드</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" name="content_type" value="2" id="content_type2" onclick="javascript:type_change(this.value);" />
							<label for="content_type2">웹에디터 <?php if{?> (모바일 미지원) <?php }?></label>
						</div>
					</div>

					<div class="form-group" id="text_only">
						<label class="col-sm-1 control-label">프로필</label>
						<div class="col-sm-8">
							<textarea name="ProFile1" class="col-sm-12" style="height:150px;"></textarea>
						</div>
					</div>

					<div class="form-group" id="web_editor" style="display:none;">
						<label class="col-sm-1 control-label">프로필</label>
						<div class="col-sm-8">
							<textarea class="ckeditor" id="ProFile2" name="ProFile2"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">연락처</label>
						<div class="col-sm-8">
							<input type="text" name="phone" value="" maxlength="30">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">프로필사진</label>
						<div class="col-sm-8">
							<input type="file" class="col-sm-12" name="NewPhoto" id="NewPhoto">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상태</label>
						<div class="col-sm-8">
							<input type="radio" name="author_display" id="display1" value="1" checked>
							<label for="display1"> 노출</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" name="author_display" id="display2" value="2">
							<label for="display2"> 숨김</label>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw[admin_path]?>/publishing_author_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
function type_change(check) {
	if (check == "1"){
		document.getElementById('text_only').style.display="";
		document.getElementById('web_editor').style.display="none";
	}else if (check == "2"){
		document.getElementById('text_only').style.display="none";
		document.getElementById('web_editor').style.display="";
	}
}

function check_form() {
	if (author_form.Author.value == ""){
		alert("작가명(한글)을 입력하세요.");
		author_form.Author.focus();
		return;
	}
	if (author_form.content_type.value == "1" && author_form.ProFile1.value == ""){
		alert("프로필을 입력하세요.");
		author_form.ProFile1.focus();
		return;
	} else if (author_form.content_type.value == "2" && CKEDITOR.instances.ProFile2.getData() == "") {
		alert("프로필을 입력하세요.");
		CKEDITOR.instances.ProFile2.focus();
		return;
	}
	var regex = /^(.*\.(?!(jpg|jpeg|gif|png)$))?[^.]*$/i;
	if ($("#NewPhoto").get(0).files.length !== 0 && regex.test($("#NewPhoto")[0].files[0].name)) {
		alert("프로필 사진은 이미지 파일만 가능합니다.");
		return;
	}
	author_form.submit();
}
</script>

<?php
include_once("_tail.php");
?>



