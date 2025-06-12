<?php
include_once("_common.php");
include_once("_head.php");

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 후 이용 가능합니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");

$menu = $_GET["menu"];
?>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="breadcrumb-box input-group">
				<ol class="breadcrumb ">
					<li>
					<?
						$hm_code = $_GET["menu"];
						$hm_row = sql_fetch("select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and hm_code = '$hm_code'");
						echo stripslashes($hm_row[hm_name])
					?>
					</li>
				</ol>
			</div>
			<!-- content list starting -->
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<form id="lecture_form" name="lecture_form" action="<?=$iw['m_path']?>/publishing_lecture_application_ok.php?ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
						<input type="hidden" name="userName" value="<?=$row['mb_name']?>" />
						
						<label for="realname">신청기관 분류</label>
						<div class="form-group">
							<input type="radio" name="strGubun" id="strGubun1" value="일반 도서관" onclick = "setGubunTxt('N');">
							<label for="strGubun1" style="font-weight:normal;">일반 도서관</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun2" value="어린이 도서관" onclick = "setGubunTxt('N');">
							<label for="strGubun2" style="font-weight:normal;">어린이 도서관</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun3" value="초등학교" onclick = "setGubunTxt('N');">
							<label for="strGubun3" style="font-weight:normal;">초등학교</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun4" value="중/고등학교" onclick = "setGubunTxt('N');">
							<label for="strGubun4" style="font-weight:normal;">중/고등학교</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun5" value="문화센터" onclick = "setGubunTxt('N');">
							<label for="strGubun5" style="font-weight:normal;">문화센터</label>
							&nbsp;
							<input type="radio" name="strGubun" id="strGubun6" value="기타" onclick = "setGubunTxt('Y');">
							<label for="strGubun6" style="font-weight:normal;">기타</label>
							<input type="text" name="strGubunTxt" id="strGubunTxt" value="" maxlength="100" style="display:none;">
						</div>
						
						<label for="nick">기관명</label>
						<div class="form-group">
							<input type="text" class="form-control" name="strOrgan" value="" maxlength="100" />
						</div>
						
						<label for="nick">담당자명</label>
						<div class="form-group">
							<input type="text" class="form-control" name="strCharge" value="" maxlength="50" />
						</div>
						
						<label for="nick">연락처</label>
						<div class="form-group">
							<input type="text" class="form-control" name="userTel" value="" maxlength="50" />
						</div>
						
						<label for="nick">이메일</label>
						<div class="form-group">
							<input type="text" class="form-control" name="userEmail" value="" maxlength="50" />
						</div>
						
						<label>주소</label>
						<div class="row form-group">
							<div class="col-sm-2">
								<input type="text" class="form-control" name="zipcode" id="zipcode" maxlength="7" placeholder="우편번호" readonly value="" onclick="fnFindAd();" />
							</div>
							<div class="col-sm-2">
								<button type="button" class="btn btn-theme" onclick="fnFindAd();">우편번호 검색</button>
							</div>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="userAddr1" id="userAddr1" placeholder="주소" readonly value="" maxlength="100" onclick="fnFindAd();" />
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="userAddr2" id="userAddr2" placeholder="상세주소" value="" maxlength="100" />
						</div>
						
						<label for="realname">강연회 대상(중복 체크 가능)</label>
						<div class="form-group">
							<input type="checkbox" name="strTarget" id="strTarget1" value="일반인">
							<label for="strTarget1" style="font-weight:normal;">일반인</label>
							&nbsp;
							<input type="checkbox" name="strTarget" id="strTarget2" value="교사">
							<label for="strTarget2" style="font-weight:normal;">교사</label>
							&nbsp;
							<input type="checkbox" name="strTarget" id="strTarget3" value="학부모">
							<label for="strTarget3" style="font-weight:normal;">학부모</label>
							&nbsp;
							<input type="checkbox" name="strTarget" id="strTarget4" value="고등학생">
							<label for="strTarget4" style="font-weight:normal;">고등학생</label>
							&nbsp;
							<input type="checkbox" name="strTarget" id="strTarget5" value="중학생">
							<label for="strTarget5" style="font-weight:normal;">중학생</label>
							&nbsp;
							<input type="checkbox" name="strTarget" id="strTarget6" value="초등학생">
							<label for="strTarget6" style="font-weight:normal;">초등학생</label>
							&nbsp;
							<input type="checkbox" name="strTarget" id="strTarget7" value="유아">
							<label for="strTarget7" style="font-weight:normal;">유아</label>
							&nbsp;
							<input type="checkbox" name="strTarget" id="strTarget8" value="기타" onclick = "setTargetTxt(this);">
							<label for="strTarget8" style="font-weight:normal;">기타</label>
							<input type="text" name="strTargetTxt" id="strTargetTxt" value="" maxlength="100" style="display:none;">
						</div>
						
						<label for="nick">대상 연령대</label>
						<div class="form-group">
							<input type="text" class="form-control" name="strTargetInfo" value="" placeholder="연령,학년 등" maxlength="50" />
						</div>
						
						<label for="nick">희망 작가</label>
						<div class="form-group" style="margin:0;">
							<div class="row">
								<div class="col-sm-12">
									<span style="display:inline-block;">1지망 - </span>
									<input type="text" class="form-control" name="strAuthor1" value="" placeholder="작가명" maxlength="50" onclick="searchAuthor(1);" readonly style="display:inline-block; width:20%;">
									<button type="button" class="btn btn-theme" onclick="searchAuthor(1);">검색</button>
									<input type="text" class="form-control" name="strAuthorBook1" value="" placeholder="도서명" maxlength="50" onclick="searchBook(1);" readonly style="display:inline-block; width:30%;">
									<button type="button" class="btn btn-theme" onclick="searchBook(1);">검색</button>
								</div>
							</div>
						</div>
						<div class="form-group" style="margin:0;">
							<div class="row">
								<div class="col-sm-12">
									<span style="display:inline-block;">2지망 - </span>
									<input type="text" class="form-control" name="strAuthor2" value="" placeholder="작가명" maxlength="50" onclick="searchAuthor(2);" readonly style="display:inline-block; width:20%;">
									<button type="button" class="btn btn-theme" onclick="searchAuthor(2);">검색</button>
									<input type="text" class="form-control" name="strAuthorBook2" value="" placeholder="도서명" maxlength="50" onclick="searchBook(2);" readonly style="display:inline-block; width:30%;">
									<button type="button" class="btn btn-theme" onclick="searchBook(2);">검색</button>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<span style="display:inline-block;">3지망 - </span>
									<input type="text" class="form-control" name="strAuthor3" value="" placeholder="작가명" maxlength="50" onclick="searchAuthor(3);" readonly style="display:inline-block; width:20%;">
									<button type="button" class="btn btn-theme" onclick="searchAuthor(3);">검색</button>
									<input type="text" class="form-control" name="strAuthorBook3" value="" placeholder="도서명" maxlength="50" onclick="searchBook(3);" readonly style="display:inline-block; width:30%;">
									<button type="button" class="btn btn-theme" onclick="searchBook(3);">검색</button>
								</div>
							</div>
						</div>
						
						<label for="nick">희망 일정</label>
						<div class="form-group" style="margin:0;">
							<div class="row">
								<div class="col-sm-12">
									<span style="display:inline-block;">1지망 - </span>
									<input type="text" name="strDateYmd1" class="form-control strDate" value="" placeholder="일자" maxlength="10" readonly style="display:inline-block; width:15%;">
									<select name="strStartHour1" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=9; $i<22; $i++) {
											echo "<option value='".sprintf("%02d", $i)."'>".sprintf("%02d", $i)."</option>";
										}
										?>
									</select> 시
									<select name="strStartMin1" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=0; $i<6; $i++) {
											echo "<option value='".sprintf("%02d", $i*10)."'>".sprintf("%02d", $i*10)."</option>";
										}
										?>
									</select> 분
									<span style="display:inline-block; text-align:center; width:20px;"> ~ </span>
									<select name="strEndHour1" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=9; $i<22; $i++) {
											echo "<option value='".sprintf("%02d", $i)."'>".sprintf("%02d", $i)."</option>";
										}
										?>
									</select> 시
									<select name="strEndMin1" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=0; $i<6; $i++) {
											echo "<option value='".sprintf("%02d", $i*10)."'>".sprintf("%02d", $i*10)."</option>";
										}
										?>
									</select> 분
								</div>
							</div>
						</div>
						<div class="form-group" style="margin:0;">
							<div class="row">
								<div class="col-sm-12">
									<span style="display:inline-block;">2지망 - </span>
									<input type="text" name="strDateYmd2" class="form-control strDate" value="" placeholder="일자" maxlength="10" readonly style="display:inline-block; width:15%;">
									<select name="strStartHour2" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=9; $i<22; $i++) {
											echo "<option value='".sprintf("%02d", $i)."'>".sprintf("%02d", $i)."</option>";
										}
										?>
									</select> 시
									<select name="strStartMin2" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=0; $i<6; $i++) {
											echo "<option value='".sprintf("%02d", $i*10)."'>".sprintf("%02d", $i*10)."</option>";
										}
										?>
									</select> 분
									<span style="display:inline-block; text-align:center; width:20px;"> ~ </span>
									<select name="strEndHour2" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=9; $i<22; $i++) {
											echo "<option value='".sprintf("%02d", $i)."'>".sprintf("%02d", $i)."</option>";
										}
										?>
									</select> 시
									<select name="strEndMin2" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=0; $i<6; $i++) {
											echo "<option value='".sprintf("%02d", $i*10)."'>".sprintf("%02d", $i*10)."</option>";
										}
										?>
									</select> 분
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12">
									<span style="display:inline-block;">3지망 - </span>
									<input type="text" name="strDateYmd3" class="form-control strDate" value="" placeholder="일자" maxlength="10" readonly style="display:inline-block; width:15%;">
									<select name="strStartHour3" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=9; $i<22; $i++) {
											echo "<option value='".sprintf("%02d", $i)."'>".sprintf("%02d", $i)."</option>";
										}
										?>
									</select> 시
									<select name="strStartMin3" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=0; $i<6; $i++) {
											echo "<option value='".sprintf("%02d", $i*10)."'>".sprintf("%02d", $i*10)."</option>";
										}
										?>
									</select> 분
									<span style="display:inline-block; text-align:center; width:20px;"> ~ </span>
									<select name="strEndHour3" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=9; $i<22; $i++) {
											echo "<option value='".sprintf("%02d", $i)."'>".sprintf("%02d", $i)."</option>";
										}
										?>
									</select> 시
									<select name="strEndMin3" class="form-control" style="display:inline-block; width:60px; padding:6px;">
										<option value=""></option>
										<?php
										for ($i=0; $i<6; $i++) {
											echo "<option value='".sprintf("%02d", $i*10)."'>".sprintf("%02d", $i*10)."</option>";
										}
										?>
									</select> 분
								</div>
							</div>
						</div>
						
						<label for="nick">참석 인원</label>
						<div class="form-group">
							<input type="text" class="form-control" name="strNum" value="" placeholder="" maxlength="4" onblur="checkNumber(this)" style="display:inline-block; width:200px;" /> 명
						</div>
						
						<label for="nick">강연료(예산)</label>
						<div class="form-group">
							<input type="text" class="form-control" name="strPrice" value="" placeholder="" maxlength="10" style="display:inline-block; width:200px;" /> 원
						</div>
						
						<label for="realname">사전 독서 여부</label>
						<div class="form-group">
							<input type="radio" name="strPreView" id="strPreView1" value="예">
							<label for="strPreView1" style="font-weight:normal;">예</label>
							&nbsp;
							<input type="radio" name="strPreView" id="strPreView2" value="아니요">
							<label for="strPreView2" style="font-weight:normal;">아니요</label>
						</div>
						
						<label for="nick">독후 활동 계획</label>
						<div class="form-group">
							<textarea class="form-control" name="strPlan" style="height:150px;"></textarea>
						</div>
						
						<label for="nick">남기고 싶은 말(기타 요청 사항)</label>
						<div class="form-group">
							<textarea class="form-control" name="strContent" style="height:150px;"></textarea>
						</div>
						
						<br />
						
						<button type="button" class="btn btn-theme" onclick="javascript:check_form();">신청하기</button>
					</form>
				</div>
			</div>
		</div> <!-- /.col -->
	</div> <!-- /.row -->
