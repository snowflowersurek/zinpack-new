<?php
include_once("_common.php");
include_once("_head.php");

$bd_code = $_GET["idx"];
$bt_no = $_GET["no"];

$sql = "select * from $iw[book_main_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code'";
$row = sql_fetch($sql);
$bn_color = $row["bn_color"];
$bn_font = $row["bn_font"];
$bn_file = $row["bn_file"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select * from $iw[book_thesis_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and bt_no = '$bt_no'";
$row = sql_fetch($sql);
if (!$row["bt_no"]) alert("잘못된 접근입니다!","");
$bt_page = $row["bt_page"];

?>

<div class="book_main_wrap" style="background-color:<?=$bn_color?>;">
	<div class="wrap">
		<div class="top_back" style="border-bottom:2px solid <?=$bn_font?>;">
			<a href="<?=$iw['viewer_path']?>/thesis_main.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>" style="color:<?=$bn_font?>;">< BACK</a>
		</div>
		<div class="pdf_viewer">
		<iframe type="text/html" width="100%" height="100%" src="pdf_viewer/viewer.php?file=<?=$upload_path."/".$bn_file?>#page=<?=$bt_page?>" frameborder="0">
		</iframe>
		</div>
	</div>
</div>

<?php
include_once("_tail.php");
?>



