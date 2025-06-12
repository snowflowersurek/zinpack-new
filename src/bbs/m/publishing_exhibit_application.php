<?php
include_once("_common.php");
include_once("_head.php");

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 후 이용 가능합니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");

$mb_tel = explode("-", $row["mb_tel"]);

$picture_idx = $_GET["idx"];
$picture_name = $_GET["name"];
$select_month = $_GET["month"];

if ($picture_idx != "") {
	$month_array = array();

	$current_year = date("Y");
	$current_month = date("n");
	
	for ($i=$current_month; $i<=12; $i++) {
		$date_value = $current_year."-".$i;
		$date_text = $current_year."년 ".sprintf("%02d", $i)."월";
		
		$check_row = sql_fetch("select idx from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' and year = $current_year and month = $i");
		$check_idx = $check_row["idx"];
		
		if (!$check_idx) {
			$row_array['date_value'] = $date_value;
			$row_array['date_text'] = $date_text;
			array_push($month_array, $row_array);
		}
	}
}

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
				<div class="alert-danger text-center" style="padding:16px 20px;">
					그림전시는 신청자분이 다음전시기관으로 직접 택배발송 하시는 순회전시입니다.<br/>
					신청자분 변경시 새 담당자분께 아이디와 비번을 공유하셔서 원활한 순회전시가 이뤄지도록 부탁드립니다.
				</div>
				
				<div class="box br-theme">
					<form id="exhibit_form" name="exhibit_form" action="<?=$iw['m_path']?>/publishing_exhibit_application_ok.php?ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
						<input type="hidden" name="userName" value="<?=$row['mb_name']?>" />
						
						<label for="realname">신청기관 분류</label>
						<div class="form-group">
							<input type="radio" name="strgubun" id="strgubun1" value="일반 도서관" onclick = "setGubunTxt('N');">
							<label for="strgubun1" style="font-weight:normal;">일반 도서관</label>
							&nbsp;
							<input type="radio" name="strgubun" id="strgubun2" value="어린이 도서관" onclick = "setGubunTxt('N');">
							<label for="strgubun2" style="font-weight:normal;">어린이 도서관</label>
							&nbsp;
							<input type="radio" name="strgubun" id="strgubun3" value="초등학교" onclick = "setGubunTxt('N');">
							<label for="strgubun3" style="font-weight:normal;">초등학교</label>
							&nbsp;
							<input type="radio" name="strgubun" id="strgubun4" value="중/고등학교" onclick = "setGubunTxt('N');">
							<label for="strgubun4" style="font-weight:normal;">중/고등학교</label>
							&nbsp;
							<input type="radio" name="strgubun" id="strgubun5" value="기타" onclick = "setGubunTxt('Y');">
							<label for="strgubun5" style="font-weight:normal;">기타</label>
							<input type="text" name="strgubunTxt" id="strgubunTxt" value="" maxlength="100" style="display:none;">
						</div>
						
						<label for="nick">기관명</label>
						<div class="form-group">
							<input type="text" class="form-control" name="strOrgan" value="" maxlength="100" />
						</div>
						
						<label for="nick">일반전화</label>
						<div class="row form-group">
							<div class="col-sm-2">
								<input type="text" class="form-control" name="userTel1" maxlength="4" onblur="checkNumber(this)" value="" />
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="userTel2" maxlength="4" onblur="checkNumber(this)" value="" />
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="userTel3" maxlength="4" onblur="checkNumber(this)" value="" />
							</div>
						</div>
						
						<label for="nick">휴대전화</label>
						<div class="row form-group">
							<div class="col-sm-2">
								<select name="userPhone1" class="form-control">
									<option value="010" <?if($mb_tel[0] == "010"){?>selected<?}?>>010</option>
									<option value="011" <?if($mb_tel[0] == "011"){?>selected<?}?>>011</option>
									<option value="016" <?if($mb_tel[0] == "016"){?>selected<?}?>>016</option>
									<option value="017" <?if($mb_tel[0] == "017"){?>selected<?}?>>017</option>
									<option value="018" <?if($mb_tel[0] == "018"){?>selected<?}?>>018</option>
									<option value="019" <?if($mb_tel[0] == "019"){?>selected<?}?>>019</option>
								</select>
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="userPhone2" maxlength="4" onblur="checkNumber(this)" value="<?=$mb_tel[1]?>" />
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="userPhone3" maxlength="4" onblur="checkNumber(this)" value="<?=$mb_tel[2]?>" />
							</div>
						</div>
						
						<label for="nick">이메일</label>
						<div class="form-group">
							<input type="text" class="form-control" name="userEmail" value="<?=$row['mb_mail']?>" maxlength="50" />
						</div>
						
						<label>주소</label>
						<div class="row form-group">
							<div class="col-sm-2">
								<input type="text" class="form-control" name="zipcode" id="zipcode" maxlength="7" placeholder="우편번호" readonly value="<?=$row["mb_zip_code"]?>" onclick="fnFindAd();" />
							</div>
							<div class="col-sm-2">
								<button type="button" class="btn btn-theme" onclick="fnFindAd();">우편번호 검색</button>
							</div>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="addr1" id="addr1" placeholder="주소" readonly value="<?=$row['mb_address']?>" maxlength="100" onclick="fnFindAd();" />
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="addr2" id="addr2" placeholder="상세주소" value="<?=$row['mb_address_sub']?>" maxlength="100" />
						</div>
						
						<label for="nick">홈페이지</label>
						<div class="form-group">
							<input type="text" class="form-control" name="homepage" value="" placeholder="http://" maxlength="50" />
						</div>
						
						<label for="nick">신청 그림</label>
						<div class="row form-group">
							<div class="col-sm-4">
								<input type="hidden" name="picture_idx" id="picture_idx" value="<?=$picture_idx?>" />
								<input type="text" name="picture_name" id="picture_name" class="form-control" value="<?=$picture_name?>" maxlength="50" onclick="searchExhibit();" readonly>
							</div>
							<div class="col-sm-2">
								<button type="button" class="btn btn-theme" onclick="searchExhibit();">검색</button>
							</div>
						</div>
						
						<label for="nick">전시일정</label>
						<div class="row form-group">
							<div class="col-sm-2">
								<select name="exhibitDate" id="exhibitDate" class="form-control">
									<option>선택</option>
									<?php 
									if ($month_array > 0) {
										for ($i = 0; $i < count($month_array); $i++) {
											$value = $month_array[$i];
											
											if ($value[date_value] == ($current_year."-".$select_month)) {
												echo "<option value='$value[date_value]' selected>$value[date_text]</option>";
											} else {
												echo "<option value='$value[date_value]'>$value[date_text]</option>";
											}
										}
									}
									?>
								</select>
							</div>
							<div class="col-sm-10">
								<span style="line-height:34px;">항목에 표시되지 않는 월은 다른 기관에서 먼저 신청한 일정입니다.</span>
							</div>
						</div>
						
						<label for="nick">남기고 싶은 말(기타 요청 사항)</label>
						<div class="form-group">
							<textarea class="form-control" name="else_txt" style="height:150px;"></textarea>
						</div>
						
						<br />
						
						<button type="button" class="btn btn-theme" onclick="javascript:check_form();">신청하기</button>
					</form>
				</div>
			</div>
		</div> <!-- /.col -->
	</div> <!-- /.row -->
