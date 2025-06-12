<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
for ($i=0; $i<10; $i++) {
	$gl_no = trim(mysql_real_escape_string($_POST[gl_no][$i]));
	$gl_level = trim(mysql_real_escape_string($_POST[gl_level][$i]));
	$gl_name = trim(mysql_real_escape_string($_POST[gl_name][$i]));
	$gl_content = trim(mysql_real_escape_string($_POST[gl_content][$i]));

	$sql = "update $iw[group_level_table] set
			gl_name = '$gl_name',
			gl_content = '$gl_content'
			where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_level = '$gl_level' and gl_no = '$gl_no'
			";
	sql_query($sql);
}
alert("회원등급 설정이 저장되었습니다.","$iw[admin_path]/group_level_edit.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>