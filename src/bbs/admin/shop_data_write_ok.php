<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$upload_path = $_POST[upload_path];
$sd_code = trim(mysql_real_escape_string($_POST[sd_code]));
$cg_code = trim(mysql_real_escape_string($_POST[cg_code]));
$sd_price = trim(mysql_real_escape_string($_POST[sd_price]));
$sd_sale = trim(mysql_real_escape_string($_POST[sd_sale]));
$sd_subject = trim(mysql_real_escape_string($_POST[sd_subject]));
$sd_information = mysql_real_escape_string($_POST[sd_information]);
$sd_max = trim(mysql_real_escape_string($_POST[sd_max]));
$sy_code = trim(mysql_real_escape_string($_POST[sy_code]));
$sd_content = mysql_real_escape_string($_POST[contents1]);

if($iw[language]=="en"){
	$sd_price = ($sd_price * 1000) + (trim(mysql_real_escape_string($_POST[sd_price_2]))*10);
	$sd_sale = ($sd_sale * 1000) + (trim(mysql_real_escape_string($_POST[sd_sale_2]))*10);
}

$row = sql_fetch(" select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code' ");
if (!$row[cg_code]) {
	alert("카테고리가 존재하지 않습니다.","");
}else if($iw[mb_level] < $row[cg_level_write]){
	alert("해당 카테고리에 글쓰기 권한이 없습니다.","");
}else{
	if($_FILES["sd_image"]["name"] && $_FILES["sd_image"]["size"]>0){
		$abs_dir = $iw[path].$upload_path;
		@mkdir($abs_dir, 0707);
		@chmod($abs_dir, 0707);

		$sd_image_name = uniqid(rand());

		$filename = $sd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["sd_image"]["name"]);
		$result = move_uploaded_file($_FILES["sd_image"]["tmp_name"], "{$abs_dir}/{$filename}");
		
		if(!$result){
			alert("이미지 첨부에러.","");
		}
	}

	$sd_datetime = date("Y-m-d H:i:s");

	$sql = "insert into $iw[shop_data_table] set
			sd_code = '$sd_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			cg_code = '$cg_code',
			sd_image = '$filename',
			sd_subject = '$sd_subject',
			sd_price = '$sd_price',
			sd_sale = '$sd_sale',
			sd_information = '$sd_information',
			sd_tag = '$sd_tag',
			sd_max = '$sd_max',
			sd_content = '$sd_content',
			sy_code = '$sy_code',
			sd_datetime = '$sd_datetime',
			sd_display = 0
			";

	sql_query($sql);

	for ($i=0; $i<count($_POST[so_name]); $i++) {
		$so_name = trim(mysql_real_escape_string($_POST[so_name][$i]));
		$so_amount = trim(mysql_real_escape_string($_POST[so_amount][$i]));
		$so_price = trim(mysql_real_escape_string($_POST[so_price][$i]));
		$so_taxfree = trim(mysql_real_escape_string($_POST[so_taxfree][$i]));
		if($iw[language]=="en"){
			$so_price = ($so_price * 1000) + (trim(mysql_real_escape_string($_POST[so_price_2][$i]))*10);
		}
		$sql = "insert into $iw[shop_option_table] set
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			sd_code = '$sd_code',
			so_name = '$so_name',
			so_amount = '$so_amount',
			so_price = '$so_price',
			so_taxfree = '$so_taxfree'
			";
		sql_query($sql);

	}

	$sql = "insert into $iw[total_data_table] set
			td_code = '$sd_code',
			cg_code = '$cg_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			state_sort = '$iw[type]',
			td_title = '$sd_subject',
			td_datetime = '$sd_datetime',
			td_edit_datetime = '$sd_datetime'
			";
	sql_query($sql);

	alert("상품정보가 등록되었습니다.","$iw[admin_path]/shop_data_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>



