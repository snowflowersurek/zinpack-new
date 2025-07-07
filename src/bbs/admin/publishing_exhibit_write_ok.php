<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$picture_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['picture_name']));
$can_rent = trim(mysqli_real_escape_string($iw['connect'], $_POST['can_rent']));
$special = trim(mysqli_real_escape_string($iw['connect'], $_POST['special']));
$how_many = trim(mysqli_real_escape_string($iw['connect'], $_POST['how_many']));
$size = trim(mysqli_real_escape_string($iw['connect'], $_POST['size']));
$book_id = trim(mysqli_real_escape_string($iw['connect'], $_POST['book_id']));
$contents = mysqli_real_escape_string($iw['connect'], $_POST['contents']);
$reg_date = trim(mysqli_real_escape_string($iw['connect'], $_POST['reg_date']));

if ($reg_date) {
	if (!validateDate($reg_date.":00")) {
		alert("등록일을 잘못 입력하였습니다.","");
	} else {
		$reg_date = $reg_date.":00";
	}
} else {
	$reg_date = date("Y-m-d H:i:s");
}

$sql = "insert into $iw[publishing_exhibit_table] set
		ep_code = '$iw[store]',
		gp_code = '$iw[group]',
		mb_code = '$iw[member]',
		picture_name = '$picture_name',
		special = '$special',
		how_many = '$how_many',
		size = '$size',
		book_id = '$book_id',
		contents = '$contents',
		can_rent = '$can_rent',
		reg_date = '$reg_date'
		";
sql_query($sql);

$td_code = mysqli_insert_id($iw['connect']);

if ($can_rent == "Y") {
	$td_display = 1;
} else {
	$td_display = 0;
}

$sql = "insert into $iw[total_data_table] set
		td_code = '$td_code',
		cg_code = 'exhibit',
		ep_code = '$iw[store]',
		gp_code = '$iw[group]',
		state_sort = 'exhibit',
		td_title = '$picture_name',
		td_datetime = '$reg_date',
		td_edit_datetime = '$reg_date',
		td_display = '$td_display'
		";
sql_query($sql);

alert("그림전시가 추가되었습니다.","$iw[admin_path]/publishing_exhibit_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



