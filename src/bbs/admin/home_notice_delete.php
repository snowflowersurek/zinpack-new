<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
if (!$_GET['idx']) exit;
$nt_no = $_GET[idx];

$sql = "delete from $iw[notice_table] where nt_no = '$nt_no' and ep_code = '$iw[store]' and gp_code = '$iw[group]'";
sql_query($sql);

alert("공지사항이 삭제되었습니다.","$iw[admin_path]/home_notice_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



