<?php
include_once("_common.php");
if ($iw[type] != "publishing" || ($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

$iw[CKeditor] = "on";

$row = sql_fetch("select ep_nick,ep_upload,ep_upload_size from $iw[enterprise_table] where ep_code = '$iw[store]'");
$ep_upload = $row[ep_upload];
$ep_upload_size = $row[ep_upload_size];
$upload_path = "/$iw[type]/$row[ep_nick]/book";
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
		<li class="active">도서관리</li>
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
			도서관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				도서정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="books_form" name="books_form" action="<?=$iw['admin_path']?>/publishing_books_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">

					<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
					<input type="hidden" name="ep_upload_size" value="<?=$ep_upload_size?>" />

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">도서상태</label>
						<div class="col-sm-8">
							<select name="stock_status">
								<option value="">선택</option>
								<option value="0">정상</option>
								<option value="1">품절</option>
								<option value="2">절판</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">도서명</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" placeholder="" name="BookName" value="" maxlength="100">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">부제</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" placeholder="" name="sub_title" value="" maxlength="100">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">원서명</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" placeholder="" name="original_title" value="" maxlength="100">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">기본분류</label>
						<div class="col-sm-8">
							<select name="cg_code">
								<option value="">선택</option>
								<?php
								$category_result = sql_query("select cg_code, cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'publishing' order by cg_name asc");

								while($row = @sql_fetch_array($category_result)){
									echo "<option value='$row[cg_code]'>$row[cg_name]</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">추가분류</label>
						<div class="col-sm-8">
							<select name="sub_cg_code" onchange="checkSubCategory(this.value);">
								<option value="">선택</option>
								<?php
								$category_result = sql_query("select cg_code, cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'publishing' order by cg_name asc");

								while($row = @sql_fetch_array($category_result)){
									echo "<option value='$row[cg_code]'>$row[cg_name]</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">브랜드(출판사)</label>
						<div class="col-sm-8">
							<select name="brand_cg_code">
								<option value="">선택</option>
								<?php
								$category_result = sql_query("select cg_code, cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'publishing_brand' order by cg_name asc");

								while($row = @sql_fetch_array($category_result)){
									echo "<option value='$row[cg_code]'>$row[cg_name]</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">지은이</label>
						<div class="col-sm-8">
							<div class="tags-input author" data="1">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">옮긴이</label>
						<div class="col-sm-8">
							<div class="tags-input translate" data="2">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">그린이</label>
						<div class="col-sm-8">
							<div class="tags-input painter" data="3">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">엮은이</label>
						<div class="col-sm-8">
							<div class="tags-input editor" data="4">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">펴낸날</label>
						<div class="col-sm-8">
							<input type="text" name="PubDate" id="PubDate" value="" maxlength="10" readonly>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">가격</label>
						<div class="col-sm-8">
							<input type="text" name="Price" value="" maxlength="10" onblur="checkNumber(this)"> 원
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">책꼴</label>
						<div class="col-sm-8">
							<input type="text" name="BookSize" value="" maxlength="30"> ex) A4:210×297mm, A5신(154×224mm)
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">쪽수</label>
						<div class="col-sm-8">
							<input type="text" name="pages" value="" maxlength="4" onblur="checkNumber(this)">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">개별ISBN</label>
						<div class="col-sm-8">
							<input type="text" name="Isbn" id="Isbn" value="" maxlength="50" style="min-width:230px;">
							<button type="button" class="btn btn-sm btn-default" onclick="checkIsbn();" name="IsbnCheck" id="IsbnCheck" value="" >중복체크</button>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">세트ISBN</label>
						<div class="col-sm-8">
							<input type="text" name="SIsbn" value="" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">도서십진</label>
						<div class="col-sm-8">
							<select name="bookGubun1" id="bookGubun1">
								<option value="">선택</option>
								<?php
								$ddc_large_result = sql_query("select strLargeName, strLargeCode from $iw[publishing_books_ddc_table] group by strLargeName, strLargeCode order by strLargeCode asc");

								while($row = @sql_fetch_array($ddc_large_result)){
									echo "<option value='$row[strLargeCode]'>$row[strLargeName]</option>";
								}
								?>
							</select>
							<select name="bookGubun2" id="bookGubun2">
								<option value="">선택</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">추천기관</label>
						<div class="col-sm-8">
							<textarea name="organ" class="col-xs-12" style="height:100px;"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">태그(키워드)</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" placeholder="" name="Tag" value="" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">도서구매 사이트</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 mb-3" placeholder="교보문고 URL" name="kyobo_shop_link" value="" maxlength="200">
							<input type="text" class="col-xs-12 mb-3" placeholder="예스24 URL" name="yes24_shop_link" value="" maxlength="200">
							<input type="text" class="col-xs-12 mb-3" placeholder="알라딘 URL" name="aladin_shop_link" value="" maxlength="200">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">책정보 및 내용요약</label>
						<div class="col-sm-8">
							<textarea name="Intro" class="col-xs-12" style="height:200px;"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">목차</label>
						<div class="col-sm-8">
							<textarea name="BookList" class="col-xs-12" style="height:200px;"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">편집자 추천글</label>
						<div class="col-sm-8">
							<textarea class="ckeditor" id="PubReview" name="PubReview"></textarea>
						</div>
					</div>

					<!--
					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">널리 읽힌 책</label>
						<div class="col-sm-8">
							<input type="checkbox" name="readmore" value="1"> 널리 읽힌 책인 경우 체크하세요
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">추천도서</label>
						<div class="col-sm-8">
							<input type="checkbox" name="themabook" value="1"> 이달의 테마 책인 경우 체크하세요
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">포인트상품도서</label>
						<div class="col-sm-8">
							<input type="checkbox" name="strPoint" value="1"> 포인트상품도서일 경우 체크하세요
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">재고여부</label>
						<div class="col-sm-8">
							<input type="checkbox" name="soldout" value="2"> 재고가 없으면 체크하세요
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">사계절문학상</label>
						<div class="col-sm-8">
							<input type="checkbox" name="award" value="1"> 사계절문학상에 해당되는 도서는  체크하세요
						</div>
					</div>
					-->
					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">책표지</label>
						<div class="col-sm-8">
							<input type="file" name="NewBookImage" id="NewBookImage">
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">전자책</label>
						<div class="col-sm-8">
							<input type="radio" name="ebook_yn" id="ebook_y" value="1">
							<label for="ebook_y"> 있음</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" name="ebook_yn" id="ebook_n" value="0" checked>
							<label for="ebook_n"> 없음</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">오디오북</label>
						<div class="col-sm-8">
							<input type="radio" name="audiobook_yn" id="audiobook_y" value="1">
							<label for="audiobook_y"> 있음</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" name="audiobook_yn" id="audiobook_n" value="0" checked>
							<label for="audiobook_n"> 없음</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-1 col-sm-2 control-label">등록상태</label>
						<div class="col-sm-8">
							<input type="radio" name="book_display" id="display1" value="1" checked>
							<label for="display1"> 노출</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" name="book_display" id="display2" value="2">
							<label for="display2"> 숨김</label>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/publishing_books_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
	$("#PubDate").datepicker({
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

$(document).ready(function() {
	$("#bookGubun1").change(function(){
		$.ajax({
			type: "GET", 
			url: "<?=$iw['admin_path']?>/ajax/publishing_books_ddc_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&code=" + $(this).val(), 
			dataType: "json", 
			success: function(responseText){
				$("#bookGubun2 option").remove();
				$("#bookGubun2").append($("<option value=''>선택</option>"));
				
				for(var i = 0; i < responseText.length; i++){
					$("#bookGubun2").append($("<option>" + responseText[i].strSmallName + "</option>").attr({value: responseText[i].strSmallCode}));
				}
			},
			error: function(response){
				alert('error\n\n' + response.responseText);
				return false;
			}
		});
	});

	/* 저자 추가 */
	$(document).on("click", ".tags-input", function(e){
		if ($(this).hasClass("author") && $(".tags-input.author span").size() == 5) {
			alert("지은이는 5명까지 등록할 수 있습니다");
		} else {
			searchAuthor($(this).attr("data"));
		}
	});

	/* 저자 삭제 */
	$(document).on("click", ".tags-input .label", function(e){
		$(this).remove();
		e.stopImmediatePropagation();
	});
});

var authorType = 0;

function searchAuthor(type) {
	authorType = type;
	window.open('publishing_author_search.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>', 'SearchAuthor', 'width=800,height=600');
}

function selectAuthor(code, name){
	var html = "";
	
	if (authorType == 1) {
		if ($(".tags-input.author > ." + code).size() == 0) {
			html += "<span class='label label-default " + code + "'>";
			html += "	<input type='hidden' name='authorID[]' value='" + code + "' />" + name + " <i class='fa fa-times'></i>";
			html += "</span>";
			$(".tags-input.author").append(html);
		}
	}
	
	if (authorType == 2) {
		html += "<span class='label label-default'>";
		html += "	<input type='hidden' name='translateID' value='" + code + "' />" + name + " <i class='fa fa-times'></i>";
		html += "</span>";
		$(".tags-input.translate").html(html);
	}
	
	if (authorType == 3) {
		html += "<span class='label label-default'>";
		html += "	<input type='hidden' name='painterID' value='" + code + "' />" + name + " <i class='fa fa-times'></i>";
		html += "</span>";
		$(".tags-input.painter").html(html);
	}
	
	if (authorType == 4) {
		html += "<span class='label label-default'>";
		html += "	<input type='hidden' name='editorID' value='" + code + "' />" + name + " <i class='fa fa-times'></i>";
		html += "</span>";
		$(".tags-input.editor").html(html);
	}
}

function checkSubCategory(code) {
	if (document.books_form.cg_code.value == code) {
		document.books_form.sub_cg_code.selectedIndex = 0;
		alert("기본분류 외 다른 항목을 선택해주세요.");
	}
}

function checkIsbn() {
	if($("#Isbn").val() ==""){
		alert("개별 ISBN을 입력하세요");
		$("#Isbn").focus();
		return false;
	}
	
	$.ajax({
		type: "GET", 
		url: "<?=$iw['admin_path']?>/ajax/publishing_books_isbn_check.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&BookID=<?=$BookID?>&Isbn=" + $("#Isbn").val(), 
		success: function(responseText){
			if(responseText =="OK"){
				alert("등록 가능한 ISBN입니다.");
				$("#IsbnCheck").val("Y");	
			}else{
				alert("이미 등록된 ISBN입니다.");	
				$("#Isbn").val("");
			}
		},
		error: function(response){
			alert('error\n\n' + response.responseText);
			return false;
		}
	});
	return false;
}

function checkNumber(element){
	var inText = element.value;
	var ret;

	for (var i = 0; i < inText.length; i++) {
    	ret = inText.charCodeAt(i);
		if (!((ret > 47) && (ret < 58))){
			alert("숫자만 입력이 가능합니다.");
			element.value = "";
			element.focus();
			return false;
		}
	}
	return true;
}

function check_form() {
	if (books_form.BookName.value == ""){
		alert("도서명을 입력하세요.");
		books_form.BookName.focus();
		return;
	}
	if (books_form.cg_code.selectedIndex == 0){
		alert("기본분류를 선택하세요.");
		books_form.cg_code.focus();
		return;
	}
	if (books_form.PubDate.value == ""){
		alert("펴낸날을 입력하세요.");
		books_form.PubDate.focus();
		return;
	}
	if (books_form.Price.value == ""){
		alert("가격을 입력하세요.");
		books_form.Price.focus();
		return;
	}
	if (books_form.pages.value == ""){
		alert("쪽수를 입력하세요.");
		books_form.pages.focus();
		return;
	}
	if (books_form.Isbn.value == ""){
		alert("개별 ISBN을 입력하세요.");
		books_form.Isbn.focus();
		return;
	}
	if (books_form.IsbnCheck.value != "Y"){
		alert("개별 ISBN 중복체크를 해주세요.");
		books_form.Isbn.focus();
		return;
	}
	if (books_form.kyobo_shop_link.value != "" && books_form.kyobo_shop_link.value.startsWith('https://product.kyobobook.co.kr') == false){
		alert("교보문고 도서구매 사이트 URL이 아닙니다.");
		books_form.kyobo_shop_link.focus();
		return;
	}
	if (books_form.yes24_shop_link.value != "" && books_form.yes24_shop_link.value.startsWith('https://www.yes24.com') == false){
		alert("예스24 도서구매 사이트 URL이 아닙니다.");
		books_form.yes24_shop_link.focus();
		return;
	}
	if (books_form.aladin_shop_link.value != "" && books_form.aladin_shop_link.value.startsWith('https://www.aladin.co.kr') == false){
		alert("알라딘 도서구매 사이트 URL이 아닙니다.");
		books_form.aladin_shop_link.focus();
		return;
	}
	var regex = /^(.*\.(?!(jpg|jpeg|gif|png)$))?[^.]*$/i;
	if ($("#NewBookImage").get(0).files.length !== 0 && regex.test($("#NewBookImage")[0].files[0].name)) {
		alert("책표지는 이미지 파일만 가능합니다.");
		return;
	}
	books_form.submit();
}
</script>

<?
include_once("_tail.php");
?>