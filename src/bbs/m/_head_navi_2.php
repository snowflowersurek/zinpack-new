<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
?>	
		<form id="top_search_form" action="<?=$iw[m_path]?>/main_search_list.php" method="get" onsubmit="return checkSearchForm(this);">
		<input type="hidden" name="type" value="main">
		<input type="hidden" name="ep" value="<?=$iw[store]?>">
		<input type="hidden" name="gp" value="<?=$iw[group]?>">
		<div id="navbar-region" class="region">
			<div id="main-navbar" class="navbar navbar-default <?php if{?>onclick="location.href='<?=$menu_html?>'"<?php }?>><?=$hm_name?></a>
							</li>
						<?php
						}else{
							if($state_sort=="main") $menu_html = "<a href='".$iw[m_path]."/main.php?type=main&ep=".$iw[store]."&gp=".$iw[group]."'>".$hm_name."</a>";
							if($state_sort=="scrap") $menu_html = "<a href='".$iw[m_path]."/main.php?type=scrap&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="open") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=open&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="close") $menu_html =  $hm_name;
							if($state_sort=="link") $menu_html = "<a href='".$hm_link."'>".$hm_name."</a>";
							if($state_sort=="mcb") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=mcb&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="publishing") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="publishing_brand") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing_brand&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="publishing_contest") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing_contest&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="author") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="exhibit") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if ($state_sort == "exhibit_monthly") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_status_month.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if ($state_sort == "exhibit_application") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_application.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if ($state_sort == "exhibit_status") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_status.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if ($state_sort == "lecture_application") $menu_html = "<a href='".$iw[m_path]."/publishing_lecture_application.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if ($state_sort == "lecture_status") $menu_html = "<a href='".$iw[m_path]."/publishing_lecture_status.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="shop") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=shop&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="doc") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=doc&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="book") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=book&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
							if($state_sort=="about") $menu_html = "<a href='".$iw[m_path]."/about_data_view.php?type=about&ep=".$iw[store]."&gp=".$iw[group]."&item=".$cg_code."'>".$hm_name."</a>";
						?>
							<li>
								<a href="#"><?=$hm_name?></a>
								<div class="cbp-hrsub">
									<div class="cbp-hrsub-inner">
										<div>
											<h3><?=$menu_html?> <i class='fa fa-chevron-right'></i></h3>
										</div>
							<?php
								$sql2 = "select hm_code,cg_code,hm_name,state_sort,hm_link from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 2 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
								$result2 = sql_query($sql2);
								$middle_num = 0;
								while($row2 = @sql_fetch_array($result2)){
									$hm_code = $row2["hm_code"];
									$cg_code = $row2["cg_code"];
									$hm_name = stripslashes($row2["hm_name"]);
									$state_sort = $row2["state_sort"];
									$hm_link = $row2["hm_link"];

									if($state_sort=="main") $menu_html = "<a href='".$iw[m_path]."/main.php?type=main&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="scrap") $menu_html = "<a href='".$iw[m_path]."/main.php?type=scrap&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="open") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=open&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="close") $menu_html =  $hm_name;
									if($state_sort=="link") $menu_html = "<a href='".$hm_link."'>".$hm_name."</a>";
									if($state_sort=="mcb") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=mcb&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="publishing") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="publishing_brand") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing_brand&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="publishing_contest") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing_contest&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="author") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="exhibit") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if ($state_sort == "exhibit_monthly") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_status_month.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if ($state_sort == "exhibit_application") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_application.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if ($state_sort == "exhibit_status") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_status.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if ($state_sort == "lecture_application") $menu_html = "<a href='".$iw[m_path]."/publishing_lecture_application.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if ($state_sort == "lecture_status") $menu_html = "<a href='".$iw[m_path]."/publishing_lecture_status.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="shop") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=shop&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="doc") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=doc&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="book") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=book&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
									if($state_sort=="about") $menu_html = "<a href='".$iw[m_path]."/about_data_view.php?type=about&ep=".$iw[store]."&gp=".$iw[group]."&item=".$cg_code."'>".$hm_name."</a>";
							?>
								<div>
									<h4><?=$menu_html?></h4>
									<ul>				
								<?php
									$sql3 = "select hm_code,cg_code,hm_name,state_sort,hm_link from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 3 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
									$result3 = sql_query($sql3);
									$small_num = 0;
									while($row3 = @sql_fetch_array($result3)){
										$hm_code = $row3["hm_code"];
										$cg_code = $row3["cg_code"];
										$hm_name = stripslashes($row3["hm_name"]);
										$state_sort = $row3["state_sort"];
										$hm_link = $row3["hm_link"];

										if($state_sort=="main") $menu_html = "<a href='".$iw[m_path]."/main.php?type=main&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="scrap") $menu_html = "<a href='".$iw[m_path]."/main.php?type=scrap&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="open") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=open&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="close") $menu_html =  $hm_name;
										if($state_sort=="link") $menu_html = "<a href='".$hm_link."'>".$hm_name."</a>";
										if($state_sort=="mcb") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=mcb&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="publishing") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="publishing_brand") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing_brand&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="publishing_contest") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing_contest&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="author") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="exhibit") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if ($state_sort == "exhibit_monthly") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_status_month.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if ($state_sort == "exhibit_application") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_application.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if ($state_sort == "exhibit_status") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_status.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if ($state_sort == "lecture_application") $menu_html = "<a href='".$iw[m_path]."/publishing_lecture_application.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if ($state_sort == "lecture_status") $menu_html = "<a href='".$iw[m_path]."/publishing_lecture_status.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="shop") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=shop&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="doc") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=doc&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="book") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=book&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
										if($state_sort=="about") $menu_html = "<a href='".$iw[m_path]."/about_data_view.php?type=about&ep=".$iw[store]."&gp=".$iw[group]."&item=".$cg_code."'>".$hm_name."</a>";
								?>
									<li><?=$menu_html?>
									<ul>
									<?php
										$sql4 = "select hm_code,cg_code,hm_name,state_sort,hm_link from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 4 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
										$result4 = sql_query($sql4);
										while($row4 = @sql_fetch_array($result4)){
											$hm_code = $row4["hm_code"];
											$cg_code = $row4["cg_code"];
											$hm_name = stripslashes($row4["hm_name"]);
											$state_sort = $row4["state_sort"];
											$hm_link = $row4["hm_link"];

											if($state_sort=="main") $menu_html = "<a href='".$iw[m_path]."/main.php?type=main&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="scrap") $menu_html = "<a href='".$iw[m_path]."/main.php?type=scrap&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="open") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=open&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="close") $menu_html =  $hm_name;
											if($state_sort=="link") $menu_html = "<a href='".$hm_link."'>".$hm_name."</a>";
											if($state_sort=="mcb") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=mcb&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="publishing") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="publishing_brand") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing_brand&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="publishing_contest") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing_contest&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="author") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="exhibit") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if ($state_sort == "exhibit_monthly") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_status_month.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if ($state_sort == "exhibit_application") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_application.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if ($state_sort == "exhibit_status") $menu_html = "<a href='".$iw[m_path]."/publishing_exhibit_status.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if ($state_sort == "lecture_application") $menu_html = "<a href='".$iw[m_path]."/publishing_lecture_application.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if ($state_sort == "lecture_status") $menu_html = "<a href='".$iw[m_path]."/publishing_lecture_status.php?ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="shop") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=shop&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="doc") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=doc&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="book") $menu_html = "<a href='".$iw[m_path]."/all_data_list.php?type=book&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".$hm_name."</a>";
											if($state_sort=="about") $menu_html = "<a href='".$iw[m_path]."/about_data_view.php?type=about&ep=".$iw[store]."&gp=".$iw[group]."&item=".$cg_code."'>".$hm_name."</a>";
									?>
										<li><?=$menu_html?></li>
									<?php
										}
										echo "</ul></li>";
									}
									echo "</ul></div>";
								}
						?>
								</div><!-- /cbp-hrsub-inner -->
							</div>
						</li>
						<?php } ?><?php }
						?>
						<!-- search box -->
						<li><a href="#" id="zp-top-search" data-container="#navbar-region"><i class="fa fa-search"></i></a></li>
						</ul>
					</div> <!-- /#main-navbar-item -->
				</div> <!-- /.container -->
			</div> <!-- /#main-navbar -->
		</div> <!-- /#navbar-region -->
		</form>

		<div id="sticky-anchor"></div>

        <div class="clearfix"></div>

		<div class="container"><!-- Main container -->



