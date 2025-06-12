<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$nt_no = trim(mysql_real_escape_string($_POST[nt_no]));
$nt_subject = trim(mysql_real_escape_string($_POST[nt_subject]));
$nt_type = trim(mysql_real_escape_string($_POST[nt_type]));
$nt_content = mysql_real_escape_string($_POST[contents1]);

$sql = "update $iw[notice_table] set
		nt_type = '$nt_type',
		nt_subject = '$nt_subject',
		nt_content = '$nt_content'
		where nt_no = '$nt_no' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
		";

sql_query($sql);

alert("공지사항이 수정되었습니다.","$iw[admin_path]/home_notice_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$nt_no");

?>