</div> <!-- /.content -->

<script type="text/javascript">
$(document).ready(function() {
	$("#picture_idx").change(function(){
		$.ajax({
			type: "GET", 
			url: "publishing_exhibit_month_list.php?ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=" + $(this).val(), 
			dataType: "json", 
			success: function(responseText){
				$("#exhibitDate option").remove();
				$("#exhibitDate").append($("<option>선택</option>"));
				
				for(var i = 0; i < responseText.length; i++){
					$("#exhibitDate").append($("<option>" + responseText[i].date_text + "</option>").attr({value: responseText[i].date_value}));
				}
			},
			error: function(response){
				$("#exhibitDate option").remove();
				alert('error\n\n' + response.responseText);
				return false;
			}
		});
	});
	
	$("#exhibitDate").on("click", function(){
		if ($("#picture_idx").val() == "") {
			alert("신청 그림을 먼저 선택하여 주세요.");
		}
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

function searchExhibit() {
	window.open('/bbs/admin/publishing_exhibit_search.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>', 'SearchExhibit', 'width=800,height=600');
}

function selectExhibit(code, name){
	document.exhibit_form.picture_idx.value = code;
	document.exhibit_form.picture_name.value = name;
	$("#picture_idx").trigger('change');
}

function setGubunTxt(str) {
	if(str =="N"){
		$("#strgubunTxt").hide();
		$("#strgubunTxt").val("");
	}else{
		$("#strgubunTxt").show();
	}
}

function check_form() {
	if ($("input[name=strgubun]:checked").size() == 0) {
		alert("신청기관을 선택하세요.");
		exhibit_form.strgubun[0].focus();
		return;
	}
	
	if (exhibit_form.strgubun[4].checked == true && exhibit_form.strgubunTxt.value == ""){
		alert("신청기관 분류 기타를 입력하세요.");
		exhibit_form.strgubunTxt.focus();
		return;
	}
	
	if ((exhibit_form.strOrgan.value).replace(/\s/g, "").length == 0){
		alert("기관명을 입력하세요.");
		exhibit_form.strOrgan.value = "";
		exhibit_form.strOrgan.focus();
		return;
	}
	
	if ((exhibit_form.userTel1.value).replace(/\s/g, "").length == 0 
			|| (exhibit_form.userTel2.value).replace(/\s/g, "").length == 0 
			|| (exhibit_form.userTel3.value).replace(/\s/g, "").length == 0){
		alert("전화번호를 입력하세요.");
		exhibit_form.userTel1.focus();
		return;
	}
	
	if ((exhibit_form.userEmail.value).replace(/\s/g, "").length == 0){
		alert("이메일주소를 입력하세요.");
		exhibit_form.userEmail.value = "";
		exhibit_form.userEmail.focus();
		return;
	}
	
	if (exhibit_form.addr1.value == ""){
		alert("주소를 입력하세요.");
		exhibit_form.addr1.focus();
		return;
	}
	
	if ((exhibit_form.addr2.value).replace(/\s/g, "").length == 0){
		alert("상세주소를 입력하세요.");
		exhibit_form.addr2.value = "";
		exhibit_form.addr2.focus();
		return;
	}
	
	if (exhibit_form.picture_name.value == ""){
		alert("신청 그림을 선택하세요.");
		exhibit_form.picture_name.focus();
		return;
	}
	
	if (exhibit_form.exhibitDate.selectedIndex == 0){
		alert("전시일정을 선택하세요.");
		exhibit_form.exhibitDate.focus();
		return;
	}
	
	exhibit_form.submit();
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
				document.getElementById("addr1").value = fullAddr;

				// 커서를 상세주소 필드로 이동한다.
				document.getElementById("addr2").focus();
			}
		}).open();
	}
</script>

<?
include_once("_tail.php");
?>