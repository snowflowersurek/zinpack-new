<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
	$bd_code = trim(mysql_real_escape_string($_POST[bd_code]));
	$bt_no = trim(mysql_real_escape_string($_POST[bt_no]));
	$bt_title_kr = trim(mysql_real_escape_string($_POST[bt_title_kr]));
	$bt_sub_kr = trim(mysql_real_escape_string($_POST[bt_sub_kr]));
	$bt_title_us = trim(mysql_real_escape_string($_POST[bt_title_us]));
	$bt_sub_us = trim(mysql_real_escape_string($_POST[bt_sub_us]));
	$bt_person = trim(mysql_real_escape_string($_POST[bt_person]));
	$bt_page = trim(mysql_real_escape_string($_POST[bt_page]));

	$sql = "update $iw[book_thesis_table] set
			bt_title_kr = '$bt_title_kr',
			bt_sub_kr = '$bt_sub_kr',
			bt_title_us = '$bt_title_us',
			bt_sub_us = '$bt_sub_us',
			bt_person = '$bt_person',
			bt_page = '$bt_page'
			where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bt_no = '$bt_no' 
			";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/thesis/thesis_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>