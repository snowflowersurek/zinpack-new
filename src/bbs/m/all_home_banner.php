<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
include_once("$iw[include_path]/lib/lib_image_resize.php");
?>
<style type="text/css">
	.main_banner_<?=$hs_no?>{
		-moz-border-radius:<?=$hs_border_radius?>px;
		-webkit-border-radius:<?=$hs_border_radius?>px;
		border-radius:<?=$hs_border_radius?>px;
		border:<?=$hs_border_width?>px solid <?=$hs_border_color?>;
		background:rgba(<?=$hs_bg_color[red]?>,<?=$hs_bg_color[green]?>,<?=$hs_bg_color[blue]?>,1);
	}
</style>

<div class="masonry-item w-<?=$hs_size_width?> <?if($hs_type=="custom"){?>h-full<?}else{?>h-<?=$hs_size_height?>-scrap<?}?> scrap-wrap" style="padding:<?=$hs_box_padding?>px">
	<div class="box box-img main_banner_<?=$hs_no?>">
		<div id="carousel-box<?=$hs_no?>" class="carousel image slide" data-ride="carousel">
			<!-- Wrapper for slides -->
			<div class="carousel-inner">
				<?
					$a = 0;
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

							if($state_sort=="mcb" || $state_sort=="publishing" || $state_sort=="author" || $state_sort=="book" || $state_sort=="doc" || $state_sort=="shop"){
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
									if($state_sort2=="mcb" || $state_sort2=="publishing" || $state_sort2=="author" || $state_sort2=="book" || $state_sort2=="doc" || $state_sort2=="shop"){
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

						$sql = "select * from $iw[total_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and td_display=1 $sql_cg_code_sc order by $hs_sort limit 0, $hs_banner_cnt";
						$result = sql_query($sql);

						while($row = @sql_fetch_array($result)){
							$td_code = $row["td_code"];
							$cg_code = $row["cg_code"];
							$check_state_sort = $row["state_sort"];
							
							$list_true = "no";
							if($check_state_sort == "mcb"){ //게시판
								$row2 = sql_fetch("select md_code,md_subject,md_type,md_file_1,md_content from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and md_code = '$td_code' and md_display=1 and ((md_type=1 and md_file_1<>'') or (md_type=2 and md_content like '%<img%'))");
								if (!$row2[md_code]){
									$list_true = "no";
								}else{
									$list_true = "yes";
									$banner_code = $row2["md_code"];
									$banner_subject = stripslashes($row2["md_subject"]);
									$md_type = $row2["md_type"];

									if($md_type == 1){
										$banner_path = $iw[path]."/mcb".$upload_path."/".$banner_code."/";
										$banner_image = $row2["md_file_1"];
									}else if($md_type == 2){
										$pattern = "!<(.*?)\>!is";
										preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($row2["md_content"]),$md_images);
										$banner_image = htmlspecialchars($md_images[1][0]);
									}
								}
							}else if($check_state_sort == "publishing"){ //출판도서
								$row2 = sql_fetch("select BookID, BookName, BookImage from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and BookID = '$td_code' and book_display=1");
								if (!$row2[BookID]){
									$list_true = "no";
								}else{
									$list_true = "yes";
									$banner_code = $row2["BookID"];
									$banner_subject = stripslashes($row2["BookName"]);
									$banner_image = $row2["BookImage"];
									$banner_path = $iw[path]."/publishing".$book_path."/";
								}
							}else if($check_state_sort == "author"){ //저자
								$row2 = sql_fetch("select AuthorID, Author, Photo from $iw[publishing_author_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and AuthorID = '$td_code' and Photo != '' and author_display=1");
								if (!$row2[AuthorID]){
									$list_true = "no";
								}else{
									$list_true = "yes";
									$banner_code = $row2["AuthorID"];
									$banner_subject = stripslashes($row2["Author"]);
									$banner_image = $row2["Photo"];
									$banner_path = $iw[path]."/publishing".$author_path."/";
								}
							}else if($check_state_sort == "book"){ //이북몰
								$row2 = sql_fetch("select bd_code,bd_subject,bd_image from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and bd_code = '$td_code' and bd_display=1");
								if (!$row2[bd_code]){
									$list_true = "no";
								}else{
									$list_true = "yes";
									$banner_code = $row2["bd_code"];
									$banner_subject = stripslashes($row2["bd_subject"]);
									$banner_path = $iw[path]."/book".$upload_path."/".$banner_code."/";
									$banner_image = $row2["bd_image"];
								}
							}else if($check_state_sort == "doc"){ //컨텐츠몰
								$row2 = sql_fetch("select dd_code,dd_subject,dd_image from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and dd_code = '$td_code' and dd_display=1");
								if (!$row2[dd_code]){
									$list_true = "no";
								}else{
									$list_true = "yes";
									$banner_code = $row2["dd_code"];
									$banner_subject = stripslashes($row2["dd_subject"]);
									$banner_path = $iw[path]."/doc".$upload_path."/".$banner_code."/";
									$banner_image = $row2["dd_image"];
								}
							}else if($check_state_sort == "shop"){ //쇼핑몰
								$row2 = sql_fetch("select sd_code,sd_subject,sd_image from $iw[shop_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and cg_code = '$cg_code' and sd_code = '$td_code' and sd_display=1");
								if (!$row2[sd_code]){
									$list_true = "no";
								}else{
									$list_true = "yes";
									$banner_code = $row2["sd_code"];
									$banner_subject = stripslashes($row2["sd_subject"]);
									$banner_path = $iw[path]."/shop".$upload_path."/".$banner_code."/";
									$banner_image = $row2["sd_image"];
								}
							}

							if($list_true == "yes"){
								if ($a == 0) {
									echo "<div class='item active'>";
								} else {
									echo "<div class='item'>";
								}
								echo "<a href='".$iw[m_path]."/".$check_state_sort."_data_view.php?type=".$check_state_sort."&ep=".$iw[store]."&gp=".$iw[group]."&item=".$banner_code."'>";
								if($check_state_sort == "mcb" && $md_type == 2){
									echo "<img src='".$banner_image."' class='img-responsive' alt='".$hs_name."'>";
								}else{
									echo "<img src='".$banner_path.$banner_image."' class='img-responsive' alt='".$hs_name."'>";
								}
								echo "<div class='carousel-caption'><h4 class='box-title'>".$banner_subject."</h4></div>";
								echo "</a>";
								echo "</div>";
								$a++;
							}
						}
					}else if($hs_type=="custom"){

						array_multisort($hs_file_sort, SORT_ASC, $hs_file, $hs_link);

						for($i=0; $i<10; $i++){
							if($hs_file[$i]!=""){
								if ($a == 0) {
									echo "<div class='item active'>";
								} else {
									echo "<div class='item'>";
								}
								if ($hs_link[$i] == "#" || $hs_link[$i] == "") {
									echo "<img src='".$iw[path].$upload_path_list."/".$hs_file[$i]."' class='img-responsive' alt='".$hs_title[$i]."'>";
								} else {
									echo "<a href='".$hs_link[$i]."'><img src='".$iw[path].$upload_path_list."/".$hs_file[$i]."' class='img-responsive' alt='".$hs_title[$i]."'></a>";
								}
								echo "</div>";
								$a++;
							}
						}
					}
				?>
			</div> <!-- /.carousel-inner -->

			<?if($a > 1){?>
			<!-- Controls -->
			<a class="left carousel-control" href="#carousel-box<?=$hs_no?>" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span>
			</a>
			<a class="right carousel-control" href="#carousel-box<?=$hs_no?>" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
			<?}?>
		</div> <!-- /.carousel image -->
	</div>
</div>
