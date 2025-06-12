<?php
include_once("_common.php");
include_once("_head.php");

$sql = "select * from $iw[master_table] where ma_no = 1";
$row = sql_fetch($sql);
if (!$row["ma_no"]) alert("마스터키를 설정하여 주십시오.","");

if (!$_GET['mep']) exit;
$ep_code = $_GET['mep'];
if (!$_GET['mgp']) exit;
$gp_code = $_GET['mgp'];
if (!$_GET['mmb']) exit;
$mb_code = $_GET['mmb'];

$row = sql_fetch("select ep_corporate from $iw[enterprise_table] where ep_code = '$ep_code'");
$ep_corporate = $row["ep_corporate"];

$row = sql_fetch("select gp_subject from $iw[group_table] where ep_code = '$ep_code' and gp_code = '$gp_code'");
$gp_subject = $row["gp_subject"];

$row = sql_fetch("select * from $iw[member_table] where mb_code = '$mb_code'");
$mb_mail = $row["mb_mail"];
$mb_point = $row["mb_point"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">마스터키</li>
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
			마스터키
			<small>
				<i class="fa fa-angle-double-right"></i>
				포인트 설정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ma_form" name="ma_form" action="<?=$iw['super_path']?>/master_point_ok.php?ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
					<input type="hidden" name="mb_code" value="<?=$mb_code?>">
					<input type="hidden" name="ep_code" value="<?=$ep_code?>">
					<input type="hidden" name="gp_code" value="<?=$gp_code?>">
					<div class="form-group">
						<label class="col-sm-1 control-label">사이트명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ep_corporate?></p>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">그룹명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$gp_subject?></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">회원ID</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_mail?></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">현재 포인트</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_point?></p>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">변경 포인트</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-3" name="change_point">
						</div>
					</div>
					<div class="space-4"></div>


					<div class="form-group">
						<label class="col-sm-1 control-label">아이디</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ma_userid">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">비밀번호</label>
						<div class="col-sm-11">
							<input type="password" placeholder="입력" class="col-xs-12 col-sm-8" name="ma_password" onkeydown="javascript: if (event.keyCode == 13) {check_form();}">
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								로그인
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
		var e1;
		var num ="0123456789";
		event.returnValue = true;
		if (ma_form.change_point.value == "") {
			alert('변경 포인트를 입력하여 주십시오.');
			ma_form.change_point.focus();
			return;
		}
		e1 = ma_form.change_point;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			ma_form.change_point.focus();
			return;
		}

		if (ma_form.ma_userid.value == ""){
			alert("아이디를 입력하여 주십시오.");
			ma_form.ma_userid.focus();
			return;
		}
		if (ma_form.ma_password.value == ""){
			alert("비밀번호를 입력하여 주십시오.");
			ma_form.ma_password.focus();
			return;
		}
		ma_form.submit();
	}

</script>
 
<?
include_once("_tail.php");
?>