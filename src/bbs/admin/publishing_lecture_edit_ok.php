<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$intSeq = trim(mysql_real_escape_string($_POST[intSeq]));
$strConfirm = trim(mysql_real_escape_string($_POST[strConfirm]));
$strGubun = trim(mysql_real_escape_string($_POST[strGubun]));
$strGubunTxt = trim(mysql_real_escape_string($_POST[strGubunTxt]));
$strOrgan = trim(mysql_real_escape_string($_POST[strOrgan]));
$strCharge = trim(mysql_real_escape_string($_POST[strCharge]));
$userTel = trim(mysql_real_escape_string($_POST[userTel]));
$userEmail = trim(mysql_real_escape_string($_POST[userEmail]));
$userAddr = trim(mysql_real_escape_string($_POST[userAddr]));
$strTarget = trim(mysql_real_escape_string($_POST[strTarget]));
$strTargetTxt = trim(mysql_real_escape_string($_POST[strTargetTxt]));
$strTargetInfo = trim(mysql_real_escape_string($_POST[strTargetInfo]));
$strNum = trim(mysql_real_escape_string($_POST[strNum]));

$confirm_Author = trim(mysql_real_escape_string($_POST[confirm_Author]));
$strAuthor1 = trim(mysql_real_escape_string($_POST[strAuthor1]));
$strAuthor2 = trim(mysql_real_escape_string($_POST[strAuthor2]));
$strAuthor3 = trim(mysql_real_escape_string($_POST[strAuthor3]));
$strAuthorBook1 = trim(mysql_real_escape_string($_POST[strAuthorBook1]));
$strAuthorBook2 = trim(mysql_real_escape_string($_POST[strAuthorBook2]));
$strAuthorBook3 = trim(mysql_real_escape_string($_POST[strAuthorBook3]));

$confirm_date = trim(mysql_real_escape_string($_POST[confirm_date]));
$strDateYmd1 = str_replace("-", "", trim(mysql_real_escape_string($_POST[strDateYmd1])));
$strStartHour1 = trim(mysql_real_escape_string($_POST[strStartHour1]));
$strStartMin1 = trim(mysql_real_escape_string($_POST[strStartMin1]));
$strEndHour1 = trim(mysql_real_escape_string($_POST[strEndHour1]));
$strEndMin1 = trim(mysql_real_escape_string($_POST[strEndMin1]));
$strDate1 = $strDateYmd1.$strStartHour1.$strStartMin1.$strEndHour1.$strEndMin1;
$strDateYmd2 = str_replace("-", "", trim(mysql_real_escape_string($_POST[strDateYmd2])));
$strStartHour2 = trim(mysql_real_escape_string($_POST[strStartHour2]));
$strStartMin2 = trim(mysql_real_escape_string($_POST[strStartMin2]));
$strEndHour2 = trim(mysql_real_escape_string($_POST[strEndHour2]));
$strEndMin2 = trim(mysql_real_escape_string($_POST[strEndMin2]));
$strDate2 = $strDateYmd2.$strStartHour2.$strStartMin2.$strEndHour2.$strEndMin2;
$strDateYmd3 = str_replace("-", "", trim(mysql_real_escape_string($_POST[strDateYmd3])));
$strStartHour3 = trim(mysql_real_escape_string($_POST[strStartHour3]));
$strStartMin3 = trim(mysql_real_escape_string($_POST[strStartMin3]));
$strEndHour3 = trim(mysql_real_escape_string($_POST[strEndHour3]));
$strEndMin3 = trim(mysql_real_escape_string($_POST[strEndMin3]));
$strDate3 = $strDateYmd3.$strStartHour3.$strStartMin3.$strEndHour3.$strEndMin3;

$strPrice = trim(mysql_real_escape_string($_POST[strPrice]));
$strPreView = trim(mysql_real_escape_string($_POST[strPreView]));
$strPlan = trim(mysql_real_escape_string($_POST[strPlan]));
$strContent = trim(mysql_real_escape_string($_POST[strContent]));
$strAdminMemo = trim(mysql_real_escape_string($_POST[strAdminMemo]));

$sql = "update $iw[publishing_lecture_table] set
		strConfirm = '$strConfirm',
		strGubun = '$strGubun',
		strGubunTxt = '$strGubunTxt',
		strOrgan = '$strOrgan',
		strCharge = '$strCharge',
		userTel = '$userTel',
		userEmail = '$userEmail',
		userAddr = '$userAddr',
		strTarget = '$strTarget',
		strTargetTxt = '$strTargetTxt',
		strTargetInfo = '$strTargetInfo',
		strNum = '$strNum',
		
		confirm_Author = '$confirm_Author',
		strAuthor1 = '$strAuthor1',
		strAuthor2 = '$strAuthor2',
		strAuthor3 = '$strAuthor3',
		strAuthorBook1 = '$strAuthorBook1',
		strAuthorBook2 = '$strAuthorBook2',
		strAuthorBook3 = '$strAuthorBook3',
		
		confirm_date = '$confirm_date',
		strDate1 = '$strDate1',
		strDate2 = '$strDate2',
		strDate3 = '$strDate3',
		
		strPrice = '$strPrice',
		strPreView = '$strPreView',
		strPlan = '$strPlan',
		strContent = '$strContent',
		strAdminMemo = '$strAdminMemo'
		where intSeq = '$intSeq' and ep_code = '$iw[store]'";
sql_query($sql);

alert("작가강연회 신청정보가 수정되었습니다.","$iw[admin_path]/publishing_lecture_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



