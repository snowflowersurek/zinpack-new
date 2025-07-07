<?php
include_once("_common.php");
if (($iw['group'] == "all" && $iw['level'] != "admin") || ($iw['group'] != "all" && $iw['gp_level'] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$cg_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['cg_code']));
$state_sort = trim(mysqli_real_escape_string($iw['connect'], $_POST['state_sort']));
$hm_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_name']));
$hm_list_scrap = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_list_scrap']));
$hm_view_scrap = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_view_scrap']));
$hm_list_style = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_list_style']));
$hm_link = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_link']));
$hm_view_size = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_view_size']));
$hm_view_scrap_mobile = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_view_scrap_mobile']));
$hm_list_order = trim(mysqli_real_escape_string($iw['connect'], $_POST['hm_list_order']));

$hm_code = "hm".uniqid(rand());

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

$sql = "insert into $iw['home_menu_table'] set
		hm_code = '$hm_code',
		cg_code = '$cg_code',
		ep_code = '$iw['store']',
		gp_code = '$iw['group']',
		state_sort = '$state_sort',
		hm_name = '$hm_name',
		hm_order = '0',
		hm_deep = '1',
		hm_upper_code = '',
		hm_list_scrap = '$hm_list_scrap',
		hm_view_scrap = '$hm_view_scrap',
		hm_list_style = '$hm_list_style',
		hm_link = '$hm_link',
		hm_view_size = $hm_view_size,
		hm_view_scrap_mobile = $hm_view_scrap_mobile,
		hm_list_order = $hm_list_order,
		hm_display = '1'
		";

sql_query($sql);

echo "<script>window.parent.location.href='$iw['admin_path']/design_menu_list.php?type=$iw['type']&ep=$iw['store']&gp=$iw['group']';</script>";

?>



