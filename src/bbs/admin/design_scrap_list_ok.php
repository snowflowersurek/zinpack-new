<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$hs_scrap = $_GET[scrap];
$hm_code = $_GET[menu];
$menu_output = str_replace("\\", "", $_POST[menu_output]);
$json = json_decode($menu_output, true);

$a = 1;
foreach($json as $key => $value)
{
	$sql = "update $iw[home_scrap_table] set
		hs_order = '$a'
		where hs_no = '$value[id]' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and hs_scrap = '$hs_scrap' and hm_code = '$hm_code'
		";
	sql_query($sql);

	$a++;
}

echo "<script>window.parent.location.href='$iw[admin_path]/design_scrap_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&menu=$hm_code&scrap=$hs_scrap';</script>";

?>



