<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$idx = trim(mysqli_real_escape_string($iw['connect'], $_POST['idx'] ?? ''));
$stat = trim(mysqli_real_escape_string($iw['connect'], $_POST['stat'] ?? ''));
$strgubun = trim(mysqli_real_escape_string($iw['connect'], $_POST['strgubun'] ?? ''));
$strgubunTxt = trim(mysqli_real_escape_string($iw['connect'], $_POST['strgubunTxt'] ?? ''));
$strOrgan = trim(mysqli_real_escape_string($iw['connect'], $_POST['strOrgan'] ?? ''));
$userTel1 = trim(mysqli_real_escape_string($iw['connect'], $_POST['userTel1'] ?? ''));
$userTel2 = trim(mysqli_real_escape_string($iw['connect'], $_POST['userTel2'] ?? ''));
$userTel3 = trim(mysqli_real_escape_string($iw['connect'], $_POST['userTel3'] ?? ''));
$userTel = $userTel1."-".$userTel2."-".$userTel3;
$userPhone1 = trim(mysqli_real_escape_string($iw['connect'], $_POST['userPhone1'] ?? ''));
$userPhone2 = trim(mysqli_real_escape_string($iw['connect'], $_POST['userPhone2'] ?? ''));
$userPhone3 = trim(mysqli_real_escape_string($iw['connect'], $_POST['userPhone3'] ?? ''));
$userPhone = $userPhone1."-".$userPhone2."-".$userPhone3;
$userEmail = trim(mysqli_real_escape_string($iw['connect'], $_POST['userEmail'] ?? ''));
$zipcode = trim(mysqli_real_escape_string($iw['connect'], $_POST['zipcode'] ?? ''));
$addr1 = trim(mysqli_real_escape_string($iw['connect'], $_POST['addr1'] ?? ''));
$addr2 = trim(mysqli_real_escape_string($iw['connect'], $_POST['addr2'] ?? ''));
$homepage = trim(mysqli_real_escape_string($iw['connect'], $_POST['homepage'] ?? ''));
$picture_idx = trim(mysqli_real_escape_string($iw['connect'], $_POST['picture_idx'] ?? ''));
$picture_name = trim(mysqli_real_escape_string($iw['connect'], $_POST['picture_name'] ?? ''));
$exhibitDate = trim(mysqli_real_escape_string($iw['connect'], $_POST['exhibitDate'] ?? ''));
$arrayExhibitDate = explode("-", $exhibitDate);
$else_txt = trim(mysqli_real_escape_string($iw['connect'], $_POST['else_txt'] ?? ''));
$admin_txt = trim(mysqli_real_escape_string($iw['connect'], $_POST['admin_txt'] ?? ''));

$sql = "update $iw[publishing_exhibit_status_table] set
		stat = '$stat',
		strgubun = '$strgubun',
		strgubunTxt = '$strgubunTxt',
		strOrgan = '$strOrgan',
		userTel = '$userTel',
		userPhone = '$userPhone',
		userEmail = '$userEmail',
		zipcode = '$zipcode',
		addr1 = '$addr1',
		addr2 = '$addr2',
		homepage = '$homepage',
		picture_idx = '$picture_idx',
		picture_name = '$picture_name',
		year = $arrayExhibitDate[0],
		month = $arrayExhibitDate[1],
		else_txt = '$else_txt',
		admin_txt = '$admin_txt'
		where idx = '$idx' and ep_code = '$iw[store]'";
sql_query($sql);

alert("그림전시 신청정보가 수정되었습니다.","$iw[admin_path]/publishing_exhibit_status_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



