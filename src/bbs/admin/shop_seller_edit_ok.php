<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$ss_no = trim(mysql_real_escape_string($_POST[ss_no]));
$ss_name = trim(mysql_real_escape_string($_POST[ss_name]));
$ss_tel = trim(mysql_real_escape_string($_POST[ss_tel]));
$ss_zip_code = trim(mysql_real_escape_string($_POST[ss_zip_code]));
$ss_address = trim(mysql_real_escape_string($_POST[ss_address]));
$ss_address_sub = trim(mysql_real_escape_string($_POST[ss_address_sub]));
$ss_content = mysql_real_escape_string($_POST[ss_content]);

$sql = "update $iw[shop_seller_table] set
		ss_name = '$ss_name',
		ss_tel = '$ss_tel',
		ss_zip_code = '$ss_zip_code',
		ss_address = '$ss_address',
		ss_address_sub = '$ss_address_sub',
		ss_content = '$ss_content'
		where ss_no = '$ss_no' and ep_code = '$iw[store]'
		";

sql_query($sql);

alert("판매자정보가 수정되었습니다.","$iw[admin_path]/shop_seller_edit.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>