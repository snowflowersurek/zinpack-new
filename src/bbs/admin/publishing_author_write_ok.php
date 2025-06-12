<?php
include_once("_common.php");
include_once("$iw[include_path]/lib/lib_image_resize.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$sql = "select AuthorID from $iw[publishing_author_table] where ep_code = '$iw[store]' order by CAST(SUBSTR(AuthorID, 2) AS UNSIGNED) desc limit 1";
$row = sql_fetch($sql);
if ($row["AuthorID"]) {
	$num = (int) substr($row["AuthorID"], 1);
	$AuthorID = "A".sprintf("%04d", $num + 1);
} else {
	$AuthorID = "A0001";
}

$upload_path = $_POST[upload_path];
$ep_upload_size = trim(mysql_real_escape_string($_POST[ep_upload_size]));
$Author = trim(mysql_real_escape_string($_POST[Author]));
$original_name = trim(mysql_real_escape_string($_POST[original_name]));
$content_type = trim(mysql_real_escape_string($_POST[content_type]));
if($content_type == 1){
	$ProFile = mysql_real_escape_string($_POST[ProFile1]);
}else{
	$ProFile = mysql_real_escape_string($_POST[ProFile2]);
}
$phone = trim(mysql_real_escape_string($_POST[phone]));
$author_display = trim(mysql_real_escape_string($_POST[author_display]));

$RegDate = date("Y-m-d H:i:s");
$td_datetime = date("Y-m-d H:i:s");

if($_FILES["NewPhoto"]["name"] && $_FILES["NewPhoto"]["size"] > 1024*1024*$ep_upload_size){
	alert("파일첨부의 최대크기는 {$ep_upload_size}MB 입니다.","");
}else{
	if($_FILES["NewPhoto"]["name"] && $_FILES["NewPhoto"]["size"]>0){
		$abs_dir = $iw[path].$upload_path;
		@mkdir($abs_dir, 0707);
		@chmod($abs_dir, 0707);
		
		$photo_img_name = uniqid(rand());
		$filename = $photo_img_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES["NewPhoto"]["name"]);
		
		if(move_uploaded_file($_FILES["NewPhoto"]["tmp_name"], "{$abs_dir}/{$filename}")){
			$img_size = GetImageSize($abs_dir."/".$filename);
			
			if($img_size[0] > 360){
				$image = new SimpleImage();
				$image->load($abs_dir."/".$filename);
				$image->resizeToWidth(360);
				$image->save($abs_dir."/".$filename);
			}
		} else {
			alert("이미지 첨부에러!","");
			exit;
		}
	}
	
	$sql = "insert into $iw[publishing_author_table] set
			AuthorID = '$AuthorID',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			Author = '$Author',
			original_name = '$original_name',
			content_type = '$content_type',
			ProFile = '$ProFile',
			phone = '$phone',
			Photo = '$filename',
			RegDate = '$RegDate',
			author_display = '$author_display'
			";
	sql_query($sql);

	$sql = "insert into $iw[total_data_table] set
			td_code = '$AuthorID',
			cg_code = 'author',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			state_sort = 'author',
			td_title = '$Author',
			td_datetime = '$td_datetime',
			td_edit_datetime = '$td_datetime',
			td_display = '$author_display'
			";
	sql_query($sql);
	
	alert("작가가 추가되었습니다.","$iw[admin_path]/publishing_author_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>