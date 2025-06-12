<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$idx = $_GET["idx"];

$sql = "delete from $iw[publishing_exhibit_status_table] where idx = '$idx' and ep_code = '$iw[store]'";
sql_query($sql);

alert("그림전시 신청정보가 삭제되었습니다.","$iw[admin_path]/publishing_exhibit_status_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>