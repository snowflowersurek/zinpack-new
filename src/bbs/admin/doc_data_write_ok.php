<?php
include_once("_common.php");
if ($iw[type] != "doc" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$upload_path = $_POST[upload_path];
$dd_code = trim(mysql_real_escape_string($_POST[dd_code]));
$cg_code = trim(mysql_real_escape_string($_POST[cg_code]));
$dd_subject = trim(mysql_real_escape_string($_POST[dd_subject]));
$dd_amount = trim(mysql_real_escape_string($_POST[dd_amount]));
$dd_type = trim(mysql_real_escape_string($_POST[dd_type]));
$dd_price = trim(mysql_real_escape_string($_POST[dd_price]));
$dd_download = trim(mysql_real_escape_string($_POST[dd_download]));
$dd_tag = trim(mysql_real_escape_string($_POST[dd_tag]));
$dd_content = mysql_real_escape_string($_POST[contents1]);

$row = sql_fetch(" select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code' ");
if (!$row[cg_code]) {
	alert("카테고리가 존재하지 않습니다.","");
}else if($iw[mb_level] < $row[cg_level_write]){
	alert("해당 카테고리에 글쓰기 권한이 없습니다.","");
}else{
	function rmdirAll($dir) {
		   $dirs = dir($dir);
	   while(false !== ($entry = $dirs->read())) {
		  if(($entry != '.') && ($entry != '..')) {
			 if(is_dir($dir.'/'.$entry)) {
				rmdirAll($dir.'/'.$entry);
			 } else {
				@unlink($dir.'/'.$entry);
			 }
		   }
		}
		$dirs->close();
		@rmdir($dir);
	}

	$abs_dir = $iw[path].$upload_path;

	if($_FILES["dd_file"]["name"] && $_FILES["dd_file"]["size"]>209715200){
		alert("컨텐츠 최대용량은 200MB 이하입니다.","");
	}else if($_FILES["dd_file"]["name"] && $_FILES["dd_file"]["size"]>0){
		@mkdir($abs_dir, 0707);
		@chmod($abs_dir, 0707);
		
		$dd_file_name = preg_replace('/(\s|\<|\>|\=|\(|\))/', '_', $_FILES["dd_file"]["name"]);
		$dd_file_size = $_FILES["dd_file"]["size"];
		$dd_file = uniqid(rand());

		$result = move_uploaded_file($_FILES["dd_file"]["tmp_name"], "{$abs_dir}/{$dd_file}");
		
		if(!$result){
			rmdirAll($abs_dir);
			alert("컨텐츠 첨부에러.","");
		}
	}
	
	$dd_image = "";
	if($_FILES["dd_image"]["name"] && $_FILES["dd_image"]["size"]>0){
		$dd_image_name = uniqid(rand());

		$dd_image = $dd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["dd_image"]["name"]);
		$result = move_uploaded_file($_FILES["dd_image"]["tmp_name"], "{$abs_dir}/{$dd_image}");
		
		if(!$result){
			rmdirAll($abs_dir);
			alert("표지 첨부에러.","");
		}
	}

	$dd_datetime = date("Y-m-d H:i:s");

	$sql = "insert into $iw[doc_data_table] set
			dd_code = '$dd_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			cg_code = '$cg_code',
			dd_image = '$dd_image',
			dd_file = '$dd_file',
			dd_file_name = '$dd_file_name',
			dd_file_size = '$dd_file_size',
			dd_subject = '$dd_subject',
			dd_amount = '$dd_amount',
			dd_type = '$dd_type',
			dd_price = '$dd_price',
			dd_download = '$dd_download',
			dd_tag = '$dd_tag',
			dd_content = '$dd_content',
			dd_datetime = '$dd_datetime',
			dd_display = 0
			";

	sql_query($sql);

	$sql = "insert into $iw[total_data_table] set
			td_code = '$dd_code',
			cg_code = '$cg_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			state_sort = '$iw[type]',
			td_title = '$dd_subject',
			td_datetime = '$dd_datetime',
			td_edit_datetime = '$dd_datetime'
			";
	sql_query($sql);

	alert("컨텐츠정보가 등록되었습니다.","$iw[admin_path]/doc_data_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>