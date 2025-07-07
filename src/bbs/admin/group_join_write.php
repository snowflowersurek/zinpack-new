<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] == "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$gp_no = $_GET["idx"];

$row = sql_fetch(" select * from $iw[enterprise_table] where ep_code = '$iw[store]' ");
$ep_nick = $row["ep_nick"];

$sql = "select * from $iw[group_table] where ep_code = '$iw[store]' and gp_no = '$gp_no'";
$row = sql_fetch($sql);
if (!$row["gp_no"]) alert("잘못된 접근입니다!","");
$gp_nick = $row["gp_nick"];
$gp_subject = $row["gp_subject"];
$gp_content = $row["gp_content"];
$gp_type = $row["gp_type"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">그룹 가입</li>
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
			그룹 가입
			<small>
				<i class="fa fa-angle-double-right"></i>
				신청
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="gp_form" name="gp_form" action="<?=$iw['admin_path']?>/group_join_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="gp_no" value="<?=$gp_no?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">그룹이름</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$gp_subject?></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹설명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$gp_content?></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그룹URL</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><a href="<?=$iw['path']?>/main/<?=$ep_nick?>/<?=$gp_nick?>" target="_blank"><?=$iw['url']?>/mcb/<?=$ep_nick?>/<?=$gp_nick?></a></p>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">가입방식</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?php if($gp_type == 0){?>
									가입 불가
								<?php }else if($gp_type == 1){?>
									가입 신청 > 관리자 승인
								<?php }else if($gp_type == 2){?>
									무조건 가입 > 자동 승인
								<?php }else if($gp_type == 4){?>
									초대후 가입 > 자동 승인
								<?php }else if($gp_type == 5){?>
									가입코드 입력 > 자동 승인
								<?php }?>
							</p>
						</div>
					</div>
					<div class="space-4"></div>
					
					<?php if($gp_type == 5){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">가입코드</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="gp_autocode" maxlength="100">
						</div>
					</div>
					<?php }?>
					
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
						<?php if($gp_type != 0){?>
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								가입하기
							</button>
						<?php }?>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/group_all_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
		gp_form.submit();
	}
</script>

<?php
include_once("_tail.php");
?>



