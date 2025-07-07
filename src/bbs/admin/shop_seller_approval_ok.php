<?php
include_once("_common.php");
if ($iw[type] != "shop" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$ss_no = $_GET[idx];
$ss_display = $_GET[dis];

$row = sql_fetch("select * from $iw[shop_seller_table] where ep_code = '$iw[store]' and ss_display = 0 and ss_no = '$ss_no'");
if (!$row["ss_no"]) alert("잘못된 접근입니다!!","");
$mb_code = $row["mb_code"];

$row = sql_fetch("select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code' and (mb_display = 1 or mb_display = 7)");
if (!$row["mb_no"]) alert("잘못된 접근입니다!!!","");

$sql = "update $iw[shop_seller_table] set
		ss_display = '$ss_display'
		where ss_no = '$ss_no' and ep_code = '$iw[store]'
		";

sql_query($sql);

$sql = "update $iw[member_table] set
		mb_display = 4
		where mb_code = '$mb_code' and ep_code = '$iw[store]' and mb_display = 1
		";

sql_query($sql);

alert("판매자등록이 승인되었습니다.","$iw[admin_path]/shop_seller_approval_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



