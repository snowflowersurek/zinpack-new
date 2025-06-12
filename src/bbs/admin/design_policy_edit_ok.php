<?php
include_once("_common.php");
if ($iw['level'] != "admin") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$ep_no = trim(mysql_real_escape_string($_POST[ep_no]));
$ep_policy_agreement = mysql_real_escape_string($_POST[contents1]);
$ep_policy_private = mysql_real_escape_string($_POST[contents2]);
$ep_policy_email = mysql_real_escape_string($_POST[contents3]);

$sql = "update $iw[enterprise_table] set
		ep_policy_agreement = '$ep_policy_agreement',
		ep_policy_private = '$ep_policy_private',
		ep_policy_email = '$ep_policy_email'
		where ep_no = '$ep_no' and ep_code = '$iw[store]' and mb_code = '$iw[member]' 
		";

sql_query($sql);

alert("이용약관이 변경되었습니다.","$iw[admin_path]/design_policy_edit.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>