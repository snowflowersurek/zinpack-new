<?php
include_once("_common_guest.php");
include_once("_head.php");
if($iw[level] != "guest") goto_url("$iw[m_path]/main.php?type=main&ep=$iw[store]&gp=$iw[group]");

$row = sql_fetch("select * from $iw[enterprise_table] where ep_code = '$iw[store]'");
$ep_jointype = $row["ep_jointype"];
if ($ep_jointype == 0) {
	alert(national_language($iw[language],"a0064","회원가입이 불가능합니다."),"");
}
$ep_policy_agreement = stripslashes($row["ep_policy_agreement"]);
$ep_policy_private = stripslashes($row["ep_policy_private"]);
?>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="breadcrumb-box input-group">
                <ol class="breadcrumb ">
                    <li><a href="#"><?=national_language($iw[language],"a0017","회원가입");?></a></li>
                </ol>
            </div>
			<!-- content list starting -->
			<div class="masonry-item w-full h-full">
				<?php
					if ($iw[store] == "ep993587793575e55ff04653") {
						echo '
							<div class="alert-danger text-center" style="padding:16px 20px;">
								기관 및 회사 이메일로 가입 시, 비밀번호 찾기 및 인증메일 등 서비스가 원활하지 않을 수 있습니다.<br/>
								가급적 \'naver.com\', \'gmail.com\', \'daum.net\' 등의 이메일을 활용해 주시길 바랍니다.<br/>
								이후에도 메일이 오지 않고 인증이 안 될 경우에는 <strong>031-955-8590</strong>으로 상담 부탁 드립니다.
							</div>
						';
					}
				?>
				<div class="box br-theme">
                    <form id="mb_form" name="mb_form" action="<?=$iw['m_path']?>/all_join_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
                        <div class="form-group">
                            <label for="id_name"><?=national_language($iw[language],"a0065","아이디(이메일주소)");?></label>
                            <input type="text" class="form-control" name="mb_mail" placeholder="<?=national_language($iw[language],"a0066","이메일 - 아이디로 사용");?>" />
                        </div>
                        <div class="form-group">
                            <label for="password"><?=national_language($iw[language],"a0067","비밀번호");?></label>
                            <input type="password" class="form-control" name="mb_password" placeholder="<?=national_language($iw[language],"a0068","비밀번호 - 특수문자 포함 8글자 이상");?>" />
                        </div>
                        <div class="form-group">
                            <label for="password_confirm"><?=national_language($iw[language],"a0069","비밀번호 확인");?></label>
                            <input type="password" class="form-control" name="mb_password_re" placeholder="<?=national_language($iw[language],"a0069","비밀번호 확인");?>" />
                        </div>
                        <div class="form-group">
                            <label for="realname"><?=national_language($iw[language],"a0070","이름");?></label>
                            <input type="text" class="form-control" name="mb_name" placeholder="<?=national_language($iw[language],"a0071","이름 - 실명 작성");?>" />
                        </div>
                        <div class="form-group">
                            <label for="nick"><?=national_language($iw[language],"a0072","닉네임");?></label>
                            <input type="text" class="form-control" name="mb_nick" placeholder="<?=national_language($iw[language],"a0073","닉네임 - 공백없이 한글,영문,숫자");?>" />
                        </div>

						<div class="form-group">
                            <label><input type="checkbox" name="option_chk" onChange="javascript:option_click();"> 추가정보 입력</label>
                        </div>

						<div id="option_wrap" style="display:none;">
							<?php if($iw[language]=="ko"){?>
								<label>휴대폰번호</label>
								<div class="row form-group">
									<div class="col-sm-2">
										<label class="sr-only" for="mb_tel_1">통신사번호</label>
										<select name="mb_tel_1" class="form-control">
											<option value="010">010</option>
											<option value="011">011</option>
											<option value="016">016</option>
											<option value="017">017</option>
											<option value="018">018</option>
											<option value="019">019</option>
										</select>
									</div>
									<div class="col-sm-2">
										<label class="sr-only" for="mb_tel_2">중간번호</label>
										<input type="text" class="form-control" name="mb_tel_2" maxlength="4" />
									</div>
									<div class="col-sm-2">
										<label class="sr-only" for="mb_tel_3">뒷번호</label>
										<input type="text" class="form-control" name="mb_tel_3" maxlength="4" />
									</div>
								</div>
								<label>주소</label>
								<div class="row form-group">
									<div class="col-sm-2">
										<label class="sr-only" for="mb_zip_code">우편번호</label>
										<input type="text" class="form-control" name="mb_zip_code" id="mb_zip_code" maxlength="7" placeholder="우편번호" readonly onClick="fnFindAd();" />
									</div>
									<div class="col-sm-2">
										<button type="button" class="btn btn-theme" onClick="fnFindAd();">우편번호 검색</button>
									</div>
								</div>
								<div class="form-group">
									<label class="sr-only" for="mb_address">주소</label>
									<input type="text" class="form-control" name="mb_address" id="mb_address" placeholder="주소" readonly />
								</div>
								<div class="form-group">
									<label class="sr-only" for="mb_address_sub">상세주소</label>
									<input type="text" class="form-control" name="mb_address_sub" id="mb_address_sub" placeholder="상세주소" />
								</div>
							<?php }else if($iw[language]=="en"){?>
								<div class="form-group">
									<label for="id_name">Address Line1</label>
									<input type="text" class="form-control" name="mb_address" placeholder="Street address, P.O. box, company name, c/o" />
								</div>
								<div class="form-group">
									<label for="id_name">Address Line2</label>
									<input type="text" class="form-control" name="mb_address_sub" placeholder="Apartment, suite, unit, building, floor, etc." />
								</div>

								<div class="form-group">
									<label for="id_name">City</label>
									<input type="text" class="form-control" name="mb_address_city" />
								</div>
								
								<div class="form-group">
									<label for="id_name">County/Province/Region/State</label>
									<input type="text" class="form-control" name="mb_address_state" />
								</div>

								<div class="form-group">
									<label for="id_name">Country</label>
									<select name="mb_address_country" class="form-control">
										<option value="">--</option>
									<?php
										$sql = "select * from $iw[country_table] order by ct_no asc";
										$result = sql_query($sql);

										while($row = @sql_fetch_array($result)){
									?>
										<option value="<?=$row["ct_code"];?>"><?=$row["ct_name"];?></option>
									<?php }?>
									</select>
								</div>

								<div class="form-group">
									<label for="id_name">Postal/Zip Code</label>
									<input type="text" class="form-control" name="mb_zip_code" />
								</div>

								<div class="form-group">
									<label for="id_name">Phone Number</label>
									<input type="text" class="form-control" name="mb_tel" />
								</div>
							<?php }?>
						</div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="check1"> <?=national_language($iw[language],"a0079","이용약관 동의");?></label>
                        </div>
                        <div class="terms" style="overflow:auto;height:100px;border:1px solid #b6b6b6;">
							<?=$ep_policy_agreement?>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="check2"> <?=national_language($iw[language],"a0080","개인정보 처리방침 동의");?></label>
                        </div>
                        <div class="terms" style="overflow:auto;height:100px;border:1px solid #b6b6b6;">
							<?=$ep_policy_private?>
                        </div>
					<?php if($ep_jointype == 5) {?>
						<div class="form-group">
							<label for="id_name"><?=national_language($iw[language],"a0056","가입코드");?></label>
							<input type="text" class="form-control" name="ep_autocode" placeholder="<?=national_language($iw[language],"a0056","가입코드");?>" />
						</div>
					<?php }?>
                        <br />
                        <button type="button" class="btn btn-theme" onclick="javascript:check_form();"><?=national_language($iw[language],"a0081","가입하기");?></button>
                    </form>
			    </div>
			</div>
		</div> <!-- /.col -->
	</div> <!-- /.row -->
