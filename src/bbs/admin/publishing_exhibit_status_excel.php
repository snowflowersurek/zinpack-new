<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

$excelFileName = iconv("UTF-8", "EUC-KR", "그림전시_신청_").date('Ymd').".xls";

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=".$excelFileName );

$search_sql = "and year >= ".date('Y')." and month >= ".date('m');

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr style='background-color:#ccc; font-weight:bold;'>
<td>신청현황</td>
<td>신청그림</td>
<td>전시일정</td>
<td>신청자</td>
<td>신청기관 분류</td>
<td>기관명</td>
<td>일반전화</td>
<td>휴대전화</td>
<td>이메일</td>
<td>주소</td>
<td>홈페이지</td>
<td>이전 전시기관</td>
<td>다음 전시기관</td>
<td>남기고싶은말(기타 요청 사항)</td>
<td>관리자 메모</td>
<td>신청일</td>
</tr>
";

$sql = "select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' $search_sql order by picture_name, strDate1 asc";
$result = sql_query($sql);

while($row = @sql_fetch_array($result)){
	$stat = $row["stat"];
	$picture_name = $row["picture_name"];
	$picture_idx = $row["picture_idx"];
	$year = $row["year"];
	$month = $row["month"];
	$userName = $row["userName"];
	$strgubun = $row["strgubun"];
	$strgubunTxt = $row["strgubunTxt"];
	$strOrgan = $row["strOrgan"];
	$userTel = $row["userTel"];
	$userPhone = $row["userPhone"];
	$userEmail = $row["userEmail"];
	$zipcode = $row["zipcode"];
	$addr1 = $row["addr1"];
	$addr2 = $row["addr2"];
	$homepage = $row["homepage"];
	$else_txt = $row["else_txt"];
	$admin_txt = $row["admin_txt"];
	$md_date = substr($row["md_date"], 0, 10);
	
	if ($stat == "1") {
		$statTxt = "대기 중";
	} else if ($stat == "2") {
		$statTxt = "<span style='color:red;'>전시확정</span>";
	} else if ($stat == "3") {
		$statTxt = "보류";
	} else if ($stat == "4") {
		$statTxt = "전시연기";
	}
	
	if ($strgubun == "기타") {
		$strgubun = $strgubun."(".$strgubunTxt.")";
	}
	
	if ($year != "" && $month != "") {
		$exhibit_month = sprintf("%04d", $year)."년 ".sprintf("%02d", $month)."월";
		
		if ($month == 1) {
			$prev_year = $year - 1;
			$prev_month = 12;
			$next_year = $year;
			$next_month = $month + 1;
		} else if ($month == 12) {
			$prev_year = $year - 1;
			$prev_month = 12;
			$next_year = $year + 1;
			$next_month = 1;
		} else {
			$prev_year = $year;
			$prev_month = $month - 1;
			$next_year = $year;
			$next_month = $month + 1;
		}
		
		$prev_row = sql_fetch("select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' and year = '$prev_year' and month = '$prev_month'");
		$prev_strOrgan = $prev_row["strOrgan"];
		
		$next_row = sql_fetch("select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' and year = '$next_year' and month = '$next_month'");
		$next_strOrgan = $next_row["strOrgan"];
	} else {
		$exhibit_month = "";
		$prev_strOrgan = "";
		$next_strOrgan = "";
	}
	
	echo "
	<tr>
	<td style='mso-number-format:\@;'>$statTxt</td>
	<td style='mso-number-format:\@;'>$picture_name</td>
	<td style='mso-number-format:\@;'>$exhibit_month</td>
	<td style='mso-number-format:\@;'>$userName</td>
	<td style='mso-number-format:\@;'>$strgubun</td>
	<td style='mso-number-format:\@;'>$strOrgan</td>
	<td style='mso-number-format:\@;'>$userTel</td>
	<td style='mso-number-format:\@;'>$userPhone</td>
	<td style='mso-number-format:\@;'>$userEmail</td>
	<td style='mso-number-format:\@;'>($zipcode)$addr1 $addr2</td>
	<td style='mso-number-format:\@;'>$homepage</td>
	<td style='mso-number-format:\@;'>$prev_strOrgan</td>
	<td style='mso-number-format:\@;'>$next_strOrgan</td>
	<td style='mso-number-format:\@;'>$else_txt</td>
	<td style='mso-number-format:\@;'>$admin_txt</td>
	<td style='mso-number-format:\@;'>$md_date</td>
	</tr>";
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>