</div> <!-- /.content -->

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
	window.open('/bbs/admin/publishing_author_search.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>', 'SearchAuthor', 'width=800,height=600');
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
	window.open('/bbs/admin/publishing_books_search.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&searchby=author&keyword='+authorName, 'SearchBook', 'width=800,height=600');
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

function setGubunTxt(str) {
	if(str =="N"){
		$("#strGubunTxt").hide();
		$("#strGubunTxt").val("");
	}else{
		$("#strGubunTxt").show();
	}
}

function setTargetTxt(element) {
	if($(element).is(":checked") == true){
		$("#strTargetTxt").show();
	}else{
		$("#strTargetTxt").hide();
		$("#strTargetTxt").val("");
	}
}

function check_form() {
	if ($("input[name=strGubun]:checked").size() == 0) {
		alert("신청기관을 선택하세요.");
		lecture_form.strGubun[0].focus();
		return;
	}
	
	if (lecture_form.strGubun[5].checked == true && lecture_form.strGubunTxt.value == ""){
		alert("신청기관 분류 기타를 입력하세요.");
		lecture_form.strGubunTxt.focus();
		return;
	}
	
	if ((lecture_form.strOrgan.value).replace(/\s/g, "").length == 0){
		alert("기관명을 입력하세요.");
		lecture_form.strOrgan.value = "";
		lecture_form.strOrgan.focus();
		return;
	}
	
	if ((lecture_form.strCharge.value).replace(/\s/g, "").length == 0){
		alert("담당자명을 입력하세요.");
		lecture_form.strCharge.value = "";
		lecture_form.strCharge.focus();
		return;
	}
	
	if ((lecture_form.userTel.value).replace(/\s/g, "").length == 0){
		alert("연락처를 입력하세요.");
		lecture_form.userTel.value = "";
		lecture_form.userTel.focus();
		return;
	}
	
	if ((lecture_form.userEmail.value).replace(/\s/g, "").length == 0){
		alert("이메일주소를 입력하세요.");
		lecture_form.userEmail.value = "";
		lecture_form.userEmail.focus();
		return;
	}
	
	if (lecture_form.userAddr1.value == ""){
		alert("주소를 입력하세요.");
		lecture_form.userAddr1.focus();
		return;
	}
	
	if ((lecture_form.userAddr2.value).replace(/\s/g, "").length == 0){
		alert("상세주소를 입력하세요.");
		lecture_form.userAddr2.value = "";
		lecture_form.userAddr2.focus();
		return;
	}
	
	if ($("input[name=strTarget]:checked").size() == 0) {
		alert("강연회 대상을 선택하세요.");
		lecture_form.strTarget[0].focus();
		return;
	}
	
	if (lecture_form.strTarget[7].checked == true && lecture_form.strTargetTxt.value == ""){
		alert("강연회 대상 기타를 입력하세요.");
		lecture_form.strTargetTxt.focus();
		return;
	}
	
	if ((lecture_form.strTargetInfo.value).replace(/\s/g, "").length == 0){
		alert("대상 연령대를 입력하세요.");
		lecture_form.strTargetInfo.value = "";
		lecture_form.strTargetInfo.focus();
		return;
	}
	
	if ((lecture_form.strNum.value).replace(/\s/g, "").length == 0){
		alert("참석 인원을 입력하세요.");
		lecture_form.strNum.value = "";
		lecture_form.strNum.focus();
		return;
	}
	
	if (lecture_form.strAuthor1.value == ""){
		alert("희망작가 1지망을 입력하세요.");
		lecture_form.strAuthor1.focus();
		return;
	}
	
	if (lecture_form.strAuthorBook1.value == ""){
		alert("희망작가 1지망 도서를 입력하세요.");
		lecture_form.strAuthorBook1.focus();
		return;
	}
	
	if (lecture_form.strDateYmd1.value == ""){
		alert("희망일정 1지망 날짜를 선택하세요.");
		lecture_form.strDateYmd1.focus();
		return;
	}
	
	if (lecture_form.strStartHour1.selectedIndex == 0 || lecture_form.strStartMin1.selectedIndex == 0 
			|| lecture_form.strEndHour1.selectedIndex == 0 || lecture_form.strEndMin1.selectedIndex == 0){
		alert("희망일정 1지망 시간을 선택하세요.");
		lecture_form.strStartHour1.focus();
		return;
	}
	
	if ((lecture_form.strPrice.value).replace(/\s/g, "").length == 0){
		alert("강연료을 입력하세요.");
		lecture_form.strPrice.value = "";
		lecture_form.strPrice.focus();
		return;
	}
	
	if ($("input[name=strPreView]:checked").size() == 0) {
		alert("사전독서 여부를 선택하세요.");
		lecture_form.strPreView[0].focus();
		return;
	}
	
	if ((lecture_form.strPlan.value).replace(/\s/g, "").length == 0){
		alert("독후 활동 계획을 입력하세요.");
		lecture_form.strPlan.value = "";
		lecture_form.strPlan.focus();
		return;
	}
	
	lecture_form.submit();
}
</script>

<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
	function fnFindAd() {
		new daum.Postcode({
			oncomplete: function(data) {
				// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
				var fullAddr = ''; // 최종 주소 변수
				var extraAddr = ''; // 조합형 주소 변수
				// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
				if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
					fullAddr = data.roadAddress;
				} else { // 사용자가 지번 주소를 선택했을 경우(J)
					fullAddr = data.jibunAddress;
				}
				// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
				if(data.userSelectedType === 'R'){
					//법정동명이 있을 경우 추가한다.
					if(data.bname !== ''){
						extraAddr += data.bname;
					}
					// 건물명이 있을 경우 추가한다.
					if(data.buildingName !== ''){
						extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
					fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
				}

				// 우편번호와 주소 정보를 해당 필드에 넣는다.
				document.getElementById("zipcode").value = data.zonecode;
				document.getElementById("userAddr1").value = fullAddr;

				// 커서를 상세주소 필드로 이동한다.
				document.getElementById("userAddr2").focus();
			}
		}).open();
	}
</script>

<?
include_once("_tail.php");
?>