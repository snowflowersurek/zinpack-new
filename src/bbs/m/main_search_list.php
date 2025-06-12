<?php
include_once("_common.php");
include_once("_head.php");

$row = sql_fetch(" select ep_nick, ep_state_publishing, ep_state_mcb, ep_state_shop, ep_state_doc, ep_state_book from $iw[enterprise_table] where ep_code = '$iw[store]'");
$ep_nick = "$row[ep_nick]";
$ep_state_publishing = $row["ep_state_publishing"];
$ep_state_mcb = $row["ep_state_mcb"];
$ep_state_shop = $row["ep_state_shop"];
$ep_state_doc = $row["ep_state_doc"];
$ep_state_book = $row["ep_state_book"];

if ($iw[group] == "all") {
	$gp_nick = "all";
} else {
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$gp_nick = "$row[gp_nick]";
}


$search = $_GET['search'];

?>

<?php if ($ep_state_publishing == 1) { ?>
<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="#">도서 '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/publishing_search_list.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=urlencode($search)?>" title="더보기">더보기</a>
			</span>
		</div>
		<?
			$start_line = 0;
			if($st_publishing_list==4 || $st_publishing_list==7){
				$end_line = $st_publishing_list-1;
			}else{
				$end_line = $st_publishing_list;
			}
			
			// 해시태그 검색 여부
			if (strpos($search, "#") === 0) {
				$where_clause = "Tag LIKE '%".str_replace("#", "", $search)."%'";
			} else {
				$where_clause = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(BookName, ' ', ''), ',', ''), '-', ''), '!', ''), '?', ''), '(', ''), ')', '') LIKE '%".preg_replace("/\s|,|-|\!|\?|\(|\)/", "", $search)."%' OR Authors LIKE '%$search%'";
			}

			$sql = "
				SELECT * FROM (
					SELECT B.cg_code, B.BookID, B.BookImage, B.BookName, B.Tag, B.Hit, B.book_recommend, GROUP_CONCAT(A.Author) AS Authors 
				    FROM iw_publishing_books AS B 
						LEFT JOIN iw_publishing_books_author AS BA ON BA.ep_code = B.ep_code AND BA.bookID = B.BookID 
						LEFT JOIN iw_publishing_author AS A ON A.ep_code = BA.ep_code AND A.AuthorID = BA.authorID 
					WHERE B.ep_code = '$iw[store]' AND B.gp_code = '$iw[group]' AND B.book_display = 1 
					GROUP BY B.BookID 
					ORDER BY B.BookName 
				) AS T WHERE $where_clause 
				LIMIT $start_line, $end_line
			";
			$result = sql_query($sql);

			$i=0;
			while($row = @sql_fetch_array($result)){
				$cg_code = $row["cg_code"];
				$BookID = $row["BookID"];
				$BookImage = $row["BookImage"];
				$BookName = stripslashes($row["BookName"]);
				$Hit = number_format($row["Hit"]);
				$book_recommend = number_format($row["book_recommend"]);

				$author_result = sql_query("select authorType, $iw[publishing_author_table].AuthorID, Author from $iw[publishing_books_author_table] join $iw[publishing_author_table] on $iw[publishing_books_author_table].ep_code = $iw[publishing_author_table].ep_code and $iw[publishing_books_author_table].authorID = $iw[publishing_author_table].AuthorID where $iw[publishing_books_author_table].ep_code = '$iw[store]' and BookID = '$BookID' order by authorType asc");
				$authorName = "";
				while($author_row = @sql_fetch_array($author_result)){
					if ($author_row["authorType"] == "1" || $author_row["authorType"] == "3") {
						if ($authorName != "") {
							$authorName = $authorName." / ";
						}
						$authorName = $authorName.$author_row["Author"];
					}
				}

				$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'publishing' and cm_code = '$BookID' and cm_display = 1";
				$result2 = sql_fetch($sql2);
				$reply_count = number_format($result2[cnt]);

				$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'publishing' and cg_code = '$cg_code'";
				$row2 = sql_fetch($sql2);
				$cg_hit = $row2[cg_hit];
				$cg_comment = $row2[cg_comment];
				$cg_recommend = $row2[cg_recommend];
		?>
		<?if($st_publishing_list==2){?>
			<div class="masonry-item w-6">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/publishing_data_view.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$BookID?>">
							<div class="img-frame pull-left">
								<table style="height:100%;width:100%;background-color:#000000;"><tr><td>
								<?php if ($BookImage) {?>
								<img class="media-object img-responsive" src="<?=$iw[path]."/publishing/".$ep_nick."/book/".$BookImage?>" alt="">
								<?php } ?>
								</td></tr></table>
							</div>
							<div class="media-body">
								<h4 class="media-heading box-title"><?=$BookName?></h4>
								<p><?=$authorName?></p>
								<ul class="list-inline">
									<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?}?>
									<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
									<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$book_recommend?></li><?}?>
								</ul>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		<?}else if($st_publishing_list==3 || $st_publishing_list==4 || $st_publishing_list==6 || $st_publishing_list==7){?>
			<?if($st_publishing_list==3 || $st_publishing_list==4){?>
			<div class="<?if($i%3==0){?> clearfix-6<?}?><?if($i%2==0){?> clearfix-4<?}?><?if($i%3!=0&&$i%2!=0&&$i%1==0){?> clearfix-2<?}?>"></div>
			<?}else if($st_publishing_list==6 || $st_publishing_list==7){?>
			<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
			<?}?>
			<div class="masonry-item h-full <?if($st_publishing_list==3 || $st_publishing_list==4){?>w-4<?}else{?>w-2<?}?>">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/publishing_data_view.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$BookID?>">
							<div>
								<?php if ($BookImage) {?>
								<img class="media-object img-responsive" src="<?=$iw[path]."/publishing/".$ep_nick."/book/".$BookImage?>" alt="">
								<?php } ?>
							</div>
							<div class="media-body">
								<h4 class="media-heading"><?=$BookName?></h4>
								<p><?=$authorName?></p>
								<ul class="list-inline">
									<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?}?>
									<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
									<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$book_recommend?></li><?}?>
								</ul>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		<?}?>
		<?
			$i++;
			}
		?>
		<div class="clearfix"></div>
	</div> <!-- /.row -->
