<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$ad_code = $_GET["idx"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/about/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$ad_code;

$sql = "select * from $iw[about_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and ad_code = '$ad_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["ad_no"]) alert("잘못된 접근입니다!","");

$content = stripslashes($row["ad_content"]);
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-object-group"></i>
			디자인 설정
		</li>
		<li class="active">독립페이지 관리</li>
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
			독립페이지 관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				내용
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["ad_code"]?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-1 control-label">네비게이션</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=stripslashes($row["ad_navigation"])?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=stripslashes($row["ad_subject"])?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-1 control-label">내용</label>
						<div class="col-sm-11">
							<p class="col-xs-12 form-control-static"><div class="html_edit_wrap" style="max-width:700px;"><?=$content?></div></p>
						</div>
					</div>
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-danger" type="button" onclick="javascript:board_delete('<?=$iw[type]?>', '<?=$iw[store]?>','<?=$iw[group]?>','<?=$ad_code?>');">
							<i class="fa fa-check"></i>
							삭제
						</button>
						<button class="btn btn-info" type="button" onclick="location='<?=$iw['admin_path']?>/about_data_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$ad_code?>'">
							<i class="fa fa-check"></i>
							수정
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/about_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
	function board_delete(type,ep,gp,idx) { 
		if (confirm("정말 삭제하시겠습니까?")) {
			location.href="about_data_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
		}
	}
</script>

<?
include_once("_tail.php");
?>