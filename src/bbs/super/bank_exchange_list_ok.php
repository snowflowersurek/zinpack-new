<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$ec_no = $_GET["idx"];
$ec_give_datetime = date("Y-m-d H:i:s");

$sql = "update $iw[exchange_table] set
		ec_display = 1,
		ec_give_datetime = '$ec_give_datetime'
		where ec_no = '$ec_no'";
sql_query($sql);

alert("입금으로 처리되었습니다.","$iw[super_path]/bank_exchange_list.php?type=$iw[level]&ep=$iw[store]&gp=$iw[group]");
?>



