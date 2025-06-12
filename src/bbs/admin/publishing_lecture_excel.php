<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

$download_year = $_GET[year];

if (!$download_year) {
	$download_year = date("Y");
}

$excelFileName = iconv("UTF-8", "EUC-KR", "작가강연회_신청내역_").$download_year.iconv("UTF-8", "EUC-KR", "년.xls");

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=".$excelFileName );

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr style='background-color:#ccc; font-weight:bold;'>
<td>접수일자</td>
<td>신청현황</td>
<td>신청자</td>
<td>신청기관 분류</td>
<td>기관명</td>
<td>담당자명</td>
<td>연락처</td>
<td>이메일</td>
<td>주소</td>
<td>강연회대상</td>
<td>대상 연령대</td>
<td>참석 인원</td>

<td>확정작가</td>
<td>희망작가 1지망</td>
<td>희망작가 2지망</td>
<td>희망작가 3지망</td>

<td>확정일정</td>
<td>희망 일정 1지망</td>
<td>희망 일정 2지망</td>
<td>희망 일정 3지망</td>

<td>강연료(예산)</td>
<td>사전독서여부</td>
<td>독후 활동 계획</td>
<td>남기고싶은말(기타 요청 사항)</td>
<td>관리자 메모</td>
</tr>
";

$sql = "select * from $iw[publishing_lecture_table] where ep_code = '$iw[store]' and strRegDate >= '$download_year-01-01 00:00:00' and strRegDate <= '$download_year-12-31 23:59:59' order by intSeq desc";
$result = sql_query($sql);

while($row = @sql_fetch_array($result)){
	$strConfirm = $row["strConfirm"];
	$userName = $row["userName"];
	$strGubun = $row["strGubun"];
	$strGubunTxt = $row["strGubunTxt"];
	$strOrgan = $row["strOrgan"];
	$strCharge = $row["strCharge"];
	$userTel = $row["userTel"];
	$userEmail = $row["userEmail"];
	$userAddr = $row["userAddr"];
	$strTarget = $row["strTarget"];
	$strTargetTxt = $row["strTargetTxt"];
	$strTargetInfo = $row["strTargetInfo"];
	$strNum = $row["strNum"];
	
	$confirm_Author = $row["confirm_Author"];
	$strAuthor1 = $row["strAuthor1"];
	$strAuthor2 = $row["strAuthor2"];
	$strAuthor3 = $row["strAuthor3"];
	$strAuthorBook1 = $row["strAuthorBook1"];
	$strAuthorBook2 = $row["strAuthorBook2"];
	$strAuthorBook3 = $row["strAuthorBook3"];
	
	$confirm_date = $row["confirm_date"];
	$strDate1 = $row["strDate1"];
	$strDate2 = $row["strDate2"];
	$strDate3 = $row["strDate3"];
	
	$strPrice = $row["strPrice"];
	$strPreView = $row["strPreView"];
	$strPlan = $row["strPlan"];
	$strContent = $row["strContent"];
	$strAdminMemo = $row["strAdminMemo"];
	$strRegDate = substr($row["strRegDate"], 0, 10);
	
	if ($strConfirm == "N") {
		$strConfirmText = "접수대기";
	} else if ($strConfirm == "A") {
		$strConfirmText = "접수완료";
	} else if ($strConfirm == "D") {
		$strConfirmText = "도서관연락";
	} else if ($strConfirm == "J") {
		$strConfirmText = "작가섭외";
	} else if ($strConfirm == "Y") {
		$strConfirmText = "<span style='color:red;'>강연확정</span>";
	} else if ($strConfirm == "C") {
		$strConfirmText = "강연취소";
	}
	
	if ($strGubun == "기타") {
		$strGubun = $strGubun."(".$strGubunTxt.")";
	}
	
	if (strpos($strTarget, "기타") !== false) {
		$strTarget = $strTarget."(".$strTargetTxt.")";
	}
	
	if ($confirm_Author != "") {
		$confirm_Author = $confirm_Author."지망";
	}
	
	if ($confirm_date != "") {
		$confirm_date = $confirm_date."지망";
	}
	
	if ($strDate1 != "") {
		$strDateText1 = substr($strDate1, 0, 4)."-".substr($strDate1, 4, 2)."-".substr($strDate1, 6, 2)." ".substr($strDate1, 8, 2).":".substr($strDate1, 10, 2)." ~ ".substr($strDate1, 12, 2).":".substr($strDate1, 14, 2);
	} else {
		$strDateText1 = "";
	}
	
	if ($strDate2 != "") {
		$strDateText2 = substr($strDate2, 0, 4)."-".substr($strDate2, 4, 2)."-".substr($strDate2, 6, 2)." ".substr($strDate2, 8, 2).":".substr($strDate2, 10, 2)." ~ ".substr($strDate2, 12, 2).":".substr($strDate2, 14, 2);
	} else {
		$strDateText2 = "";
	}
	
	if ($strDate3 != "") {
		$strDateText3 = substr($strDate3, 0, 4)."-".substr($strDate3, 4, 2)."-".substr($strDate3, 6, 2)." ".substr($strDate3, 8, 2).":".substr($strDate3, 10, 2)." ~ ".substr($strDate3, 12, 2).":".substr($strDate3, 14, 2);
	} else {
		$strDateText3 = "";
	}
	
	echo "
	<tr>
	<td style='mso-number-format:\@;'>$strRegDate</td>
	<td style='mso-number-format:\@;'>$strConfirmText</td>
	<td style='mso-number-format:\@;'>$userName</td>
	<td style='mso-number-format:\@;'>$strGubun</td>
	<td style='mso-number-format:\@;'>$strOrgan</td>
	<td style='mso-number-format:\@;'>$strCharge</td>
	<td style='mso-number-format:\@;'>$userTel</td>
	<td style='mso-number-format:\@;'>$userEmail</td>
	<td style='mso-number-format:\@;'>$userAddr</td>
	<td style='mso-number-format:\@;'>$strTarget</td>
	<td style='mso-number-format:\@;'>$strTargetInfo</td>
	<td style='mso-number-format:\@;'>$strNum</td>
	
	<td style='mso-number-format:\@;'>$confirm_Author</td>
	<td style='mso-number-format:\@;'>$strAuthor1 - $strAuthorBook1</td>
	<td style='mso-number-format:\@;'>$strAuthor2 - $strAuthorBook2</td>
	<td style='mso-number-format:\@;'>$strAuthor3 - $strAuthorBook3</td>
	
	<td style='mso-number-format:\@;'>$confirm_date</td>
	<td style='mso-number-format:\@;'>$strDateText1</td>
	<td style='mso-number-format:\@;'>$strDateText2</td>
	<td style='mso-number-format:\@;'>$strDateText3</td>
	
	<td style='mso-number-format:\@;'>$strPrice원</td>
	<td style='mso-number-format:\@;'>$strPreView</td>
	<td style='mso-number-format:\@;'>$strPlan</td>
	<td style='mso-number-format:\@;'>$strContent</td>
	<td style='mso-number-format:\@;'>$strAdminMemo</td>
	</tr>";
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>