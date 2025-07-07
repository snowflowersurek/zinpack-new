<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
	$upload_path = $_POST[upload_path];
	$bd_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['bd_code']));
	$bm_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['bm_type']));
	$bmd_order = trim(mysqli_real_escape_string($iw['connect'], $_POST['bmd_order']));
	$bmd_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['bmd_type']));

	$abs_dir = $iw[path].$upload_path;
	if($_FILES["bm_image"]["name"] && $_FILES["bm_image"]["size"]>0){
		$bm_image_name = uniqid(rand());

		$bm_image = $bm_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bm_image"]["name"]);
		$result = move_uploaded_file($_FILES["bm_image"]["tmp_name"], "{$abs_dir}/{$bm_image}");
		
		if(!$result){
			alert("썸네일 첨부에러.", "");
		}
	}
	
	if($_FILES["bmd_image"]["name"] && $_FILES["bmd_image"]["size"]>0){
		$bmd_image_name = uniqid(rand());

		$bmd_image = $bmd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bmd_image"]["name"]);
		$result = move_uploaded_file($_FILES["bmd_image"]["tmp_name"], "{$abs_dir}/{$bmd_image}");
		
		if(!$result){
			if(is_file($abs_dir."/".$bm_image)==true){
				unlink($abs_dir."/".$bm_image);
			}
			alert("파노라마 이미지 첨부에러.", "");
		}
	}

	$row = sql_fetch(" select max(bm_order) as max_order from $iw[book_media_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'");
	$bm_order = $row["max_order"]+1;

	$sql = "insert into $iw[book_media_table] set
			bd_code = '$bd_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			bm_order = '$bm_order',
			bm_image = '$bm_image',
			bm_type = '$bm_type',
			bm_display = 1
			";
	sql_query($sql);

	$sql = "insert into $iw[book_media_detail_table] set
			bd_code = '$bd_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			bm_order = '$bm_order',
			bmd_order = '$bmd_order',
			bmd_image = '$bmd_image',
			bmd_type = '$bmd_type',
			bmd_display = 1
			";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/media/media_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>



