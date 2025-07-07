<?php
include_once("_common.php");
include_once("_head.php");

$sql = "select gl_name from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 and gl_level = $iw[mb_level]";
$row = sql_fetch($sql);
$gl_name = $row["gl_name"];

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");

$mb_tel = explode("-", $row["mb_tel"]);
$mb_zip_code = $row["mb_zip_code"];
?>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="breadcrumb-box input-group">
                <ol class="breadcrumb ">
                    <li><a href="#"><?=national_language($iw[language],"a0111","회원정보 수정");?></a></li>
                </ol>
            </div>
			<!-- content list starting -->
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
                    <form id="mb_form" name="mb_form" action="<?=$iw['m_path']?>/all_member_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
                        <div class="form-group">
                            <label for="id_name"><?=national_language($iw[language],"a0065","아이디(이메일주소)");?></label>
                            <input type="text" class="form-control" name="mb_mail" placeholder="<?=national_language($iw[language],"a0066","이메일 - 아이디로 사용");?>" readonly value="<?=$row['mb_mail']?> (등급 : <?=$gl_name?>)" />
                        </div>
                        <div class="form-group">
                            <label for="password"><?=national_language($iw[language],"a0112","비밀번호(변경 시)");?></label>
                            <input type="password" class="form-control" name="mb_password" placeholder="<?=national_language($iw[language],"a0068","비밀번호 - 4글자 이상");?>" />
                        </div>
                        <div class="form-group">
                            <label for="password_confirm"><?=national_language($iw[language],"a0069","비밀번호 확인");?></label>
                            <input type="password" class="form-control" name="mb_password_re" placeholder="<?=national_language($iw[language],"a0069","비밀번호 확인");?>" />
                        </div>
                        <div class="form-group">
                            <label for="realname"><?=national_language($iw[language],"a0070","이름");?></label>
                            <input type="text" class="form-control" name="mb_name" placeholder="<?=national_language($iw[language],"a0071","이름 - 실명 작성");?>" value="<?=$row['mb_name']?>" />
                        </div>
                        <div class="form-group">
                            <label for="nick"><?=national_language($iw[language],"a0072","닉네임");?></label>
                            <input type="text" class="form-control" name="mb_nick" placeholder="<?=national_language($iw[language],"a0073","닉네임 - 공백없이 한글,영문,숫자");?>" value="<?=$row['mb_nick']?>" />
                        </div>
						<?php if($iw[language]=="ko"){?>
							<label>휴대폰번호</label>
							<div class="row form-group">
								<div class="col-sm-2">
									<label class="sr-only" for="mb_tel_1">통신사번호</label>
									<select name="mb_tel_1" class="form-control">
										<option value="010" <?php if{?>selected<?php }?>>010</option>
										<option value="011" <?php if{?>selected<?php }?>>011</option>
										<option value="016" <?php if{?>selected<?php }?>>016</option>
										<option value="017" <?php if{?>selected<?php }?>>017</option>
										<option value="018" <?php if{?>selected<?php }?>>018</option>
										<option value="019" <?php if{?>selected<?php }?>>019</option>
									</select>
								</div>
								<div class="col-sm-2">
									<label class="sr-only" for="mb_tel_2">중간번호</label>
									<input type="text" class="form-control" name="mb_tel_2" maxlength="4" value="<?=$mb_tel[1]?>" />
								</div>
								<div class="col-sm-2">
									<label class="sr-only" for="mb_tel_3">뒷번호</label>
									<input type="text" class="form-control" name="mb_tel_3" maxlength="4" value="<?=$mb_tel[2]?>" />
								</div>
							</div>
							<label>주소</label>
							<div class="row form-group">
								<div class="col-sm-2">
									<label class="sr-only" for="mb_zip_code">우편번호</label>
									<input type="text" class="form-control" name="mb_zip_code" id="mb_zip_code" maxlength="7" placeholder="우편번호" readonly value="<?=$mb_zip_code?>" onclick="fnFindAd();" />
								</div>
								<div class="col-sm-2">
									<button type="button" class="btn btn-theme" onclick="fnFindAd();">우편번호 검색</button>
								</div>
							</div>
							<div class="form-group">
								<label class="sr-only" for="mb_address">주소</label>
								<input type="text" class="form-control" name="mb_address" id="mb_address" placeholder="주소" readonly value="<?=$row['mb_address']?>" />
							</div>
							<div class="form-group">
								<label class="sr-only" for="mb_address_sub">상세주소</label>
								<input type="text" class="form-control" name="mb_address_sub" id="mb_address_sub" placeholder="상세주소" value="<?=$row['mb_address_sub']?>" />
							</div>
						<?php }else if($iw[language]=="en"){?>
							<div class="form-group">
								<label for="id_name">Address Line1</label>
								<input type="text" class="form-control" name="mb_address" placeholder="Street address, P.O. box, company name, c/o" value="<?=$row['mb_address']?>" />
							</div>
							<div class="form-group">
								<label for="id_name">Address Line2</label>
								<input type="text" class="form-control" name="mb_address_sub" placeholder="Apartment, suite, unit, building, floor, etc." value="<?=$row['mb_address_sub']?>" />
							</div>

							<div class="form-group">
								<label for="id_name">City</label>
								<input type="text" class="form-control" name="mb_address_city" value="<?=$row['mb_address_city']?>" />
							</div>
							
							<div class="form-group">
								<label for="id_name">County/Province/Region/State</label>
								<input type="text" class="form-control" name="mb_address_state" value="<?=$row['mb_address_state']?>" />
							</div>

							<div class="form-group">
								<label for="id_name">Country</label>
								<select name="mb_address_country" class="form-control">
									<option value="">--</option>
								<?php
									$sql2 = "select * from $iw[country_table] order by ct_no asc";
									$result2 = sql_query($sql2);

									while($row2 = @sql_fetch_array($result2)){
								?>
									<option value="<?=$row2["ct_code"];?>" <?php if{?>selected<?php }?>><?=$row2["ct_name"];?></option>
								<?php }?>
								</select>
							</div>

							<div class="form-group">
								<label for="id_name">Postal/Zip Code</label>
								<input type="text" class="form-control" name="mb_zip_code" value="<?=$row['mb_zip_code']?>" />
							</div>
							<div class="form-group">
								<label for="id_name">Phone Number</label>
								<input type="text" class="form-control" name="mb_tel" value="<?=$row['mb_tel']?>" />
							</div>
						<?php }?>
						<div class="form-group">
                            <label for="password"><?=national_language($iw[language],"a0113","기존 비밀번호 확인");?></label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="" />
                        </div>
                        <br />
                        <button type="button" class="btn btn-theme" onclick="javascript:check_form();"><?=national_language($iw[language],"a0259","저장");?></button>
                        <?php if ($iw[level] == "member") {?>
                        <button type="button" class="btn btn-danger pull-right" onclick="javascript:check_withdraw();">회원탈퇴</button>
						<?php }?>
                    </form>
			    </div>
			</div>
		</div> <!-- /.col -->
	</div> <!-- /.row -->
