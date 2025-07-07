<?php
include_once("_common.php");
include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]";

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}

if($_POST['search']){
	$search = $_POST['search'];
}else{
	$search = $_GET['search'];
}
?>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="#"><?=$st_mcb_name?> '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw['m_path']?>/mcb_data_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$cg_code?>" title="<?=national_language($iw[language],"a0198","글쓰기");?>"><i class="fa fa-pencil fa-lg"></i></a>
			</span>
		</div>
		<?php if($st_mcb_list==4 || $st_mcb_list==7){?>
		<div class="masonry">
			<div class="grid-sizer"></div>
		<?php }
				$sql = "
					SELECT 
						A.* 
					FROM iw_mcb_data AS A 
						INNER JOIN iw_home_menu AS B ON A.cg_code = B.cg_code AND B.cg_code <> '' 
					WHERE A.ep_code = '$iw[store]' AND A.gp_code='$iw[group]' AND A.md_display = 1 AND (A.md_subject LIKE '%$search%' OR A.md_content LIKE '%$search%') 
				";
				$result = sql_query($sql);
				$total_line = mysqli_num_rows($result);

				if($st_mcb_list==5 || $st_mcb_list==8){
					$max_line = ($st_mcb_list-2)*6;
				}else if($st_mcb_list==4 || $st_mcb_list==7){
					$max_line = ($st_mcb_list-1)*6;
				}else{
					$max_line = $st_mcb_list*6;
				}
				$max_page = 5;
					
				$page = $_GET["page"];
				if(!$page) $page=1;
				$start_line = ($page-1)*$max_line;
				$total_page = ceil($total_line/$max_line);
				
				if($total_line < $max_line) {
					$end_line = $total_line;
				} else if($page==$total_page) {
					$end_line = $total_line - ($max_line*($total_page-1));
				} else {
					$end_line = $max_line;
				}

				$sql = "
					SELECT 
						A.* 
					FROM iw_mcb_data AS A 
						INNER JOIN iw_home_menu AS B ON A.cg_code = B.cg_code AND B.cg_code <> '' 
					WHERE A.ep_code = '$iw[store]' AND A.gp_code='$iw[group]' AND A.md_display = 1 AND (A.md_subject LIKE '%$search%' OR A.md_content LIKE '%$search%') 
					ORDER BY A.md_no DESC 
					LIMIT $start_line, $end_line
				";
				$result = sql_query($sql);

				$i=0;
				while($row = @sql_fetch_array($result)){
					$cg_code = $row["cg_code"];
					$md_code = $row["md_code"];
					$md_type = $row["md_type"];
					$md_subject = stripslashes($row["md_subject"]);
					$md_content = $row["md_content"];
					$md_youtube = $row["md_youtube"];
					$md_file_1 = $row["md_file_1"];
					$md_ip = $row["md_ip"];
					$md_hit = number_format($row["md_hit"]);
					$md_recommend = number_format($row["md_recommend"]);
					$md_datetime = $row["md_datetime"];
					$md_attach = explode(".",$row["md_attach_name"]);
					$md_attach = strtoupper($md_attach[count($md_attach)-1]);
					$md_secret = $row["md_secret"];
						
					if($md_type == 1){
						$pattern = "/(mms|http|HTTP|https|HTTPS|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/"; 
						$youtube_code="";
						if(strstr($md_youtube, "youtu.be")){
							$youtube = explode("youtu.be/",$md_youtube);
							if(strstr($youtube[1], "?")){
								$youtube2 = explode("?",$youtube[1]);
								$youtube_code = $youtube2[0];
							}else{
								$youtube_code = $youtube[1];
							}
						}else if(strstr($md_youtube, "youtube.com")){
							$youtube = explode("v=",$md_youtube);
							if(strstr($youtube[1], "&")){
								$youtube2 = explode("&",$youtube[1]);
								$youtube_code = $youtube2[0];
							}else{
								$youtube_code = $youtube[1];
							}
						}
					}else if($md_type == 2){
						$pattern = "!<(.*?)\>!is";
						preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($md_content),$md_images);
					}

					if($md_secret == 1){
						$md_content = "<i class='fa fa-lock'></i> 비밀글입니다.";
					}else{
						$md_content = preg_replace($pattern, "", $md_content);
					}

					if(($md_type == 1 && !$row["md_file_1"] && !$row["md_youtube"]) || ($md_type == 2 && !$md_images[1][0])){
						$no_image = "no-img";
						$content_size = 250;
					}else{
						$no_image = "";
						$content_size = 150;
					}

					$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$md_code' and cm_display = 1";
					$result2 = sql_fetch($sql2);
					$reply_count = number_format($result2[cnt]);

					$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
					$row2 = sql_fetch($sql2);
					$cg_hit = $row2[cg_hit];
					$cg_comment = $row2[cg_comment];
					$cg_recommend = $row2[cg_recommend];
			 if($st_mcb_list==2){?>
				<div class="masonry-item w-6">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$md_code?>">
								<div class="img-frame pull-left <?=$no_image?>">
								<?php if($no_image !="no-img"){?><table style="height:100%;width:100%;background-color:#000000;"><tr><td><?php } ?><?php if($row["md_file_1"]){?>
									<img src="<?=$iw[path]."/".$upload_path."/".$md_code."/".$md_file_1?>" class="media-object img-responsive" alt=""/>
								<?php }else if($row["md_youtube"]){?>
									<img src="//img.youtube.com/vi/<?=$youtube_code?>/0.jpg" class="media-object img-responsive" alt=""/>
								<?php }else if($md_type == 2 && $md_images[1][0]){?>
									<img src="<?=htmlspecialchars($md_images[1][0]);?>" class="media-object img-responsive" alt=""/>
								<?php } ?><?php if($no_image !="no-img"){?></td></tr></table><?php }?>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$md_subject?></h3>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$md_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$md_recommend?></li><?php } ?><?php if($md_attach){?>
										<li><i class="fa fa-file"></i> <?=$md_attach?></li>
										<?php }?>
									</ul>
									<p><?=cut_str($md_content,$content_size)?></p>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?php }else if($st_mcb_list>=3 && $st_mcb_list<=8){ if($st_mcb_list>=3 && $st_mcb_list<=5){?>
				<div class="<?php if{?> clearfix-6<?php } if{?> clearfix-4<?php } if{?> clearfix-2<?php }?>"></div>
				<?php }else if($st_mcb_list>=6 && $st_mcb_list<=8){?>
				<div class="<?php if{?> clearfix-6<?php } if{?> clearfix-4<?php } if{?> clearfix-2<?php }?>"></div>
				<?php }?>
				<div class="masonry-item <?php if{?>h-4<?php }else{?>h-full<?php } if{?>w-4<?php }else{?>w-2<?php }?>">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$md_code?>">
								<div>
									<?php if($row["md_file_1"]){?>
										<img src="<?=$iw[path]."/".$upload_path."/".$md_code."/".$md_file_1?>" class="media-object img-responsive" alt=""/>
									<?php }else if($row["md_youtube"]){?>
										<img src="//img.youtube.com/vi/<?=$youtube_code?>/0.jpg" class="media-object img-responsive" alt=""/>
									<?php }else if($md_type == 2 && $md_images[1][0]){?>
										<img src="<?=htmlspecialchars($md_images[1][0]);?>" class="media-object img-responsive" alt=""/>
									<?php }?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$md_subject?></h4>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$md_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$md_recommend?></li><?php } ?><?php if($md_attach){?>
										<li><i class="fa fa-file"></i> <?=$md_attach?></li>
										<?php }?>
									</ul>
									<p><?=cut_str($md_content,$content_size)?></p>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?php }
				$i++;
				}
			 if($st_mcb_list==4 || $st_mcb_list==7){?>
		</div> <!-- /#grid -->
		<?php }?>
		<div class="clearfix"></div>
		<div class="pagContainer text-center">
			<ul class="pagination">
				<?php
					$search = urlencode($search);
					if($total_page!=0){
						if($page>$total_page) { $page=$total_page; }
						$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
						$end_page = $start_page+$max_page-1;
					 
						if($end_page>$total_page) {$end_page=$total_page;}
					 
						if($page>$max_page) {
							$pre = $start_page - 1;
							echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&search=$search&page=$pre'>&laquo;</a></li>";
						} else {
							echo "<li><a href='#'>&laquo;</a></li>";
						}
						
						for($i=$start_page;$i<=$end_page;$i++) {
							if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
							else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&search=$search&page=$i'>$i</a></li>";
						}
					 
						if($end_page<$total_page) {
							$next = $end_page + 1;
							echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&search=$search&page=$next'>&raquo;</a></li>";
						} else {
							echo "<li><a href='#'>&raquo;</a></li>";
						}
					}
				?>
			</ul>
		</div>
		<div class="btn-list text-center"><a href="<?=$iw['m_path']?>/mcb_data_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" class="btn btn-theme"><?=national_language($iw[language],"a0258","글쓰기");?></a></div>
	</div> <!-- /.row -->
</div> <!-- /.content -->

<?php
include_once("_tail.php");
?>



