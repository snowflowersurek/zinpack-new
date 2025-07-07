<?php
include_once("_common.php");
include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]/book";

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
				<li><a href="#">도서 '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
			</span>
		</div>

		<?php if($st_publishing_list==4 || $st_publishing_list==7){?>
		<div class="masonry">
			<div class="grid-sizer"></div>
		<?php }
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
		";
		$result = sql_query($sql);
		$total_line = mysqli_num_rows($result);

		if($st_shop_list==5 || $st_shop_list==8){
			$max_line = ($st_shop_list-2)*6;
		}else if($st_shop_list==4 || $st_shop_list==7){
			$max_line = ($st_shop_list-1)*6;
		}else{
			$max_line = $st_shop_list*6;
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

			$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$BookID' and cm_display = 1";
			$result2 = sql_fetch($sql2);
			$reply_count = number_format($result2[cnt]);

			$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
			$row2 = sql_fetch($sql2);
			$cg_hit = $row2[cg_hit];
			$cg_comment = $row2[cg_comment];
			$cg_recommend = $row2[cg_recommend];
		 if($st_publishing_list==2){?>
			<div class="masonry-item w-6">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$BookID?>">
							<div class="img-frame pull-left">
								<table style="height:100%;width:100%;background-color:#000000;"><tr><td>
								<?php if ($BookImage) {?>
								<img class="media-object img-responsive" src="<?=$iw[path]."/".$upload_path."/".$BookImage?>" alt="">
								<?php } ?>
								</td></tr></table>
							</div>
							<div class="media-body">
								<h4 class="media-heading box-title"><?=$BookName?></h4>
								<p><?=$authorName?></p>
								<ul class="list-inline">
									<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$book_recommend?></li><?php }?>
								</ul>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			<?php }else if($st_publishing_list>=3 && $st_publishing_list<=8){ if($st_shop_list>=3 && $st_shop_list<=5){?>
			<div class="<?php if{?> clearfix-6<?php } if{?> clearfix-4<?php } if{?> clearfix-2<?php }?>"></div>
				<?php }else if($st_shop_list>=6 && $st_shop_list<=8){?>
			<div class="<?php if{?> clearfix-6<?php } if{?> clearfix-4<?php } if{?> clearfix-2<?php }?>"></div>
				<?php }?>
			<div class="masonry-item <?php if{?>h-4<?php }else{?>h-full<?php } if{?>w-4<?php }else{?>w-2<?php }?>">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$BookID?>">
							<div>
								<?php if ($BookImage) {?>
								<img class="media-object img-responsive" src="<?=$iw[path]."/".$upload_path."/".$BookImage?>" alt="">
								<?php } ?>
							</div>
							<div class="media-body">
								<h4 class="media-heading"><?=$BookName?></h4>
								<p><?=$authorName?></p>
								<ul class="list-inline">
									<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$book_recommend?></li><?php }?>
								</ul>
							</div>
						</a>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
				<?php }
			$i++;
			}
			 if($st_publishing_list==4 || $st_publishing_list==7){?>
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
	</div> <!-- /.row -->
</div> <!-- /.content -->

<?php
include_once("_tail.php");
?>



