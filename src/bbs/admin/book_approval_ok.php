<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$bd_code = $_GET["idx"];
$bd_display = $_GET["dis"];

$sql = "update $iw[book_data_table] set
		bd_display = '$bd_display'
		where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
		";

sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_display = '$bd_display'
		where td_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]'
		";
sql_query($sql);

goto_url("$iw[admin_path]/book_approval_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



