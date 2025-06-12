<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$mb_no = $_GET[idx];

$sql = "update $iw[member_table] set
		mb_display = 1
		where mb_no = '$mb_no' and ep_code = '$iw[store]'
		";

sql_query($sql);

alert("이메일인증이 승인되었습니다.","$iw[admin_path]/member_data_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

?>