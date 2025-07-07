<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$dd_code = $_GET["idx"];
$dd_display = $_GET["dis"];

$sql = "update $iw[doc_data_table] set
		dd_display = '$dd_display'
		where dd_code = '$dd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
		";

sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_display = '$dd_display'
		where td_code = '$dd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]'
		";
sql_query($sql);

goto_url("$iw[admin_path]/doc_approval_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



