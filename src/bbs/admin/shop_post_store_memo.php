<?php
include_once("_common.php");
if ($iw[level] != "seller") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$sr_code = trim(mysql_real_escape_string($_POST[memo_sr_code]));
$srm_content = mysql_real_escape_string($_POST[srm_content]);

$row = sql_fetch(" select count(*) as cnt from $iw[shop_order_memo_table] where ep_code = '$iw[store]' and sr_code = '$sr_code' and seller_mb_code = '$iw[member]' ");
if ($row[cnt]) {
	$sql = "update $iw[shop_order_memo_table] set
		srm_content = '$srm_content'
		where ep_code = '$iw[store]' and sr_code = '$sr_code' and seller_mb_code = '$iw[member]'
		";
	sql_query($sql);
}else{
	$sql = "insert into $iw[shop_order_memo_table] set
			ep_code = '$iw[store]',
			sr_code = '$sr_code',
			seller_mb_code = '$iw[member]',
			srm_content = '$srm_content'
			";
	sql_query($sql);
}
alert("판매자 메모가 저장되었습니다.","$iw[admin_path]/shop_post_store_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$sr_code");
?>



