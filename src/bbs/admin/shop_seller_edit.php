<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$sql = "select * from $iw[shop_seller_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if ($row["ss_no"]) $ss_confirm = "true";
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-shopping-cart"></i>
			쇼핑몰
		</li>
		<li class="active">판매자정보</li>
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
			판매자정보
			<small>
				<i class="fa fa-angle-double-right"></i>
				관리
			</small>
		</h1>
	</div>
	
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="ss_form" name="ss_form" action="<?=$iw['admin_path']?>/shop_seller_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="ss_no" value="<?=$row['ss_no']?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">판매자(상호명)</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-5" name="ss_name" maxlength="100" value="<?=$row['ss_name']?>">
							<span class="help-block col-xs-12">쇼핑몰에서 판매자정보로 노출되는 정보입니다. 정확하게 입력하여 주시기 바랍니다.</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">전화번호</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="ss_tel" maxlength="50" value="<?=$row['ss_tel']?>">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">우편번호</label>
						<div class="col-sm-11">
							<input type="text" name="ss_zip_code" placeholder="우편번호" value="<?=$row['ss_zip_code']?>" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">주소</label>
						<div class="col-sm-11">
							<input type="text" placeholder="주소" class="col-xs-12 col-sm-8" name="ss_address" value="<?=$row['ss_address']?>">
							<input type="text" placeholder="상세주소" class="col-xs-12 col-sm-8" name="ss_address_sub" value="<?=$row['ss_address_sub']?>">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">설명</label>
						<div class="col-sm-11">
							<textarea name="ss_content" class="col-xs-12 col-sm-8" style="height:100px;"><?=$row['ss_content']?></textarea>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								수정하기
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
		if (ss_form.ss_name.value == ""){
			alert("판매자(상호명)을 입력하여 주십시오.");
			ss_form.ss_name.focus();
			return;
		}
		if (ss_form.ss_tel.value == ""){
			alert("전화번호를 입력하여 주십시오.");
			ss_form.ss_tel.focus();
			return;
		}
		if (ss_form.ss_zip_code.value == ""){
			alert("우편번호를 입력하여 주십시오.");
			ss_form.ss_zip_code.focus();
			return;
		}
		if (ss_form.ss_address.value == ""){
			alert("주소를 입력하여 주십시오.");
			ss_form.ss_address.focus();
			return;
		}
		if (ss_form.ss_address_sub.value == ""){
			alert("상세주소를 입력하여 주십시오.");
			ss_form.ss_address_sub.focus();
			return;
		}
		if (ss_form.ss_content.value == ""){
			alert("소개를 입력하여 주십시오.");
			ss_form.ss_content.focus();
			return;
		}
		ss_form.submit();
	}
</script>
 
<?
include_once("_tail.php");
?>