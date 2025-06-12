<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
	$upload_path = $_POST[upload_path];
	$bd_code = trim(mysql_real_escape_string($_POST[bd_code]));
	$bg_page = trim(mysql_real_escape_string($_POST[bg_page]));

	$abs_dir = $iw[path].$upload_path;
	
	if($_FILES["bg_image"]["name"] && $_FILES["bg_image"]["size"]>0){
		$bg_image_name = uniqid(rand());

		$bg_image = $bg_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bg_image"]["name"]);
		$result = move_uploaded_file($_FILES["bg_image"]["tmp_name"], "{$abs_dir}/{$bg_image}");
		
		if(!$result){
			alert("썸네일 첨부에러.", "");
		}
	}

	$row = sql_fetch(" select max(bg_order) as max_order from $iw[book_blog_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'");
	$bg_order = $row["max_order"]+1;

	$sql = "insert into $iw[book_blog_table] set
			bd_code = '$bd_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			bg_order = '$bg_order',
			bg_image = '$bg_image',
			bg_page = '$bg_page',
			bg_display = 1
			";
	sql_query($sql);

	echo "<script>window.parent.location.href='$iw[admin_path]/blog/blog_main_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code';</script>";
?>