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
					COFA2015 "COFFEE&TEA 세미나" 수강신청서
				</div>
				<div class="space-4"></div>

			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="mail_form" name="mail_form" action="coffeentea_7_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">

					<div class="form-group">
						<label class="col-sm-2 control-label">수강과목</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-12" name="mail_01">
								<option value="">선택</option>
								<option value="G1">G1</option>
								<option value="G2">G2</option>
								<option value="G3">G3</option>
								<option value="G4">G4</option>
								<option value="G5">G5</option>
								<option value="G6">G6</option>
								<option value="G7">G7</option>
								<option value="G8">G8</option>
								<option value="G9">G9</option>
								<option value="C1">C1</option>
								<option value="C2">C2</option>
								<option value="C3">C3</option>
								<option value="C4">C4</option>
								<option value="C5">C5</option>
								<option value="C6">C6</option>
								<option value="C7">C7</option>
								<option value="C8">C8</option>
								<option value="C9">C9</option>
								<option value="T1">T1</option>
								<option value="T2">T2</option>
								<option value="T3">T3</option>
								<option value="T4">T4</option>
								<option value="T5">T5</option>
								<option value="T6">T6</option>
								<option value="T7">T7</option>
								<option value="T8">T8</option>
								<option value="T9">T9</option>
								<option value="SC(비즈니스컵핑 프로그램)">SC(비즈니스컵핑 프로그램)</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">수강자명</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_02">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">핸드폰번호</label>
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
						<label class="col-sm-2 control-label">주소</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_05">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">소속사명</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_06">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">대표자명</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_07">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">전화번호</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_08">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">팩스번호</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_09">
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
						<label class="col-sm-2 control-label">이메일(본인)</label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-12 col-sm-12" name="mail_11">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">특기사항</label>
						<div class="col-sm-8">
							<textarea name='mail_12' class='col-xs-12 col-sm-12' style='height:100px;resize:none;'></textarea>
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
			alert('수강과목을 선택하여 주십시오.');
			mail_form.mail_01.focus();
			return;
		}
		if (mail_form.mail_02.value == "") {
			alert('수강자명을 입력하여 주십시오.');
			mail_form.mail_02.focus();
			return;
		}
		if (mail_form.mail_11.value == ""){
			alert("이메일을 입력하여 주십시오.");
			mail_form.mail_11.focus();
			return;
		}
		if (mail_form.mail_11.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("잘못된 이메일 주소입니다.");
			mail_form.mail_11.focus();
			return;
		}
		mail_form.submit();
	}
</script>

<?
include_once("_tail.php");
?>