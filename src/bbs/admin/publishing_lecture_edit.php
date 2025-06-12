<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$intSeq = $_GET["id"];

$sql = "select * from $iw[publishing_lecture_table] where ep_code = '$iw[store]' and intSeq = '$intSeq'";
$row = sql_fetch($sql);
if (!$row["intSeq"]) alert("잘못된 접근입니다!","");

$userName = $row["userName"];
$strConfirm = $row["strConfirm"];
$strGubun = $row["strGubun"];
$strGubunTxt = $row["strGubunTxt"];
$strOrgan = $row["strOrgan"];
$strCharge = $row["strCharge"];
$userTel = $row["userTel"];
$userEmail = $row["userEmail"];
$userAddr = $row["userAddr"];
$strTarget = $row["strTarget"];
$strTargetTxt = $row["strTargetTxt"];
$strTargetInfo = $row["strTargetInfo"];
$strNum = $row["strNum"];

$confirm_Author = $row["confirm_Author"];
$strAuthor1 = $row["strAuthor1"];
$strAuthor2 = $row["strAuthor2"];
$strAuthor3 = $row["strAuthor3"];
$strAuthorBook1 = $row["strAuthorBook1"];
$strAuthorBook2 = $row["strAuthorBook2"];
$strAuthorBook3 = $row["strAuthorBook3"];

$confirm_date = $row["confirm_date"];
$strDate1 = $row["strDate1"];
$strDate2 = $row["strDate2"];
$strDate3 = $row["strDate3"];

