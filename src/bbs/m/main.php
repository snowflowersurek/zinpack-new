<?php
include_once("_common.php");
include_once("_head.php");

if($iw[type] == "scrap"){
	$hm_code = $_GET["menu"];
	$sql = "select hm_name from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_code'";
	$row = sql_fetch($sql);

	$scrap_type = "list";
	$hm_name = stripslashes($row["hm_name"]);
	$menu_html = $iw[m_path]."/main.php?type=scrap&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code;
}else{
	$scrap_type = "main";
	$hm_name = $st_title;
	$menu_html = $iw[m_path]."/main.php?type=main&ep=".$iw[store]."&gp=".$iw[group];
}
?>
<div class="content">
	<div class="row">
		<?php if($st_navigation==1){?>
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li>
					<a href='#' onclick="location.href='<?=$menu_html?>'"><?=$hm_name?></a>
				</li>
			</ol>
		</div>
		<?php }?>
		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
		<div class="grid-sizer"></div>
		<?php
			include_once("all_home_scrap.php");
		?>
		</div> <!-- /.masonry -->
	</div> <!-- /.row -->
</div> <!-- /.content -->
<?php
include_once("_tail.php");
if($scrap_type == "main" && !preg_match('/iPhone|iPod|iPad|BlackBerry|Android|Windows CE|LG|MOT|SAMSUNG|SonyEricsson|IEMobile|Mobile|lgtelecom|PPC|opera mobi|opera mini|nokia|webos/',$_SERVER['HTTP_USER_AGENT'])){
	/*----- 팝업창 -----*/
	include_once("main_popup.php");
}
?>



