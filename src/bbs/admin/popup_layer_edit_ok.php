<?php
include_once("_common.php");
if ($iw['level'] != "admin") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$pl_no = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_no']));
$pl_subject = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_subject']));
$pl_stime = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_stime']));
$pl_etime = date("Y-m-d H:i:s", strtotime($pl_etime.' + 23 hours + 59 minutes + 59 seconds'));
$pl_width = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_width']));
$pl_height = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_height']));
$pl_top = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_top']));
$pl_left = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_left']));
$pl_state = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_state']));
$pl_dayback = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_dayback']));
$pl_dayfont = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_dayfont']));
$pl_line = trim(mysqli_real_escape_string($iw['connect'], $_POST['pl_line']));
$pl_content = mysqli_real_escape_string($iw['connect'], $_POST['contents1']);

$sql = "update $iw[popup_layer_table] set
		pl_content = '$pl_content',
		pl_subject = '$pl_subject',
		pl_stime = '$pl_stime',
		pl_etime = '$pl_etime',
		pl_width = '$pl_width',
		pl_height = '$pl_height',
		pl_top = '$pl_top',
		pl_left = '$pl_left',
		pl_state = '$pl_state',
		pl_dayback = '$pl_dayback',
		pl_dayfont = '$pl_dayfont',
		pl_line = '$pl_line'
		where ep_code = '$iw[store]' and gp_code = '$iw[group]' and pl_no = '$pl_no'
		";

sql_query($sql);

alert("팝업창이 수정되었습니다.","$iw[admin_path]/popup_layer_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



