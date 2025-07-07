<?php
include_once("_common.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$contest_code = trim($_POST['contest_code']);
$mb_code = trim($_POST['mb_code']);
$user_name = trim($_POST['user_name']);
$user_phone = trim($_POST['user_phone']);
$user_email = trim($_POST['user_email']);
$zipcode = trim($_POST['zipcode']);
$addr1 = trim($_POST['addr1']);
$addr2 = trim($_POST['addr2']);
$work_title = trim($_POST['work_title']);
$reg_date = date("Y-m-d H:i:s");

$sql_check1 = "select end_date from iw_publishing_contest where ep_code = ? and gp_code = ? and contest_code = ?";
$stmt_check1 = mysqli_prepare($db_conn, $sql_check1);
mysqli_stmt_bind_param($stmt_check1, "sss", $iw['store'], $iw['group'], $contest_code);
mysqli_stmt_execute($stmt_check1);
$result_check1 = mysqli_stmt_get_result($stmt_check1);
$check_row1 = mysqli_fetch_assoc($result_check1);
mysqli_stmt_close($stmt_check1);
if(strtotime($check_row1["end_date"]) - strtotime($reg_date) < 0){
	alert("공모가 마감되어 응모할 수 없습니다.","{$iw['m_path']}/publishing_contest_data_view.php?type=publishing_contest&ep={$iw['store']}&gp={$iw['group']}&item={$contest_code}");
	exit;
}

$sql_check2 = "select idx from iw_publishing_contestant where ep_code = ? and contest_code = ? and user_phone = ?";
$stmt_check2 = mysqli_prepare($db_conn, $sql_check2);
mysqli_stmt_bind_param($stmt_check2, "sss", $iw['store'], $contest_code, $user_phone);
mysqli_stmt_execute($stmt_check2);
$result_check2 = mysqli_stmt_get_result($stmt_check2);
$check_row2 = mysqli_fetch_assoc($result_check2);
mysqli_stmt_close($stmt_check2);
if ($check_row2["idx"]) {
	alert("해당 공모에 응모한 회원입니다.","{$iw['m_path']}/publishing_contest_data_view.php?type=publishing_contest&ep={$iw['store']}&gp={$iw['group']}&item={$contest_code}");
	exit;
}

$sql_ent = "select ep_nick, ep_upload_size from {$iw['enterprise_table']} where ep_code = ?";
$stmt_ent = mysqli_prepare($db_conn, $sql_ent);
mysqli_stmt_bind_param($stmt_ent, "s", $iw['store']);
mysqli_stmt_execute($stmt_ent);
$result_ent = mysqli_stmt_get_result($stmt_ent);
$row_ent = mysqli_fetch_assoc($result_ent);
mysqli_stmt_close($stmt_ent);
$ep_nick = $row_ent['ep_nick'];
$ep_upload_size = $row_ent['ep_upload_size'];
$upload_path = "/publishing/{$ep_nick}/contest/{$contest_code}";

if($_FILES["attach_file"]["name"] && $_FILES["attach_file"]["size"] > 1024*1024*$ep_upload_size){
	alert("파일첨부의 최대크기는 {$ep_upload_size}MB 입니다.","");
}else{
	if($_FILES["attach_file"]["name"] && $_FILES["attach_file"]["size"]>0){
		$abs_dir = $iw[path].$upload_path;
		@mkdir($abs_dir, 0707, true);
		@chmod($abs_dir, 0707);
		
		$origin_filename = $_FILES["attach_file"]["name"];
		$attach_filename = uniqid(rand());
		
		$result = move_uploaded_file($_FILES["attach_file"]["tmp_name"], "{$abs_dir}/{$attach_filename}");
		
		if($result){
			$sql_insert = "insert into iw_publishing_contestant set
					ep_code = ?, gp_code = ?, contest_code = ?, mb_code = ?, user_name = ?,
					user_phone = ?, user_email = ?, zipcode = ?, addr1 = ?, addr2 = ?,
					work_title = ?, origin_filename = ?, attach_filename = ?, reg_date = ?";
			$stmt_insert = mysqli_prepare($db_conn, $sql_insert);
			mysqli_stmt_bind_param($stmt_insert, "ssssssssssssss",
				$iw['store'], $iw['group'], $contest_code, $mb_code, $user_name, $user_phone,
				$user_email, $zipcode, $addr1, $addr2, $work_title, $origin_filename,
				$attach_filename, $reg_date);
			mysqli_stmt_execute($stmt_insert);
			mysqli_stmt_close($stmt_insert);

			alert("응모되었습니다.","{$iw['m_path']}/publishing_contest_data_view.php?type=publishing_contest&ep={$iw['store']}&gp={$iw['group']}&item={$contest_code}");
		} else {
			alert("파일등록 에러!","");
			exit;
		}
		
	} else {
		alert("파일첨부 에러!","");
		exit;
	}
}
?>