</div> <!-- /.content -->

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="#">저자 '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/publishing_author_search_list.php?type=author&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=urlencode($search)?>" title="더보기">더보기</a>
			</span>
		</div>
		<?
			$start_line = 0;
			if($st_publishing_list==4 || $st_publishing_list==7){
				$end_line = $st_publishing_list-1;
			}else{
				$end_line = $st_publishing_list;
			}

			$sql = "select * from $iw[publishing_author_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and author_display = 1 and (Author like '%$search%') order by Author asc limit $start_line, $end_line";
			$result = sql_query($sql);

			$i=0;
			while($row = @sql_fetch_array($result)){
				$cg_code = 'author';
				$AuthorID = $row["AuthorID"];
				$Author = stripslashes($row["Author"]);
				$Photo = $row["Photo"];
				$Hit = number_format($row["Hit"]);
				$author_recommend = $row["author_recommend"];
		?>
		<?if($st_publishing_list==2){?>
			<div class="masonry-item w-6">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/publishing_author_data_view.php?type=author&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$AuthorID?>">
							<div class="img-frame pull-left">
								<table style="height:100%;width:100%;background-color:#fff;"><tr><td>
								<?php if ($Photo) {?>
								<img class="media-object img-responsive" src="<?=$iw[path]."/publishing/".$ep_nick."/author/".$Photo?>" alt="">
								<?php } ?>
								</td></tr></table>
							</div>
							<div class="media-body">
								<h4 class="media-heading box-title"><?=$Author?></h4>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		<?}else if($st_publishing_list==3 || $st_publishing_list==4 || $st_publishing_list==6 || $st_publishing_list==7){?>
			<?if($st_publishing_list==3 || $st_publishing_list==4){?>
			<div class="<?if($i%3==0){?> clearfix-6<?}?><?if($i%2==0){?> clearfix-4<?}?><?if($i%3!=0&&$i%2!=0&&$i%1==0){?> clearfix-2<?}?>"></div>
			<?}else if($st_publishing_list==6 || $st_publishing_list==7){?>
			<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
			<?}?>
			<div class="masonry-item h-full <?if($st_publishing_list==3 || $st_publishing_list==4){?>w-4<?}else{?>w-2<?}?>">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/publishing_author_data_view.php?type=author&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$AuthorID?>">
							<div>
								<?php if ($Photo) {?>
								<img class="media-object img-responsive" src="<?=$iw[path]."/publishing/".$ep_nick."/author/".$Photo?>" alt="">
								<?php } ?>
							</div>
							<div class="media-body">
								<h4 class="media-heading"><?=$Author?></h4>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		<?}?>
		<?
			$i++;
			}
		?>
		<div class="clearfix"></div>
	</div> <!-- /.row -->
