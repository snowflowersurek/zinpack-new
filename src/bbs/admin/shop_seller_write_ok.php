<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "member" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$ss_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['ss_name'] ?? ''));
$ss_tel = trim(mysqli_real_escape_string($iw['connect'], $_POST['ss_tel'] ?? ''));
$ss_zip_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['ss_zip_code'] ?? ''));
$ss_address = trim(mysqli_real_escape_string($iw['connect'], $_POST['ss_address'] ?? ''));
$ss_address_sub = trim(mysqli_real_escape_string($iw['connect'], $_POST['ss_address_sub'] ?? ''));
$ss_content = mysqli_real_escape_string($iw['connect'], $_POST['ss_content'] ?? '');

$rowsubject = sql_fetch(" select count(*) as cnt from $iw[shop_seller_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' ");
$rownick = sql_fetch(" select count(*) as cnt from $iw[shop_seller_table] where ep_code = '$iw[store]' and ss_name = '$ss_name' ");
if ($rowsubject[cnt]) {
	alert("이미 등록되었거나 신청되었습니다.","");
}else if ($rownick[cnt]) {
	alert("판매자(상호명)가 이미 존재합니다.","");
}else{
	$ss_datetime = date("Y-m-d H:i:s");

	$sql = "insert into $iw[shop_seller_table] set
			ep_code = '$iw[store]',
			mb_code = '$iw[member]',
			ss_name = '$ss_name',
			ss_tel = '$ss_tel',
			ss_zip_code = '$ss_zip_code',
			ss_address = '$ss_address',
			ss_address_sub = '$ss_address_sub',
			ss_content = '$ss_content',
			ss_datetime = '$ss_datetime',
			ss_display = 0
			";
	sql_query($sql);

	alert("판매자등록이 신청되었습니다.","$iw[admin_path]/shop_seller_write.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>



