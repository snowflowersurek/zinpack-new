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
					"2015 골든커피어워드(GCA)" [현장대회 부문]
				</div>
				<div class="space-4"></div>

			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="mail_form" name="mail_form" action="coffeentea_2_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">

					<div class="form-group">
						<label class="col-sm-2 control-label">참가부문(품목)</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-12" name="mail_01">
								<option value="">선택</option>
								<option value="커피로스팅챔피언십">커피로스팅챔피언십</option>
								<option value="핸드드립챔피언십">핸드드립챔피언십</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">참가자명</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_02">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">소속업체명</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_03">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">우편번호</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_04">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">사업장 주소</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_05">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">전화번호(사무실)</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_06">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">전화번호(핸드폰)</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_07">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">팩스번호</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_8">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">이메일</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_9">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">홈페이지</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_10">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">기타요청</label>
						<div class="col-sm-8">
							<textarea name='mail_11' class='col-xs-12 col-sm-12' style='height:100px;resize:none;'></textarea>
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
			alert('참가부문을 선택하여 주십시오.');
			mail_form.mail_01.focus();
			return;
		}
		if (mail_form.mail_02.value == "") {
			alert('참가자명을 입력하여 주십시오.');
			mail_form.mail_02.focus();
			return;
		}
		if (mail_form.mail_9.value == ""){
			alert("이메일을 입력하여 주십시오.");
			mail_form.mail_9.focus();
			return;
		}
		if (mail_form.mail_9.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("잘못된 이메일 주소입니다.");
			mail_form.mail_9.focus();
			return;
		}
		mail_form.submit();
	}
</script>

<?
include_once("_tail.php");
?>