</div> <!-- /.content -->

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="#">그림전시 '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/publishing_exhibit_search_list.php?type=exhibit&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=urlencode($search)?>" title="더보기">더보기</a>
			</span>
		</div>
		<?
			$start_line = 0;
			if($st_publishing_list==4 || $st_publishing_list==7){
				$end_line = $st_publishing_list-1;
			}else{
				$end_line = $st_publishing_list;
			}

			$sql = "select * from $iw[publishing_exhibit_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and can_rent = 'Y' and picture_name like '%$search%' order by picture_name asc limit $start_line, $end_line";
			$result = sql_query($sql);

			$i=0;
			while($row = @sql_fetch_array($result)){
				$mb_code = $row["mb_code"];
				$picture_idx = $row["picture_idx"];
				$picture_name = stripslashes($row["picture_name"]);
				$how_many = $row["how_many"];
				$picture_size = $row["size"];
				$book_id = $row["book_id"];
				
				$picture_photo = "";
				
				if ($book_id != "") {
					$book_row = sql_fetch("select BookImage from $iw[publishing_books_table] where ep_code = '$iw[store]' and BookID = '$book_id'");
					$picture_photo = $book_row["BookImage"];
					
					if ($picture_photo != "") {
						$picture_photo = $iw[path]."/publishing/".$ep_nick."/book/".$picture_photo;
					}
				}
		?>
		<?if($st_publishing_list==2){?>
			<div class="masonry-item w-6">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/publishing_exhibit_data_view.php?type=exhibit&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$picture_idx?>">
							<div class="img-frame pull-left">
								<table style="height:100%;width:100%;background-color:#fff;"><tr><td>
								<?php if ($picture_photo == "") {?>
								<!--<img class="media-object img-responsive" src="<?=$iw[path]."/publishing/no_image.jpg"?>" style='max-height:100%;'>-->
								<?php } else {?>
								<img class="media-object img-responsive" src="<?=$picture_photo?>" alt="">
								<?php } ?>
								</td></tr></table>
							</div>
							<div class="media-body">
								<h4 class="media-heading box-title"><?=$picture_name?></h4>
								<p>액자수(점) : <?=$how_many?></p>
								<p>액자크기 : <?=$picture_size?></p>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		<?}else if($st_publishing_list==3 || $st_publishing_list==4 || $st_publishing_list==6 || $st_publishing_list==7){?>
			<?if($st_publishing_list==3 || $st_publishing_list==4){?>
			<div class="<?if($i%3==0){?> clearfix-6<?}?><?if($i%2==0){?> clearfix-4<?}?><?if($i%3!=0&&$i%2!=0&&$i%1==0){?> clearfix-2<?}?>"></div>
			<?}else if($st_publishing_list==6 || $st_publishing_list==7){?>
			<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
			<?}?>
			<div class="masonry-item h-full <?if($st_publishing_list==3 || $st_publishing_list==4){?>w-4<?}else{?>w-2<?}?>">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/publishing_exhibit_data_view.php?type=exhibit&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$picture_idx?>">
							<div>
								<?php if ($picture_photo == "") {?>
								<!--<img class="media-object img-responsive" src="<?=$iw[path]."/publishing/no_image.jpg"?>" style='max-height:100%;'>-->
								<?php } else {?>
								<img class="media-object img-responsive" src="<?=$picture_photo?>" alt="">
								<?php } ?>
							</div>
							<div class="media-body">
								<h4 class="media-heading"><?=$picture_name?></h4>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		<?}?>
		<?
			$i++;
			}
		?>
		<div class="clearfix"></div>
	</div> <!-- /.row -->