</div> <!-- /.content -->

<script type="text/javascript">
	function check_form() {
		if (mb_form.mb_password.value.length != 0 && mb_form.mb_password.value.length < 4) {
			alert("<?=national_language($iw[language],'a0084','비밀번호를 4글자 이상 입력하십시오.');?>");
			mb_form.mb_password.focus();
			return;
		}
		if (mb_form.mb_password.value != mb_form.mb_password_re.value) {
			alert("<?=national_language($iw[language],'a0085','비밀번호를 확인하여 주십시오.');?>");
			mb_form.mb_password_re.focus();
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
		if (mb_form.confirm_password.value == ""){
			alert("<?=national_language($iw[language],'a0114','기존 비밀번호를 입력하여 주십시오.');?>");
			mb_form.confirm_password.focus();
			return;
		}
		mb_form.submit();
	}

	function check_withdraw() {
		if (mb_form.confirm_password.value == ""){
			alert("<?=national_language($iw[language],'a0114','기존 비밀번호를 입력하여 주십시오.');?>");
			mb_form.confirm_password.focus();
			return;
		}
		
		if (confirm('회원탈퇴 후 삭제된 데이터는 복구되지 않습니다.\n진행 하시겠습니까?')) {
			mb_form.action = "<?=$iw['m_path']?>/all_member_delete_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>";
			mb_form.submit();
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
                document.getElementById("mb_zip_code").value = data.zonecode;
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



