<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$ep_exposed = trim(mysqli_real_escape_string($iw['connect'], $_POST['ep_exposed']));
$ep_autocode = trim(mysqli_real_escape_string($iw['connect'], $_POST['ep_autocode']));
$ep_jointype = trim(mysqli_real_escape_string($iw['connect'], $_POST['ep_jointype']));
$ep_anonymity = trim(mysqli_real_escape_string($iw['connect'], $_POST['ep_anonymity']));

$sql = "update $iw[enterprise_table] set
		ep_exposed = '$ep_exposed',
		ep_autocode = '$ep_autocode',
		ep_jointype = '$ep_jointype',
		ep_anonymity = '$ep_anonymity'
		where ep_code = '$iw[store]' and mb_code = '$iw[member]'
		";

sql_query($sql);

alert("회원가입 설정이 수정되었습니다.","$iw[admin_path]/member_join_setting.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



