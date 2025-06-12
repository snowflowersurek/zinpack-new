<?php
include_once("_common.php");
include_once("_head.php");

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if ($row["mb_no"]) {
	$mb_code = $row["mb_code"];
	$mb_tel = str_replace("-", "", $row["mb_tel"]);
	$mb_name = $row['mb_name'];
	$mb_mail = $row['mb_mail'];
	$mb_zip_code = $row["mb_zip_code"];
	$mb_address = $row['mb_address'];
	$mb_address_sub = $row['mb_address_sub'];
} else {
	$mb_code = "guest";
}

$contest_code = $_GET["contest_code"];

$sql2 = "select * from iw_publishing_contest where ep_code = '$iw[store]' and gp_code='$iw[group]' and contest_code = '$contest_code'";
$row2 = sql_fetch($sql2);
$contest_cg_code = $row2["cg_code"];
$subject = stripslashes($row2["subject"]);

$category_sql = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$contest_cg_code'";
$category_row = sql_fetch($category_sql);

if ($category_row[cg_level_read] != 10 && $iw[level]=="guest"){
	alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
}else if($category_row[cg_level_read] != 10 && $iw[mb_level] < $category_row[cg_level_read]){
	alert(national_language($iw[language],"a0169","해당 페이지에 접근 권한이 없습니다."),"");
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
						echo stripslashes($hm_row[hm_name]);
					?>
					</li>
				</ol>
			</div>
			<!-- content list starting -->
			<div class="masonry-item w-full h-full">
				<div class="panel text-center">
					<h3><?=$subject?></h3>
				</div>

				<div class="box br-theme">
					<form name="contest_form" action="<?=$iw['m_path']?>/publishing_contest_application_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
						<input type="hidden" name="contest_code" value="<?=$contest_code?>" />
						<input type="hidden" name="mb_code" value="<?=$mb_code?>" />
						
						<div class="form-group">
							<label>이름</label>
							<input type="text" class="form-control" name="user_name" maxlength="10" value="<?=$mb_name?>" />
						</div>
						
						<div class="form-group">
							<label>휴대전화</label>
							<input type="text" class="form-control" name="user_phone" maxlength="11" value="<?=$mb_tel?>" onkeyup="return this.value=this.value.replace(/\D/g,'');" />
						</div>
						
						<div class="form-group">
							<label>이메일</label>
							<input type="text" class="form-control" name="user_email" maxlength="50" value="<?=$mb_mail?>" />
						</div>
						
						<div class="form-group">
							<label>주소</label>
							<div class="row">
								<div class="col-sm-2">
									<input type="text" class="form-control" name="zipcode" id="zipcode" maxlength="7" placeholder="우편번호" readonly value="<?=$mb_zip_code?>" onclick="fnFindAd();" />
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-theme" onclick="fnFindAd();">우편번호 검색</button>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<input type="text" class="form-control" name="addr1" id="addr1" placeholder="주소" readonly value="<?=$row['mb_address']?>" maxlength="100" onclick="fnFindAd();" />
								</div>
								<div class="col-sm-12 col-md-6">
									<input type="text" class="form-control" name="addr2" id="addr2" placeholder="상세주소" value="<?=$row['mb_address_sub']?>" maxlength="100" />
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label>작품 제목</label>
							<input type="text" class="form-control" name="work_title" maxlength="50" value="" />
						</div>
						
						<div class="form-group">
							<label>공모작품 첨부</label>
							<input type="file" name="attach_file" />
							※ 첨부 가능 파일: .doc, .docx, .hwp, .pdf, .zip
						</div>
						
						<br/>
						
                        <div class="checkbox">
                            <label><input type="checkbox" name="check1"> 응모 자격과 응모 요령을 확인하셨습니까?</label>
                        </div>
						
                        <div class="checkbox">
                            <label><input type="checkbox" name="check2"> 개인정보 수집 이용에 대한 동의</label>
                        </div>
                        <div style="margin-bottom:20px; padding:10px 20px; border:1px solid #b6b6b6;">
							공모 진행을 위해 다음과 같이 개인정보를 수집 이용합니다.<br/>
							- 수집항목: 이름, 휴대전화번호, 이메일, 주소<br/>
							- 수집목적: 공모 접수<br/>
							- 보유 및 이용기간: 신청 후 1년<br/>
							<br/>
							신청자는 개인정보 수집 이용에 거부하실 수 있으며, 거부 시에는 공모 참여가 불가함을 안내드립니다.
                        </div>
						
						<button type="button" class="btn btn-theme" onclick="javascript:check_form();">응모하기</button>
					</form>
				</div>
			</div>
		</div> <!-- /.col -->
	</div> <!-- /.row -->
</div> <!-- /.content -->

<script type="text/javascript">
$(document).ready(function() {
	
});

function check_form() {
	if ((contest_form.user_name.value).replace(/\s/g, "").length == 0){
		alert("이름을 입력하세요.");
		contest_form.user_name.focus();
		return;
	}
	
	if ((contest_form.user_phone.value).replace(/\s/g, "").length != 11){
		alert("휴대전화 번호를 입력하세요.");
		contest_form.user_phone.focus();
		return;
	}
	
	if ((contest_form.user_email.value).replace(/\s/g, "").length == 0){
		alert("이메일주소를 입력하세요.");
		contest_form.user_email.focus();
		return;
	}
	
	if (contest_form.addr1.value == ""){
		alert("주소를 입력하세요.");
		contest_form.addr1.focus();
		return;
	}
	
	if ((contest_form.addr2.value).replace(/\s/g, "").length == 0){
		alert("상세주소를 입력하세요.");
		contest_form.addr2.focus();
		return;
	}
	
	if ((contest_form.work_title.value).replace(/\s/g, "").length == 0){
		alert("작품 제목을 입력하세요.");
		contest_form.work_title.focus();
		return;
	}
	
	if (contest_form.attach_file.value == ""){
		alert("파일을 첨부하세요.");
		contest_form.attach_file.focus();
		return;
	}
	
	if (contest_form.check1.checked == false){
		alert("응모 자격 확인에 체크하여 주십시오");
		contest_form.check1.focus();
		return;
	}
	
	if (contest_form.check2.checked == false){
		alert("개인정보 수집 이용에 대한 동의에 체크하여 주십시오.");
		contest_form.check2.focus();
		return;
	}
	
	if (!confirm("응모하시겠습니까?")) {
		return;
	}
	
	contest_form.submit();
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