<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<style>
	.dd2-handle {display:block;position: relative;float: left;}
	.dd2-content span {padding-left:10px;}
</style>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-object-group"></i>
			디자인 설정
		</li>
		<li class="active">
		<?php
			$hs_scrap = $_GET[scrap];
			$hm_code = $_GET[menu];
			if ($hs_scrap=="list" || $hs_scrap == "view"){
				echo "스크래퍼 설정";
				$row = sql_fetch(" select hm_name from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_code'");
				$hm_name = $row[hm_name];
			}else{
				echo "메인화면 설정";
				$hm_name = "목록";
			}
		?>
		</li>
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
			<?php
				if ($hs_scrap=="list"){
					echo "목록 스크래퍼 설정";
				}else if($hs_scrap == "view"){
					echo "본문 스크래퍼 설정";
				}else if($hs_scrap == "main"){
					echo "메인화면 설정";
				}
			?>
			<small>
				<i class="fa fa-angle-double-right"></i>
				<?=$hm_name?>
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<div class="row">
					<div class="col-xs-12 col-sm-5">
						<button class='btn btn-danger' type='button' onclick="location.href='<?=$iw[admin_path]?>/design_menu_list.php?type=design&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'"><i class="fa fa-reply"></i> 목록</button>
						<button class='btn btn-primary' type='button' onclick="javascript:hs_form.submit();"><i class="fa fa-check"></i> 저장</button>
						<button class='btn btn-success' type='button' onclick="javascript:categoryFrameNew('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>','<?=$hs_scrap?>');"><i class="fa fa-plus"></i> 추가</button>
						<div class="dd" id="sortable">
							<ol class="dd-list">
							<?php
								$sql = "select * from $iw[home_scrap_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hs_scrap = '$hs_scrap' and hm_code = '$hm_code' order by hs_order asc,hs_no asc";
								$result = sql_query($sql);
								while($row = @sql_fetch_array($result)){
									$hs_no = $row["hs_no"];
									$hs_name = stripslashes($row["hs_name"]);
									$hs_style = $row["hs_style"];

							?>
								<li class="dd-item dd2-item" data-id="<?=$hs_no?>">
									<div class="dd-handle dd2-handle">
										<i class="fa fa-arrows"></i>
									</div>
									<div class="dd2-content">
										<span>
											<?php if($hs_style==1){?><i class="fa fa-columns"></i><?php } ?><?php if($hs_style==2){?><i class="fa fa-picture-o"></i><?php } ?><?php if($hs_style==3){?><i class="fa fa-list-ul"></i><?php } ?><?php if($hs_style==5){?><i class="fa fa-reply"></i><?php } ?><?php if($hs_style==6){?><i class="fa fa-facebook-square"></i><?php }?>
											<?=$hs_name?>
										</span>
										<div class="pull-right action-buttons">
											<a class="blue" href="javascript:categoryFrameEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hs_no?>');">
												<i class="fa fa-cog"></i>
											</a>
											<a class="red" href="javascript:category_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hs_no?>','<?=preg_replace("/[^A-Za-z90-9가-힣]/i","",$hs_name)?>');">
												<i class="fa fa-trash-o"></i>
											</a>
										</div>
									</div>
								</li>
							<?php
								}
							?>
							</ol>
						</div>
					</div>
					<form id="hs_form" name="hs_form" action="<?=$iw['admin_path']?>/design_scrap_list_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$hm_code?>&scrap=<?=$hs_scrap?>" method="post"><input type="hidden" id='menu_output' name='menu_output'></form>
					<div class="col-xs-12 col-sm-6">
						<iframe name="bannerFrame" id="bannerFrame" width="100%" height="650px" frameborder="0" scrolling="no" src="">
						</iframe>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div>
</div><!-- /end .page-content -->

<script language="javascript">
	function categoryFrameNew(type,ep,gp,menu,scrap){
		document.getElementById('bannerFrame').height = 1500;
		bannerFrame.location.href="design_scrap_write.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu+"&scrap="+scrap;
	}
	function categoryFrameEdit(type,ep,gp,menu){
		document.getElementById('bannerFrame').height = 1500;
		bannerFrame.location.href="design_scrap_edit.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu;
	}

	function category_delete(type,ep,gp,menu,name_cg) { 
		if (confirm(name_cg+' 을(를) 삭제하시겠습니까?')) {
			location.href="design_scrap_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu;
		}
	}

	$(document).ready(function()
	{
		var updateOutput = function(e)
		{
			var list   = e.length ? e : $(e.target),
				output = list.data('output');
			if (window.JSON) {
				output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
			} else {
				output.val('JSON browser support required for this demo.');
			}
		};

		// activate Nestable for list 1
		$('#sortable').nestable({
			group: 1
		})
		.on('change', updateOutput);
		// output initial serialised data
		updateOutput($('#sortable').data('output', $('#menu_output')));
	});
</script>

<?php
include_once("_tail.php");
?>



