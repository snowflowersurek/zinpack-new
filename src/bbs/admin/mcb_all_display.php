<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$md_code = $_GET[idx];
$md_display = $_GET[dis];

$sql = "update $iw[mcb_data_table] set
		md_display = '$md_display'
		where md_code = '$md_code' and ep_code = '$iw[store]'
		";

sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_display = '$md_display'
		where td_code = '$md_code' and ep_code = '$iw[store]' and state_sort = '$iw[type]'
		";
sql_query($sql);

goto_url("$iw[admin_path]/mcb_all_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$md_code");
?>