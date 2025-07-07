<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
if (!$_GET['idx'] || !$_GET['no']) exit;
$bd_code = $_GET["idx"];
$bt_no = $_GET["no"];

$sql = "select * from $iw[book_thesis_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]' and bt_no = '$bt_no'";
$row = sql_fetch($sql);
if (!$row["bt_no"]) alert("잘못된 접근입니다!","");

$sql = "delete from $iw[book_thesis_table] where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bt_no = '$bt_no'";
sql_query($sql);

echo "<script>window.parent.location.href='$iw[admin_path]/thesis/thesis_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>



