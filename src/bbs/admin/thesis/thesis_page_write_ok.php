<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
	$bd_code = trim(mysql_real_escape_string($_POST[bd_code]));
	$bt_title_kr = trim(mysql_real_escape_string($_POST[bt_title_kr]));
	$bt_sub_kr = trim(mysql_real_escape_string($_POST[bt_sub_kr]));
	$bt_title_us = trim(mysql_real_escape_string($_POST[bt_title_us]));
	$bt_sub_us = trim(mysql_real_escape_string($_POST[bt_sub_us]));
	$bt_person = trim(mysql_real_escape_string($_POST[bt_person]));
	$bt_page = trim(mysql_real_escape_string($_POST[bt_page]));

	$row = sql_fetch(" select max(bt_order) as max_order from $iw[book_thesis_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'");
	$bt_order = $row["max_order"]+1;

	$sql = "insert into $iw[book_thesis_table] set
			bd_code = '$bd_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			bt_order = '$bt_order',
			bt_title_kr = '$bt_title_kr',
			bt_title_us = '$bt_title_us',
			bt_sub_kr = '$bt_sub_kr',
			bt_sub_us = '$bt_sub_us',
			bt_person = '$bt_person',
			bt_page = '$bt_page',
			bt_display = 1
			";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/thesis/thesis_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>



