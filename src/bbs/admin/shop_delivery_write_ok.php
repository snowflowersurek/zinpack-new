<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$sy_price = trim(mysql_real_escape_string($_POST[sy_price]));
$sy_name = trim(mysql_real_escape_string($_POST[sy_name]));
$sy_display = trim(mysql_real_escape_string($_POST[sy_display]));

if($sy_display == 1){
	$sy_max = trim(mysql_real_escape_string($_POST[sy_max]));
	if($iw[language]=="en"){
		$sy_max = ($sy_max * 1000) + (trim(mysql_real_escape_string($_POST[sy_max_2]))*10);
	}
}else{
	$sy_max = trim(mysql_real_escape_string($_POST[sy_max_3]));
}
if($iw[language]=="en"){
	$sy_price = ($sy_price * 1000) + (trim(mysql_real_escape_string($_POST[sy_price_2]))*10);
}

$row = sql_fetch(" select sy_code from $iw[shop_delivery_table] where sy_no = (select max(sy_no) from $iw[shop_delivery_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]') ");
$sy_code = number_format($row["sy_code"])+1;
$sy_code = str_pad($sy_code,"4","0",STR_PAD_LEFT); 

$sql = "insert into $iw[shop_delivery_table] set
		ep_code = '$iw[store]',
		mb_code = '$iw[member]',
		sy_code = '$sy_code',
		sy_name = '$sy_name',
		sy_price = '$sy_price',
		sy_max = '$sy_max',
		sy_display = '$sy_display'
		";

sql_query($sql);

alert("배송코드($sy_code)가 생성되었습니다.","$iw[admin_path]/shop_delivery_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

?>



