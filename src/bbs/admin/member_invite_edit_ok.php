<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$gi_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_no']));
$gi_name_check = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_name_check']));
$gi_mail_check = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_mail_check']));
$gi_tel_check = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_tel_check']));
$gi_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_name']));
$gi_mail = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_mail']));
$gi_tel_1 = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_tel_1']));
$gi_tel_2 = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_tel_2']));
$gi_tel_3 = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_tel_3']));
$gi_message = mysqli_real_escape_string($iw['connect'], $_POST['gi_message']);
$gi_datetime_end_1 = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_datetime_end_1']));
$gi_datetime_end_2 = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_datetime_end_2']));
$gi_datetime_end_3 = trim(mysqli_real_escape_string($iw['connect'], $_POST['gi_datetime_end_3']));

$gi_tel = $gi_tel_1."-".$gi_tel_2."-".$gi_tel_3;

$rowmember = sql_fetch(" select count(*) as cnt from $iw[member_table] where ep_code = '$iw[store]' and mb_mail = '$gi_mail' ");
$rowmail = sql_fetch(" select count(*) as cnt from $iw[group_invite_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gi_mail = '$gi_mail' and gi_no <> '$gi_no'");
$rowtel = sql_fetch(" select count(*) as cnt from $iw[group_invite_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gi_tel = '$gi_tel' and gi_no <> '$gi_no' ");
if ($rowmember[cnt]) {
	alert("이미 가입된 이메일입니다.","");
}else if ($rowmail[cnt]) {
	alert("이미 동일한 이메일로 초대하였습니다.","");
}else if ($rowtel[cnt]) {
	alert("이미 동일한 연락처로 초대하였습니다.","");
}else{
	$gi_datetime_end = date("Y-m-d H:i:s",strtotime($gi_datetime_end_1.'-'.$gi_datetime_end_2.'-'.$gi_datetime_end_3.' 23:59:59'));

	$sql = "update $iw[group_invite_table] set
			gi_name = '$gi_name',
			gi_name_check = '$gi_name_check',
			gi_mail = '$gi_mail',
			gi_mail_check = '$gi_mail_check',
			gi_tel = '$gi_tel',
			gi_tel_check = '$gi_tel_check',
			gi_message = '$gi_message',
			gi_datetime_end = '$gi_datetime_end'
			where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gi_no = '$gi_no'
			";

	sql_query($sql);

	alert("초대정보가 수정되었습니다.","$iw[admin_path]/member_invite_edit.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$gi_no");
}
?>



