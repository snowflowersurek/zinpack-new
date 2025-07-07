<?php
include_once("_common.php");
if (($iw['group'] == "all" && $iw['level'] != "admin") || ($iw['group'] != "all" && $iw['gp_level'] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$ad_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['ad_code']));
$ad_navigation = trim(mysqli_real_escape_string($iw['connect'], $_POST['ad_navigation']));
$ad_subject = trim(mysqli_real_escape_string($iw['connect'], $_POST['ad_subject']));
$ad_content = mysqli_real_escape_string($iw['connect'], $_POST['contents1']);


$ad_datetime = date("Y-m-d H:i:s");

$sql = "insert into $iw['about_data_table'] set
		ad_code = '$ad_code',
		ep_code = '$iw['store']',
		gp_code = '$iw['group']',
		mb_code = '$iw['member']',
		ad_navigation = '$ad_navigation',
		ad_subject = '$ad_subject',
		ad_content = '$ad_content',
		ad_datetime = '$ad_datetime',
		ad_display = 1
		";

sql_query($sql);

alert("페이지가 등록되었습니다.","$iw['admin_path']/about_data_list.php?type=$iw['type']&ep=$iw['store']&gp=$iw['group']");
?>



