<?php
include_once("_common.php");
include_once("_head.php");

$mb_code = $_GET["idx"];

$sql = "select * from $iw[member_table] where mb_code = '$mb_code'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert("잘못된 접근입니다!","");
$ep_code = $row[ep_code];
$row2 = sql_fetch("select * from $iw[enterprise_table] where ep_code = '$ep_code'");
$iw['language'] = $row2["ep_language"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">회원정보</li>
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
			회원정보
			<small>
				<i class="fa fa-angle-double-right"></i>
				수정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="mb_form" name="mb_form" action="<?=$iw['super_path']?>/member_data_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="mb_code" value="<?=$mb_code?>" />
				<input type="hidden" name="ep_code" value="<?=$ep_code?>" />
				<input type="hidden" name="language" value="<?=$iw['language']?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">회원코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_code"]?></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">아이디</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_mail" value="<?=$row["mb_mail"]?>">
							<span class="help-block col-xs-12">이메일 - 아이디로 사용</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">비밀번호(변경 시)</label>
						<div class="col-sm-11">
							<input type="password" placeholder="입력" name="mb_password">
							<span class="help-block col-xs-12">비밀번호 - 4글자 이상</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">비밀번호 확인</label>
						<div class="col-sm-11">
							<input type="password" placeholder="입력" name="mb_password_re">
							<span class="help-block col-xs-12">비밀번호 - 4글자 이상</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이름</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" name="mb_name" value="<?=$row['mb_name']?>">
							<span class="help-block col-xs-12">실명 작성</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">닉네임</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" name="mb_nick" value="<?=$row['mb_nick']?>">
							<span class="help-block col-xs-12">공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">보조이메일</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_sub_mail" value="<?=$row['mb_sub_mail']?>">
							<span class="help-block col-xs-12">아이디 분실시 사용</span>
						</div>
					</div>
					<div class="space-4"></div>

					<?if($iw[language]=="ko"){?>
						<?
							$mb_tel = explode("-", $row["mb_tel"]);
							$mb_zip_code = $row["mb_zip_code"];
						?>
						<div class="form-group">
							<label class="col-sm-1 control-label">휴대폰번호</label>
							<div class="col-sm-11">
								<select name="mb_tel_1">
									<option value="010" <?if($mb_tel[0] == "010"){?>checked<?}?>>010</option>
									<option value="011" <?if($mb_tel[0] == "011"){?>checked<?}?>>011</option>
									<option value="016" <?if($mb_tel[0] == "016"){?>checked<?}?>>016</option>
									<option value="017" <?if($mb_tel[0] == "017"){?>checked<?}?>>017</option>
									<option value="018" <?if($mb_tel[0] == "018"){?>checked<?}?>>018</option>
									<option value="019" <?if($mb_tel[0] == "019"){?>checked<?}?>>019</option>
								</select>&nbsp;-&nbsp;
								<input type="text" placeholder="입력" name="mb_tel_2" maxlength="4" value="<?=$mb_tel[1]?>">&nbsp;-&nbsp;
								<input type="text" placeholder="입력" name="mb_tel_3" maxlength="4" value="<?=$mb_tel[2]?>">
							</div>
						</div>
						<div class="space-4"></div>
						
						<div class="form-group">
							<label class="col-sm-1 control-label">주소</label>
							<div class="col-sm-2">
								<label class="sr-only" for="mb_zip_code">우편번호</label>
								<input type="text" class="form-control" name="mb_zip_code" id="mb_zip_code" maxlength="7" placeholder="우편번호" readonly value="<?=$mb_zip_code?>" />
							</div>
							<div class="col-sm-2">
								<button type="button" class="btn btn-default" onclick="fnFindAd('mb_form');">우편번호 검색</button>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-1 control-label"></label>
							<div class="col-sm-11">
								<input type="text" class="form-control" name="mb_address" id="mb_address" placeholder="주소" readonly value="<?=$row['mb_address']?>" />
								<input type="text" class="form-control" name="mb_address_sub" id="mb_address_sub" placeholder="상세주소" value="<?=$row['mb_address_sub']?>" />
							</div>
						</div>
					<?}else if($iw[language]=="en"){?>
						<div class="form-group">
							<label class="col-sm-1 control-label">Phone Number</label>
							<div class="col-sm-11">
								<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_tel" value="<?=$row['mb_tel']?>">
							</div>
						</div>
						<div class="space-4"></div>
						
						<div class="form-group">
							<label class="col-sm-1 control-label">ZIP</label>
							<div class="col-sm-11">
								<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_zip_code" value="<?=$row['mb_zip_code']?>">
							</div>
						</div>
						<div class="space-4"></div>
						
						<div class="form-group">
							<label class="col-sm-1 control-label">Address Line1</label>
							<div class="col-sm-11">
								<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_address" value="<?=$row['mb_address']?>">
								<span class="help-block col-xs-12">Street address, P.O. box, company name, c/o</span>
							</div>
						</div>
						<div class="space-4"></div>

						<div class="form-group">
							<label class="col-sm-1 control-label">Address Line2</label>
							<div class="col-sm-11">
								<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_address_sub" value="<?=$row['mb_address_sub']?>">
								<span class="help-block col-xs-12">Apartment, suite, unit, building, floor, etc.</span>
							</div>
						</div>
						<div class="space-4"></div>

						<div class="form-group">
							<label class="col-sm-1 control-label">City</label>
							<div class="col-sm-11">
								<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_address_city" value="<?=$row['mb_address_city']?>">
							</div>
						</div>
						<div class="space-4"></div>

						<div class="form-group">
							<label class="col-sm-1 control-label">State/Province/Region</label>
							<div class="col-sm-11">
								<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_address_state" value="<?=$row['mb_address_state']?>">
							</div>
						</div>
						<div class="space-4"></div>

						<div class="form-group">
							<label class="col-sm-1 control-label">Country</label>
							<div class="col-sm-11">
								<select name="mb_address_country">
									<option value="">--</option>
								<?
									$sql2 = "select * from $iw[country_table] order by ct_no asc";
									$result2 = sql_query($sql2);

									while($row2 = @sql_fetch_array($result2)){
								?>
									<option value="<?=$row2["ct_code"];?>" <?if($row['mb_address_country']==$row2["ct_code"]){?>selected<?}?>><?=$row2["ct_name"];?></option>
								<?}?>
								</select>
							</div>
						</div>
					<?}?>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								확인
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['super_path']?>/member_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$mb_code?>'">
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
		if (mb_form.mb_sub_mail.value != "" && mb_form.mb_sub_mail.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("<?=national_language($iw[language],'a0092','잘못된 이메일 주소입니다.');?>");
			mb_form.mb_sub_mail.focus();
			return;
		}
		<?if($iw[language]=="ko"){?>
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
		<?}else if($iw[language]=="en"){?>
			if (mb_form.mb_tel.value == ""){
				alert("<?=national_language($iw[language],'a0088','휴대폰 번호를 입력하여 주십시오.');?>");
				mb_form.mb_tel.focus();
				return;
			}
			if (mb_form.mb_zip_code.value == ""){
				alert("<?=national_language($iw[language],'a0089','우편번호를 입력하여 주십시오.');?>");
				mb_form.mb_zip_code.focus();
				return;
			}
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
		<?}?>
		mb_form.submit();
	}
</script>

<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function fnFindAd(f) {
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
<?
include_once("_tail.php");
?>