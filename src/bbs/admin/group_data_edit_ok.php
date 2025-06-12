<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[gp_level] != "gp_admin" || $iw[group] == "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$gp_language = trim(mysql_real_escape_string($_POST[gp_language]));
$gp_subject = trim(mysql_real_escape_string($_POST[gp_subject]));
$gp_content = mysql_real_escape_string($_POST[gp_content]);
$gp_autocode = trim(mysql_real_escape_string($_POST[gp_autocode]));
$gp_type = trim(mysql_real_escape_string($_POST[gp_type]));
$gp_closed = trim(mysql_real_escape_string($_POST[gp_closed]));

$rowsubject = sql_fetch(" select count(*) as cnt from $iw[group_table] where ep_code = '$iw[store]' and gp_subject = '$gp_subject' and gp_code != '$iw[group]'");
if ($rowsubject[cnt]) {
	alert("그룹이름이 이미 존재합니다.","");
}else{
	$sql = "update $iw[group_table] set
			gp_language = '$gp_language',
			gp_subject = '$gp_subject',
			gp_content = '$gp_content',
			gp_autocode = '$gp_autocode',
			gp_type = '$gp_type',
			gp_closed = '$gp_closed'
			where ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]'
			";

	sql_query($sql);

	alert("그룹정보가 수정되었습니다.","$iw[admin_path]/group_data_edit.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>