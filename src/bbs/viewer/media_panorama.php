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
$bmd_image = $row["bmd_image"];

?>

<div class="book_main_wrap" style="background-color:<?=$bn_color?>;">
	<div class="wrap">
		<div class="top_back" style="border-bottom:2px solid <?=$bn_font?>;">
			<a href="<?=$iw['viewer_path']?>/media_main.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>" style="color:<?=$bn_font?>;">< BACK</a>
		</div>
		<div class="top_title" style="color:<?=$bn_font?>;">
			<?=$bmd_type?>
		</div>
		<div class="panorama-view">
			<div class="panorama-container">
				<img src="<?=$iw["path"].$upload_path."/".$bmd_image?>" data-width="1922" data-height="500" />
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="media_panorama/jquery.min.js"></script>
<script type="text/javascript" src="media_panorama/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="media_panorama/jquery.panorama360.js"></script>
<script>
	$(function(){
		$('.panorama-view').panorama360({
			sliding_controls: true
		});
	});
</script>

<style type="text/css">

/* panorama layout */
.panorama-view { margin-top: 2.6%; margin-left: 2.6%; width: 94.8%; height: 100%; overflow: hidden; }
.panorama-container { position: relative; }
.panorama-container img { height: 100%; position: absolute; top: 0; }

/* panorama style */
.panorama-view { cursor: url(../images/openhand.cur),default; }
.panorama-view img { -o-user-select: none; -moz-user-select: none; -webkit-user-select: none; user-select: none; }

/* retina display graphics */
@media only screen and (min-width: 480px) and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min--moz-device-pixel-ratio: 1.5), only screen and (min-device-pixel-ratio: 1.5) {
	.panorama .controls a.prev span, .panorama .controls a.stop span, .panorama .controls a.next span { background-image: url(../images/panorama-controls@2x.png); -webkit-background-size: 32px 14px; background-size: 32px 14px; }
}
</style>

<?php
include_once("_tail.php");
?>



