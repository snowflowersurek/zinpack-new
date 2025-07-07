<?php
include_once("_common.php");
include_once("$iw[include_path]/lib/lib_image_resize.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$upload_path = $_POST[upload_path];
$contest_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['contest_code']));
$ep_upload_size = trim(mysqli_real_escape_string($iw['connect'], $_POST['ep_upload_size']));
$cg_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['cg_code']));
$subject = trim(mysqli_real_escape_string($iw['connect'], $_POST['subject']));
$start_date = trim(mysqli_real_escape_string($iw['connect'], $_POST['start_date']))." 00:00:00";
$end_date = trim(mysqli_real_escape_string($iw['connect'], $_POST['end_date']))." 23:59:59";
$content = trim(mysqli_real_escape_string($iw['connect'], $_POST['content']));
$display = trim(mysqli_real_escape_string($iw['connect'], $_POST['display']));
$is_delfile = trim(mysqli_real_escape_string($iw['connect'], $_POST['is_delfile']));
$delete_filename = trim(mysqli_real_escape_string($iw['connect'], $_POST['delete_filename']));
$edit_date = date("Y-m-d H:i:s");

$abs_dir = $iw[path].$upload_path;

if($_FILES["new_attach_file"]["name"] && $_FILES["new_attach_file"]["size"] > 1024*1024*$ep_upload_size){
	alert("파일첨부의 최대크기는 {$ep_upload_size}MB 입니다.","");
}else{
	if($_FILES["new_attach_file"]["name"] && $_FILES["new_attach_file"]["size"]>0){
		@mkdir($abs_dir, 0707, true);
		@chmod($abs_dir, 0707);
		
		$origin_filename = preg_replace('/(\s|\<|\>|\=|\(|\))/', '_', $_FILES["new_attach_file"]["name"]);
		$attach_filename = uniqid(rand());
		
		if(move_uploaded_file($_FILES["new_attach_file"]["tmp_name"], "{$abs_dir}/{$attach_filename}")){
			$sql = "update iw_publishing_contest set
					origin_filename = '$origin_filename',
					attach_filename = '$attach_filename'
					where 
					contest_code = '$contest_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]'";
			sql_query($sql);
			
			if(is_file($abs_dir."/".$delete_filename) == true){
				unlink($abs_dir."/".$delete_filename);
			}
		} else {
			alert("파일첨부 에러!","");
			exit;
		}
	} else {
		if($is_delfile == "Y"){
			$sql = "update iw_publishing_contest set
				origin_filename = '',
				attach_filename = ''
				where 
				contest_code = '$contest_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]'";
			sql_query($sql);
			
			if(is_file($abs_dir."/".$delete_filename) == true){
				unlink($abs_dir."/".$delete_filename);
			}
		}
	}
	
	$sql = "update iw_publishing_contest set
			cg_code = '$cg_code',
			subject = '$subject',
			content = '$content',
			start_date = '$start_date',
			end_date = '$end_date',
			display = '$display'
			where 
			contest_code = '$contest_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]'";
	sql_query($sql);
	
	$sql = "update $iw[total_data_table] set
			cg_code = '$cg_code',
			td_title = '$subject',
			td_edit_datetime = '$edit_date',
			td_display = '$display'
			where 
			td_code = '$contest_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'publishing_contest'";
	sql_query($sql);
	
	alert("수정되었습니다.","$iw[admin_path]/publishing_contest_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>



