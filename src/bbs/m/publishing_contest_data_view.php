<?php
include_once("_common.php");
include_once("_head_sub.php");

$contest_code = $_GET["item"];

$sql = "select * from iw_publishing_contest where ep_code = '$iw[store]' and gp_code='$iw[group]' and contest_code = '$contest_code'";
$row = sql_fetch($sql);
if (!$row["contest_code"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$contest_cg_code = $row["cg_code"];
$subject = stripslashes($row["subject"]);
$contest_content = stripslashes($row["content"]);
$start_date = substr($row["start_date"], 0, 10);
$end_date = substr($row["end_date"], 0, 10);
$origin_filename = $row["origin_filename"];
$attach_filename = $row["attach_filename"];

preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($contest_content),$contest_images);

$category_sql = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$contest_cg_code'";
$category_row = sql_fetch($category_sql);

if ($category_row[cg_level_read] != 10 && $iw[level]=="guest"){
	alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
}else if($category_row[cg_level_read] != 10 && $iw[mb_level] < $category_row[cg_level_read]){
	alert(national_language($iw[language],"a0169","해당 페이지에 접근 권한이 없습니다."),"");
}

include_once("_head_share.php");
?>

<style>
	.image-container		{ position:relative; max-width: 1100px;}
	.image-container img	{ max-width:100%; height: auto; overflow: hidden;}
</style>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb">
				<?php
					$row3 = sql_fetch(" select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$contest_cg_code'");
					$hm_code = $row3[hm_code];
					$hm_view_scrap = $row3[hm_view_scrap];
					$hm_view_size = $row3[hm_view_size];
					$hm_view_scrap_mobile = $row3[hm_view_scrap_mobile];
					if($hm_view_size==12){$hm_view_size = "full";}
					if ($hm_view_scrap!=1){$hm_view_size = "full";}
					if (preg_match('/iPad/',$_SERVER['HTTP_USER_AGENT']) ){$hm_view_size = "full";}

					$navi_cate = "<li><a href='".$iw[m_path]."/all_data_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".stripslashes($row3[hm_name])."</a></li>";
					echo $navi_cate;
				?>
			</ol>
		</div>

		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
		<div class="grid-sizer"></div>
		
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<h3 class="media-heading"><?=$subject?></h3>
					<div class="content-info">
						<ul class="list-inline">
							<li><i class="fa fa-calendar"></i> 응모기간 <?=$start_date." ~ ".$end_date?></li>
							
							<?php if($attach_filename){?>
								<li><i class="fa fa-file"></i> <a href="javascript:downloadFile('publishing','<?=$iw[store]?>','<?=$iw[group]?>','<?=$contest_code?>');"><?=$origin_filename?></a> </li>
							<?php }?>
						</ul>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->	
		
			<div class="masonry-item w-<?=$hm_view_size?> h-full">
				<div class="box br-default <?php if{?>box-padding-pc<?php }?>">
				<?php
					echo "<div class='image-container'>".stripslashes($contest_content)."</div>";
				?>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			
			<?php if($hm_view_scrap_mobile==1){?>
				<style>
					@media (min-width:768px){
						.scrap-wrap	{display:;}
					}
					@media (max-width:767px){
						.scrap-wrap	{display:none;}
					}
				</style>
			<?php } ?><?php if($hm_view_scrap==1){
					$scrap_type = "view";
					include_once("all_home_scrap.php");
				}
			?>

			<div class="masonry-item w-full h-full text-center">
				<?php
					if(strtotime($end_date) - strtotime(date("Y-m-d")) > -1){
				?>
					<a href="<?=$iw['m_path']?>/publishing_contest_application.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$hm_code?>&contest_code=<?=$contest_code?>" class="btn btn-theme">응모하기</a>
				<?php
					}
				?>
			</div>
	
		<div class="clearfix"></div>

		</div> <!-- /.masonry -->
	</div>
</div>

<script type="text/javascript">
	function downloadFile(type, ep, gp, cd) {
		location.href="publishing_contest_data_download.php?type="+type+"&ep="+ep+"&gp="+gp+"&contest_code="+cd;
	}
</script>

<?php
$sql = "update iw_publishing_contest set
		hit = hit+1
		where ep_code = '$iw[store]' and gp_code='$iw[group]' and contest_code = '$contest_code'";
sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_hit = td_hit+1
		where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and td_code = '$contest_code'";
sql_query($sql);

include_once("_tail.php");
?>




