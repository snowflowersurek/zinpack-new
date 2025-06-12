<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

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
		<li class="active">그림전시관리</li>
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
			그림전시관리
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
				<form class="form-horizontal" id="exhibit_form" name="exhibit_form" action="<?=$iw['admin_path']?>/publishing_exhibit_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">

					<div class="form-group">
						<label class="col-sm-1 control-label">등록일시</label>
						<div class="col-sm-8">
							<input type="text" name="reg_date" value="" maxlength="16"> 예) <?=date("Y-m-d H:i")?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그림전시명</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" name="picture_name" value="" maxlength="30">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">대여가능여부</label>
						<div class="col-sm-8">
							<select name="can_rent">
								<option value="Y">가능</option>
								<option value="M">점검</option>
								<option value="N">불가능</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">특별기획전</label>
						<div class="col-sm-8">
							<input type="text" name="special" value="" maxlength="30"> 특별기획전 그림이 아니면 공란
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">액자수(점)</label>
						<div class="col-sm-8">
							<input type="text" name="how_many" value="" maxlength="30">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">액자사이즈</label>
						<div class="col-sm-8">
							<input type="text" name="size" value="" maxlength="30"> 65x35 형태, 단위는 cm
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">도서정보</label>
						<div class="col-sm-8">
							<input type="text" name="book_id" value="" onclick="searchBook();" readonly> 
							<button type="button" onclick="searchBook();">검색</button> 해당 그림의 도서코드
						</div>
					</div>

					<div class="form-group" id="web_editor">
						<label class="col-sm-1 control-label">그림전시 소개</label>
						<div class="col-sm-8">
							<textarea class="ckeditor" id="contents" name="contents"></textarea>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/publishing_exhibit_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
function searchBook(type) {
	window.open('publishing_books_search.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>', 'SearchBook', 'width=800,height=600');
}

function selectBook(code, name){
	document.exhibit_form.book_id.value = code;
}

function check_form() {
	if (exhibit_form.picture_name.value == ""){
		alert("그림전시명 입력하세요.");
		exhibit_form.picture_name.focus();
		return;
	}
	exhibit_form.submit();
}
</script>

<?
include_once("_tail.php");
?>