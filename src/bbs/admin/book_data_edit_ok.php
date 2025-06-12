<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$upload_path = $_POST[upload_path];
$bd_code = trim(mysql_real_escape_string($_POST[bd_code]));
$cg_code = trim(mysql_real_escape_string($_POST[cg_code]));
$bd_image = trim(mysql_real_escape_string($_POST[bd_image]));
$bd_subject = trim(mysql_real_escape_string($_POST[bd_subject]));
$bd_author = trim(mysql_real_escape_string($_POST[bd_author]));
$bd_publisher = trim(mysql_real_escape_string($_POST[bd_publisher]));
$bd_price = trim(mysql_real_escape_string($_POST[bd_price]));
$bd_tag = trim(mysql_real_escape_string($_POST[bd_tag]));
$bd_content = mysql_real_escape_string($_POST[contents1]);
$bd_image_old = trim(mysql_real_escape_string($_POST[bd_image_old]));

$row = sql_fetch(" select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code' ");
if (!$row[cg_code]) {
	alert("카테고리가 존재하지 않습니다.","");
}else if($iw[mb_level] < $row[cg_level_write]){
	alert("해당 카테고리에 글쓰기 권한이 없습니다.","");
}else{
	$abs_dir = $iw[path].$upload_path;
	if($_FILES["bd_image"]["name"] && $_FILES["bd_image"]["size"]>0){
		$bd_image_name = uniqid(rand());

		$bd_image = $bd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bd_image"]["name"]);
		$result = move_uploaded_file($_FILES["bd_image"]["tmp_name"], "{$abs_dir}/{$bd_image}");
		
		if($result){
			$sql = "update $iw[book_data_table] set
				bd_image = '$bd_image'
				where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
				";
			sql_query($sql);

			if(is_file($abs_dir."/".$bd_image_old)==true){
				unlink($abs_dir."/".$bd_image_old);
			}
		}else{
			alert("이미지 첨부에러.","");
		}
	}

	$sql = "update $iw[book_data_table] set
			cg_code = '$cg_code',
			bd_subject = '$bd_subject',
			bd_author = '$bd_author',
			bd_publisher = '$bd_publisher',
			bd_price = '$bd_price',
			bd_tag = '$bd_tag',
			bd_content = '$bd_content'
			where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
			";

	sql_query($sql);
	
	$td_edit_datetime = date("Y-m-d H:i:s");
	$sql = "update $iw[total_data_table] set
			cg_code = '$cg_code',
			td_title = '$bd_subject',
			td_edit_datetime = '$td_edit_datetime'
			where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and td_code = '$bd_code'";
	sql_query($sql);

	alert("이북정보가 변경되었습니다.","$iw[admin_path]/book_data_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code");
}
?>