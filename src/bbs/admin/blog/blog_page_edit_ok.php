<?php
include_once("_common.php");
if ($iw['type'] != "book" || ($iw['level'] != "seller" && $iw['level'] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
	$upload_path = $_POST['upload_path'];
	$bd_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['bd_code']));
	$bg_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['bg_no']));
	$bg_page = trim(mysqli_real_escape_string($iw['connect'], $_POST['bg_page']));
	$bg_image_old = trim(mysqli_real_escape_string($iw['connect'], $_POST['bg_image_old']));

	$abs_dir = $iw['path'].$upload_path;
	
	if($_FILES["bg_image"]["name"] && $_FILES["bg_image"]["size"]>0){
		$bg_image_name = uniqid(rand());

		$bg_image = $bg_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bg_image"]["name"]);
		$result = move_uploaded_file($_FILES["bg_image"]["tmp_name"], "{$abs_dir}/{$bg_image}");
		
		if($result){
			$sql = "update $iw['book_blog_table'] set
					bg_image = '$bg_image'
					where bd_code = '$bd_code' and ep_code = '$iw['store']' and gp_code = '$iw['group']' and mb_code = '$iw['member']' and bg_no = '$bg_no' 
					";
			sql_query($sql);

			if(is_file($abs_dir."/".$bg_image_old)==true){
				unlink($abs_dir."/".$bg_image_old);
			}
		}else{
			alert("썸네일 첨부에러.", "");
		}
	}

	$sql = "update $iw['book_blog_table'] set
			bg_page = '$bg_page'
			where bd_code = '$bd_code' and ep_code = '$iw['store']' and gp_code = '$iw['group']' and mb_code = '$iw['member']' and bg_no = '$bg_no' 
			";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw['admin_path']/blog/blog_main_list.php?type=$iw['type']&ep=$iw['store']&gp=$iw['group']&idx=$bd_code';</script>";
?>



