<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$bd_code = $_GET["idx"];
$sql = "update $iw[book_data_table] set
		bd_display = 2
		where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code='$iw[member]'
		";

sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_display = 2
		where td_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]'
		";
sql_query($sql);

alert("이북 승인을 요청하였습니다.","$iw[admin_path]/book_data_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>