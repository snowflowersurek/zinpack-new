<?php
include_once("_common.php");
if ($iw[type] != "doc" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$upload_path = $_POST[upload_path];
$dd_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['dd_code']));
$cg_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['cg_code']));
$dd_subject = trim(mysqli_real_escape_string($iw['connect'], $_POST['dd_subject']));
$dd_amount = trim(mysqli_real_escape_string($iw['connect'], $_POST['dd_amount']));
$dd_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['dd_type']));
$dd_price = trim(mysqli_real_escape_string($iw['connect'], $_POST['dd_price']));
$dd_download = trim(mysqli_real_escape_string($iw['connect'], $_POST['dd_download']));
$dd_tag = trim(mysqli_real_escape_string($iw['connect'], $_POST['dd_tag']));
$dd_content = mysqli_real_escape_string($iw['connect'], $_POST['contents1']);
$dd_file_old = mysqli_real_escape_string($iw['connect'], $_POST['dd_file_old']);
$dd_image_old = trim(mysqli_real_escape_string($iw['connect'], $_POST['dd_image_old']));

$row = sql_fetch(" select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code' ");
if (!$row[cg_code]) {
	alert("카테고리가 존재하지 않습니다.","");
}else if($iw[mb_level] < $row[cg_level_write]){
	alert("해당 카테고리에 글쓰기 권한이 없습니다.","");
}else{
	$abs_dir = $iw[path].$upload_path;
	
	if($_FILES["dd_file"]["name"] && $_FILES["dd_file"]["size"]>209715200){
		alert("컨텐츠 최대용량은 200MB 이하입니다.","");
	}else if($_FILES["dd_file"]["name"] && $_FILES["dd_file"]["size"]>0){
		
		$dd_file_name = preg_replace('/(\s|\<|\>|\=|\(|\))/', '_', $_FILES["dd_file"]["name"]);
		$dd_file_size = $_FILES["dd_file"]["size"];
		$dd_file = $dd_file_old;

		$result = move_uploaded_file($_FILES["dd_file"]["tmp_name"], "{$abs_dir}/{$dd_file}");
		
		if($result){
			$sql = "update $iw[doc_data_table] set
				dd_file = '$dd_file',
				dd_file_name = '$dd_file_name',
				dd_file_size = '$dd_file_size'
				where dd_code = '$dd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
				";
			sql_query($sql);
		}else{
			alert("컨텐츠 첨부에러.","");
		}
	}

	$dd_image = "";
	if($_FILES["dd_image"]["name"] && $_FILES["dd_image"]["size"]>0){
		$dd_image_name = uniqid(rand());

		$dd_image = $dd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["dd_image"]["name"]);
		$result = move_uploaded_file($_FILES["dd_image"]["tmp_name"], "{$abs_dir}/{$dd_image}");
		
		if($result){
			$sql = "update $iw[doc_data_table] set
				dd_image = '$dd_image'
				where dd_code = '$dd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
				";
			sql_query($sql);

			if(is_file($abs_dir."/".$dd_image_old)==true){
				unlink($abs_dir."/".$dd_image_old);
			}
		}else{
			alert("이미지 첨부에러.","");
		}
	}

	$sql = "update $iw[doc_data_table] set
			cg_code = '$cg_code',
			dd_subject = '$dd_subject',
			dd_amount = '$dd_amount',
			dd_type = '$dd_type',
			dd_price = '$dd_price',
			dd_download = '$dd_download',
			dd_tag = '$dd_tag',
			dd_content = '$dd_content'
			where dd_code = '$dd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
			";

	sql_query($sql);

	$td_edit_datetime = date("Y-m-d H:i:s");
	$sql = "update $iw[total_data_table] set
			cg_code = '$cg_code',
			td_title = '$dd_subject',
			td_edit_datetime = '$td_edit_datetime'
			where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and td_code = '$dd_code'";
	sql_query($sql);

	alert("컨텐츠정보가 변경되었습니다.","$iw[admin_path]/doc_data_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$dd_code");
}
?>



