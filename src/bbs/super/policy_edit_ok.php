<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$ma_policy_agreement = mysql_real_escape_string($_POST[contents1]);
$ma_policy_private = mysql_real_escape_string($_POST[contents2]);
$ma_policy_email = mysql_real_escape_string($_POST[contents3]);

$sql = "update $iw[master_table] set
		ma_policy_agreement = '$ma_policy_agreement',
		ma_policy_private = '$ma_policy_private',
		ma_policy_email = '$ma_policy_email'
		where ma_no = 1";

sql_query($sql);

alert("이용약관이 수정되었습니다.","$iw[super_path]/policy_edit.php?type=$iw[level]&ep=$iw[store]&gp=$iw[group]");
?>