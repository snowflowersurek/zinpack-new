<?php
include_once("_common.php");
if ($iw[type] != "shop" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$ss_no = $_GET["idx"];

$sql = "select * from $iw[shop_seller_table] where ep_code = '$iw[store]' and ss_no = '$ss_no'";
$row = sql_fetch($sql);
if (!$row["ss_no"]) alert("잘못된 접근입니다!","");

$mb_code = $row["mb_code"];
$ss_name = $row["ss_name"];
$ss_tel = $row["ss_tel"];
$ss_zip_code = $row["ss_zip_code"];
$ss_address = $row["ss_address"];
$ss_address_sub = $row["ss_address_sub"];
$ss_content = stripslashes($row["ss_content"]);
$ss_datetime = $row["ss_datetime"];

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert("잘못된 접근입니다!","");

$mb_mail = $row["mb_mail"];
$mb_name = $row["mb_name"];
$mb_nick = $row["mb_nick"];
$mb_tel = $row["mb_tel"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-shopping-cart"></i>
			쇼핑몰
		</li>
		<li class="active">전체 판매자</li>
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
			전체 판매자
			<small>
				<i class="fa fa-angle-double-right"></i>
				판매자 정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">판매자(상호명)</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ss_name?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">전화번호</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ss_tel?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">주소</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">[<?=$row["mb_zip_code"]?>] <?=$row["mb_address"]?> <?=$row["mb_address_sub"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">소개</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ss_content?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ss_datetime?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">회원코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_code?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">회원아이디</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_mail?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">회원이름</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_name?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">회원닉네임</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_nick?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">연락처</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_tel?></p>
						</div>
					</div>
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/shop_seller_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
							<i class="fa fa-undo"></i>
							목록
						</button>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<?php
include_once("_tail.php");
?>



