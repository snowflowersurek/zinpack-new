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
		<li class="active">메뉴 및 레이아웃 설정</li>
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
			메뉴 및 레이아웃 설정
			<small>
				<i class="fa fa-angle-double-right"></i>
				 목록
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<div class="row">
					<div class="col-xs-12 col-sm-5">
						<button class='btn btn-primary' type='button' onclick="javascript:hm_form.submit();"><i class="fa fa-check"></i> 저장</button>
						<button class='btn btn-success' type='button' onclick="javascript:categoryFrameNew('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','1','new');"><i class="fa fa-plus"></i> 추가</button>
						<div class="dd" id="sortable">
							<ol class="dd-list">
							<?
								$sql = "select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_deep = 1 order by hm_order asc,hm_no asc";
								$result = sql_query($sql);
								while($row = @sql_fetch_array($result)){
									$hm_no = $row["hm_no"];
									$hm_code = $row["hm_code"];
									$hm_name = stripslashes($row["hm_name"]);
									$hm_list_scrap = $row["hm_list_scrap"];
									$hm_view_scrap = $row["hm_view_scrap"];
									$state_sort = $row["state_sort"];
							?>
								<li class="dd-item dd2-item" data-id="<?=$hm_no?>">
									<div class="dd-handle dd2-handle">
										<i class="fa fa-arrows"></i>
									</div>
									<div class="dd2-content">
										<span>
											<?if($state_sort=="mcb"){?><i class="fa fa-file-text-o"></i><?}?>
											<?if($state_sort=="publishing"){?><i class="fa fa-book"></i><?}?>
											<?if($state_sort=="author"){?><i class="fa fa-id-card-o"></i><?}?>
											<?if($state_sort=="exhibit"){?><i class="fa fa-picture-o"></i><?}?>
											<?if($state_sort=="exhibit_monthly"){?><i class="fa fa-picture-o"></i><?}?>
											<?if($state_sort=="exhibit_application"){?><i class="fa fa-picture-o"></i><?}?>
											<?if($state_sort=="exhibit_status"){?><i class="fa fa-picture-o"></i><?}?>
											<?if($state_sort=="lecture_application"){?><i class="fa fa-bullhorn"></i><?}?>
											<?if($state_sort=="lecture_status"){?><i class="fa fa-bullhorn"></i><?}?>
											<?if($state_sort=="shop"){?><i class="fa fa-shopping-cart"></i><?}?>
											<?if($state_sort=="doc"){?><i class="fa fa-inbox"></i><?}?>
											<?if($state_sort=="book"){?><i class="fa fa-newspaper-o"></i><?}?>
											<?if($state_sort=="about"){?><i class="fa fa-window-maximize"></i><?}?>
											<?if($state_sort=="open"){?><i class="fa fa-list-alt"></i><?}?>
											<?if($state_sort=="close"){?><i class="fa fa-level-down"></i><?}?>
											<?if($state_sort=="link"){?><i class="fa fa-link"></i><?}?>
											<?if($state_sort=="scrap"){?><i class="fa fa-bookmark-o"></i><?}?>
											<?if($state_sort=="main"){?><i class="fa fa-home"></i><?}?>
											<?=$hm_name?>
										</span>
										<div class="pull-right action-buttons">
											<?if($hm_list_scrap==1){?>
											<a class="grey" href="javascript:scrapListEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
												<i class="fa fa-folder-open-o"></i>
											</a>
											<?}?>
											<?if($hm_view_scrap==1){?>
											<a class="grey" href="javascript:scrapViewEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
												<i class="fa fa-file-o"></i>
											</a>
											<?}?>
											<a class="blue" href="javascript:categoryFrameEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
												<i class="fa fa-cog"></i>
											</a>
											<a class="red" href="javascript:category_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>','<?=preg_replace("/[^A-Za-z90-9가-힣]/i","",$hm_name)?>');">
												<i class="fa fa-trash-o"></i>
											</a>
										</div>
									</div>
								<?
									$sql2 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 2 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
									$result2 = sql_query($sql2);
									$middle_num = 0;
									while($row2 = @sql_fetch_array($result2)){
										$hm_no = $row2["hm_no"];
										$hm_code = $row2["hm_code"];
										$hm_name = stripslashes($row2["hm_name"]);
										$hm_list_scrap = $row2["hm_list_scrap"];
										$hm_view_scrap = $row2["hm_view_scrap"];
										$state_sort = $row2["state_sort"];
								?>
								<?if($middle_num==0){?><ol class="dd-list"><?}?>
									<li class="dd-item dd2-item" data-id="<?=$hm_no?>">
										<div class="dd-handle dd2-handle">
											<i class="fa fa-arrows"></i>
										</div>
										<div class="dd2-content">
											<span>
												<?if($state_sort=="mcb"){?><i class="fa fa-file-text-o"></i><?}?>
												<?if($state_sort=="publishing"){?><i class="fa fa-book"></i><?}?>
												<?if($state_sort=="publishing_brand"){?><i class="fa fa-book"></i><?}?>
												<?if($state_sort=="author"){?><i class="fa fa-id-card-o"></i><?}?>
												<?if($state_sort=="exhibit"){?><i class="fa fa-picture-o"></i><?}?>
												<?if($state_sort=="exhibit_monthly"){?><i class="fa fa-picture-o"></i><?}?>
												<?if($state_sort=="exhibit_application"){?><i class="fa fa-picture-o"></i><?}?>
												<?if($state_sort=="exhibit_status"){?><i class="fa fa-picture-o"></i><?}?>
												<?if($state_sort=="lecture_application"){?><i class="fa fa-bullhorn"></i><?}?>
												<?if($state_sort=="lecture_status"){?><i class="fa fa-bullhorn"></i><?}?>
												<?if($state_sort=="publishing_contest"){?><i class="fa fa-edit"></i><?}?>
												<?if($state_sort=="shop"){?><i class="fa fa-shopping-cart"></i><?}?>
												<?if($state_sort=="doc"){?><i class="fa fa-inbox"></i><?}?>
												<?if($state_sort=="book"){?><i class="fa fa-newspaper-o"></i><?}?>
												<?if($state_sort=="about"){?><i class="fa fa-window-maximize"></i><?}?>
												<?if($state_sort=="open"){?><i class="fa fa-list-alt"></i><?}?>
												<?if($state_sort=="close"){?><i class="fa fa-level-down"></i><?}?>
												<?if($state_sort=="link"){?><i class="fa fa-link"></i><?}?>
												<?if($state_sort=="scrap"){?><i class="fa fa-bookmark-o"></i><?}?>
												<?if($state_sort=="main"){?><i class="fa fa-home"></i><?}?>
												<?=$hm_name?>
											</span>
											<div class="pull-right action-buttons">
												<?if($hm_list_scrap==1){?>
												<a class="grey" href="javascript:scrapListEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
													<i class="fa fa-folder-open-o"></i>
												</a>
												<?}?>
												<?if($hm_view_scrap==1){?>
												<a class="grey" href="javascript:scrapViewEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
													<i class="fa fa-file-o"></i>
												</a>
												<?}?>
												<a class="blue" href="javascript:categoryFrameEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
													<i class="fa fa-cog"></i>
												</a>
												<a class="red" href="javascript:category_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>','<?=preg_replace("/[^A-Za-z90-9가-힣]/i","",$hm_name)?>');">
													<i class="fa fa-trash-o"></i>
												</a>
											</div>
										</div>
									<?
										$sql3 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 3 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
										$result3 = sql_query($sql3);
										$small_num = 0;
										while($row3 = @sql_fetch_array($result3)){
											$hm_no = $row3["hm_no"];
											$hm_code = $row3["hm_code"];
											$hm_name = stripslashes($row3["hm_name"]);
											$hm_list_scrap = $row3["hm_list_scrap"];
											$hm_view_scrap = $row3["hm_view_scrap"];
											$state_sort = $row3["state_sort"];
									?>
									<?if($small_num==0){?><ol class="dd-list"><?}?>
										<li class="dd-item dd2-item" data-id="<?=$hm_no?>">
											<div class="dd-handle dd2-handle">
												<i class="fa fa-arrows"></i>
											</div>
											<div class="dd2-content">
												<span>
													<?if($state_sort=="mcb"){?><i class="fa fa-file-text-o"></i><?}?>
													<?if($state_sort=="publishing"){?><i class="fa fa-book"></i><?}?>
													<?if($state_sort=="publishing_brand"){?><i class="fa fa-book"></i><?}?>
													<?if($state_sort=="author"){?><i class="fa fa-id-card-o"></i><?}?>
													<?if($state_sort=="exhibit"){?><i class="fa fa-picture-o"></i><?}?>
													<?if($state_sort=="exhibit_monthly"){?><i class="fa fa-picture-o"></i><?}?>
													<?if($state_sort=="exhibit_application"){?><i class="fa fa-picture-o"></i><?}?>
													<?if($state_sort=="exhibit_status"){?><i class="fa fa-picture-o"></i><?}?>
													<?if($state_sort=="lecture_application"){?><i class="fa fa-bullhorn"></i><?}?>
													<?if($state_sort=="lecture_status"){?><i class="fa fa-bullhorn"></i><?}?>
													<?if($state_sort=="publishing_contest"){?><i class="fa fa-edit"></i><?}?>
													<?if($state_sort=="shop"){?><i class="fa fa-shopping-cart"></i><?}?>
													<?if($state_sort=="doc"){?><i class="fa fa-inbox"></i><?}?>
													<?if($state_sort=="book"){?><i class="fa fa-newspaper-o"></i><?}?>
													<?if($state_sort=="about"){?><i class="fa fa-window-maximize"></i><?}?>
													<?if($state_sort=="open"){?><i class="fa fa-list-alt"></i><?}?>
													<?if($state_sort=="close"){?><i class="fa fa-level-down"></i><?}?>
													<?if($state_sort=="link"){?><i class="fa fa-link"></i><?}?>
													<?if($state_sort=="scrap"){?><i class="fa fa-bookmark-o"></i><?}?>
													<?if($state_sort=="main"){?><i class="fa fa-home"></i><?}?>
													<?=$hm_name?>
												</span>
												<div class="pull-right action-buttons">
													<?if($hm_list_scrap==1){?>
													<a class="grey" href="javascript:scrapListEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
														<i class="fa fa-folder-open-o"></i>
													</a>
													<?}?>
													<?if($hm_view_scrap==1){?>
													<a class="grey" href="javascript:scrapViewEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
														<i class="fa fa-file-o"></i>
													</a>
													<?}?>
													<a class="blue" href="javascript:categoryFrameEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
														<i class="fa fa-cog"></i>
													</a>
													<a class="red" href="javascript:category_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>','<?=preg_replace("/[^A-Za-z90-9가-힣]/i","",$hm_name)?>');">
														<i class="fa fa-trash-o"></i>
													</a>
												</div>
											</div>
										<?

											$sql4 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 4 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
											$result4 = sql_query($sql4);
											$tint_num = 0;
											while($row4 = @sql_fetch_array($result4)){
												$hm_no = $row4["hm_no"];
												$hm_code = $row4["hm_code"];
												$hm_name = stripslashes($row4["hm_name"]);
												$hm_list_scrap = $row4["hm_list_scrap"];
												$hm_view_scrap = $row4["hm_view_scrap"];
												$state_sort = $row4["state_sort"];
										?>
										<?if($tint_num==0){?><ol class="dd-list"><?}?>
											<li class="dd-item dd2-item" data-id="<?=$hm_no?>">
												<div class="dd-handle dd2-handle">
													<i class="fa fa-arrows"></i>
												</div>
												<div class="dd2-content">
													<span>
														<?if($state_sort=="mcb"){?><i class="fa fa-file-text-o"></i><?}?>
														<?if($state_sort=="publishing"){?><i class="fa fa-book"></i><?}?>
														<?if($state_sort=="publishing_brand"){?><i class="fa fa-book"></i><?}?>
														<?if($state_sort=="author"){?><i class="fa fa-id-card-o"></i><?}?>
														<?if($state_sort=="exhibit"){?><i class="fa fa-picture-o"></i><?}?>
														<?if($state_sort=="exhibit_monthly"){?><i class="fa fa-picture-o"></i><?}?>
														<?if($state_sort=="exhibit_application"){?><i class="fa fa-picture-o"></i><?}?>
														<?if($state_sort=="exhibit_status"){?><i class="fa fa-picture-o"></i><?}?>
														<?if($state_sort=="lecture_application"){?><i class="fa fa-bullhorn"></i><?}?>
														<?if($state_sort=="lecture_status"){?><i class="fa fa-bullhorn"></i><?}?>
														<?if($state_sort=="publishing_contest"){?><i class="fa fa-edit"></i><?}?>
														<?if($state_sort=="shop"){?><i class="fa fa-shopping-cart"></i><?}?>
														<?if($state_sort=="doc"){?><i class="fa fa-inbox"></i><?}?>
														<?if($state_sort=="book"){?><i class="fa fa-newspaper-o"></i><?}?>
														<?if($state_sort=="about"){?><i class="fa fa-window-maximize"></i><?}?>
														<?if($state_sort=="open"){?><i class="fa fa-list-alt"></i><?}?>
														<?if($state_sort=="close"){?><i class="fa fa-level-down"></i><?}?>
														<?if($state_sort=="link"){?><i class="fa fa-link"></i><?}?>
														<?if($state_sort=="scrap"){?><i class="fa fa-bookmark-o"></i><?}?>
														<?if($state_sort=="main"){?><i class="fa fa-home"></i><?}?>
														<?=$hm_name?>
													</span>
													<div class="pull-right action-buttons">
														<?if($hm_list_scrap==1){?>
														<a class="grey" href="javascript:scrapListEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
															<i class="fa fa-folder-open-o"></i>
														</a>
														<?}?>
														<?if($hm_view_scrap==1){?>
														<a class="grey" href="javascript:scrapViewEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
															<i class="fa fa-file-o"></i>
														</a>
														<?}?>
														<a class="blue" href="javascript:categoryFrameEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');">
															<i class="fa fa-cog"></i>
														</a>
														<a class="red" href="javascript:category_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>','<?=preg_replace("/[^A-Za-z90-9가-힣]/i","",$hm_name)?>');">
															<i class="fa fa-trash-o"></i>
														</a>
													</div>
												</div>
											</li>
										<?
												$tint_num++;
											}
											if($tint_num!=0){?></ol><?}
											$small_num++;
										?></li><?
										}
										if($small_num!=0){?></ol><?}
										$middle_num++;
									?></li><?
									}
									if($middle_num!=0){?></ol><?}
								}
							?>
								</li>
							</ol>
						</div>
					</div>
					<form id="hm_form" name="hm_form" action="<?=$iw['admin_path']?>/design_menu_list_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post"><input type="hidden" id='menu_output' name='menu_output'></form>
					<div class="col-xs-12 col-sm-6">
						<iframe id="categoryFrame" name="categoryFrame" width="100%" height="850px" frameborder="0" scrolling="no" src="">
						</iframe>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div>
</div><!-- /end .page-content -->

<script language="javascript">
	function categoryFrameNew(type,ep,gp,menu){
		categoryFrame.location.href="design_menu_write.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu;
	}
	function categoryFrameEdit(type,ep,gp,menu){
		categoryFrame.location.href="design_menu_edit.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu;
	}

	function scrapListEdit(type,ep,gp,menu){
		location.href="design_scrap_list.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu+"&scrap=list";
	}
	function scrapViewEdit(type,ep,gp,menu){
		location.href="design_scrap_list.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu+"&scrap=view";
	}

	function category_delete(type,ep,gp,menu,name_cg) { 
		if (confirm(name_cg+'를 삭제하시겠습니까?')) {
			location.href="design_menu_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu;
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

<?
include_once("_tail.php");
?>