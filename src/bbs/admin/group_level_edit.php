<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">회원등급 설정</li>
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
			회원등급 설정
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
				<form class="form-horizontal" id="gl_form" name="gl_form" action="<?=$iw['admin_path']?>/group_level_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<?
					$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
					$result = sql_query($sql);
					while($row = @sql_fetch_array($result)){
				?>
					<input type="hidden" name="gl_no[]" value="<?=$row["gl_no"]?>"/>
					<input type="hidden" name="gl_level[]" value="<?=$row["gl_level"]?>"/>
					<div class="form-group">
						<label class="col-sm-1 control-label"><?=$row["gl_level"]?>등급</label>
						<div class="col-sm-11">
							<input type="text" placeholder="등급명" class="col-xs-12 col-sm-3" name="gl_name[]" value="<?=$row["gl_name"]?>">
							<input type="text" placeholder="설명" class="col-xs-12 col-sm-5" name="gl_content[]" value="<?=$row["gl_content"]?>">
						</div>
					</div>
					<div class="space-4"></div>
				<?		
					}
				?>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장하기
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
		for(var a=0; a < 10; a++){
			if (document.getElementsByName('gl_name[]')[a].value == "") {
				alert('등급명을 입력하여 주십시오.');
				document.getElementsByName('gl_name[]')[a].focus();
				return;
			}
		}
		gl_form.submit();
	}
</script>
 
<?
include_once("_tail.php");
?>