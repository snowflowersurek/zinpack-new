<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[group] != "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$gp_language = trim(mysqli_real_escape_string($iw['connect'], $_POST['gp_language']));
$gp_nick = trim(mysqli_real_escape_string($iw['connect'], $_POST['gp_nick']));
$gp_subject = trim(mysqli_real_escape_string($iw['connect'], $_POST['gp_subject']));
$gp_content = mysqli_real_escape_string($iw['connect'], $_POST['gp_content']);
$gp_autocode = trim(mysqli_real_escape_string($iw['connect'], $_POST['gp_autocode']));
$gp_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['gp_type']));
$gp_closed = trim(mysqli_real_escape_string($iw['connect'], $_POST['gp_closed']));

$rowsubject = sql_fetch(" select count(*) as cnt from $iw[group_table] where ep_code = '$iw[store]' and gp_subject = '$gp_subject' ");
$rownick = sql_fetch(" select count(*) as cnt from $iw[group_table] where ep_code = '$iw[store]' and gp_nick = '$gp_nick' ");
if ($rowsubject[cnt]) {
	alert("그룹이름이 이미 존재합니다.","");
}else if ($rownick[cnt]) {
	alert("포워딩주소가 이미 존재합니다.","");
}else{
	$gp_datetime = date("Y-m-d H:i:s");
	$gp_code = "gp".uniqid(rand());

	$sql = "insert into $iw[group_table] set
			gp_code = '$gp_code',
			ep_code = '$iw[store]',
			mb_code = '$iw[member]',
			gp_language = '$gp_language',
			gp_nick = '$gp_nick',
			gp_subject = '$gp_subject',
			gp_content = '$gp_content',
			gp_autocode = '$gp_autocode',
			gp_type = '$gp_type',
			gp_closed = '$gp_closed',
			gp_datetime = '$gp_datetime',
			gp_display = 0
			";
	sql_query($sql);

	alert("신규그룹이 신청되었습니다.","$iw[admin_path]/group_all_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>



