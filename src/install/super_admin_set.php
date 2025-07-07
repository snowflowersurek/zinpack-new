<?php

include_once("_common.php");

$sql = "insert into $iw[member_table] set
		mb_code = 'wizwindigital',
		ep_code = 'wizwindigital',
		mb_mail = 'wizwindigital',
		mb_password = '".sql_password("wizwin00")."',
		mb_name = '(주)위즈윈디지털',
		mb_nick = '최고관리자',
		mb_display = 9
		";
sql_query($sql);

$ma_userid = sql_password("infoway");
$ma_password = sql_password("infoway123");
$buy_rate = 1523;
$sell_rate = 1523;
$shop_rate = 1523;

$sql = "insert into $iw[master_table] set
		ma_userid = '$ma_userid',
		ma_password = '$ma_password',
		ma_buy_rate = '$buy_rate',
		ma_sell_rate = '$sell_rate',
		ma_shop_rate = '$shop_rate',
		ma_display = 1
		";
sql_query($sql);

?>