</div> <!-- /.content -->

<script type="text/javascript">
	function check_form() {
		if (mb_form.mb_mail.value == ""){
			alert("<?=national_language($iw[language],'a0082','이메일을 입력하여 주십시오.');?>");
			mb_form.mb_mail.focus();
			return;
		}
		if (mb_form.mb_mail.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("<?=national_language($iw[language],'a0083','잘못된 이메일 주소입니다.');?>");
			mb_form.mb_mail.focus();
			return;
		}
/*
		if (mb_form.mb_password.value.length < 4) {
			alert("<?=national_language($iw[language],'a0084','비밀번호를 4글자 이상 입력하십시오.');?>");
			mb_form.mb_password.focus();
			return;
		}
		if (mb_form.mb_password.value != mb_form.mb_password_re.value) {
			alert("<?=national_language($iw[language],'a0085','비밀번호를 확인하여 주십시오.');?>");
			mb_form.mb_password.focus();
			return;
		}
*/
		if (mb_form.mb_password.value.search(/^(?=.*[a-z])(?=.*[!@#$%^&*()_+]).{8,}/)== -1) {
			alert("<?=national_language($iw[language],'a0084','비밀번호를 특수문자 포함해서 8글자 이상 입력하십시오.');?>");
			mb_form.mb_password.focus();
			return;
		}
		if (mb_form.mb_password.value != mb_form.mb_password_re.value) {
			alert("<?=national_language($iw[language],'a0085','비밀번호를 확인하여 주십시오.');?>");
			mb_form.mb_password.focus();
			return;
		}

		if (mb_form.mb_name.value == ""){
			alert("<?=national_language($iw[language],'a0086','이름을 입력하여 주십시오.');?>");
			mb_form.mb_name.focus();
			return;
		}
		if (mb_form.mb_nick.value == ""){
			alert("<?=national_language($iw[language],'a0087','닉네임을 입력하여 주십시오.');?>");
			mb_form.mb_nick.focus();
			return;
		}
		if (mb_form.option_chk.checked == true){
		<?php if($iw[language]=="ko"){?>
			if (mb_form.mb_tel_2.value == ""){
				alert("휴대폰 번호를 입력하여 주십시오.");
				mb_form.mb_tel_2.focus();
				return;
			}
			if (mb_form.mb_tel_3.value == ""){
				alert("휴대폰 번호를 입력하여 주십시오.");
				mb_form.mb_tel_3.focus();
				return;
			}
			if (mb_form.mb_address.value == ""){
				alert("우편번호를 입력하여 주십시오.");
				mb_form.mb_address.focus();
				return;
			}
			if (mb_form.mb_address_sub.value == ""){
				alert("주소를 입력하여 주십시오.");
				mb_form.mb_address_sub.focus();
				return;
			}
		<?php }else if($iw[language]=="en"){?>
			if (mb_form.mb_address.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				mb_form.mb_address.focus();
				return;
			}
			if (mb_form.mb_address_sub.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				mb_form.mb_address_sub.focus();
				return;
			}
			if (mb_form.mb_address_city.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				mb_form.mb_address_city.focus();
				return;
			}
			if (mb_form.mb_address_state.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				mb_form.mb_address_state.focus();
				return;
			}
			if (mb_form.mb_address_country.value == ""){
				alert("<?=national_language($iw[language],'a0090','주소를 입력하여 주십시오.');?>");
				mb_form.mb_address_country.focus();
				return;
			}
			if (mb_form.mb_zip_code.value == ""){
				alert("<?=national_language($iw[language],'a0089','우편번호를 입력하여 주십시오.');?>");
				mb_form.mb_zip_code.focus();
				return;
			}
			if (mb_form.mb_tel.value == ""){
				alert("<?=national_language($iw[language],'a0088','휴대폰 번호를 입력하여 주십시오.');?>");
				mb_form.mb_tel.focus();
				return;
			}
	<?php }?>
		}
		if (mb_form.check1.checked == false){
			alert("<?=national_language($iw[language],'a0093','이용약관 동의에 체크하여 주십시오');?>");
			mb_form.check1.focus();
			return;
		}
		if (mb_form.check2.checked == false){
			alert("<?=national_language($iw[language],'a0094','개인정보 처리방침 동의에 체크하여 주십시오.');?>");
			mb_form.check2.focus();
			return;
		}
		mb_form.submit();
	}
	
	function option_click()
    {
        if (mb_form.option_chk.checked == true){
			document.getElementById('option_wrap').style.display="";
		}else{
			document.getElementById('option_wrap').style.display="none";
		}
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
                document.getElementById("mb_zip_code").value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById("mb_address").value = fullAddr;

                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("mb_address_sub").focus();
            }
        }).open();
    }
</script>

<?php
include_once("_tail.php");
?>