</div> <!-- /.content -->
<?php }?>

<?php if ($ep_state_mcb == 1) { ?>
<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="#"><?=$st_mcb_name?> '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/mcb_search_list.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=urlencode($search)?>" title="더보기">더보기</a>
			</span>
		</div>
		<div class="masonry">
			<div class="grid-sizer"></div>
			<?
				$start_line = 0;
				if($st_mcb_list==4 || $st_mcb_list==7){
					$end_line = $st_mcb_list-1;
				}else{
					$end_line = $st_mcb_list;
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

					$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'mcb' and cm_code = '$md_code' and cm_display = 1";
					$result2 = sql_fetch($sql2);
					$reply_count = number_format($result2[cnt]);

					$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'mcb' and cg_code = '$cg_code'";
					$row2 = sql_fetch($sql2);
					$cg_hit = $row2[cg_hit];
					$cg_comment = $row2[cg_comment];
					$cg_recommend = $row2[cg_recommend];
			?>
			<?if($st_mcb_list==2){?>
				<div class="masonry-item w-6 h-2">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/mcb_data_view.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$md_code?>">
								<div class="img-frame pull-left <?=$no_image?>">
								<?if($no_image !="no-img"){?><table style="height:100%;width:100%;background-color:#000000;"><tr><td><?}?>
								<?if($row["md_file_1"]){?>
									<img src="<?=$iw[path]."/mcb/".$ep_nick."/".$gp_nick."/".$md_code."/".$md_file_1?>" class="media-object img-responsive" alt=""/>
								<?}else if($row["md_youtube"]){?>
									<img src="//img.youtube.com/vi/<?=$youtube_code?>/0.jpg" class="media-object img-responsive" alt=""/>
								<?}else if($md_type == 2 && $md_images[1][0]){?>
									<img src="<?=htmlspecialchars($md_images[1][0]);?>" class="media-object img-responsive" alt=""/>
								<?}?>
								<?if($no_image !="no-img"){?></td></tr></table><?}?>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$md_subject?></h4>
									<ul class="list-inline">
										<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$md_hit?></li><?}?>
										<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
										<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$md_recommend?></li><?}?>
										<?if($md_attach){?>
										<li><i class="fa fa-file"></i> <?=$md_attach?></li>
										<?}?>
									</ul>
									<p><?=cut_str($md_content,$content_size)?></p>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?}else if($st_mcb_list==3 || $st_mcb_list==4 || $st_mcb_list==6 || $st_mcb_list==7){?>
				<?if($st_mcb_list==3 || $st_mcb_list==4){?>
				<div class="<?if($i%3==0){?> clearfix-6<?}?><?if($i%2==0){?> clearfix-4<?}?><?if($i%3!=0&&$i%2!=0&&$i%1==0){?> clearfix-2<?}?>"></div>
				<?}else if($st_mcb_list==6 || $st_mcb_list==7){?>
				<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
				<?}?>
				<div class="masonry-item h-full <?if($st_mcb_list==3 || $st_mcb_list==4){?>w-4<?}else{?>w-2<?}?>">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/mcb_data_view.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$md_code?>">
								<div>
									<?if($row["md_file_1"]){?>
										<img src="<?=$iw[path]."/mcb/".$ep_nick."/".$gp_nick."/".$md_code."/".$md_file_1?>" class="media-object img-responsive" alt=""/>
									<?}else if($row["md_youtube"]){?>
										<img src="//img.youtube.com/vi/<?=$youtube_code?>/0.jpg" class="media-object img-responsive" alt=""/>
									<?}else if($md_type == 2 && $md_images[1][0]){?>
										<img src="<?=htmlspecialchars($md_images[1][0]);?>" class="media-object img-responsive" alt=""/>
									<?}?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$md_subject?></h4>
									<ul class="list-inline">
										<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$md_hit?></li><?}?>
										<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
										<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$md_recommend?></li><?}?>
										<?if($md_attach){?>
										<li><i class="fa fa-file"></i> <?=$md_attach?></li>
										<?}?>
									</ul>
									<p><?=cut_str($md_content,$content_size)?></p>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?}?>
			<?
				$i++;
				}
			?>
		</div> <!-- /#grid -->

	</div> <!-- /.row -->
