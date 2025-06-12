<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$idx = trim(mysql_real_escape_string($_POST[idx]));
$stat = trim(mysql_real_escape_string($_POST[stat]));
$strgubun = trim(mysql_real_escape_string($_POST[strgubun]));
$strgubunTxt = trim(mysql_real_escape_string($_POST[strgubunTxt]));
$strOrgan = trim(mysql_real_escape_string($_POST[strOrgan]));
$userTel1 = trim(mysql_real_escape_string($_POST[userTel1]));
$userTel2 = trim(mysql_real_escape_string($_POST[userTel2]));
$userTel3 = trim(mysql_real_escape_string($_POST[userTel3]));
$userTel = $userTel1."-".$userTel2."-".$userTel3;
$userPhone1 = trim(mysql_real_escape_string($_POST[userPhone1]));
$userPhone2 = trim(mysql_real_escape_string($_POST[userPhone2]));
$userPhone3 = trim(mysql_real_escape_string($_POST[userPhone3]));
$userPhone = $userPhone1."-".$userPhone2."-".$userPhone3;
$userEmail = trim(mysql_real_escape_string($_POST[userEmail]));
$zipcode = trim(mysql_real_escape_string($_POST[zipcode]));
$addr1 = trim(mysql_real_escape_string($_POST[addr1]));
$addr2 = trim(mysql_real_escape_string($_POST[addr2]));
$homepage = trim(mysql_real_escape_string($_POST[homepage]));
$picture_idx = trim(mysql_real_escape_string($_POST[picture_idx]));
$picture_name = trim(mysql_real_escape_string($_POST[picture_name]));
$exhibitDate = trim(mysql_real_escape_string($_POST[exhibitDate]));
$arrayExhibitDate = explode("-", $exhibitDate);
$else_txt = trim(mysql_real_escape_string($_POST[else_txt]));
$admin_txt = trim(mysql_real_escape_string($_POST[admin_txt]));

$sql = "update $iw[publishing_exhibit_status_table] set
		stat = '$stat',
		strgubun = '$strgubun',
		strgubunTxt = '$strgubunTxt',
		strOrgan = '$strOrgan',
		userTel = '$userTel',
		userPhone = '$userPhone',
		userEmail = '$userEmail',
		zipcode = '$zipcode',
		addr1 = '$addr1',
		addr2 = '$addr2',
		homepage = '$homepage',
		picture_idx = '$picture_idx',
		picture_name = '$picture_name',
		year = $arrayExhibitDate[0],
		month = $arrayExhibitDate[1],
		else_txt = '$else_txt',
		admin_txt = '$admin_txt'
		where idx = '$idx' and ep_code = '$iw[store]'";
sql_query($sql);

alert("그림전시 신청정보가 수정되었습니다.","$iw[admin_path]/publishing_exhibit_status_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>