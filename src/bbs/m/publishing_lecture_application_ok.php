<?php
include_once("_common.php");
if ($iw['level']=="guest") alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$userName = trim($_POST['userName']);
$strGubun = trim($_POST['strGubun']);
$strGubunTxt = trim($_POST['strGubunTxt']);
$strOrgan = trim($_POST['strOrgan']);
$strCharge = trim($_POST['strCharge']);
$userTel = trim($_POST['userTel']);
$userEmail = trim($_POST['userEmail']);
$zipcode = trim($_POST['zipcode']);
$userAddr1 = trim($_POST['userAddr1']);
$userAddr2 = trim($_POST['userAddr2']);
$userAddr = "(".$zipcode.") ".$userAddr1." ".$userAddr2;
$strTarget = trim($_POST['strTarget']);
$strTargetTxt = trim($_POST['strTargetTxt']);
$strTargetInfo = trim($_POST['strTargetInfo']);

$strAuthor1 = trim($_POST['strAuthor1']);
$strAuthor2 = trim($_POST['strAuthor2']);
$strAuthor3 = trim($_POST['strAuthor3']);
$strAuthorBook1 = trim($_POST['strAuthorBook1']);
$strAuthorBook2 = trim($_POST['strAuthorBook2']);
$strAuthorBook3 = trim($_POST['strAuthorBook3']);

$strDateYmd1 = str_replace("-", "", trim($_POST['strDateYmd1']));
$strStartHour1 = trim($_POST['strStartHour1']);
$strStartMin1 = trim($_POST['strStartMin1']);
$strEndHour1 = trim($_POST['strEndHour1']);
$strEndMin1 = trim($_POST['strEndMin1']);
$strDate1 = $strDateYmd1.$strStartHour1.$strStartMin1.$strEndHour1.$strEndMin1;

$strDateYmd2 = str_replace("-", "", trim($_POST['strDateYmd2']));
$strStartHour2 = trim($_POST['strStartHour2']);
$strStartMin2 = trim($_POST['strStartMin2']);
$strEndHour2 = trim($_POST['strEndHour2']);
$strEndMin2 = trim($_POST['strEndMin2']);
$strDate2 = $strDateYmd2.$strStartHour2.$strStartMin2.$strEndHour2.$strEndMin2;

$strDateYmd3 = str_replace("-", "", trim($_POST['strDateYmd3']));
$strStartHour3 = trim($_POST['strStartHour3']);
$strStartMin3 = trim($_POST['strStartMin3']);
$strEndHour3 = trim($_POST['strEndHour3']);
$strEndMin3 = trim($_POST['strEndMin3']);
$strDate3 = $strDateYmd3.$strStartHour3.$strStartMin3.$strEndHour3.$strEndMin3;

$strNum = trim($_POST['strNum']);
$strPrice = trim($_POST['strPrice']);
$strPreView = trim($_POST['strPreView']);
$strPlan = trim($_POST['strPlan']);
$strContent = trim($_POST['strContent']);

$strRegDate = date("Y-m-d H:i:s");

$sql = "insert into {$iw['publishing_lecture_table']} set
		ep_code = ?, gp_code = ?, mb_code = ?, userName = ?, strConfirm = 'N',
		strGubun = ?, strGubunTxt = ?, strOrgan = ?, strCharge = ?, userTel = ?,
		userEmail = ?, userAddr = ?, strTarget = ?, strTargetTxt = ?, strTargetInfo = ?,
		strNum = ?, confirm_Author = '', strAuthor1 = ?, strAuthor2 = ?, strAuthor3 = ?,
		strAuthorBook1 = ?, strAuthorBook2 = ?, strAuthorBook3 = ?, confirm_date = '',
		strDate1 = ?, strDate2 = ?, strDate3 = ?, strPrice = ?, strPreView = ?,
		strPlan = ?, strContent = ?, strRegDate = ?";

$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssssssssss",
    $iw['store'], $iw['group'], $iw['member'], $userName, $strGubun, $strGubunTxt,
    $strOrgan, $strCharge, $userTel, $userEmail, $userAddr, $strTarget, $strTargetTxt,
    $strTargetInfo, $strNum, $strAuthor1, $strAuthor2, $strAuthor3, $strAuthorBook1,
    $strAuthorBook2, $strAuthorBook3, $strDate1, $strDate2, $strDate3, $strPrice,
    $strPreView, $strPlan, $strContent, $strRegDate);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

alert("작가강연회 신청이 완료되었습니다.","{$iw['m_path']}/publishing_lecture_status.php?ep={$iw['store']}&gp={$iw['group']}");
?>



