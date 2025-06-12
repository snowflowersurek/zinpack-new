<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$ma_buy_rate = trim(mysql_real_escape_string($_POST[ma_buy_rate]));
$ma_sell_rate = trim(mysql_real_escape_string($_POST[ma_sell_rate]));
$ma_shop_rate = trim(mysql_real_escape_string($_POST[ma_shop_rate]));
$ma_exchange_point = trim(mysql_real_escape_string($_POST[ma_exchange_point]));
$ma_exchange_amount = trim(mysql_real_escape_string($_POST[ma_exchange_amount]));

$buy_rate = ($ma_buy_rate * 100) + (trim(mysql_real_escape_string($_POST[ma_buy_rate_2]))*1);
$sell_rate = ($ma_sell_rate * 100) + (trim(mysql_real_escape_string($_POST[ma_sell_rate_2]))*1);
$shop_rate = ($ma_shop_rate * 100) + (trim(mysql_real_escape_string($_POST[ma_shop_rate_2]))*1);

$sql = "update $iw[master_table] set
		ma_buy_rate = '$buy_rate',
		ma_sell_rate = '$sell_rate',
		ma_shop_rate = '$shop_rate',
		ma_exchange_point = '$ma_exchange_point',
		ma_exchange_amount = '$ma_exchange_amount'
		where ma_no = 1";

sql_query($sql);

alert("포인트 환율이 수정되었습니다.","$iw[super_path]/bank_point_rate.php?type=$iw[level]&ep=$iw[store]&gp=$iw[group]");
?>