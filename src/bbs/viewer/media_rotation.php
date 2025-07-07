<?php
include_once("_common.php");
include_once("_head.php");

$bd_code = $_GET["idx"];
$bm_no = $_GET["no"];

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

$sql = "select * from $iw[book_media_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and bm_no = '$bm_no'";
$row = sql_fetch($sql);
if (!$row["bm_no"]) alert("잘못된 접근입니다!","");
$bm_order = $row['bm_order'];

$sql = "select * from $iw[book_media_detail_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and bm_order = '$bm_order'";
$row = sql_fetch($sql);
if (!$row["bmd_no"]) alert("잘못된 접근입니다!","");
$bmd_type = stripslashes($row['bmd_type']);
$bmd_image = explode(";", $row["bmd_image"]);

for ($i=1; $i<count($bmd_image); $i++) {
	if($bmd_image[$i]){
		if($i != 1){
			$data_images .=", ";
		}
		$data_images .= $iw[path].$upload_path."/".$bmd_image[$i];
	}
}
?>

<div class="book_main_wrap" style="background-color:<?=$bn_color?>;">
	<div class="wrap">
		<div class="top_back" style="border-bottom:2px solid <?=$bn_font?>;">
			<a href="<?=$iw['viewer_path']?>/media_main.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>" style="color:<?=$bn_font?>;">< BACK</a>
		</div>
		<div class="top_title" style="color:<?=$bn_font?>;">
			<?=$bmd_type?>
		</div>
		<div class="ratation_view">
			<div class="box_type"> 
				<div class="cell"> 
					<div class="inner"> 
						<img src="<?=$iw[path].$upload_path."/".$bmd_image[1]?>" width="100%" height="100%" class="reel" id="image" data-images="<?=$data_images?>">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="media_rotation/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="media_rotation/jquery.reel.js"></script>

<?php
include_once("_tail.php");
?>



