<?php
include_once("_common.php");
include_once("_head.php");

$bd_code = $_GET["idx"];
$bm_no = $_GET["no"];
$bmd_no = $_GET["order"];

$sql = "select * from $iw[book_main_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code'";
$row = sql_fetch($sql);
$bn_color = $row["bn_color"];
$bn_font = $row["bn_font"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select bmd_big_image from $iw[book_media_detail_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and bmd_no = '$bmd_no'";
$row = sql_fetch($sql);
if (!$row["bmd_big_image"]) alert("잘못된 접근입니다!","");
$bmd_big_image = $row['bmd_big_image'];
?>

<div class="book_main_wrap" style="background-color:<?=$bn_color?>;">
	<div class="wrap">
		<div class="top_back" style="border-bottom:2px solid <?=$bn_font?>;">
			<a href="<?=$iw['viewer_path']?>/media_multiple.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>&no=<?=$bm_no?>" style="color:<?=$bn_font?>;">< BACK</a>
		</div>
		<div class="image_slide">
			<div class="box_type"> 
				<div class="cell"> 
					<div class="inner">
						<img src="<?=$iw["path"].$upload_path."/".$row["bmd_big_image"]?>" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include_once("_tail.php");
?>



