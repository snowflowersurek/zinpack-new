<?php
include_once("_common.php");
include_once("_head.php");
?>
<title>신청서</title>
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="table-header">
					COFA 2015 사전등록 신청서
				</div>
				<div class="space-4"></div>

			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="mail_form" name="mail_form" action="coffeentea_6_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">

					<div class="form-group">
						<label class="col-sm-2 control-label">성명</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_01">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">회사명</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_02">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">회사분류</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-12" name="mail_03">
								<option value="">선택</option>
								<option value="제조/생산업">제조/생산업</option>
								<option value="무역/유통업">무역/유통업</option>
								<option value="요식업">요식업</option>
								<option value="서비스업">서비스업</option>
								<option value="자영업">자영업</option>
								<option value="프랜차이즈">프랜차이즈</option>
								<option value="연구원/교육계">연구원/교육계</option>
								<option value="광고/미디어/출판">광고/미디어/출판</option>
								<option value="공공/정부기관">공공/정부기관</option>
								<option value="언론">언론</option>
								<option value="학생">학생</option>
								<option value="창업희망자">창업희망자</option>
								<option value="기타">기타</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">부서/직위</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_04">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">전화</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_05">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">휴대전화</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_06">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">이메일</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_07">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">우편번호</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_08">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">주소</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_09">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">관심분야</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-12" name="mail_10">
								<option value="">선택</option>
								<option value="커피">커피</option>
								<option value="생두 및 원두">생두 및 원두</option>
								<option value="관련 부재료">관련 부재료</option>
								<option value="커피관련">커피관련</option>
								<option value="기기">기기</option>
								<option value="차(茶)">차(茶)</option>
								<option value="로스팅">로스팅</option>
								<option value="기타">기타</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">관람목적</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-12" name="mail_11">
								<option value="">선택</option>
								<option value="구매">구매</option>
								<option value="정보수집">정보수집</option>
								<option value="신규거래선 확보">신규거래선 확보</option>
								<option value="일반관람">일반관람</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">인지경로</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-12" name="mail_12">
								<option value="">선택</option>
								<option value="초청장">초청장</option>
								<option value="잡지광고">잡지광고</option>
								<option value="방송매체">방송매체</option>
								<option value="SNS(인터넷)">SNS(인터넷)</option>
								<option value="거래업체">거래업체</option>
								<option value="기타">기타</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">연령</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-12" name="mail_13">
								<option value="">선택</option>
								<option value="10대">10대</option>
								<option value="20대">20대</option>
								<option value="30대">30대</option>
								<option value="40대">40대</option>
								<option value="50대">50대</option>
								<option value="60대 이상">60대 이상</option>
							</select>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								신청서 전송
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
		if (mail_form.mail_01.value == "") {
			alert('성명을 입력하여 주십시오.');
			mail_form.mail_01.focus();
			return;
		}
		if (mail_form.mail_03.value == "") {
			alert('회사분류를 선택하여 주십시오.');
			mail_form.mail_03.focus();
			return;
		}
		if (mail_form.mail_07.value == ""){
			alert("이메일을 입력하여 주십시오.");
			mail_form.mail_07.focus();
			return;
		}
		if (mail_form.mail_07.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("잘못된 이메일 주소입니다.");
			mail_form.mail_07.focus();
			return;
		}
		if (mail_form.mail_10.value == "") {
			alert('관심분야를 선택하여 주십시오.');
			mail_form.mail_10.focus();
			return;
		}
		if (mail_form.mail_11.value == "") {
			alert('관람목적을 선택하여 주십시오.');
			mail_form.mail_11.focus();
			return;
		}
		if (mail_form.mail_12.value == "") {
			alert('인지경로를 선택하여 주십시오.');
			mail_form.mail_12.focus();
			return;
		}
		if (mail_form.mail_13.value == "") {
			alert('연령을 선택하여 주십시오.');
			mail_form.mail_13.focus();
			return;
		}
		mail_form.submit();
	}
</script>

<?
include_once("_tail.php");
?>