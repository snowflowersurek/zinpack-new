<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
?>
<style type="text/css">
	.main_topic_<?=$hs_no?>{
		-moz-border-radius:<?=$hs_border_radius?>px;
		-webkit-border-radius:<?=$hs_border_radius?>px;
		border-radius:<?=$hs_border_radius?>px;
		border:<?=$hs_border_width?>px solid <?=$hs_border_color?>;
		background:rgba(<?=$hs_bg_color[red]?>,<?=$hs_bg_color[green]?>,<?=$hs_bg_color[blue]?>,<?=$hs_bg_alpha/100?>);
		color:<?=$hs_font_color?>;
	}
	.main_topic_<?=$hs_no?> a:hover{
		color:<?=$hs_font_hover?>;
	}
</style>
<div class="masonry-item h-<?=$hs_size_height?>-scrap w-<?=$hs_size_width?> scrap-wrap" style="padding:<?=$hs_box_padding?>px">
	<div class="box box-list main_topic_<?=$hs_no?>">
		<h3 class="box-title">
			<div class="col-sm-12 col-xs-12" style="color:<?=$hs_title_color?>;">
				<?=$hs_name?>
			</div>
		</h3>
		<ul class="media-list">
			<?php
				$hs_limit = ($hs_size_height-1) * 3;
				if($hs_size_height == 2){
					$topic_paragraph_1000 = 3;
					$topic_paragraph_800 = 2;
				}else if($hs_size_height == 3){
					$topic_paragraph_1000 = 5;
					$topic_paragraph_800 = 3;
				}else if($hs_size_height == 4){
					$topic_paragraph_1000 = 8;
					$topic_paragraph_800 = 5;
				}else if($hs_size_height == 5){
					$topic_paragraph_1000 = 10;
					$topic_paragraph_800 = 7;
				}else if($hs_size_height == 6){
					$topic_paragraph_1000 = 13;
					$topic_paragraph_800 = 9;
				}else if($hs_size_height == 7){
					$topic_paragraph_1000 = 15;
					$topic_paragraph_800 = 11;
				}else{
					$topic_paragraph_1000 = 18;
					$topic_paragraph_800 = 13;
				}

				if($hs_type=="menu"){
					$sql_cg_code_sc = "";
					if($hs_menu == "all"){
						$sql_sc2 = "select state_sort,cg_code from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_no asc";
						$result_sc2 = sql_query($sql_sc2);
						while($row_sc2 = @sql_fetch_array($result_sc2)){
							$state_sort2 = $row_sc2[state_sort];
							if($state_sort2=="mcb" || $state_sort2=="publishing" || $state_sort2=="book" || $state_sort2=="doc" || $state_sort2=="shop"){
								if(strlen($sql_cg_code_sc) == 0){
									$sql_cg_code_sc = "and cg_code in('".$row_sc2[cg_code]."'";
								}else{
									$sql_cg_code_sc .= ", '".$row_sc2[cg_code]."'";
								}
							}
						}
					}else{
						$row_sc2 = sql_fetch("select state_sort,hm_deep,hm_code,cg_code from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and hm_code = '$hs_menu'");
						$state_sort = $row_sc2[state_sort];
						$hm_deep = $row_sc2[hm_deep];
						$hm_upper_code = "and hm_upper_code = '".$row_sc2[hm_code]."'";

						if($state_sort=="mcb" || $state_sort=="publishing" || $state_sort=="book" || $state_sort=="doc" || $state_sort=="shop"){
							$sql_cg_code_sc = "and cg_code in('".$row_sc2[cg_code]."'";
						}

						for ($i=$hm_deep+1; $i<5; $i++) {
							$sql_sc2 = "select state_sort,hm_code,cg_code from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_deep = '$i' $hm_upper_code order by hm_no asc";
							$result_sc2 = sql_query($sql_sc2);
							$hm_upper_code = "";
							while($row_sc2 = @sql_fetch_array($result_sc2)){
								$state_sort2 = $row_sc2[state_sort];
								if(strlen($hm_upper_code) == 0){
									$hm_upper_code = "and hm_upper_code in ('".$row_sc2[hm_code]."'";
								}else{
									$hm_upper_code .= ", '".$row_sc2[hm_code]."'";
								}
								if($state_sort2=="mcb" || $state_sort2=="publishing" || $state_sort2=="book" || $state_sort2=="doc" || $state_sort2=="shop"){
									if(strlen($sql_cg_code_sc) == 0){
										$sql_cg_code_sc = "and cg_code in('".$row_sc2[cg_code]."'";
									}else{
										$sql_cg_code_sc .= ", '".$row_sc2[cg_code]."'";
									}
								}
							}
							if(strlen($hm_upper_code) == 0){
								$hm_upper_code = "and hm_upper_code = null";
							}else{
								$hm_upper_code .= ")";
							}
						}
					}
					if(strlen($sql_cg_code_sc) == 0){
						$sql_cg_code_sc = "and cg_code = null";
					}else{
						$sql_cg_code_sc .= ")";
					}
					
					$cm_code = "";
					$sql = "select td_code from $iw[total_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and td_display=1 $sql_cg_code_sc order by td_no desc";
					$result = sql_query($sql);
					while($row = @sql_fetch_array($result)){
						if(strlen($cm_code) == 0){
							$cm_code = "and cm_code in ('".$row[td_code]."'";
						}else{
							$cm_code .= ", '".$row[td_code]."'";
						}					
					}
					if(strlen($cm_code) != 0){
						$cm_code .= ")";
					}
					
					$topic_count = 1;
					$sql = "select a.cm_code,a.state_sort,a.cm_content,c.hm_name from $iw[comment_table] a LEFT JOIN $iw[total_data_table] b ON a.cm_code = b.td_code LEFT JOIN $iw[home_menu_table] c ON b.cg_code = c.cg_code where a.ep_code = '$iw[store]' and a.gp_code='$iw[group]' and a.cm_display = 1 $cm_code order by a.cm_no desc limit 0, $hs_limit";
					$result = sql_query($sql);
					while($row = @sql_fetch_array($result)){
						echo "<li class='media";
						if($topic_paragraph_800 == $topic_count) echo " topic_paragraph_800";
						if($topic_paragraph_1000 == $topic_count) echo " topic_paragraph_1000";
						echo "'><a href='";
						echo $iw['m_path']."/".$row['state_sort']."_data_view.php?type=".$row['state_sort']."&ep=".$iw[store]."&gp=".$iw[group]."&item=".$row['cm_code'];
						echo "'>";
						echo "<div class='img-box pull-left'><div class='no-img'></div></div>";
						echo "<div class='media-body'><h4 class='media-heading'>";
						echo "[".stripslashes($row["hm_name"])."] ".stripslashes($row["cm_content"]);
						echo "</h4>";
						echo "</div></a></li>";
						$topic_count++;
					}
				}
			?>
		</ul>
	</div>
</div>



