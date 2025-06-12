<?php
include_once("_common.php");
include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">업체정보</li>
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
			업체정보
			<small>
				<i class="fa fa-angle-double-right"></i>
				신규 등록
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ep_form" name="ep_form" action="<?=$iw['super_path']?>/enterprise_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="ep_jointype" value="0" />
					<div class="form-group">
						<label class="col-sm-1 control-label">포워딩ID</label>
						<div class="col-sm-11">
							<?=$iw['url']?>/main/<input type="text" placeholder="입력" name="ep_nick">
							<span class="help-block col-xs-12">
								공백없이 영어,숫자만 입력 가능 (3글자 이상)<br />신규등록 후 수정 불가
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">업체명</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ep_corporate">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사업자등록번호</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ep_permit_number">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">도메인</label>
						<div class="col-sm-11">
							www. <input type="text" placeholder="입력" name="ep_domain">
							<span class="help-block col-xs-12">
								결제시스템에 미등록시 결제 불가
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">언어설정</label>
						<div class="col-sm-11">
							<select name="ep_language">
								<option value="ko">한글 / TOSS</option>
								<option value="en">영문 / PAYPAL & ALIPAY</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">몰타입</label>
						<div class="col-sm-11">
							<label class="middle">
								<input type="checkbox" name="ep_state_mcb" value="1">
								<span class="lbl"> 게시판</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="ep_state_publishing" value="1">
								<span class="lbl"> 출판도서</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="ep_state_doc" value="1">
								<span class="lbl"> 컨텐츠몰</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="ep_state_shop" value="1">
								<span class="lbl"> 쇼핑몰</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="ep_state_book" value="1">
								<span class="lbl"> 이북몰</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">정산비율</label>
						<div class="col-sm-11">
							위즈윈디지털 <input type="text" placeholder="입력" name="ep_point_super" value="0"> %<br/>
							사이트 <input type="text" placeholder="입력" name="ep_point_site" value="0"> %<br/>
							판매자 <input type="text" placeholder="입력" name="ep_point_seller" value="0"> %
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">복제 방지</label>
						<div class="col-sm-11">
							<div class="radio">
								<label>
									<input type="radio" name="ep_copy_off" value="0" checked>
									<span class="lbl"> OFF</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="ep_copy_off" value="1">
									<span class="lbl"> ON</span>
								</label>
							</div>
							<span class="help-block col-xs-12">마우스 드래그, 마우스 오른쪽, ctrl + c 키 방지</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">파일업로드</label>
						<div class="col-sm-11">
							<div class="radio">
								<label>
									<input type="radio" name="ep_upload" value="0" checked>
									<span class="lbl"> 불가능</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="ep_upload" value="1">
									<span class="lbl"> 가능</span>
									<input type="text" placeholder="입력" name="ep_upload_size" value="0"> MB
								</label>
							</div>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">노출설정</label>
						<div class="col-sm-11">
							<label class="middle">
								<input type="checkbox" name="ep_exposed" value="1" checked>
								<span class="lbl"> 회원에게만 노출</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">가입방식</label>
						<div class="col-sm-11">
							<div class="radio">
								<label>
									<input type="radio" name="type" value="0" onclick="javascript:radio_check(this.value);" checked>
									<span class="lbl"> 가입불가</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="1" onclick="javascript:radio_check(this.value);">
									<span class="lbl"> 가입신청 > 관리자승인</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="2" onclick="javascript:radio_check(this.value);">
									<span class="lbl"> 무조건 가입</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="4" onclick="javascript:radio_check(this.value);">
									<span class="lbl"> 초대후 가입</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="5" onclick="javascript:radio_check(this.value);">
									<span class="lbl"> 가입코드 입력후 자동승인</span> <input type="text" placeholder="입력" name="ep_autocode" maxlength="20">
								</label>
							</div>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">닉네임 공개</label>
						<div class="col-sm-11">
							<div class="radio">
								<label>
									<input type="radio" name="ep_anonymity" value="0" checked>
									<span class="lbl"> 공개</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="ep_anonymity" value="1">
									<span class="lbl"> 비공개</span>
								</label>
							</div>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이메일주소</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_mail">
							<span class="help-block col-xs-12">이메일을 아이디로 사용합니다.</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">비밀번호</label>
						<div class="col-sm-11">
							<input type="password" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_password">
							<span class="help-block col-xs-12">4글자 이상</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">비밀번호 확인</label>
						<div class="col-sm-11">
							<input type="password" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_password_re">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이름</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_name">
							<span class="help-block col-xs-12">공백없이 한글만 입력 가능</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">닉네임</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="mb_nick">
							<span class="help-block col-xs-12">공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">휴대폰번호</label>
						<div class="col-sm-11">
							<select name="mb_tel_1">
								<option value="010">010</option>
								<option value="011">011</option>
								<option value="016">016</option>
								<option value="017">017</option>
								<option value="018">018</option>
								<option value="019">019</option>
							</select>&nbsp;-&nbsp;
							<input type="text" placeholder="입력" name="mb_tel_2" maxlength="4">&nbsp;-&nbsp;
							<input type="text" placeholder="입력" name="mb_tel_3" maxlength="4">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">주소</label>
						<div class="col-sm-11">
							<input type="text" placeholder="우편번호" name="mb_zip_code" id="mb_zip_code" maxlength="5" readonly>
							<button class="btn btn-success" type="button" onclick="fnFindAd('ep_form');">
								<i class="fa fa-check"></i>
								우편번호 검색
							</button>
							<br>
							<input type="text" placeholder="주소" class="col-xs-12 col-sm-8" name="mb_address" id="mb_address" readonly>
							<input type="text" placeholder="상세주소" class="col-xs-12 col-sm-8" name="mb_address_sub" id="mb_address_sub">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">진팩 CMS 사용</label>
						<div class="col-sm-11">
							만료일자 &nbsp;<input type="date" id="date" name="ep_expiry_date" max="2050-06-20" min="2023-01-01" value=""> <br/>
							사용료/년 <input type="number" placeholder="숫자 입력" name="ep_charge" value=""> 원
						</div>
					</div>
					<div class="space-4"></div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw["super_path"]?>/enterprise_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
		if (ep_form.ep_nick.value.length < 3) {
			alert('포워딩ID를 3글자 이상 입력하십시오.');
			ep_form.ep_nick.focus();
			return;
		}
		if (ep_form.ep_corporate.value == ""){
			alert("업체명을 입력하여 주십시오.");
			ep_form.ep_corporate.focus();
			return;
		}
		if (ep_form.ep_point_super.value*1 + ep_form.ep_point_site.value*1 + ep_form.ep_point_seller.value*1 != 100){
			alert("정산비율은 100%가 되도록 입력하여 주십시오.");
			ep_form.ep_point_super.focus();
			return;
		}
		
		var e1;
		var num ="0123456789";
		event.returnValue = true;
		if (ep_form.ep_upload_size.value == "") {
			alert('파입업로드 크기를 입력하여 주십시오.');
			ep_form.ep_upload_size.focus();
			return;
		}
		e1 = ep_form.ep_upload_size;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			ep_form.ep_upload_size.focus();
			return;
		}
		if (ep_form.ep_jointype.value == "5" && ep_form.ep_autocode.value == ""){
			alert("가입코드를 입력하여 주십시오.");
			ep_form.ep_autocode.focus();
			return;
		}
		if (ep_form.mb_mail.value == ""){
			alert("이메일을 입력하여 주십시오.");
			ep_form.mb_mail.focus();
			return;
		}
		if(ep_form.mb_mail.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("잘못된 이메일 주소입니다.");
			ep_form.mb_mail.focus();
			return;
		}
		if (ep_form.mb_password.value.length < 4) {
			alert('패스워드를 4글자 이상 입력하십시오.');
			ep_form.mb_password.focus();
			return;
		}
		if (ep_form.mb_password.value != ep_form.mb_password_re.value) {
			alert('패스워드를 확인하여 주십시오.');
			ep_form.mb_password.focus();
			return;
		}
		if (ep_form.mb_name.value == ""){
			alert("이름을 입력하여 주십시오.");
			ep_form.mb_name.focus();
			return;
		}
		if (ep_form.mb_nick.value == ""){
			alert("닉네임을 입력하여 주십시오.");
			ep_form.mb_nick.focus();
			return;
		}
		var pattern = /([^가-힣\x20])/i; 
		if (pattern.test(ep_form.mb_name.value)) {
			alert('이름은 한글로 입력하십시오.');
			ep_form.mb_name.focus();
			return false;
		}
		if (ep_form.mb_tel_2.value == ""){
			alert("휴대폰 번호를 입력하여 주십시오.");
			ep_form.mb_tel_2.focus();
			return;
		}
		if (ep_form.mb_tel_3.value == ""){
			alert("휴대폰 번호를 입력하여 주십시오.");
			ep_form.mb_tel_3.focus();
			return;
		}
		ep_form.submit();
	}

	function radio_check(chk) {
		ep_form.ep_jointype.value = chk;
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