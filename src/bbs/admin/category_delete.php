<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
if (!$_GET['menu']) exit;
$cg_code = $_GET[menu];

if ($iw[type] == "publishing") {
	$table_name = "publishing_books_table";
	$row = sql_fetch(" select count(*) as cnt from $iw[$table_name] where cg_code = '$cg_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]'");
} else if ($iw[type] == "publishing_brand") {
	$table_name = "publishing_books_table";
	$row = sql_fetch(" select count(*) as cnt from $iw[$table_name] where brand_cg_code = '$cg_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]'");
} else {
	$table_name = $iw[type]."_data_table";
	$row = sql_fetch(" select count(*) as cnt from $iw[$table_name] where cg_code = '$cg_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]'");
}

if ($row[cnt]) {
	alert("게시물이 존재하므로, 삭제할 수 없습니다.","");
}else{

	$sql = "delete from $iw[category_table] where cg_code = '$cg_code' and state_sort = '$iw[type]' and ep_code = '$iw[store]' and gp_code = '$iw[group]'";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/category_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]';</script>";
}
?>