</div> <!-- /.content -->
<?php }?>

<?php if ($ep_state_shop == 1) { ?>
<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="#"><?=$st_shop_name?> '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_search_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=urlencode($search)?>" title="더보기">더보기</a>
			</span>
		</div>
		<?
			$start_line = 0;
			if($st_shop_list==4 || $st_shop_list==7){
				$end_line = $st_shop_list-1;
			}else{
				$end_line = $st_shop_list;
			}
			
			// 해시태그 검색 여부
			if (strpos($search, "#") === 0) {
				$where_clause = "A.sd_tag LIKE '%".str_replace("#", "", $search)."%'";
			} else {
				$where_clause = "(A.sd_subject LIKE '%$search%' OR A.sd_information LIKE '%$search%' OR A.sd_content LIKE '%$search%')";
			}

			$sql = "
				SELECT 
					A.* 
				FROM iw_shop_data AS A 
					INNER JOIN iw_home_menu AS B ON A.cg_code = B.cg_code AND B.cg_code <> '' 
				WHERE A.ep_code = '$iw[store]' AND A.gp_code='$iw[group]' AND A.sd_display = 1 AND $where_clause 
				ORDER BY A.sd_no DESC 
				LIMIT $start_line, $end_line
			";
			$result = sql_query($sql);

			$i=0;
			while($row = @sql_fetch_array($result)){
				$cg_code = $row["cg_code"];
				$sd_code = $row["sd_code"];
				$sd_image = $row["sd_image"];
				$sd_subject = stripslashes($row["sd_subject"]);
				$sd_sale = $row["sd_sale"];
				$sd_price = $row["sd_price"];
				$sd_percent = floor(100-($sd_sale/$sd_price*100));
				$sd_sell = number_format($row["sd_sell"]);
				$sd_hit = number_format($row["sd_hit"]);
				$sd_recommend = number_format($row["sd_recommend"]);

				$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'shop' and cm_code = '$sd_code' and cm_display = 1";
				$result2 = sql_fetch($sql2);
				$reply_count = number_format($result2[cnt]);

				$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'shop' and cg_code = '$cg_code'";
				$row2 = sql_fetch($sql2);
				$cg_hit = $row2[cg_hit];
				$cg_comment = $row2[cg_comment];
				$cg_recommend = $row2[cg_recommend];
		?>
		<?if($st_shop_list==2){?>
			<div class="masonry-item w-6">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/shop_data_view.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$sd_code?>">
							<div class="img-frame pull-left">
								<?if($sd_price != $sd_sale){?>
									<span class="sale-tag">
										<i class="fa fa-certificate fa-5x"></i>
										<i class="text-icon"><?=$sd_percent?>%</i>
									</span>
								<?}?>
								<table style="height:100%;width:100%;background-color:#000000;"><tr><td>
								<img class="media-object img-responsive" src="<?=$iw[path]."/shop/".$ep_nick."/".$gp_nick."/".$sd_code."/".$sd_image?>" alt="">
								</td></tr></table>
							</div>
							<div class="media-body">
								<h4 class="media-heading box-title"><?=$sd_subject?></h4>
								<ul class="list-inline">
									<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$sd_hit?></li><?}?>
									<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
									<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$sd_recommend?></li><?}?>
								</ul>
								<?if($sd_price != $sd_sale){?><p class="line-through"><small><?=national_money($iw[language], $sd_price);?></small></p><?}?>
								<span class="label label-info"><?=national_money($iw[language], $sd_sale);?></span>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		<?}else if($st_shop_list==3 || $st_shop_list==4 || $st_shop_list==6 || $st_shop_list==7){?>
			<?if($st_shop_list==3 || $st_shop_list==4){?>
			<div class="<?if($i%3==0){?> clearfix-6<?}?><?if($i%2==0){?> clearfix-4<?}?><?if($i%3!=0&&$i%2!=0&&$i%1==0){?> clearfix-2<?}?>"></div>
			<?}else if($st_shop_list==6 || $st_shop_list==7){?>
			<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
			<?}?>
			<div class="masonry-item h-full <?if($st_shop_list==3 || $st_shop_list==4){?>w-4<?}else{?>w-2<?}?>">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/shop_data_view.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$sd_code?>">
							<div>
								<?if($sd_price != $sd_sale){?>
								<span class="sale-tag">
									<i class="fa fa-certificate fa-5x"></i>
									<i class="text-icon"><?=$sd_percent?>%</i>
								</span>
								<?}?>
								<img class="media-object img-responsive" src="<?=$iw[path]."/shop/".$ep_nick."/".$gp_nick."/".$sd_code."/".$sd_image?>" alt="">
							</div>
							<div class="media-body">
								<h4 class="media-heading"><?=$sd_subject?></h4>
								<ul class="list-inline">
									<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$sd_hit?></li><?}?>
									<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
									<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$sd_recommend?></li><?}?>
								</ul>
								<?if($sd_price != $sd_sale){?><p class="line-through"><small><?=national_money($iw[language], $sd_price);?></small></p><?}?>
								<p><?=national_money($iw[language], $sd_sale);?></p>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		<?}?>
		<?
			$i++;
			}
		?>
		<div class="clearfix"></div>
	</div> <!-- /.row -->
