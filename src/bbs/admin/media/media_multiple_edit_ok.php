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
	$bm_order = trim(mysqli_real_escape_string($iw['connect'], $_POST['bm_order']));
	$bmd_no_main = trim(mysqli_real_escape_string($iw['connect'], $_POST['bmd_no_main']));
	$bmd_image_main = trim(mysqli_real_escape_string($iw['connect'], $_POST['bmd_image_main']));
	$bmd_type_main = trim(mysqli_real_escape_string($iw['connect'], $_POST['bmd_type_main']));
	$bmd_content_main = mysqli_real_escape_string($iw['connect'], $_POST['bmd_content_main']);
	$bm_image_old = trim(mysqli_real_escape_string($iw['connect'], $_POST['bm_image_old']));

	$sql = "update $iw[book_media_detail_table] set
			bmd_image = '$bmd_image_main',
			bmd_type = '$bmd_type_main',
			bmd_content = '$bmd_content_main'
			where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bmd_no = '$bmd_no_main' 
			";
	sql_query($sql);

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
	
	for ($i=0; $i<count($_POST["bmd_type"]); $i++) {
		$bmd_image_old = $_POST[bmd_image_old][$i];
		$bmd_big_image_old = $_POST["bmd_big_image_old_".$i];
		$bmd_no = $_POST[bmd_no][$i];
		$bmd_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['bmd_type'][$i]));
		$bmd_content = mysqli_real_escape_string($iw['connect'], $_POST['bmd_content'][$i]);

		if($_POST["bmd_image_delete"][$i] == "del"){
			if(is_file($abs_dir."/".$bmd_image_old)==true){
				unlink($abs_dir."/".$bmd_image_old);
			}
			if(is_file($abs_dir."/".$bmd_big_image_old)==true){
				unlink($abs_dir."/".$bmd_big_image_old);
			}
			$sql = "delete from $iw[book_media_detail_table] where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bmd_no = '$bmd_no'";
			sql_query($sql);
		}else{
			if($_FILES["bmd_image"]["name"][$i] && $_FILES["bmd_image"]["size"][$i]>0){
				$bmd_image_name = uniqid(rand());

				$bmd_image = $bmd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bmd_image"]["name"][$i]);
				$result = move_uploaded_file($_FILES["bmd_image"]["tmp_name"][$i], "{$abs_dir}/{$bmd_image}");
				
				if($result){
					if(is_file($abs_dir."/".$bmd_image_old)==true){
						unlink($abs_dir."/".$bmd_image_old);

						$sql = "update $iw[book_media_detail_table] set
								bmd_order = $i+1,
								bmd_image = '$bmd_image',
								bmd_content = '$bmd_content'
								where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bmd_no = '$bmd_no' 
								";
						sql_query($sql);
					}else{
						$sql = "insert into $iw[book_media_detail_table] set
								bd_code = '$bd_code',
								ep_code = '$iw[store]',
								gp_code = '$iw[group]',
								mb_code = '$iw[member]',
								bm_order = '$bm_order',
								bmd_order = $i+1,
								bmd_image = '$bmd_image',
								bmd_type = '$bmd_type',
								bmd_content = '$bmd_content',
								bmd_display = 1
								";
						sql_query($sql);
						$insert_now = "ok";
					}
				}else{
					alert("이미지 첨부에러.", "");
				}
			}else{
				$sql = "update $iw[book_media_detail_table] set
						bmd_order = $i+1,
						bmd_content = '$bmd_content'
						where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bmd_no = '$bmd_no' 
						";
				sql_query($sql);
			}

			if($_FILES["bmd_big_image_".$i]["name"] && $_FILES["bmd_big_image_".$i]["size"]>0){
				$bmd_big_image_name = uniqid(rand());
				$bmd_big_image = $bmd_big_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bmd_big_image_".$i]["name"]);
				$result = move_uploaded_file($_FILES["bmd_big_image_".$i]["tmp_name"], "{$abs_dir}/{$bmd_big_image}");
				
				if($result){
					if(is_file($abs_dir."/".$bmd_big_image_old)==true){
						unlink($abs_dir."/".$bmd_big_image_old);
					}
					if($insert_now == "ok"){
						$sql = "update $iw[book_media_detail_table] set
								bmd_big_image = '$bmd_big_image'
								where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bmd_image = '$bmd_image'
								";
					}else{
						$sql = "update $iw[book_media_detail_table] set
								bmd_big_image = '$bmd_big_image'
								where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bmd_no = '$bmd_no'
								";
					}
					sql_query($sql);					
				}else{
					alert("원본 이미지 첨부에러.", "");	
				}
			}
		}
	}

	echo "<script>window.parent.location.href='$iw[admin_path]/media/media_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>



