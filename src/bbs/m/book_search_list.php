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
				<li><a href="#"><?=$st_book_name?> '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/book_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-book fa-lg"></i></a>
			</span>
		</div>
		<?if($st_book_list==4 || $st_book_list==7){?>
		<div class="masonry">
			<div class="grid-sizer"></div>
		<?}?>
			<?
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
				";
				$result = sql_query($sql);
				$total_line = mysql_num_rows($result);

				if($st_book_list==5 || $st_book_list==8){
					$max_line = ($st_book_list-2)*6;
				}else if($st_book_list==4 || $st_book_list==7){
					$max_line = ($st_book_list-1)*6;
				}else{
					$max_line = $st_book_list*6;
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
					$bd_subject = cut_str(stripslashes($row["bd_subject"]),50);
					$bd_image = $row["bd_image"];
					$bd_price = number_format($row["bd_price"]);
					$bd_hit = number_format($row["bd_hit"]);
					$bd_recommend = number_format($row["bd_recommend"]);
					$bd_type = $row["bd_type"];
					$bd_author = $row["bd_author"];
					$bd_publisher = $row["bd_publisher"];

					if($bd_type == 1){
						$bd_style = "PDF";
					}else if($bd_type == 2){
						$bd_style = "미디어";
					}else if($bd_type == 3){
						$bd_style = "블로그";
					}else if($bd_type == 4){
						$bd_style = "논문";
					}

					$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$bd_code' and cm_display = 1";
					$result2 = sql_fetch($sql2);
					$reply_count = number_format($result2[cnt]);

					$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
					$row2 = sql_fetch($sql2);
					$cg_hit = $row2[cg_hit];
					$cg_comment = $row2[cg_comment];
					$cg_recommend = $row2[cg_recommend];
			?>
			<?if($st_book_list==2){?>
				<div class="masonry-item w-6">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$bd_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#000000;"><tr><td>
									<img class="media-object img-responsive" src="<?=$iw[path]."/".$upload_path."/".$bd_code."/".$bd_image?>" alt="">
									</td></tr></table>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$bd_subject?></h3>
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
			<?}else if($st_book_list>=3 && $st_book_list<=8){?>
				<?if($st_book_list>=3 && $st_book_list<=5){?>
				<div class="<?if($i%3==0){?> clearfix-6<?}?><?if($i%2==0){?> clearfix-4<?}?><?if($i%3!=0&&$i%2!=0&&$i%1==0){?> clearfix-2<?}?>"></div>
				<?}else if($st_book_list>=6 && $st_book_list<=8){?>
				<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
				<?}?>
				<div class="masonry-item <?if($st_book_list==5 || $st_book_list==8){?>h-4<?}else{?>h-full<?}?>  <?if($st_book_list>=3 && $st_book_list<=5){?>w-4<?}else{?>w-2<?}?>">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$bd_code?>">
								<div>
									<img class="media-object img-responsive" src="<?=$iw[path]."/".$upload_path."/".$bd_code."/".$bd_image?>" alt="">
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
		<?if($st_book_list==4 || $st_book_list==7){?>
		</div> <!-- /#grid -->
		<?}?>
		<div class="clearfix"></div>
		<div class="pagContainer text-center">
			<ul class="pagination">
				<?
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

<?
include_once("_tail.php");
?>