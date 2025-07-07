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
$reg_date = date("Y-m-d H:i:s");

if($_FILES["attach_file"]["name"] && $_FILES["attach_file"]["size"] > 1024*1024*$ep_upload_size){
	alert("파일첨부의 최대크기는 {$ep_upload_size}MB 입니다.","");
}else{
	if($_FILES["attach_file"]["name"] && $_FILES["attach_file"]["size"]>0){
		$abs_dir = $iw[path].$upload_path;
		@mkdir($abs_dir, 0707, true);
		@chmod($abs_dir, 0707);
		
		$origin_filename = preg_replace('/(\s|\<|\>|\=|\(|\))/', '_', $_FILES["attach_file"]["name"]);
		$attach_filename = uniqid(rand());
		
		$result = move_uploaded_file($_FILES["attach_file"]["tmp_name"], "{$abs_dir}/{$attach_filename}");
		
		if(!$result){
			alert("파일첨부 에러!","");
			exit;
		}
	}
	
	$sql = "insert into iw_publishing_contest set
			contest_code = '$contest_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			cg_code = '$cg_code',
			subject = '$subject',
			content = '$content',
			origin_filename = '$origin_filename',
			attach_filename = '$attach_filename',
			start_date = '$start_date',
			end_date = '$end_date',
			reg_date = '$reg_date',
			hit = 0,
			display = '$display'
			";
	sql_query($sql);
	
	$sql = "insert into $iw[total_data_table] set
			td_code = '$contest_code',
			cg_code = '$cg_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			state_sort = 'publishing_contest',
			td_title = '$subject',
			td_datetime = '$reg_date',
			td_edit_datetime = '$reg_date',
			td_display = '$display'
			";
	sql_query($sql);
	
	alert("등록되었습니다.","$iw[admin_path]/publishing_contest_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>



