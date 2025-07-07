<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$nt_subject = trim(mysqli_real_escape_string($iw['connect'], $_POST['nt_subject']));
$nt_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['nt_type']));
$nt_content = mysqli_real_escape_string($iw['connect'], $_POST['contents1']);

$nt_datetime = date("Y-m-d H:i:s");

$sql = "insert into $iw[notice_table] set
		ep_code = '$iw[store]',
		gp_code = '$iw[group]',
		mb_code = '$iw[member]',
		state_sort = 'main',
		nt_type = '$nt_type',
		nt_subject = '$nt_subject',
		nt_content = '$nt_content',
		nt_datetime = '$nt_datetime',
		nt_display = 1
		";

sql_query($sql);

alert("공지사항이 등록되었습니다.","$iw[admin_path]/home_notice_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