</div> <!-- /.content -->
<?php }?>

<?php if ($ep_state_doc == 1) { ?>
<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="#"><?=$st_doc_name?> '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/doc_search_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=urlencode($search)?>" title="더보기">더보기</a>
			</span>
		</div>
		<div class="masonry">
			<div class="grid-sizer"></div>
			<?
				$start_line = 0;
				if($st_doc_list==4 || $st_doc_list==7){
					$end_line = $st_doc_list-1;
				}else{
					$end_line = $st_doc_list;
				}
				
				// 해시태그 검색 여부
				if (strpos($search, "#") === 0) {
					$where_clause = "A.dd_tag LIKE '%".str_replace("#", "", $search)."%'";
				} else {
					$where_clause = "(A.dd_subject LIKE '%$search%' OR A.dd_content LIKE '%$search%')";
				}

				$sql = "
					SELECT 
						A.* 
					FROM iw_doc_data AS A 
						INNER JOIN iw_home_menu AS B ON A.cg_code = B.cg_code AND B.cg_code <> '' 
					WHERE A.ep_code = '$iw[store]' AND A.gp_code='$iw[group]' AND A.dd_display = 1 AND $where_clause 
					ORDER BY A.dd_no DESC 
					LIMIT $start_line, $end_line
				";
				$result = sql_query($sql);

				$i=0;
				while($row = @sql_fetch_array($result)){
					$cg_code = $row["cg_code"];
					$dd_code = $row["dd_code"];
					$dd_subject = stripslashes($row["dd_subject"]);
					$dd_file = explode(".",$row["dd_file_name"]);
					$dd_file = strtoupper($dd_file[count($dd_file)-1]);
					$dd_file_size = number_format($row["dd_file_size"]/1024/1000, 1);
					$dd_image = $row["dd_image"];
					$dd_price = number_format($row["dd_price"]);
					$dd_hit = number_format($row["dd_hit"]);
					$dd_recommend = number_format($row["dd_recommend"]);

					$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'doc' and cm_code = '$dd_code' and cm_display = 1";
					$result2 = sql_fetch($sql2);
					$reply_count = number_format($result2[cnt]);

					$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'doc' and cg_code = '$cg_code'";
					$row2 = sql_fetch($sql2);
					$cg_hit = $row2[cg_hit];
					$cg_comment = $row2[cg_comment];
					$cg_recommend = $row2[cg_recommend];
			?>
			<?if($st_doc_list==2){?>
				<div class="masonry-item w-6">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/doc_data_view.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$dd_code?>">
								<div class="img-frame pull-left <?if(!$dd_image){?>no-img<?}?>">
								<?if($dd_image){?>
									<table style="height:100%;width:100%;background-color:#000000;"><tr><td>
									<img class="media-object img-responsive" src="<?=$iw[path]."/doc/".$ep_nick."/".$gp_nick."/".$dd_code."/".$dd_image?>" alt="">
									</td></tr></table>
								<?}?>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$dd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-info-circle"></i> <?=$row["dd_amount"]?> <?if($row["dd_type"]==1){?><?=national_language($iw[language],"a0167","쪽");?><?}else if($row["dd_type"]==2){?><?=national_language($iw[language],"a0168","분");?><?}?></li>
										<li><i class="fa fa-file"></i> <?=$dd_file;?>(<?=$dd_file_size?> MB)</li>
										<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$dd_hit?></li><?}?>
										<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
										<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$dd_recommend?></li><?}?>
									</ul>
									<span class="label label-info"><?if($dd_price=="0"){?><?=national_language($iw[language],"a0265","무료");?><?}else{?><?=$dd_price?> Point<?}?></span>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?}else if($st_doc_list==3 || $st_doc_list==4 || $st_doc_list==6 || $st_doc_list==7){?>
				<?if($st_doc_list==3 || $st_doc_list==4){?>
				<div class="<?if($i%3==0){?> clearfix-6<?}?><?if($i%2==0){?> clearfix-4<?}?><?if($i%3!=0&&$i%2!=0&&$i%1==0){?> clearfix-2<?}?>"></div>
				<?}else if($st_doc_list==6 || $st_doc_list==7){?>
				<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
				<?}?>
				<div class="masonry-item h-full <?if($st_doc_list==3 || $st_doc_list==4){?>w-4<?}else{?>w-2<?}?>">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/doc_data_view.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$dd_code?>">
								<div>
									<?if($dd_image){?>
										<img class="media-object img-responsive" src="<?=$iw[path]."/doc/".$ep_nick."/".$gp_nick."/".$dd_code."/".$dd_image?>" alt="">
									<?}?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$dd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-info-circle"></i> <?=$row["dd_amount"]?> <?if($row["dd_type"]==1){?><?=national_language($iw[language],"a0167","쪽");?><?}else if($row["dd_type"]==2){?><?=national_language($iw[language],"a0168","분");?><?}?></li>
										<li><i class="fa fa-file"></i> <?=$dd_file;?>(<?=$dd_file_size?> MB)</li>
										<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$dd_hit?></li><?}?>
										<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
										<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$dd_recommend?></li><?}?>
									</ul>
									<p><?if($dd_price=="0"){?><?=national_language($iw[language],"a0265","무료");?><?}else{?><?=$dd_price?> Point<?}?></p>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?}?>
			<?
				$i++;
				}
			?>
		</div> <!-- /#grid -->
	</div> <!-- /.row -->
