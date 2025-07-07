<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$nt_no = $_GET["idx"];

$sql = "select * from $iw[notice_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and nt_display = 1 and nt_no = '$nt_no'";
$row = sql_fetch($sql);
if (!$row["nt_no"]) alert("잘못된 접근입니다!","");

$nt_content = stripslashes($row["nt_content"]);
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-bullhorn"></i>
			공지사항
		</li>
		<li class="active">공지사항</li>
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
			공지사항
			<small>
				<i class="fa fa-angle-double-right"></i>
				본문
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=stripslashes($row["nt_subject"])?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">등록일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=date("Y.m.d", strtotime($row["nt_datetime"]))?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">조회수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["nt_hit"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">내용</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><div class="html_edit_wrap" style="max-width:1000px;"><?=$nt_content?></div></p>
						</div>
					</div>
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" onclick="location='<?=$iw['admin_path']?>/home_notice_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$nt_no?>'">
							<i class="fa fa-check"></i>
							수정
						</button>
						<button class="btn btn-danger" type="button" onclick="javascript:notice_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$nt_no?>');">
							<i class="fa fa-check"></i>
							삭제
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/home_notice_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
	function notice_delete(type,ep,gp,idx) { 
		if (confirm('정말 삭제하시겠습니까?')) {
			location.href="home_notice_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
		}
	}
</script>

<?php
include_once("_tail.php");
?>



