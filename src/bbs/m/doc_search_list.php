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
				<li><a href="#"><?=$st_doc_name?> '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/doc_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-cloud-download fa-lg"></i></a>
			</span>
		</div>
		<?php if($st_doc_list==4 || $st_doc_list==7){?>
		<div class="masonry">
			<div class="grid-sizer"></div>
		<?php }
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
				";
				$result = sql_query($sql);
				$total_line = mysqli_num_rows($result);

				if($st_doc_list==5 || $st_doc_list==8){
					$max_line = ($st_doc_list-2)*6;
				}else if($st_doc_list==4 || $st_doc_list==7){
					$max_line = ($st_doc_list-1)*6;
				}else{
					$max_line = $st_doc_list*6;
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

					$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$dd_code' and cm_display = 1";
					$result2 = sql_fetch($sql2);
					$reply_count = number_format($result2[cnt]);

					$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
					$row2 = sql_fetch($sql2);
					$cg_hit = $row2[cg_hit];
					$cg_comment = $row2[cg_comment];
					$cg_recommend = $row2[cg_recommend];
			 if($st_doc_list==2){?>
				<div class="masonry-item w-6">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$dd_code?>">
								<div class="img-frame pull-left <?php if{?>no-img<?php }?>">
								<?php if($dd_image){?>
									<table style="height:100%;width:100%;background-color:#000000;"><tr><td>
									<img class="media-object img-responsive" src="<?=$iw[path]."/".$upload_path."/".$dd_code."/".$dd_image?>" alt="">
									</td></tr></table>
								<?php }?>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$dd_subject?></h3>
									<ul class="list-inline">
										<li><i class="fa fa-info-circle"></i> <?=$row["dd_amount"] if{?><?=national_language($iw[language],"a0167","쪽"); }else if($row["dd_type"]==2){?><?=national_language($iw[language],"a0168","분"); }?></li>
										<li><i class="fa fa-file"></i> <?=$dd_file;?>(<?=$dd_file_size?> MB)</li>
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$dd_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$dd_recommend?></li><?php }?>
									</ul>
									<span class="label label-info"><?php if{?><?=national_language($iw[language],"a0265","무료");?><?php }else{?><?=$dd_price?> Point<?php }?></span>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?php }else if($st_doc_list>=3 && $st_doc_list<=8){ if($st_doc_list>=3 && $st_doc_list<=5){?>
				<div class="<?php if{?> clearfix-6<?php } if{?> clearfix-4<?php } if{?> clearfix-2<?php }?>"></div>
				<?php }else if($st_doc_list>=6 && $st_doc_list<=8){?>
				<div class="<?php if{?> clearfix-6<?php } if{?> clearfix-4<?php } if{?> clearfix-2<?php }?>"></div>
				<?php }?>
				<div class="masonry-item <?php if{?>h-4<?php }else{?>h-full<?php } if{?>w-4<?php }else{?>w-2<?php }?>">
					<div class="box br-theme box-media">
						<div class="media">
							<a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$dd_code?>">
								<div>
									<?php if($dd_image){?>
										<img class="media-object img-responsive" src="<?=$iw[path]."/".$upload_path."/".$dd_code."/".$dd_image?>" alt="">
									<?php }?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$dd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-info-circle"></i> <?=$row["dd_amount"] if{?><?=national_language($iw[language],"a0167","쪽"); }else if($row["dd_type"]==2){?><?=national_language($iw[language],"a0168","분"); }?></li>
										<li><i class="fa fa-file"></i> <?=$dd_file;?>(<?=$dd_file_size?> MB)</li>
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$dd_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$dd_recommend?></li><?php }?>
									</ul>
									<p><?php if{?><?=national_language($iw[language],"a0265","무료");?><?php }else{?><?=$dd_price?> Point<?php }?></p>
								</div>
							</a>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?php }
				$i++;
				}
			 if($st_doc_list==4 || $st_doc_list==7){?>
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



