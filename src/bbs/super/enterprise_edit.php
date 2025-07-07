<?php
include_once("_common.php");
include_once("_head.php");

if(!$_GET["idx"])exit;
$ep_no = $_GET["idx"];

$sql = "select * from $iw[enterprise_table] where ep_no = $ep_no";
$row = sql_fetch($sql);
if (!$row["ep_no"]) alert("잘못된 접근입니다!","");
$ep_jointype = $row["ep_jointype"];
$ep_language = $row["ep_language"];
$ep_anonymity = $row["ep_anonymity"];
$ep_copy_off = $row["ep_copy_off"];
$ep_expiry_date = ($row["ep_expiry_date"]=="0000-00-00")?"":$row["ep_expiry_date"];
$ep_charge = ($row["ep_charge"]=="0")?"":$row["ep_charge"];
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
				수정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ep_form" name="ep_form" action="<?=$iw['super_path']?>/enterprise_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="ep_jointype" value="<?=$ep_jointype?>" />
				<input type="hidden" name="ep_no" value="<?=$ep_no?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">포워딩ID</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["ep_nick"]?></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">업체명</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ep_corporate" value="<?=$row["ep_corporate"]?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사업자등록번호</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ep_permit_number" value="<?=$row["ep_permit_number"]?>">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">도메인</label>
						<div class="col-sm-11">
							www. <input type="text" placeholder="입력" name="ep_domain" value="<?=$row["ep_domain"]?>">
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
								<option value="ko" <?php if{?>selected<?php }?>>한글 / LG U+</option>
								<option value="en" <?php if{?>selected<?php }?>>영문 / PAYPAL & ALIPAY</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">몰타입</label>
						<div class="col-sm-11">
							<label class="middle">
								<input type="checkbox" name="ep_state_mcb" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 게시판</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="ep_state_publishing" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 출판도서</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="ep_state_doc" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 컨텐츠몰</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="ep_state_shop" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 쇼핑몰</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="ep_state_book" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 이북몰</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">정산비율</label>
						<div class="col-sm-11">
							위즈윈디지털 <input type="text" placeholder="입력" name="ep_point_super" value="<?=$row["ep_point_super"]?>"> %<br/>
							사이트 <input type="text" placeholder="입력" name="ep_point_site" value="<?=$row["ep_point_site"]?>"> %<br/>
							판매자 <input type="text" placeholder="입력" name="ep_point_seller" value="<?=$row["ep_point_seller"]?>"> %
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">복제 방지</label>
						<div class="col-sm-11">
							<div class="radio">
								<label>
									<input type="radio" name="ep_copy_off" value="0" <?php if{?>checked<?php }?>>
									<span class="lbl"> OFF</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="ep_copy_off" value="1" <?php if{?>checked<?php }?>>
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
									<input type="radio" name="ep_upload" value="0" <?php if{?>checked<?php }?>>
									<span class="lbl"> 불가능</span>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="ep_upload" value="1" <?php if{?>checked<?php }?>>
									<span class="lbl"> 가능</span>
									<input type="text" placeholder="입력" name="ep_upload_size" value="<?=$row["ep_upload_size"]?>"> MB
								</label>
							</div>
						</div>
					</div>
					<div class="space-4"></div>

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

					<div class="form-group">
						<label class="col-sm-1 control-label">진팩 CMS 사용</label>
						<div class="col-sm-11">
							
							만료일자 &nbsp;<input type="date" id="date" name="ep_expiry_date" max="2050-06-20" min="2023-01-01" value="<?=$ep_expiry_date?>"> <br/>
							사용료/년 <input type="number" placeholder="숫자 입력" name="ep_charge" value="<?=$ep_charge?>"> 원
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">관리자 이메일</label>
						<div class="col-sm-6">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="adm_email" value="<?=$row["admin_email"]?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								확인
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw["super_path"]?>/enterprise_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$ep_no?>'">
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
		ep_form.submit();
	}

	function radio_check(chk) {
		ep_form.ep_jointype.value = chk;
	}
</script>
 
<?php
include_once("_tail.php");
?>



