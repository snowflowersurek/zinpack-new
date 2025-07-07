<?php
include_once("_common.php");
include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/publishing/$row[ep_nick]/author";

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
				<li><a href="#"><?=$st_publishing_name?> 저자 '<?=$search?>' <?=national_language($iw[language],"a0178","검색 결과");?></a></li>
			</ol>
			<span class="input-group-btn">
			</span>
		</div>

		<?php if($st_publishing_list==4 || $st_publishing_list==7){?>
		<div class="masonry">
			<div class="grid-sizer"></div>
		<?php }
		$sql = "select * from $iw[publishing_author_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and author_display = 1 and (Author like '%$search%')";
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
		 if($st_publishing_list==2){?>
			<div class="masonry-item w-6">
				<div class="box br-theme box-media">
					<div class="media">
						<a href="<?=$iw['m_path']?>/publishing_author_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$AuthorID?>">
							<div class="img-frame pull-left">
								<table style="height:100%;width:100%;background-color:#fff;"><tr><td>
								<?php if ($Photo) {?>
								<img class="media-object img-responsive" src="<?=$iw[path].$upload_path."/".$Photo?>" alt="">
								<?php } ?>
								</td></tr></table>
							</div>
							<div class="media-body">
								<h4 class="media-heading box-title"><?=$Author?></h3>
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
						<a href="<?=$iw['m_path']?>/publishing_author_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$AuthorID?>">
							<div>
								<?php if ($Photo) {?>
								<img class="media-object img-responsive" src="<?=$iw[path].$upload_path."/".$Photo?>" alt="">
								<?php } ?>
							</div>
							<div class="media-body">
								<h4 class="media-heading"><?=$Author?></h4>
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



