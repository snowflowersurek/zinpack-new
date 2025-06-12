<?php
include_once("_common.php");
include_once("{$iw['include_path']}/lib/lib_image_resize.php");

if (($iw['gp_level'] == "gp_guest" && $iw['group'] != "all") || ($iw['level'] == "guest" && $iw['group'] == "all")) {
	alert(national_language($iw['language'],"a0032","로그인 후 이용해주세요"),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
}

global $db_conn;
if (!$db_conn) {
	$db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$upload_path = $_POST['upload_path'];
$md_code = trim($_POST['md_code']);
$md_datetime2 = trim($_POST['md_datetime2']);
$md_type = trim($_POST['md_type']);
$cg_code = explode(",",$_POST['cg_code']);
$cg_code = trim($_POST['cg_code'][0]);
$md_subject = trim($_POST['md_subject']);
$md_youtube = trim($_POST['md_youtube']);
$ep_upload_size = trim($_POST['ep_upload_size']);
$md_padding = trim($_POST['md_padding']);
$md_secret = trim($_POST['md_secret']);

$md_datetime = date("Y-m-d H:i:s");

if ($md_datetime2) {
	if (!validateDate($md_datetime2.":00")) {
		alert("등록일을 잘못 입력하였습니다.","");
	} else {
		$md_datetime = $md_datetime2.":00";
	}
}

if($md_type == 1){
	$md_content = $_POST['md_content'];
}else{
	$md_content = $_POST['contents1'];
}

$sql_cat = "select * from {$iw['category_table']} where ep_code = ? and gp_code = ? and state_sort = ? and cg_code = ?";
$stmt_cat = mysqli_prepare($db_conn, $sql_cat);
mysqli_stmt_bind_param($stmt_cat, "ssss", $iw['store'], $iw['group'], $iw['type'], $cg_code);
mysqli_stmt_execute($stmt_cat);
$result_cat = mysqli_stmt_get_result($stmt_cat);
$row_cat = mysqli_fetch_assoc($result_cat);
mysqli_stmt_close($stmt_cat);

if (!$row_cat['cg_code']) {
	alert(national_language($iw['language'],"a0165","카테고리가 존재하지 않습니다."),"");
} else if($iw['mb_level'] < $row_cat['cg_level_write']){
	alert(national_language($iw['language'],"a0192","해당 카테고리에 글쓰기 권한이 없습니다."),"");
} else if($iw['mb_level'] < $row_cat['cg_level_upload'] && $_FILES["md_attach"]["name"] && $_FILES["md_attach"]["size"]>0){
	alert("해당 카테고리에 파일업로드 권한이 없습니다.","");
} else if($_FILES["md_attach"]["name"] && $_FILES["md_attach"]["size"] > 1024*1024*$ep_upload_size){
	alert("파일첨부의 최대크기는 {$ep_upload_size}MB 입니다.","");
} else {
	$sql_insert = "insert into {$iw['mcb_data_table']} set
			md_code = ?, ep_code = ?, gp_code = ?, mb_code = ?, cg_code = ?, md_type = ?,
			md_subject = ?, md_content = ?, md_youtube = ?, md_ip = ?, md_padding = ?, 
			md_secret = ?, md_datetime = ?, md_display = 1";
	$stmt_insert = mysqli_prepare($db_conn, $sql_insert);
	mysqli_stmt_bind_param($stmt_insert, "sssssssssssss", $md_code, $iw['store'], $iw['group'], $iw['member'], $cg_code, $md_type, $md_subject, $md_content, $md_youtube, $_SERVER['REMOTE_ADDR'], $md_padding, $md_secret, $md_datetime);
	mysqli_stmt_execute($stmt_insert);
	mysqli_stmt_close($stmt_insert);

	$abs_dir = $iw['path'].$upload_path;
	@mkdir($abs_dir, 0707);
	@chmod($abs_dir, 0707);

	if($md_type == 1){
		$file_num = 0;
		for ($i=0; $i<count($_POST['md_file_count']); $i++) {
			if($_FILES["md_file"]["name"][$i] && $_FILES["md_file"]["size"][$i]>0){
				$sd_image_name = uniqid(rand());

				$filename = $sd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["md_file"]["name"][$i]);
				$exifData = exif_read_data($_FILES["md_file"]["tmp_name"][$i]); 
				$result = move_uploaded_file($_FILES["md_file"]["tmp_name"][$i], "{$abs_dir}/{$filename}"); 

				if($result){
					$file_num++;
					$file_field = "md_file_".$file_num;
					$sql_update_file = "update {$iw['mcb_data_table']} set {$file_field} = ? where ep_code = ? and gp_code = ? and mb_code = ? and md_code = ?";
					$stmt_update_file = mysqli_prepare($db_conn, $sql_update_file);
					mysqli_stmt_bind_param($stmt_update_file, "sssss", $filename, $iw['store'], $iw['group'], $iw['member'], $md_code);
					mysqli_stmt_execute($stmt_update_file);
					mysqli_stmt_close($stmt_update_file);
					
					$img_size = GetImageSize($abs_dir."/".$filename);

					if($exifData['Orientation'] == 6) { 
					// 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨 
						$degree = 270;
						$img_width = $img_size[1];
					} 
					else if($exifData['Orientation'] == 8) { 
						// 반시계방향으로 90도 돌려줘야 정상 
						$degree = 90;
						$img_width = $img_size[1];
					} 
					else if($exifData['Orientation'] == 3) { 
						$degree = 180;
						$img_width = $img_size[0];
					}else{
						$img_width = $img_size[0];
					}
					if($img_width > 1100 || $degree){
						$image = new SimpleImage();
						$image->load($abs_dir."/".$filename);
						if($degree){
							$image->rotate($degree);
						}
						if($img_width > 1100){
							$image->resizeToWidth(1100);
						}
						$image->save($abs_dir."/".$filename);
					}					
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
					rmdirAll($abs_dir);

					$sql_del = "delete from {$iw['mcb_data_table']} where ep_code = ? and gp_code = ? and mb_code = ? and md_code = ?";
					$stmt_del = mysqli_prepare($db_conn, $sql_del);
					mysqli_stmt_bind_param($stmt_del, "ssss", $iw['store'], $iw['group'], $iw['member'], $md_code);
					mysqli_stmt_execute($stmt_del);
					mysqli_stmt_close($stmt_del);
					alert(national_language($iw['language'],"a0193","이미지 첨부에러."),"");
				}
			}
		}
	}

	if($_FILES["md_attach"]["name"] && $_FILES["md_attach"]["size"]>0){		
		$md_attach_name = preg_replace('/(\s|\<|\>|\=|\(|\))/', '_', $_FILES["md_attach"]["name"]);
		$md_attach_size = $_FILES["md_attach"]["size"];
		$md_attach = uniqid(rand());

		$result = move_uploaded_file($_FILES["md_attach"]["tmp_name"], "{$abs_dir}/{$md_attach}");
		
		if(!$result){
			rmdirAll($abs_dir);
			$sql_del = "delete from {$iw['mcb_data_table']} where ep_code = ? and gp_code = ? and mb_code = ? and md_code = ?";
			$stmt_del = mysqli_prepare($db_conn, $sql_del);
			mysqli_stmt_bind_param($stmt_del, "ssss", $iw['store'], $iw['group'], $iw['member'], $md_code);
			mysqli_stmt_execute($stmt_del);
			mysqli_stmt_close($stmt_del);
			alert("파일 첨부에러.","");
		}

		$sql_update_attach = "update {$iw['mcb_data_table']} set md_attach = ?, md_attach_name = ? where ep_code = ? and gp_code = ? and mb_code = ? and md_code = ?";
		$stmt_update_attach = mysqli_prepare($db_conn, $sql_update_attach);
		mysqli_stmt_bind_param($stmt_update_attach, "ssssss", $md_attach, $md_attach_name, $iw['store'], $iw['group'], $iw['member'], $md_code);
		mysqli_stmt_execute($stmt_update_attach);
		mysqli_stmt_close($stmt_update_attach);
	}

	$sql_total = "insert into {$iw['total_data_table']} set
			td_code = ?, cg_code = ?, ep_code = ?, gp_code = ?, state_sort = ?,
			td_title = ?, td_datetime = ?, td_edit_datetime = ?, td_display = 1";
	$stmt_total = mysqli_prepare($db_conn, $sql_total);
	mysqli_stmt_bind_param($stmt_total, "ssssssss", $md_code, $cg_code, $iw['store'], $iw['group'], $iw['type'], $md_subject, $md_datetime, $md_datetime);
	mysqli_stmt_execute($stmt_total);
	mysqli_stmt_close($stmt_total);

	alert(national_language($iw['language'],"a0195","글이 등록되었습니다."),"{$iw['m_path']}/mcb_data_view.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&item={$md_code}");
}
?>