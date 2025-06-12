<?
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
			<div class="col-sm-9 col-xs-9" style="color:<?=$hs_title_color?>;">
				<?=$hs_name?>
			</div>
			<div class="text-right col-sm-3 col-xs-3" style="color:<?=$hs_title_color?>;">
				<?
					$hs_topic_more_link = "";
					
					if($hs_type=="custom"){
						$hs_topic_more_link = $hs_topic_link;
					}else if($hs_type=="notice"){
						$hs_topic_more_link = $iw[m_path].'/all_notice_list.php?type='.$iw[type].'&ep='.$iw[store].'&gp='.$iw[group];
					}else if($hs_menu == "all"){
						$hs_topic_more_link = "#";
					}else{
						$hs_topic_more_link = $iw[m_path].'/all_data_list.php?type='.$iw[type].'&ep='.$iw[store].'&gp='.$iw[group].'&menu='.$hs_menu;
					}
					
					if ($hs_topic_more_link != "#" && $hs_topic_more_link != "") {
						echo"<a href='".$hs_topic_more_link."' style='font-size:14px;'>".$hs_topic_more."</a>";
					}
				?>
			</div>
		</h3>
		<ul class="media-list">
			<?
				if($hs_topic_type == "title"){
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
				}else{
					$hs_limit = $hs_size_height-1;
					if($hs_size_height == 2){
						$topic_paragraph_1000 = 1;
						$topic_paragraph_800 = 1;
					}else if($hs_size_height == 3){
						$topic_paragraph_1000 = 2;
						$topic_paragraph_800 = 2;
					}else if($hs_size_height == 4){
						$topic_paragraph_1000 = 3;
						$topic_paragraph_800 = 2;
					}else if($hs_size_height == 5){
						$topic_paragraph_1000 = 4;
						$topic_paragraph_800 = 3;
					}else if($hs_size_height == 6){
						$topic_paragraph_1000 = 5;
						$topic_paragraph_800 = 4;
					}else if($hs_size_height == 7){
						$topic_paragraph_1000 = 6;
						$topic_paragraph_800 = 4;
					}else{
						$topic_paragraph_1000 = 6;
						$topic_paragraph_800 = 5;
					}
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

					$sql = "select * from $iw[total_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and td_display=1 $sql_cg_code_sc order by $hs_sort limit 0, $hs_limit";
					$result = sql_query($sql);
					
					$topic_count = 1;
					while($row = @sql_fetch_array($result)){
						$td_code = $row["td_code"];
						$cg_code = $row["cg_code"];
						$check_state_sort = $row["state_sort"];
						
						$list_true = "no";
						$banner_image = "";
						if($check_state_sort == "mcb"){ //게시판
							$row2 = sql_fetch("select * from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and md_code = '$td_code' and md_display=1");
							if (!$row2[md_no]){
								$list_true = "no";
							}else{
								$list_true = "yes";
								$md_type = $row2["md_type"];
								$md_subject = stripslashes($row2["md_subject"]);
								$md_content = $row2["md_content"];
								$md_secret = $row2["md_secret"];
								$banner_path = $iw[path]."/mcb".$upload_path."/".$td_code."/";
								if($md_type == 1){
									$pattern = "/(mms|http|HTTP|https|HTTPS|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/";
									$banner_image = $row2["md_file_1"];
								}else if($md_type == 2){
									$pattern = "!<(.*?)\>!is";
									preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($row2["md_content"]),$md_images);
									$banner_image = htmlspecialchars($md_images[1][0]);
									if(strpos($banner_image, "http") === false) {
										$banner_image = explode("/",$banner_image);
										$banner_image = $banner_image[count($banner_image)-1];
									}else{
										$banner_image = "";
									}
								}
								if($md_secret == 1){
									$md_content = "<i class='fa fa-lock'></i> 비밀글입니다.";
								}else{
									$md_content = preg_replace($pattern, "", $md_content);
									$md_content = cut_str($md_content, 500);
								}
							}
						}else if($check_state_sort == "publishing"){ //출판도서
							$row2 = sql_fetch("select BookID, BookName, BookImage, Intro from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and BookID = '$td_code' and book_display=1");
							if (!$row2[BookID]){
								$list_true = "no";
							}else{
								$list_true = "yes";
								$book_id = $row2["BookID"];
								$book_name = stripslashes($row2["BookName"]);
								$book_intro = stripslashes($row2["Intro"]);
								$book_image = $row2["BookImage"];
								$book_path = $iw[path]."/publishing".$book_path."/";
							}
						}else if($check_state_sort == "book"){ //이북몰
							$row2 = sql_fetch("select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and bd_code = '$td_code' and bd_display=1");
							if (!$row2[bd_no]){
								$list_true = "no";
							}else{
								$list_true = "yes";
								$bd_subject = stripslashes($row2["bd_subject"]);
								$bd_price = number_format($row2["bd_price"]);
								$bd_hit = number_format($row2["bd_hit"]);
								$bd_recommend = number_format($row2["bd_recommend"]);
								$bd_author = $row2["bd_author"];
								$bd_publisher = $row2["bd_publisher"];
								$banner_path = $iw[path]."/book".$upload_path."/".$td_code."/";
								$banner_image = $row2["bd_image"];
							}
						}else if($check_state_sort == "doc"){ //컨텐츠몰
							$row2 = sql_fetch("select * from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and dd_code = '$td_code' and dd_display=1");
							if (!$row2[dd_no]){
								$list_true = "no";
							}else{
								$list_true = "yes";
								$dd_subject = stripslashes($row2["dd_subject"]);
								$dd_file = explode(".",$row2["dd_file_name"]);
								$dd_file = strtoupper($dd_file[count($dd_file)-1]);
								$dd_file_size = number_format($row2["dd_file_size"]/1024/1000, 1);
								$dd_price = number_format($row2["dd_price"]);
								$dd_hit = number_format($row2["dd_hit"]);
								$dd_recommend = number_format($row2["dd_recommend"]);
								$dd_type = $row2["dd_type"];
								$dd_amount = $row2["dd_amount"];
								$banner_path = $iw[path]."/doc".$upload_path."/".$td_code."/";
								$banner_image = $row2["dd_image"];
							}
						}else if($check_state_sort == "shop"){ //쇼핑몰
							$row2 = sql_fetch("select * from $iw[shop_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and sd_code = '$td_code' and sd_display=1");
							if (!$row2[sd_no]){
								$list_true = "no";
							}else{
								$list_true = "yes";
								$sd_subject = stripslashes($row2["sd_subject"]);
								$sd_sale = $row2["sd_sale"];
								$sd_price = $row2["sd_price"];
								$sd_percent = floor(100-($sd_sale/$sd_price*100));
								$sd_sell = number_format($row2["sd_sell"]);
								$sd_hit = number_format($row2["sd_hit"]);
								$sd_recommend = number_format($row2["sd_recommend"]);
								$banner_path = $iw[path]."/shop".$upload_path."/".$td_code."/";
								$banner_image = $row2["sd_image"];
							}
						}

						if($list_true == "yes"){
							if($hs_topic_type == "image" && $banner_image){
								$filename = "(60)".$banner_image;
								if(is_file($banner_path.$filename)==false){
									$image = new SimpleImage();
									$image->load($banner_path.$banner_image);
									$image->resize(60,60);
									$image->save($banner_path.$filename);
								}
							}

							$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$check_state_sort' and cm_code = '$td_code' and cm_display = 1";
							$result2 = sql_fetch($sql2);
							$reply_count = number_format($result2[cnt]);

							$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$check_state_sort' and cg_code = '$cg_code'";
							$row2 = sql_fetch($sql2);
							$cg_hit = $row2[cg_hit];
							$cg_comment = $row2[cg_comment];
							$cg_recommend = $row2[cg_recommend];

							if($check_state_sort == "mcb"){ //게시판
								echo "<li class='media";
								if($topic_paragraph_800 == $topic_count) echo " topic_paragraph_800";
								if($topic_paragraph_1000 == $topic_count) echo " topic_paragraph_1000";
								echo "'><a href='";
								echo $iw['m_path']."/mcb_data_view.php?type=mcb&ep=".$iw[store]."&gp=".$iw[group]."&item=".$td_code;
								echo "'>";
								if($hs_topic_type == "image" && $banner_image){
									echo "<img class='media-object pull-left' style='padding-right:10px;' src='".$banner_path.$filename."'>";
								}else{
									echo "<div class='img-box pull-left'><div class='no-img'></div></div>";
								}
								echo "<div class='media-body'><h4 class='media-heading'>";
								echo $hs_topic_bullet.$md_subject;
								echo "</h4>";
								if($hs_topic_type == "content" || $hs_topic_type == "image"){
									echo "<p>".$md_content."</p>";
								}
								echo "</div></a></li>";
							}else if($check_state_sort == "publishing"){ //출판도서
								echo "<li class='media";
								if($topic_paragraph_800 == $topic_count) echo " topic_paragraph_800";
								if($topic_paragraph_1000 == $topic_count) echo " topic_paragraph_1000";
								echo "'><a href='";
								echo $iw['m_path']."/publishing_data_view.php?type=publishing&ep=".$iw[store]."&gp=".$iw[group]."&item=".$td_code;
								echo "'>";
								if($hs_topic_type == "image"){
									echo "<img class='media-object pull-left' style='padding-right:10px;max-width:60px' src='".$book_path.$book_image."'>";
								}else{
									echo "<div class='img-box pull-left'><div class='no-img'></div></div>";
								}
								echo "<div class='media-body'><h4 class='media-heading'>";
								echo $hs_topic_bullet.$book_name;
								echo "</h4>";
								if($hs_topic_type == "content" || $hs_topic_type == "image"){
									echo "<p>".$book_intro."</p>";
								}
								echo "</div></a></li>";
							}else if($check_state_sort == "book"){ //이북몰
								echo "<li class='media";
								if($topic_paragraph_800 == $topic_count) echo " topic_paragraph_800";
								if($topic_paragraph_1000 == $topic_count) echo " topic_paragraph_1000";
								echo "'><a href='";
								echo $iw['m_path']."/book_data_view.php?type=book&ep=".$iw[store]."&gp=".$iw[group]."&item=".$td_code;
								echo "'>";
								if($hs_topic_type == "image"){
									echo "<img class='media-object pull-left' style='padding-right:10px;' src='".$banner_path.$filename."'>";
								}else{
									echo "<div class='img-box pull-left'><div class='no-img'></div></div>";
								}
								echo "<div class='media-body'><h4 class='media-heading'>";
								echo $hs_topic_bullet.$bd_subject;
								echo "</h4>";
								if($hs_topic_type == "content" || $hs_topic_type == "image"){
									echo "<p>";
									echo "<i class='fa fa-pencil'></i> ".$bd_autho;
									echo " <i class='fa fa-info-circle'></i> ".$bd_publisher;
									if($cg_hit==1){echo " <i class='fa fa-eye'></i> ".$bd_hit;}
									if($cg_comment==1){echo " <i class='fa fa-comment'></i> ".$reply_count;}
									if($cg_recommend==1){echo " <i class='fa fa-thumbs-up'></i> ".$bd_recommend;}
									echo "<br/>";
									if($bd_price=="0"){
										echo national_language($iw[language],"a0265","무료");
									}else{
										echo $bd_price." Point";
									}
									echo "</p>";
								}
								echo "</div></a></li>";
							}else if($check_state_sort == "doc"){ //컨텐츠몰
								echo "<li class='media";
								if($topic_paragraph_800 == $topic_count) echo " topic_paragraph_800";
								if($topic_paragraph_1000 == $topic_count) echo " topic_paragraph_1000";
								echo "'><a href='";
								echo $iw['m_path']."/doc_data_view.php?type=doc&ep=".$iw[store]."&gp=".$iw[group]."&item=".$td_code;
								echo "'>";
								if($hs_topic_type == "image"){
									echo "<img class='media-object pull-left' style='padding-right:10px;' src='".$banner_path.$filename."'>";
								}else{
									echo "<div class='img-box pull-left'><div class='no-img'></div></div>";
								}
								echo "<div class='media-body'><h4 class='media-heading'>";
								echo $hs_topic_bullet.$dd_subject;
								echo "</h4>";
								if($hs_topic_type == "content" || $hs_topic_type == "image"){
									echo "<p>";
									echo "<i class='fa fa-info-circle'></i> ".$dd_amount;
									if($dd_type==1){
										echo national_language($iw[language],"a0167","쪽");
									}else if($dd_type==2){
										echo national_language($iw[language],"a0168","분");
									}
									echo " <i class='fa fa-file'></i> ".$dd_file."(".$dd_file_size." MB)";
									if($cg_hit==1){echo " <i class='fa fa-eye'></i> ".$dd_hit;}
									if($cg_comment==1){echo " <i class='fa fa-comment'></i> ".$reply_count;}
									if($cg_recommend==1){echo " <i class='fa fa-thumbs-up'></i> ".$dd_recommend;}
									echo "<br/>";
									if($dd_price=="0"){
										echo national_language($iw[language],"a0265","무료");
									}else{
										echo $dd_price." Point";
									}
									echo "</p>";
								}
								echo "</div></a></li>";
							}else if($check_state_sort == "shop"){ //쇼핑몰
								echo "<li class='media";
								if($topic_paragraph_800 == $topic_count) echo " topic_paragraph_800";
								if($topic_paragraph_1000 == $topic_count) echo " topic_paragraph_1000";
								echo "'><a href='";
								echo $iw['m_path']."/shop_data_view.php?type=shop&ep=".$iw[store]."&gp=".$iw[group]."&item=".$td_code;
								echo "'>";
								if($hs_topic_type == "image"){
									echo "<img class='media-object pull-left' style='padding-right:10px;' src='".$banner_path.$filename."'>";
								}else{
									echo "<div class='img-box pull-left'><div class='no-img'></div></div>";
								}
								echo "<div class='media-body'><h4 class='media-heading'>";
								echo $hs_topic_bullet.$sd_subject;
								echo "</h4>";
								if($hs_topic_type == "content" || $hs_topic_type == "image"){
									echo "<p>";
									if($cg_hit==1){echo " <i class='fa fa-eye'></i> ".$sd_hit;}
									if($cg_comment==1){echo " <i class='fa fa-comment'></i> ".$reply_count;}
									if($cg_recommend==1){echo " <i class='fa fa-thumbs-up'></i> ".$sd_recommend;}
									echo "<br/>";
									if($sd_price != $sd_sale){
										echo "<small class='line-through'>".national_money($iw[language], $sd_price)."</small> ";
									}
									echo national_money($iw[language], $sd_sale);
									echo "</p>";
								}
								echo "</div></a></li>";
							}
							$topic_count++;
						}
					}
				}else if($hs_type=="notice"){
					$sql = "select * from $iw[notice_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and nt_display = 1 and nt_type = 1 order by $hs_sort limit 0, 3";
					$result = sql_query($sql);

					$topic_count = 1;
					while($row = @sql_fetch_array($result)){
						$topic_code = $row["nt_no"];
						$topic_subject = stripslashes($row["nt_subject"]);
						$topic_content = $row["nt_content"];
						$pattern = "!<(.*?)\>!is";
						$topic_content = preg_replace($pattern, "", $topic_content);
						$topic_content = cut_str($topic_content,150);

						echo "<li class='media";
						if($topic_paragraph_800 == $topic_count) echo " topic_paragraph_800";
						if($topic_paragraph_1000 == $topic_count) echo " topic_paragraph_1000";
						echo "'><a href='";
						echo $iw['m_path']."/all_notice_view.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&idx=".$topic_code;
						echo "'><div class='img-box pull-left'><div class='no-img'></div></div><div class='media-body'><h4 class='media-heading'>";
						echo $hs_topic_bullet.$topic_subject;
						echo "</h4>";
						if($hs_topic_type == "content" || $hs_topic_type == "image"){
							echo "<p>";
							echo $topic_content;
							echo "</p>";
						}
						echo "</div></a></li>";
						$topic_count++;
					}
				}else if($hs_type=="custom"){
					for ($i=0; $i<$hs_limit; $i++) {
						echo "<li class='media";
						if($topic_paragraph_800 == $i+1) echo " topic_paragraph_800";
						if($topic_paragraph_1000 == $i+1) echo " topic_paragraph_1000";
						echo "'>";
						
						if ($hs_link[$i] != "#" && $hs_link[$i] != "") {
							echo "<a href='".$hs_link[$i]."'>";
						}
						
						if($hs_topic_type == "image" && $hs_file[$i]){
							$filename = "(60)".$hs_file[$i];
							if(is_file($iw[path].$upload_path_list."/".$filename)==false){
								$image = new SimpleImage();
								$image->load($iw[path].$upload_path_list."/".$hs_file[$i]);
								$image->resize(60,60);
								$image->save($iw[path].$upload_path_list."/".$filename);
							}
							echo "<img class='media-object pull-left' style='padding-right:10px;' src='".$iw[path].$upload_path_list."/".$filename."'>";
						}else{
							echo "<div class='img-box pull-left'><div class='no-img'></div></div>";
						}
						echo "<div class='media-body'><h4 class='media-heading'>";
						echo $hs_topic_bullet.$hs_title[$i];
						echo "</h4>";
						if($hs_topic_type == "content" || $hs_topic_type == "image"){
							echo "<p>".$hs_content[$i]."</p>";
						}
						echo "</div>";
						
						if ($hs_link[$i] != "#" && $hs_link[$i] != "") {
							echo "</a>";
						}
						
						echo "</li>";
					}
				}
			?>
		</ul>
	</div>
</div>