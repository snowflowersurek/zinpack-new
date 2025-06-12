<?php
include_once("_common.php");
if ($iw['level'] != "admin") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$mb_password = trim(mysql_real_escape_string($_POST[mb_password]));
$mb_code = trim(mysql_real_escape_string($_POST[mb_code]));
$mb_name = trim(mysql_real_escape_string($_POST[mb_name]));
$mb_nick = trim(mysql_real_escape_string($_POST[mb_nick]));
$mb_address = trim(mysql_real_escape_string($_POST[mb_address]));
$mb_address_sub = trim(mysql_real_escape_string($_POST[mb_address_sub]));
$mb_sub_mail = trim(mysql_real_escape_string($_POST[mb_sub_mail]));

if($iw[language]=="ko"){
	$mb_tel_1 = trim(mysql_real_escape_string($_POST[mb_tel_1]));
	$mb_tel_2 = trim(mysql_real_escape_string($_POST[mb_tel_2]));
	$mb_tel_3 = trim(mysql_real_escape_string($_POST[mb_tel_3]));
	$mb_tel = $mb_tel_1."-".$mb_tel_2."-".$mb_tel_3;
	$mb_zip_code = trim(mysql_real_escape_string($_POST[mb_zip_code]));
}else if($iw[language]=="en"){
	$mb_tel = trim(mysql_real_escape_string($_POST[mb_tel]));
	$mb_zip_code = trim(mysql_real_escape_string($_POST[mb_zip_code]));
	$mb_address_city = trim(mysql_real_escape_string($_POST[mb_address_city]));
	$mb_address_state = trim(mysql_real_escape_string($_POST[mb_address_state]));
	$mb_address_country = trim(mysql_real_escape_string($_POST[mb_address_country]));
}

if ($mb_password != ""){
	$sql_password = "mb_password = '".sql_password($mb_password)."',";
}

$rownick = sql_fetch(" select count(*) as cnt from $iw[member_table] where mb_nick = '$mb_nick' and ep_code = '$iw[store]' and mb_code <> '$mb_code' ");
if ($rownick[cnt]) {
	alert("닉네임이 이미 존재합니다.","");
}else{
	$sql = "update $iw[member_table] set
			{$sql_password}
			mb_name = '$mb_name',
			mb_nick = '$mb_nick',
			mb_tel = '$mb_tel',
			mb_sub_mail = '$mb_sub_mail',
			mb_zip_code = '$mb_zip_code',
			mb_address = '$mb_address',
			mb_address_sub = '$mb_address_sub',
			mb_address_city = '$mb_address_city',
			mb_address_state = '$mb_address_state',
			mb_address_country = '$mb_address_country'
			where mb_code = '$mb_code' and ep_code = '$iw[store]'
			";

	sql_query($sql);

	alert("회원정보가 수정되었습니다.","$iw[admin_path]/member_data_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$mb_code");
}
?>