</div> <!-- /.content -->
<?php }?>

<?php if ($ep_state_book == 1) { ?>
<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="#"><?=$st_book_name?> '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/book_search_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=<?=urlencode($search)?>" title="더보기">더보기</a>
			</span>
		</div>

			<?
				$start_line = 0;
				if($st_book_list==4 || $st_book_list==7){
					$end_line = $st_book_list-1;
				}else{
					$end_line = $st_book_list;
				}
				
				// 해시태그 검색 여부
				if (strpos($search, "#") === 0) {
					$where_clause = "A.bd_tag LIKE '%".str_replace("#", "", $search)."%'";
				} else {
					$where_clause = "(A.bd_subject LIKE '%$search%' OR A.bd_content LIKE '%$search%' OR A.bd_author LIKE '%$search%' OR A.bd_publisher LIKE '%$search%')";
				}

				$sql = "
					SELECT 
						A.* 
					FROM iw_book_data AS A 
						INNER JOIN iw_home_menu AS B ON A.cg_code = B.cg_code AND B.cg_code <> '' 
					WHERE A.ep_code = '$iw[store]' AND A.gp_code='$iw[group]' AND A.bd_display = 1 AND $where_clause 
					ORDER BY A.bd_no DESC 
					LIMIT $start_line, $end_line
				";
				$result = sql_query($sql);

				$i=0;
				while($row = @sql_fetch_array($result)){
					$cg_code = $row["cg_code"];
					$bd_code = $row["bd_code"];
					$bd_subject = stripslashes($row["bd_subject"]);
					$bd_image = $row["bd_image"];
					$bd_price = number_format($row["bd_price"]);
					$bd_hit = number_format($row["bd_hit"]);
					$bd_type = $row["bd_type"];
					$bd_author = $row["bd_author"];
					$bd_publisher = $row["bd_publisher"];
					$bd_recommend = number_format($row["bd_recommend"]);

					if($bd_type == 1){
						$bd_style = "PDF";
					}else if($bd_type == 2){
						$bd_style = "미디어";
					}else if($bd_type == 3){
						$bd_style = "블로그";
					}else if($bd_type == 4){
						$bd_style = "논문";
					}

					$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'book' and cm_code = '$bd_code' and cm_display = 1";
					$result2 = sql_fetch($sql2);
					$reply_count = number_format($result2[cnt]);

					$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'book' and cg_code = '$cg_code'";
					$row2 = sql_fetch($sql2);
					$cg_hit = $row2[cg_hit];
					$cg_comment = $row2[cg_comment];
					$cg_recommend = $row2[cg_recommend];
			?>
			<?if($st_book_list==2){?>
				<div class="masonry-item w-6">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/book_data_view.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$bd_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#000000;"><tr><td>
									<img class="media-object img-responsive" src="<?=$iw[path]."/book/".$ep_nick."/".$gp_nick."/".$bd_code."/".$bd_image?>" alt="">
									</td></tr></table>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$bd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-pencil"></i> <?=$bd_author?></li>
										<li><i class="fa fa-info-circle"></i> <?=$bd_publisher?></li>
										<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$bd_hit?></li><?}?>
										<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
										<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$bd_recommend?></li><?}?>
									</ul>
									<span class="label label-info"><?if($bd_price=="0"){?><?=national_language($iw[language],"a0265","무료");?><?}else{?><?=$bd_price?> Point<?}?></span>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?}else if($st_book_list==3 || $st_book_list==4 || $st_book_list==6 || $st_book_list==7){?>
				<?if($st_book_list==3 || $st_book_list==4){?>
				<div class="<?if($i%3==0){?> clearfix-6<?}?><?if($i%2==0){?> clearfix-4<?}?><?if($i%3!=0&&$i%2!=0&&$i%1==0){?> clearfix-2<?}?>"></div>
				<?}else if($st_book_list==6 || $st_book_list==7){?>
				<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
				<?}?>
				<div class="masonry-item h-full <?if($st_book_list==3 || $st_book_list==4){?>w-4<?}else{?>w-2<?}?>">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/book_data_view.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$bd_code?>">
								<div>
									<img class="media-object img-responsive" src="<?=$iw[path]."/book/".$ep_nick."/".$gp_nick."/".$bd_code."/".$bd_image?>" alt="">
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$bd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-pencil"></i> <?=$bd_author?></li>
										<li><i class="fa fa-info-circle"></i> <?=$bd_publisher?></li>
										<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$bd_hit?></li><?}?>
										<?if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
										<?if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$bd_recommend?></li><?}?>
									</ul>
									<p><?if($bd_price=="0"){?><?=national_language($iw[language],"a0265","무료");?><?}else{?><?=$bd_price?> Point<?}?></p>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?}?>
			<?
				$i++;
				}
			?>
		<div class="clearfix"></div>
	</div> <!-- /.row -->
</div> <!-- /.content -->
<?php }?>

<?
include_once("_tail.php");
?>