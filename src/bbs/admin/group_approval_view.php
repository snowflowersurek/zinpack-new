<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$gp_no = $_GET["idx"];

$sql = "select * from $iw[group_table] where ep_code = '$iw[store]' and gp_display = 0 and gp_no = '$gp_no'";
$row = sql_fetch($sql);
if (!$row["gp_no"]) alert("잘못된 접근입니다!!","");

$gp_code = $row["gp_code"];
$mb_code = $row["mb_code"];
$gp_nick = $row["gp_nick"];
$gp_subject = $row["gp_subject"];
$gp_datetime = $row["gp_datetime"];
$gp_content = stripslashes($row["gp_content"]);

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert("잘못된 접근입니다!!!","");

$mb_mail = $row["mb_mail"];
$mb_name = $row["mb_name"];
$mb_nick = $row["mb_nick"];
$mb_tel = $row["mb_tel"];
$mb_zip_code = $row["mb_zip_code"];
$mb_address = $row["mb_address"];
$mb_address_sub = $row["mb_address_sub"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">신규그룹 승인</li>
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
			신규그룹 승인
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
						<label class="col-sm-1 control-label">그룹코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$gp_code?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">포워딩주소</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$gp_nick?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹이름</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$gp_subject?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹설명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$gp_content?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$gp_datetime?></p>
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
						<button class="btn btn-primary" type="button" onclick="javascript:approval_edit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$gp_no?>','1');">
							<i class="fa fa-undo"></i>
							승인
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/group_approval_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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

<script type="text/javascript">
	function approval_edit(type,ep,gp,no,dis) {
		if (confirm("신규그룹을 승인하시겠습니까?")) {
			location.href="group_approval_ok.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+no+"&dis="+dis;
		}
	}
</script>
<?
include_once("_tail.php");
?>