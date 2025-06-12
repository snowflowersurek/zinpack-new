<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
	$upload_path = $_POST[upload_path];
	$bd_code = trim(mysql_real_escape_string($_POST[bd_code]));
	$bm_type = trim(mysql_real_escape_string($_POST[bm_type]));
	$bmd_image_main = trim(mysql_real_escape_string($_POST[bmd_image_main]));
	$bmd_type_main = trim(mysql_real_escape_string($_POST[bmd_type_main]));
	$bmd_content_main = mysql_real_escape_string($_POST[bmd_content_main]);

	$row = sql_fetch(" select max(bm_order) as max_order from $iw[book_media_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'");
	$bm_order = $row["max_order"]+1;

	$abs_dir = $iw[path].$upload_path;
	if($_FILES["bm_image"]["name"] && $_FILES["bm_image"]["size"]>0){
		$bm_image_name = uniqid(rand());

		$bm_image = $bm_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bm_image"]["name"]);
		$result = move_uploaded_file($_FILES["bm_image"]["tmp_name"], "{$abs_dir}/{$bm_image}");
		
		if(!$result){
			alert("썸네일 첨부에러.", "");
		}
	}
	
	for ($i=0; $i<count($_POST["bmd_type"]); $i++) {
		if($_FILES["bmd_image"]["name"][$i] && $_FILES["bmd_image"]["size"][$i]>0){
			$bmd_type = trim(mysql_real_escape_string($_POST[bmd_type][$i]));
			$bmd_content = mysql_real_escape_string($_POST[bmd_content][$i]);

			$bmd_image_name = uniqid(rand());
			$bmd_image = $bmd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bmd_image"]["name"][$i]);
			$result = move_uploaded_file($_FILES["bmd_image"]["tmp_name"][$i], "{$abs_dir}/{$bmd_image}");
			
			if(!$result){
				if(is_file($abs_dir."/".$bm_image)==true){
					unlink($abs_dir."/".$bm_image);
				}

				$bmd_image_total = explode(";", $bmd_image_total);
				for ($a=1; $a<count($bmd_image_total); $a++) {
					if(is_file($abs_dir."/".$bmd_image_total[$a])==true){
						unlink($abs_dir."/".$bmd_image_total[$a]);
					}
				}
				$sql = "delete from $iw[book_media_detail_table] where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bm_order = '$bm_order'";
				sql_query($sql);

				alert("이미지 첨부에러.", "");
			}else{
				$bmd_image_total .= ";".$bmd_image;
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
			}
		}

		if($_FILES["bmd_big_image_".$i]["name"] && $_FILES["bmd_big_image_".$i]["size"]>0){
			$bmd_big_image_name = uniqid(rand());
			$bmd_big_image = $bmd_big_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bmd_big_image_".$i]["name"]);
			$result = move_uploaded_file($_FILES["bmd_big_image_".$i]["tmp_name"], "{$abs_dir}/{$bmd_big_image}");
			
			if(!$result){
				if(is_file($abs_dir."/".$bmd_big_image)==true){
					unlink($abs_dir."/".$bmd_big_image);
				}

				$bmd_big_image_total = explode(";", $bmd_big_image_total);
				for ($a=1; $a<count($bmd_big_image_total); $a++) {
					if(is_file($abs_dir."/".$bmd_big_image_total[$a])==true){
						unlink($abs_dir."/".$bmd_big_image_total[$a]);
					}
				}
				$sql = "delete from $iw[book_media_detail_table] where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bm_order = '$bm_order'";
				sql_query($sql);

				alert("원본 이미지 첨부에러.", "");
			}else{
				$bmd_big_image_total .= ";".$bmd_big_image;
				$sql = "update $iw[book_media_detail_table] set
						bmd_big_image = '$bmd_big_image'
						where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' and bm_order = '$bm_order' and bmd_order = $i+1 
						";
				sql_query($sql);
			}
		}
	}

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
			bmd_order = 0,
			bmd_image = '$bmd_image_main',
			bmd_type = '$bmd_type_main',
			bmd_content = '$bmd_content_main',
			bmd_display = 1
			";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/media/media_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>