<?php
include_once("_common.php");
if ($iw[level] != "admin") alert("잘못된 접근입니다!","");

if (!$_GET['idx']) alert("잘못된 접근입니다!","");
$pl_no = $_GET['idx'];

$sql = "select pl_no from $iw[popup_layer_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and pl_no = '$pl_no'";
$row = sql_fetch($sql);
if (!$row["pl_no"]) alert("잘못된 접근입니다!","");

$sql = "delete from $iw[popup_layer_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and pl_no = '$pl_no'";
sql_query($sql);


alert("팝업창이 삭제되었습니다.","$iw[admin_path]/popup_layer_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