$strPrice = $row["strPrice"];
$strPreView = $row["strPreView"];
$strPlan = stripslashes($row["strPlan"]);
$strContent = stripslashes($row["strContent"]);
$strAdminMemo = stripslashes($row["strAdminMemo"]);
$strRegDate = substr($row["strRegDate"], 0, 10);
?>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">작가강연회 신청내역</li>
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
			작가강연회 신청내역
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
				<form class="form-horizontal" id="lecture_form" name="lecture_form" action="<?=$iw[admin_path]?>/publishing_lecture_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">

					<input type="hidden" name="intSeq" value="<?=$intSeq?>" />

					<div class="form-group">
						<label class="col-sm-1 control-label">신청현황 상태</label>
						<div class="col-sm-8">
							<select name="strConfirm">
								<option value="N" <?if($strConfirm == "N"){?>selected="selected"<?}?>>접수대기</option>
								<option value="A" <?if($strConfirm == "A"){?>selected="selected"<?}?>>접수완료</option>
								<option value="D" <?if($strConfirm == "D"){?>selected="selected"<?}?>>도서관연락</option>
								<option value="J" <?if($strConfirm == "J"){?>selected="selected"<?}?>>작가섭외</option>
								<option value="Y" <?if($strConfirm == "Y"){?>selected="selected"<?}?>>강연확정</option>
								<option value="C" <?if($strConfirm == "C"){?>selected="selected"<?}?>>강연취소</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청자</label>
						<div class="col-sm-8">
							<p class="col-sm-12 form-control-static"><?=$userName?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청일</label>
						<div class="col-sm-8">
							<p class="col-sm-12 form-control-static"><?=$strRegDate?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청기관</label>
						<div class="col-sm-8">
							<input type="radio" name="strGubun" id="strGubun1" value="일반 도서관" <?if($strGubun == "일반 도서관"){?>checked<?}?> onclick = "setGubunTxt('N');">
							<label for="strGubun1">일반 도서관</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun2" value="어린이 도서관" <?if($strGubun == "어린이 도서관"){?>checked<?}?> onclick = "setGubunTxt('N');">
							<label for="strGubun2">어린이 도서관</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun3" value="초등학교" <?if($strGubun == "초등학교"){?>checked<?}?> onclick = "setGubunTxt('N');">
							<label for="strGubun3">초등학교</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun4" value="중/고등학교" <?if($strGubun == "중/고등학교"){?>checked<?}?> onclick = "setGubunTxt('N');">
							<label for="strGubun4">중/고등학교</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun5" value="문화센터" <?if($strGubun == "문화센터"){?>checked<?}?> onclick = "setGubunTxt('N');">
							<label for="strGubun5">문화센터</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun6" value="기타" <?if($strGubun == "기타"){?>checked<?}?> onclick = "setGubunTxt('Y');">
							<label for="strGubun6">기타</label>
							<input type="text" name="strGubunTxt" id="strGubunTxt" value="<?=$strGubunTxt?>" maxlength="100" style="display:none;">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">기관명</label>
						<div class="col-sm-8">
							<input type="text" name="strOrgan" value="<?=$strOrgan?>" maxlength="100">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">담당자명</label>
						<div class="col-sm-8">
							<input type="text" name="strCharge" value="<?=$strCharge?>" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">연락처</label>
						<div class="col-sm-8">
							<input type="text" name="userTel" value="<?=$userTel?>" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이메일</label>
						<div class="col-sm-8">
							<input type="text" name="userEmail" value="<?=$userEmail?>" maxlength="100">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">주소</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" name="userAddr" value="<?=$userAddr?>" maxlength="200">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">강연회대상</label>
						<div class="col-sm-8">
							<input type="text" name="strTarget" value="<?=$strTarget?>" maxlength="50">
							<?php if (strpos($strTarget, "기타") !== false) {?>
							<input type="text" name="strTargetTxt" value="<?=$strTargetTxt?>" maxlength="50">
							<?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">대상 연령대</label>
						<div class="col-sm-8">
							<input type="text" name="strTargetInfo" value="<?=$strTargetInfo?>" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">참석 인원</label>
						<div class="col-sm-8">
							<input type="text" name="strNum" value="<?=$strNum?>" maxlength="4" onblur="checkNumber(this)"> 명
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">희망작가</label>
						<div class="col-sm-11">
							<div <?php if ($strAuthor1 == "" && $strAuthorBook1 == "") {echo "style='display:none;'";} ?>>
								<input type="radio" name="confirm_Author" id="confirm_Author1" <?if($confirm_Author == "1"){?>checked<?}?> value="1">
								<label for="confirm_Author1">1지망</label>
								<input type="text" name="strAuthor1" value="<?=$strAuthor1?>" maxlength="50" onclick="searchAuthor(1);" readonly>
								<button type="button" onclick="searchAuthor(1);">검색</button>
								<input type="text" name="strAuthorBook1" value="<?=$strAuthorBook1?>" maxlength="50" onclick="searchBook(1);" readonly>
								<button type="button" onclick="searchBook(1);">검색</button>
							</div>
							<div <?php if ($strAuthor2 == "" && $strAuthorBook2 == "") {echo "style='display:none;'";} ?>>
								<input type="radio" name="confirm_Author" id="confirm_Author2" <?if($confirm_Author == "2"){?>checked<?}?> value="2">
								<label for="confirm_Author2">2지망</label>
								<input type="text" name="strAuthor2" value="<?=$strAuthor2?>" maxlength="50" onclick="searchAuthor(2);" readonly>
								<button type="button" onclick="searchAuthor(2);">검색</button>
								<input type="text" name="strAuthorBook2" value="<?=$strAuthorBook2?>" maxlength="50" onclick="searchBook(2);" readonly>
								<button type="button" onclick="searchBook(2);">검색</button>
							</div>
							<div <?php if ($strAuthor3 == "" && $strAuthorBook3 == "") {echo "style='display:none;'";} ?>>
								<input type="radio" name="confirm_Author" id="confirm_Author3" <?if($confirm_Author == "3"){?>checked<?}?> value="3">
								<label for="confirm_Author3">3지망</label>
								<input type="text" name="strAuthor3" value="<?=$strAuthor3?>" maxlength="50" onclick="searchAuthor(3);" readonly>
								<button type="button" onclick="searchAuthor(3);">검색</button>
								<input type="text" name="strAuthorBook3" value="<?=$strAuthorBook3?>" maxlength="50" onclick="searchBook(3);" readonly>
								<button type="button" onclick="searchBook(3);">검색</button>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">희망일정</label>
						<div class="col-sm-11">
							<?php
							if ($strDate1 != "") {
								$strDateYmd1 = substr($strDate1, 0, 4)."-".substr($strDate1, 4, 2)."-".substr($strDate1, 6, 2);
								$strStartHour1 = substr($strDate1, 8, 2);
								$strStartMin1 = substr($strDate1, 10, 2);
								$strEndHour1 = substr($strDate1, 12, 2);
								$strEndMin1 = substr($strDate1, 14);
							?>
							<div>
								<input type="radio" name="confirm_date" id="confirm_date1" <?if($confirm_date == "1"){?>checked<?}?> value="1">
								<label for="confirm_date1">1지망</label>
								<input type="text" name="strDateYmd1" class="strDate" value="<?=$strDateYmd1?>" maxlength="10" readonly style="width:90px;">
								&nbsp;
								<input type="text" name="strStartHour1" value="<?=$strStartHour1?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 시
								<input type="text" name="strStartMin1" value="<?=$strStartMin1?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 분
								~ 
								<input type="text" name="strEndHour1" value="<?=$strEndHour1?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 시
								<input type="text" name="strEndMin1" value="<?=$strEndMin1?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 분
							</div>
							<?php } ?>
							<?php
							if ($strDate2 != "") {
								$strDateYmd2 = substr($strDate2, 0, 4)."-".substr($strDate2, 4, 2)."-".substr($strDate2, 6, 2);
								$strStartHour2 = substr($strDate2, 8, 2);
								$strStartMin2 = substr($strDate2, 10, 2);
								$strEndHour2 = substr($strDate2, 12, 2);
								$strEndMin2 = substr($strDate2, 14);
							?>
							<div>
								<input type="radio" name="confirm_date" id="confirm_date2" <?if($confirm_date == "2"){?>checked<?}?> value="2">
								<label for="confirm_date2">2지망</label>
								<input type="text" name="strDateYmd2" class="strDate" value="<?=$strDateYmd2?>" maxlength="10" readonly style="width:90px;">
								&nbsp;
								<input type="text" name="strStartHour2" value="<?=$strStartHour2?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 시
								<input type="text" name="strStartMin2" value="<?=$strStartMin2?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 분
								~ 
								<input type="text" name="strEndHour2" value="<?=$strEndHour2?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 시
								<input type="text" name="strEndMin2" value="<?=$strEndMin2?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 분
							</div>
							<?php } ?>
							<?php
							if ($strDate3 != "") {
								$strDateYmd3 = substr($strDate3, 0, 4)."-".substr($strDate3, 4, 2)."-".substr($strDate3, 6, 2);
								$strStartHour3 = substr($strDate3, 8, 2);
								$strStartMin3 = substr($strDate3, 10, 2);
								$strEndHour3 = substr($strDate3, 12, 2);
								$strEndMin3 = substr($strDate3, 14);
							?>
							<div>
								<input type="radio" name="confirm_date" id="confirm_date3" <?if($confirm_date == "3"){?>checked<?}?> value="3">
								<label for="confirm_date3">3지망</label>
								<input type="text" name="strDateYmd3" class="strDate" value="<?=$strDateYmd3?>" maxlength="10" readonly style="width:90px;">
								&nbsp;
								<input type="text" name="strStartHour3" value="<?=$strStartHour3?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 시
								<input type="text" name="strStartMin3" value="<?=$strStartMin3?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 분
								~ 
								<input type="text" name="strEndHour3" value="<?=$strEndHour3?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 시
								<input type="text" name="strEndMin3" value="<?=$strEndMin3?>" maxlength="2" onblur="checkNumber(this)" style="width:30px;"> 분
							</div>
							<?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">강연료(예산)</label>
						<div class="col-sm-8">
							<input type="text" name="strPrice" value="<?=$strPrice?>" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사전독서여부</label>
						<div class="col-sm-8">
							<input type="radio" name="strPreView" id="strPreView1" value="예" <?if($strPreView == "예"){?>checked<?}?>>
							<label for="strPreView1">예</label>
							&nbsp;
							<input type="radio" name="strPreView" id="strPreView2" value="아니요" <?if($strPreView == "아니요"){?>checked<?}?>>
							<label for="strPreView2">아니요</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">독후 활동 계획</label>
						<div class="col-sm-8">
							<textarea name="strPlan" class="col-sm-12" style="height:150px;"><?=$strPlan?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">남기고싶은말(기타 요청 사항)</label>
						<div class="col-sm-8">
							<textarea name="strContent" class="col-sm-12" style="height:150px;"><?=$strContent?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">관리자 메모</label>
						<div class="col-sm-8">
							<textarea name="strAdminMemo" class="col-sm-12" style="height:150px;"><?=$strAdminMemo?></textarea>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw[admin_path]?>/publishing_lecture_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
	$(".strDate").datepicker({
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

var authorType = 0;

function searchAuthor(type) {
	authorType = type;
	window.open('publishing_author_search.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>', 'SearchAuthor', 'width=800,height=600');
}

function selectAuthor(code, name){
	if(authorType == 1){
		document.lecture_form.strAuthor1.value = name;
		document.lecture_form.strAuthorBook1.value = "";
	}
	if(authorType == 2){
		document.lecture_form.strAuthor2.value = name;
		document.lecture_form.strAuthorBook2.value = "";
	}
	if(authorType == 3){
		document.lecture_form.strAuthor3.value = name;
		document.lecture_form.strAuthorBook3.value = "";
	}
}

function searchBook(type) {
	authorType = type;
	var authorName = document.lecture_form["strAuthor" + type].value;
	window.open('publishing_books_search.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&searchby=author&keyword='+authorName, 'SearchBook', 'width=800,height=600');
}

function selectBook(code, name){
	if(authorType == 1){
		document.lecture_form.strAuthorBook1.value = name;
	}
	if(authorType == 2){
		document.lecture_form.strAuthorBook2.value = name;
	}
	if(authorType == 3){
		document.lecture_form.strAuthorBook3.value = name;
	}
}

function check_form() {
	var confirm_Author = document.getElementsByName("confirm_Author");
	var confirm_Author_value = "";
	
	for (var i=0; i<confirm_Author.length; i++) {
		if (confirm_Author[i].checked) {
			confirm_Author_value = confirm_Author[i].value;
			break;
		}
	}
	
	if (lecture_form.strConfirm.value != "Y" && lecture_form.strConfirm.value != "C" && confirm_Author_value != ""){
		alert("희망작가 지망 선택 시, 신청현황 상태를 강연확정으로 선택하여주세요.");
		lecture_form.strConfirm.focus();
		return;
	}
	
	var confirm_date = document.getElementsByName("confirm_date");
	var confirm_date_value = "";
	
	for (var i=0; i<confirm_date.length; i++) {
		if (confirm_date[i].checked) {
			confirm_date_value = confirm_date[i].value;
			break;
		}
	}
	
	if (lecture_form.strConfirm.value != "Y" && lecture_form.strConfirm.value != "C" && confirm_date_value != ""){
		alert("희망일정 지망 선택 시, 신청현황을 강연확정으로 선택하여주세요.");
		lecture_form.strConfirm.focus();
		return;
	}
	
	if (lecture_form.strConfirm.value == "Y") {
		if (confirm_Author_value == "") {
			alert("강연확정 시  희망작가 지망이 선택되어야 합니다.");
			document.getElementById("confirm_Author1").focus();
			return;
		}

		if (confirm_date_value == "") {
			alert("강연확정 시  희망일정 지망이 선택되어야 합니다.");
			document.getElementById("confirm_date1").focus();
			return;
		}
	}

	if (lecture_form.strNum.value == "") {
		alert("참석 인원을 입력하세요.");
		lecture_form.strNum.focus();
		return;
	}
	
	lecture_form.submit();
}

function setGubunTxt(str) {
	if(str =="N"){
		$("#strGubunTxt").hide();
		$("#strGubunTxt").val("");
	}else{
		$("#strGubunTxt").show();
	}
}

<?php 
if ($strGubun == "기타") {
	echo "setGubunTxt('Y');";
}
?>
</script>

<?
include_once("_tail.php");
?>