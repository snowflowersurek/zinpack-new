<?php
include_once("_common.php");
if (($iw[gp_level] == "gp_guest" && $iw[group] != "all") || ($iw[level] == "guest" && $iw[group] == "all")) alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

$seller_mb_code = $_POST["seller_mb_code"];
$ms_price = $_POST["ms_price"];
$ms_subject = $_POST["ms_subject"];

$sql = "select mb_nick from $iw[member_table] where mb_code = '$seller_mb_code' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0206","받으실분이 존재하지 않습니다."));
$seller_nick = $row["mb_nick"];

$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'");
if ($row[mb_point] < $ms_price)
    alert(national_language($iw[language],"a0207","포인트 잔액이 부족합니다."),"$iw[m_path]/all_point_charge.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

$ms_datetime = date("Y-m-d H:i:s");

$sql = "update $iw[member_table] set
		mb_point = mb_point-$ms_price
		where ep_code = '$iw[store]' and mb_code = '$iw[member]'
		";
sql_query($sql);

$pt_content = "[후원]".$seller_nick." 님에게 선물";
$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'");
$sql = "insert into $iw[point_table] set
		ep_code = '$iw[store]',
		mb_code = '$iw[member]',
		state_sort = '$iw[type]',
		pt_withdraw = '$ms_price',
		pt_balance = '$row[mb_point]',
		pt_content = '$pt_content',
		pt_datetime = '$ms_datetime'
		";
sql_query($sql);

//받는 사람
$sql = "select mb_nick from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
$pt_content = $row["mb_nick"]." 님의 선물";

$sql = "update $iw[member_table] set
			mb_point = mb_point+$ms_price
			where ep_code = '$iw[store]' and mb_code = '$seller_mb_code'
			";
sql_query($sql);

$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$seller_mb_code'");
$sql = "insert into $iw[point_table] set
		ep_code = '$iw[store]',
		mb_code = '$seller_mb_code',
		state_sort = '$iw[type]',
		pt_deposit = '$ms_price',
		pt_balance = '$row[mb_point]',
		pt_content = '$pt_content',
		pt_datetime = '$ms_datetime'
		";
sql_query($sql);


$ms_divice = explode("(", $_SERVER[HTTP_USER_AGENT]);
$ms_divice = explode(")", $ms_divice[1]);
$ms_divice = $ms_divice[0];
$sql = "insert into $iw[mcb_support_table] set
		ep_code = '$iw[store]',
		gp_code = '$iw[group]',
		mb_code = '$iw[member]',
		seller_mb_code = '$seller_mb_code',
		ms_subject = '$ms_subject',
		ms_price = '$ms_price',
		ms_ip = '$_SERVER[REMOTE_ADDR]',
		ms_divice = '$ms_divice',
		ms_datetime = '$ms_datetime',
		ms_display = 1
		";
sql_query($sql);

alert(national_language($iw[language],"a0208","성공적으로 선물하였습니다."),"$iw[m_path]/all_point_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />



