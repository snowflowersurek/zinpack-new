<?php
include_once("_common.php");
include_once("_head.php");

$mb_code = $_GET["idx"];

$sql = "select * from $iw[member_table] where mb_code = '$mb_code'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert("잘못된 접근입니다!","");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">회원정보</li>
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
			회원정보
			<small>
				<i class="fa fa-angle-double-right"></i>
				상세
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">업체코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["ep_code"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">회원코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_code"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">아이디</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_mail"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이름</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_name"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">닉네임</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_nick"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">휴대폰번호</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_tel"]?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">주소</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">[<?=$row["mb_zip_code"]?>] <?=$row["mb_address"]?> <?=$row["mb_address_sub"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">보조이메일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_sub_mail"]?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">포인트</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_point"]?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">가입IP</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_ip"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">가입일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["mb_datetime"]?></p>
						</div>
					</div>
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-primary" type="button" onclick="location='<?=$iw['super_path']?>/member_data_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$mb_code?>'">
							<i class="fa fa-undo"></i>
							수정
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['super_path']?>/member_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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

<?
include_once("_tail.php");
?>