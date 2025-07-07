<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$picture_idx = trim(mysqli_real_escape_string($iw['connect'], $_POST['picture_idx']));
$picture_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['picture_name']));
$can_rent = trim(mysqli_real_escape_string($iw['connect'], $_POST['can_rent']));
$special = trim(mysqli_real_escape_string($iw['connect'], $_POST['special']));
$how_many = trim(mysqli_real_escape_string($iw['connect'], $_POST['how_many']));
$size = trim(mysqli_real_escape_string($iw['connect'], $_POST['size']));
$book_id = trim(mysqli_real_escape_string($iw['connect'], $_POST['book_id']));
$contents = mysqli_real_escape_string($iw['connect'], $_POST['contents']);
$Photo = trim(mysqli_real_escape_string($iw['connect'], $_POST['Photo']));
$delfile = trim(mysqli_real_escape_string($iw['connect'], $_POST['delfile']));
$reg_date = trim(mysqli_real_escape_string($iw['connect'], $_POST['reg_date']));
$reg_date2 = trim(mysqli_real_escape_string($iw['connect'], $_POST['reg_date2']));

if ($reg_date2 && strcmp(substr($reg_date, 0, 16), $reg_date2)) {
	if (!validateDate($reg_date2.":00")) {
		alert("등록일을 잘못 입력하였습니다.","");
	} else {
		$reg_date = $reg_date2.":00";
	}
}

$sql = "update $iw[publishing_exhibit_table] set
		picture_name = '$picture_name',
		special = '$special',
		how_many = '$how_many',
		size = '$size',
		book_id = '$book_id',
		contents = '$contents',
		can_rent = '$can_rent',
		reg_date = '$reg_date'
		where picture_idx = '$picture_idx' and ep_code = '$iw[store]'";
sql_query($sql);

$td_edit_datetime = date("Y-m-d H:i:s");

if ($can_rent == "Y") {
	$td_display = 1;
} else {
	$td_display = 0;
}

$sql = "update $iw[total_data_table] set
		td_title = '$picture_name',
		td_datetime = '$reg_date',
		td_edit_datetime = '$td_edit_datetime',
		td_display = '$td_display'
		where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'exhibit' and td_code = '$picture_idx'";
sql_query($sql);

alert("그림전시 정보가 수정되었습니다.","$iw[admin_path]/publishing_exhibit_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



