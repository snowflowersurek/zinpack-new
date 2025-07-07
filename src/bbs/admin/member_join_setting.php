<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$sql = "select * from $iw[enterprise_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["ep_no"]) alert("잘못된 접근입니다!","");
$ep_jointype = $row["ep_jointype"];
$ep_anonymity = $row["ep_anonymity"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">회원가입 설정</li>
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
			회원가입 설정
			<small>
				<i class="fa fa-angle-double-right"></i>
				관리
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ep_form" name="ep_form" action="<?=$iw['admin_path']?>/member_join_setting_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="ep_jointype" value="<?=$ep_jointype?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">노출설정</label>
						<div class="col-sm-11">
							<label class="middle">
								<input type="checkbox" name="ep_exposed" value="1" <?php if{?>checked<?php }?>>
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
									<input type="radio" name="type" value="0" onclick="javascript:radio_check(this.value);" <?php if{?>checked<?php }?>>
									<span class="lbl"> 가입불가</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="1" onclick="javascript:radio_check(this.value);" <?php if{?>checked<?php }?>>
									<span class="lbl"> 가입신청 > 관리자승인</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="2" onclick="javascript:radio_check(this.value);" <?php if{?>checked<?php }?>>
									<span class="lbl"> 무조건 가입</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="4" onclick="javascript:radio_check(this.value);" <?php if{?>checked<?php }?>>
									<span class="lbl"> 초대후 가입</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="5" onclick="javascript:radio_check(this.value);" <?php if{?>checked<?php }?>>
									<span class="lbl"> 가입코드 입력후 자동승인</span> <input type="text" placeholder="입력" name="ep_autocode" maxlength="20" value="<?=$row[ep_autocode]?>">
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
									<input type="radio" name="ep_anonymity" value="0" <?php if{?>checked<?php }?>>
									<span class="lbl"> 공개</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="ep_anonymity" value="1" <?php if{?>checked<?php }?>>
									<span class="lbl"> 비공개</span>
								</label>
							</div>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								수정
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
		if (ep_form.ep_jointype.value == "5" && ep_form.ep_autocode.value == ""){
			alert("가입코드를 입력하여 주십시오.");
			ep_form.ep_autocode.focus();
			return;
		}
		ep_form.submit();
	}
	function radio_check(chk) {
		ep_form.ep_jointype.value = chk;
	}
</script>
 
<?php
include_once("_tail.php");
?>



