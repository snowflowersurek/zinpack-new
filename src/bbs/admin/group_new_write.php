<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]' ");
$ep_nick = $row["ep_nick"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">신규그룹 신청</li>
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
			신규그룹 신청
			<small>
				<i class="fa fa-angle-double-right"></i>
				작성
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="gp_form" name="gp_form" action="<?=$iw['admin_path']?>/group_new_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="gp_type" value="1" />
					<div class="form-group">
						<label class="col-sm-1 control-label">포워딩ID</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">www.info-way.co.kr/shop/<?=$ep_nick?>/<input type="text" placeholder="입력" name="gp_nick" maxlength="30"></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">언어설정</label>
						<div class="col-sm-11">
							<select name="gp_language">
								<option value="ko">한국어</option>
								<option value="en">영어</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹이름</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="gp_subject" maxlength="100">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹설명</label>
						<div class="col-sm-11">
							<textarea name="gp_content" class="col-xs-12 col-sm-8" style="height:300px;"></textarea>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹가입방식</label>
						<div class="col-sm-11">
							<div class="radio">
								<label>
									<input type="radio" name="type" value="0" onclick="javascript:radio_check(this.value);">
									<span class="lbl"> 가입불가</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="type" value="1" onclick="javascript:radio_check(this.value);" checked>
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
									<span class="lbl"> 가입코드 입력후 자동승인</span> <input type="text" placeholder="입력" name="gp_autocode" maxlength="20">
								</label>
							</div>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹 공개</label>
						<div class="col-sm-11">
							<select class="col-xs-12 col-sm-2" name="gp_closed">
								<option value="0" selected>비공개 (그룹회원만 열람)</option>
								<option value="1">공개 (비회원도 열람 가능)</option>
							</select>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								신청
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/group_all_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
								<i class="fa fa-check"></i>
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
		if (gp_form.gp_nick.value.length < 4){
			alert("포워딩ID를 4글자 이상 입력하십시오.");
			gp_form.gp_nick.focus();
			return;
		}
		if (gp_form.gp_nick.value == "all"){
			alert("사용할 수 없는 포워딩ID입니다.");
			gp_form.gp_nick.focus();
			return;
		}
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