<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
if (!$_GET['idx'] || !$_GET['no']) exit;
$bd_code = $_GET["idx"];
$bm_no = $_GET["no"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;
$abs_dir = $iw[path].$upload_path;

$sql = "select * from $iw[book_media_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]' and bm_no = '$bm_no'";
$row = sql_fetch($sql);
if (!$row["bm_no"]) alert("잘못된 접근입니다!","");
$bm_no = $row['bm_no'];
$bm_image = $row['bm_image'];
$bm_order = $row['bm_order'];

if(is_file($abs_dir."/".$bm_image)==true){
	unlink($abs_dir."/".$bm_image);
}

$sql = "delete from $iw[book_media_table] where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bm_no = '$bm_no'";
sql_query($sql);

$sql = "delete from $iw[book_media_detail_table] where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bm_order = '$bm_order'";
sql_query($sql);

echo "<script>window.parent.location.href='$iw[admin_path]/media/media_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>