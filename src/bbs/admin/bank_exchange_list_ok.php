<?php
include_once("_common.php");
if ($iw[type] != "bank" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$row = sql_fetch(" select count(*) as cnt from $iw[account_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and ac_display = 1");
if (!$row[cnt]) alert("계좌정보 등록후 환전하실 수 있습니다.","$iw[admin_path]/bank_account_write.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

$sql = "select * from $iw[master_table] where ma_no = 1";
$row = sql_fetch($sql);
$ma_exchange_point = $row["ma_exchange_point"];
$ma_exchange_amount = $row["ma_exchange_amount"];

$sql = "select * from $iw[account_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
$ac_bank = $row["ac_bank"];
$ac_number = $row["ac_number"];
$ac_holder = $row["ac_holder"];

$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'");
if ($row[mb_point] < $ma_exchange_point)
    alert("보유 포인트를 확인하여 주십시오.","$iw[admin_path]/bank_exchange_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

$ec_datetime = date("Y-m-d H:i:s");

$sql = "update $iw[member_table] set
		mb_point = mb_point-$ma_exchange_point
		where ep_code = '$iw[store]' and mb_code = '$iw[member]'
		";
sql_query($sql);

$pt_content = "[환전]".number_format($ma_exchange_amount)."원";
$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'");
$sql = "insert into $iw[point_table] set
		ep_code = '$iw[store]',
		mb_code = '$iw[member]',
		state_sort = '$iw[type]',
		pt_withdraw = '$ma_exchange_point',
		pt_balance = '$row[mb_point]',
		pt_content = '$pt_content',
		pt_datetime = '$ec_datetime'
		";
sql_query($sql);

$sql = "insert into $iw[exchange_table] set
		ep_code = '$iw[store]',
		mb_code = '$iw[member]',
		ec_point = '$ma_exchange_point',
		ec_amount = '$ma_exchange_amount',
		ec_datetime = '$ec_datetime',
		ec_bank = '$ac_bank',
		ec_number = '$ac_number',
		ec_holder = '$ac_holder',
		ec_display = 0
		";

sql_query($sql);

alert("환전 신청이 정상적으로 처리되었습니다.","$iw[admin_path]/bank_exchange_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>