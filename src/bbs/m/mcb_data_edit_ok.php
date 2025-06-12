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
$md_datetime = trim($_POST['md_datetime']);
$md_datetime2 = trim($_POST['md_datetime2']);
$md_type = trim($_POST['md_type']);
$cg_code_raw = explode(",",$_POST['cg_code']);
$cg_code = trim($cg_code_raw[0]);
$md_subject = trim($_POST['md_subject']);
$md_youtube = trim($_POST['md_youtube']);
$ep_upload_size = trim($_POST['ep_upload_size']);
$md_padding = trim($_POST['md_padding']);
$md_secret = trim($_POST['md_secret']);

if ($md_datetime2 && strcmp(substr($md_datetime, 0, 16), $md_datetime2)) {
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
	$sql_update_main = "update {$iw['mcb_data_table']} set
			cg_code = ?, md_type = ?, md_subject = ?, md_content = ?, md_youtube = ?,
			md_padding = ?, md_secret = ?, md_ip = ?, md_datetime = ?
			where ep_code = ? and gp_code= ? and md_code = ?";
	$stmt_update_main = mysqli_prepare($db_conn, $sql_update_main);
	mysqli_stmt_bind_param($stmt_update_main, "ssssssssssss", $cg_code, $md_type, $md_subject, $md_content, $md_youtube, $md_padding, $md_secret, $_SERVER['REMOTE_ADDR'], $md_datetime, $iw['store'], $iw['group'], $md_code);
	mysqli_stmt_execute($stmt_update_main);
	mysqli_stmt_close($stmt_update_main);

	$abs_dir = $iw['path'].$upload_path;
	@mkdir($abs_dir, 0707);
	@chmod($abs_dir, 0707);

	if($md_type == 1){
		for ($i=1; $i<=10; $i++) {
			$file_field = "md_file_".$i;
            $idx = $i - 1;
			$md_file_old = $_POST['md_file_old'][$idx];
            $new_filename = null;

			if($_POST['md_delete'][$idx] == "del"){
				if(is_file("{$abs_dir}/{$md_file_old}")==true){
					unlink("{$abs_dir}/{$md_file_old}");
				}
				$new_filename = ''; // 삭제
			}else if($_FILES["md_file"]["name"][$idx] && $_FILES["md_file"]["size"][$idx]>0){
				// 새 파일 업로드
				$sd_image_name = uniqid(rand());
				$uploaded_filename = $sd_image_name.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["md_file"]["name"][$idx]);
				if(move_uploaded_file($_FILES["md_file"]["tmp_name"][$idx], "{$abs_dir}/{$uploaded_filename}")){
					if(is_file("{$abs_dir}/{$md_file_old}")==true){
						unlink("{$abs_dir}/{$md_file_old}");
					}
					// 이미지 리사이즈 로직...
					$new_filename = $uploaded_filename;
				} else {
					alert(national_language($iw['language'],"a0193","이미지 첨부에러."),"");
				}
			}

            // DB 업데이트 (변경사항이 있을 경우에만)
			if ($new_filename !== null) {
                $sql = "update {$iw['mcb_data_table']} set {$file_field} = ? where ep_code = ? and gp_code = ? and md_code = ?";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssss", $new_filename, $iw['store'], $iw['group'], $md_code);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
			}
		}
	}else{ // md_type != 1 이면 모든 파일 필드 초기화
		$sql = "update {$iw['mcb_data_table']} set md_youtube = '', md_file_1 = '', md_file_2 = '', md_file_3 = '', md_file_4 = '', md_file_5 = '', md_file_6 = '', md_file_7 = '', md_file_8 = '', md_file_9 = '', md_file_10 = '' where ep_code = ? and gp_code = ? and md_code = ?";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $iw['store'], $iw['group'], $md_code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
	}

	$md_attach_old = $_POST['md_attach_old'];
	if($_POST['md_attach_delete'] == "del"){
		if(is_file("{$abs_dir}/{$md_attach_old}")==true){
			unlink("{$abs_dir}/{$md_attach_old}");
		}
		$sql = "update {$iw['mcb_data_table']} set md_attach = '', md_attach_name = '' where ep_code = ? and gp_code = ? and md_code = ?";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $iw['store'], $iw['group'], $md_code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
	}else if($_FILES["md_attach"]["name"] && $_FILES["md_attach"]["size"]>0){		
		$md_attach_name = preg_replace('/(\s|\<|\>|\=|\(|\))/', '_', $_FILES["md_attach"]["name"]);
		$md_attach_size = $_FILES["md_attach"]["size"];
		$md_attach = uniqid(rand());

		$result = move_uploaded_file($_FILES["md_attach"]["tmp_name"], "{$abs_dir}/{$md_attach}");
		
		if($result){
			$sql = "update {$iw['mcb_data_table']} set md_attach = ?, md_attach_name = ? where ep_code = ? and gp_code = ? and md_code = ?";
            $stmt = mysqli_prepare($db_conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $md_attach, $md_attach_name, $iw['store'], $iw['group'], $md_code);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
		}
	}
	
	$td_edit_datetime = date("Y-m-d H:i:s");
	$sql_total = "update {$iw['total_data_table']} set
			cg_code = ?, td_title = ?, td_datetime = ?, td_edit_datetime = ?
			where ep_code = ? and gp_code = ? and state_sort = ? and td_code = ?";
	$stmt_total = mysqli_prepare($db_conn, $sql_total);
	mysqli_stmt_bind_param($stmt_total, "ssssssss", $cg_code, $md_subject, $md_datetime, $td_edit_datetime, $iw['store'], $iw['group'], $iw['type'], $md_code);
	mysqli_stmt_execute($stmt_total);
	mysqli_stmt_close($stmt_total);

	alert(national_language($iw['language'],"a0194","글이 수정되었습니다."),"{$iw['m_path']}/mcb_data_view.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&item={$md_code}");
}
?>