<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$hm_code = trim(mysql_real_escape_string($_POST[hm_code]));
$cg_code = trim(mysql_real_escape_string($_POST[cg_code]));
$state_sort = trim(mysql_real_escape_string($_POST[state_sort]));
$hm_name = trim(mysql_real_escape_string($_POST[hm_name]));
$hm_list_scrap = trim(mysql_real_escape_string($_POST[hm_list_scrap]));
$hm_view_scrap = trim(mysql_real_escape_string($_POST[hm_view_scrap]));
$hm_list_style = trim(mysql_real_escape_string($_POST[hm_list_style]));
$hm_link = trim(mysql_real_escape_string($_POST[hm_link]));
$hm_view_size = trim(mysql_real_escape_string($_POST[hm_view_size]));
$hm_view_scrap_mobile = trim(mysql_real_escape_string($_POST[hm_view_scrap_mobile]));
$hm_list_order = trim(mysql_real_escape_string($_POST[hm_list_order]));

if($state_sort == "main" || $state_sort == "link" || $state_sort == "close"){
	$hm_list_scrap = 0;
	$hm_view_scrap = 0;
	$hm_view_size = 12;
	$hm_view_scrap_mobile = 0;
}else if($state_sort == "open"){
	$hm_view_scrap = 0;
	$hm_view_size = 12;
	$hm_view_scrap_mobile = 0;
}else if($state_sort == "scrap"){
	$hm_list_scrap = 1;
	$hm_view_scrap = 0;
	$hm_view_size = 12;
	$hm_view_scrap_mobile = 0;
}

$sql = "update $iw[home_menu_table] set
		cg_code = '$cg_code',
		state_sort = '$state_sort',
		hm_name = '$hm_name',
		hm_list_scrap = '$hm_list_scrap',
		hm_view_scrap = '$hm_view_scrap',
		hm_list_style = '$hm_list_style',
		hm_link = '$hm_link',
		hm_view_size = $hm_view_size,
		hm_view_scrap_mobile = $hm_view_scrap_mobile,
		hm_list_order = $hm_list_order
		where hm_code = '$hm_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
		";

sql_query($sql);

echo "<script>window.parent.location.href='$iw[admin_path]/design_menu_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]';</script>";

?>