<?php
include_once("_common.php");
include_once("$iw[include_path]/lib/lib_image_resize.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$contest_code = $_GET["contest_code"];
$idx = $_GET["idx"];

if (!$contest_code || !$idx) exit;

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = $iw[path].'/publishing/'.$row[ep_nick].'/contest/'.$contest_code;

$sql = "select attach_filename from iw_publishing_contestant where ep_code = '$iw[store]' and contest_code = '$contest_code' and idx = '$idx'";
$file_row = sql_fetch($sql);
$filepath = $upload_path."/".$file_row[attach_filename];

if(is_file($filepath)){
	unlink($filepath);
}

// contestant
$sql = "delete from iw_publishing_contestant where idx = '$idx'";
sql_query($sql);

alert("응모한 작품이 삭제되었습니다.","$iw[admin_path]/publishing_contestant_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&contest_code=$contest_code");
?>



