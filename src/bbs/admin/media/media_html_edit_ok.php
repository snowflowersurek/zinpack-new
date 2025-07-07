<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
	$upload_path = $_POST[upload_path];
	$bd_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['bd_code']));
	$bm_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['bm_no']));
	$bmd_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['bmd_no']));
	$bmd_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['bmd_type']));
	$bmd_content = mysqli_real_escape_string($iw['connect'], $_POST['contents1']);
	$bm_image_old = trim(mysqli_real_escape_string($iw['connect'], $_POST['bm_image_old']));

	$abs_dir = $iw[path].$upload_path;
	if($_FILES["bm_image"]["name"] && $_FILES["bm_image"]["size"]>0){
		$bm_image_name = uniqid(rand());

		$bm_image = $bm_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bm_image"]["name"]);
		$result = move_uploaded_file($_FILES["bm_image"]["tmp_name"], "{$abs_dir}/{$bm_image}");
		
		if($result){
			$sql = "update $iw[book_media_table] set
					bm_image = '$bm_image'
					where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bm_no = '$bm_no' 
					";
			sql_query($sql);

			if(is_file($abs_dir."/".$bm_image_old)==true){
				unlink($abs_dir."/".$bm_image_old);
			}
		}else{
			alert("썸네일 첨부에러.", "");
		}
	}

	$sql = "update $iw[book_media_detail_table] set
			bmd_type = '$bmd_type',
			bmd_content = '$bmd_content'
			where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bmd_no = '$bmd_no' 
			";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/media/media_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>



