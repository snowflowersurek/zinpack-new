<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
	$upload_path = $_POST[upload_path];
	$bd_code = trim(mysql_real_escape_string($_POST[bd_code]));
	$bn_sub_title = trim(mysql_real_escape_string($_POST[bn_sub_title]));
	$bn_color = trim(mysql_real_escape_string($_POST[bn_color]));
	$bn_font = trim(mysql_real_escape_string($_POST[bn_font]));
	$bn_logo_old = trim(mysql_real_escape_string($_POST[bn_logo_old]));
	$bn_thum_old = trim(mysql_real_escape_string($_POST[bn_thum_old]));
	$bn_display = trim(mysql_real_escape_string($_POST[bn_display]));

	$return_url = $iw['admin_path']."/blog/blog_main_edit.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&idx=".$bd_code;

	$abs_dir = $iw[path].$upload_path;
	
	if($_FILES["bn_file"]["name"] && $_FILES["bn_file"]["size"]>209715200){
		alert("PDF 최대용량은 200MB 이하입니다.", $return_url);
	}else if($_FILES["bn_file"]["name"] && $_FILES["bn_file"]["size"]>0){

		$bn_file = $bd_code.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bn_file"]["name"]);
		$result = move_uploaded_file($_FILES["bn_file"]["tmp_name"], "{$abs_dir}/{$bn_file}");
		
		if($result){
			$sql = "update $iw[book_main_table] set
					bn_file = '$bn_file'
					where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
					";
			sql_query($sql);

		}else{
			alert("PDF 첨부에러.", $return_url);
		}
	}

	if($_FILES["bn_logo"]["name"] && $_FILES["bn_logo"]["size"]>0){
		$bn_logo_name = uniqid(rand());

		$bn_logo = $bn_logo_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bn_logo"]["name"]);
		$result = move_uploaded_file($_FILES["bn_logo"]["tmp_name"], "{$abs_dir}/{$bn_logo}");
		
		if($result){
			$sql = "update $iw[book_main_table] set
					bn_logo = '$bn_logo'
					where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
					";
			sql_query($sql);
			
			if($bn_display==1 && is_file($abs_dir."/".$bn_logo_old)==true){
				unlink($abs_dir."/".$bn_logo_old);
			}
		}else{
			alert("상단로고 첨부에러.", $return_url);
		}
	}

	if($_FILES["bn_thum"]["name"] && $_FILES["bn_thum"]["size"]>0){
		$bn_thum_name = uniqid(rand());

		$bn_thum = $bn_thum_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bn_thum"]["name"]);
		$result = move_uploaded_file($_FILES["bn_thum"]["tmp_name"], "{$abs_dir}/{$bn_thum}");
		
		if($result){
			$sql = "update $iw[book_main_table] set
					bn_thum = '$bn_thum'
					where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
					";
			sql_query($sql);
			
			if($bn_display==1 && is_file($abs_dir."/".$bn_thum_old)==true){
				unlink($abs_dir."/".$bn_thum_old);
			}
		}else{
			alert("빈타이틀 썸네일 첨부에러.", $return_url);
		}
	}

	$sql = "update $iw[book_main_table] set
			bn_sub_title = '$bn_sub_title',
			bn_color = '$bn_color',
			bn_font = '$bn_font',
			bn_display = 1
			where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
			";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/blog/blog_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>