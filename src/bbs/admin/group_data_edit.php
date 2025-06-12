<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[gp_level] != "gp_admin" || $iw[group] == "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]' ");
$ep_nick = $row["ep_nick"];

$sql = "select * from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["gp_no"]) alert("잘못된 접근입니다!","");
$gp_type = $row["gp_type"];
$gp_closed = $row["gp_closed"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">그룹 정보</li>
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
			그룹 정보
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
				<form class="form-horizontal" id="gp_form" name="gp_form" action="<?=$iw['admin_path']?>/group_data_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="gp_type" value="<?=$gp_type?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">포워딩ID</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$iw['url']?>/main/<?=$ep_nick?>/<b><?=$row[gp_nick]?></b></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">언어설정</label>
						<div class="col-sm-11">
							<select name="gp_language">
								<option value="ko" <?if($row[gp_language] == "ko"){?>selected<?}?>>한국어</option>
								<option value="en" <?if($row[gp_language] == "en"){?>selected<?}?>>영어</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹이름</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="gp_subject" maxlength="100" value="<?=$row[gp_subject]?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹설명</label>
						<div class="col-sm-11">
							<textarea name="gp_content" class="col-xs-12 col-sm-8" style="height:300px;"><?=$row[gp_content]?></textarea>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹가입방식</label>
						<div class="col-sm-11">
							<div class="radio">
								<label>
									<input type="radio" name="type" value="0" onclick="javascript:radio_check(this.value);" <?if($gp_type==0){?>checked<?}?>>
									<span class="lbl"> 가입불가</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="1" onclick="javascript:radio_check(this.value);" <?if($gp_type==1){?>checked<?}?>>
									<span class="lbl"> 가입신청 > 관리자승인</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="2" onclick="javascript:radio_check(this.value);" <?if($gp_type==2){?>checked<?}?>>
									<span class="lbl"> 무조건 가입</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="4" onclick="javascript:radio_check(this.value);" <?if($gp_type==4){?>checked<?}?>>
									<span class="lbl"> 초대후 가입</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="5" onclick="javascript:radio_check(this.value);" <?if($gp_type==5){?>checked<?}?>>
									<span class="lbl"> 가입코드 입력후 자동승인</span> <input type="text" placeholder="입력" name="gp_autocode" maxlength="20" value="<?=$row[gp_autocode]?>">
								</label>
							</div>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹 공개</label>
						<div class="col-sm-11">
							<select class="col-xs-12 col-sm-2" name="gp_closed">
								<option value="0" <?if($gp_closed==0){?>selected<?}?>>비공개 (그룹회원만 열람)</option>
								<option value="1" <?if($gp_closed==1){?>selected<?}?>>공개 (비회원도 열람 가능)</option>
							</select>
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
		if (gp_form.gp_subject.value == ""){
			alert("그룹이름을 입력하여 주십시오.");
			gp_form.gp_subject.focus();
			return;
		}
		if (gp_form.gp_content.value == ""){
			alert("그룹설명을 입력하여 주십시오.");
			gp_form.gp_content.focus();
			return;
		}
		if (gp_form.gp_type.value == "5" && gp_form.gp_autocode.value == ""){
			alert("가입코드를 입력하여 주십시오.");
			gp_form.gp_autocode.focus();
			return;
		}
		gp_form.submit();
	}
	function radio_check(chk) {
		gp_form.gp_type.value = chk;
	}
</script>
 
<?
include_once("_tail.php");
?>