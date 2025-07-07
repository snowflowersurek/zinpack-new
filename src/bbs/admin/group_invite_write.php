<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[gp_level] != "gp_admin" || $iw[group] == "all") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">그룹 초대</li>
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
			그룹 초대
			<small>
				<i class="fa fa-angle-double-right"></i>
				초대하기
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="gi_form" name="gi_form" action="<?=$iw['admin_path']?>/group_invite_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="gi_no" value="<?=$gi_no?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">이름</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="gi_name" value="<?=$row[gi_name]?>">
							<span class="help-inline col-xs-12 col-sm-4">
								<label class="middle">
									<input type="checkbox" name="gi_name_check" value="1" checked>
									<span class="lbl"> 필수항목</span>
								</label>
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이메일</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="gi_mail">
							<span class="help-inline col-xs-12 col-sm-4">
								<label class="middle">
									<input type="checkbox" name="gi_mail_check" value="1" checked>
									<span class="lbl"> 필수항목</span>
								</label>
							</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">연락처</label>
						<div class="col-sm-11">
							<select name="gi_tel_1">
								<option value="010">010</option>
								<option value="011">011</option>
								<option value="016">016</option>
								<option value="017">017</option>
								<option value="018">018</option>
								<option value="019">019</option>
							</select>&nbsp;-&nbsp;
							<input type="text" placeholder="입력" name="gi_tel_2" maxlength="4">&nbsp;-&nbsp;
							<input type="text" placeholder="입력" name="gi_tel_3" maxlength="4">
							<span>
								<label class="middle">
									<input type="checkbox" name="gi_tel_check" value="1" checked>
									<span class="lbl"> 필수항목</span>
								</label>
							</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">초대메시지</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="gi_message" maxlength="150">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">가입제한일</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" name="gi_datetime_end_1" maxlength="4">년
							<input type="text" placeholder="입력" name="gi_datetime_end_2" maxlength="2">월
							<input type="text" placeholder="입력" name="gi_datetime_end_3" maxlength="2">일
						</div>
					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								초대
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/group_invite_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
		if (gi_form.gi_name_check.checked == true && gi_form.gi_name.value == ""){
			alert("이름을 입력하여 주십시오.");
			gi_form.gi_name.focus();
			return;
		}
		if (gi_form.gi_mail_check.checked == true && gi_form.gi_mail.value == ""){
			alert("이메일을 입력하여 주십시오.");
			gi_form.gi_mail.focus();
			return;
		}
		if (gi_form.gi_mail.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("잘못된 이메일 주소입니다.");
			gi_form.gi_mail.focus();
			return;
		}
		if (gi_form.gi_tel_check.checked == true && gi_form.gi_tel_2.value == ""){
			alert("연락처를 입력하여 주십시오.");
			gi_form.gi_tel_2.focus();
			return;
		}
		if (gi_form.gi_tel_check.checked == true && gi_form.gi_tel_3.value == ""){
			alert("연락처를 입력하여 주십시오.");
			gi_form.gi_tel_3.focus();
			return;
		}
		if (gi_form.gi_message.value == ""){
			alert("초대메시지를 입력하여 주십시오.");
			gi_form.gi_message.focus();
			return;
		}
		if (gi_form.gi_datetime_end_1.value == ""){
			alert("가입제한일을 입력하여 주십시오.");
			gi_form.gi_datetime_end_1.focus();
			return;
		}
		if (gi_form.gi_datetime_end_2.value == ""){
			alert("가입제한일을 입력하여 주십시오.");
			gi_form.gi_datetime_end_2.focus();
			return;
		}
		if (gi_form.gi_datetime_end_3.value == ""){
			alert("가입제한일을 입력하여 주십시오.");
			gi_form.gi_datetime_end_3.focus();
			return;
		}
		gi_form.submit();
	}
</script>
 
<?php
include_once("_tail.php");
?>



