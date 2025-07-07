<?php
include_once("_common.php");
include_once("$iw[include_path]/lib/lib_image_resize.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
if (!$_GET['seq']) exit;

$seq = $_GET[seq];

$row = sql_fetch("select ep_nick, ep_upload, ep_upload_size from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]/author";
$abs_dir = $iw[path].$upload_path;

$row = sql_fetch("select AuthorID, Photo from $iw[publishing_author_table] where intSeq = '$seq'");
$AuthorID = $row["AuthorID"];
$Photo = $row["Photo"];

if(is_file($abs_dir."/".$Photo)){
	unlink($abs_dir."/".$Photo);
}

// publishing_author
$sql = "delete from $iw[publishing_author_table] where intSeq = '$seq'";
sql_query($sql);

// total_data
$sql = "delete from $iw[total_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'author' and td_code = '$AuthorID'";
sql_query($sql);

alert("작가정보가 삭제되었습니다.","$iw[admin_path]/publishing_author_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



