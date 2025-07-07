<?php
include_once("_common.php");
include_once("$iw[include_path]/lib/lib_image_resize.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$upload_path = $_POST[upload_path];
$ep_upload_size = trim(mysqli_real_escape_string($iw['connect'], $_POST['ep_upload_size']));
$AuthorID = trim(mysqli_real_escape_string($iw['connect'], $_POST['AuthorID']));
$Author = trim(mysqli_real_escape_string($iw['connect'], $_POST['Author']));
$original_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['original_name']));
$content_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['content_type']));
if($content_type == 1){
	$ProFile = mysqli_real_escape_string($iw['connect'], $_POST['ProFile1']);
}else{
	$ProFile = mysqli_real_escape_string($iw['connect'], $_POST['ProFile2']);
}
$phone = trim(mysqli_real_escape_string($iw['connect'], $_POST['phone']));
$Photo = trim(mysqli_real_escape_string($iw['connect'], $_POST['Photo']));
$delfile = trim(mysqli_real_escape_string($iw['connect'], $_POST['delfile']));
$author_display = trim(mysqli_real_escape_string($iw['connect'], $_POST['author_display']));

$abs_dir = $iw[path].$upload_path;

if($_FILES["NewPhoto"]["name"] && $_FILES["NewPhoto"]["size"] > 1024*1024*$ep_upload_size){
	alert("파일첨부의 최대크기는 {$ep_upload_size}MB 입니다.","");
}else{
	if($_FILES["NewPhoto"]["name"] && $_FILES["NewPhoto"]["size"]>0){
		@mkdir($abs_dir, 0707);
		@chmod($abs_dir, 0707);
		
		$image_name = uniqid(rand());
		$filename = $image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES["NewPhoto"]["name"]);
		
		if(move_uploaded_file($_FILES["NewPhoto"]["tmp_name"], "{$abs_dir}/{$filename}")){
			$img_size = GetImageSize($abs_dir."/".$filename);
			
			if($img_size[0] > 360){
				$image = new SimpleImage();
				$image->load($abs_dir."/".$filename);
				$image->resizeToWidth(360);
				$image->save($abs_dir."/".$filename);
			}
			
			$sql = "update $iw[publishing_author_table] set
				Photo = '$filename'
				where AuthorID = '$AuthorID' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]'";
			sql_query($sql);
			
			if(is_file($abs_dir."/".$Photo) == true){
				unlink($abs_dir."/".$Photo);
			}
		}else{
			alert("이미지 첨부에러!","");
			exit;
		}
	} else {
		if($delfile == "Y"){
			$sql = "update $iw[publishing_author_table] set
				Photo = ''
				where AuthorID = '$AuthorID' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]'";
			sql_query($sql);
			
			if(is_file($abs_dir."/".$Photo) == true){
				unlink($abs_dir."/".$Photo);
			}
		}
	}
	
	$sql = "update $iw[publishing_author_table] set
			Author = '$Author',
			original_name = '$original_name',
			ProFile = '$ProFile',
			content_type = '$content_type',
			phone = '$phone',
			author_display = '$author_display'
			where AuthorID = '$AuthorID' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]'";
	sql_query($sql);
	
	$td_edit_datetime = date("Y-m-d H:i:s");
	$sql = "update $iw[total_data_table] set
			td_title = '$Author',
			td_edit_datetime = '$td_edit_datetime',
			td_display = '$author_display'
			where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'author' and td_code = '$AuthorID'";
	sql_query($sql);
	
	alert("작가정보가 수정되었습니다.","$iw[admin_path]/publishing_author_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>



