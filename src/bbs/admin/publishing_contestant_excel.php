<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

$contest_code = $_GET[contest_code];

$excelFileName = iconv("UTF-8", "EUC-KR", "공모전_응모내역_").date('Ymd').".xls";

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=".$excelFileName );

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr style='background-color:#ccc; font-weight:bold;'>
	<td>접수번호</td>
	<td>작품제목</td>
	<td>응모자명</td>
	<td>연락처</td>
	<td>이메일</td>
	<td>주소</td>
	<td>응모일</td>
</tr>
";

$sql = "select * from iw_publishing_contestant where ep_code = '$iw[store]' and gp_code = '$iw[group]' and contest_code = '$contest_code' order by idx asc";
$result = sql_query($sql);

while($row = @sql_fetch_array($result)){
	$idx = $row["idx"];
	$user_name = $row["user_name"];
	$user_phone = $row["user_phone"];
	$user_email = $row["user_email"];
	$zipcode = $row["zipcode"];
	$addr1 = $row["addr1"];
	$addr2 = $row["addr2"];
	$work_title = $row["work_title"];
	$origin_filename = $row["origin_filename"];
	$attach_filename = $row["attach_filename"];
	$reg_date = substr($row["reg_date"], 0, 16);
	
	echo "
	<tr>
		<td style='mso-number-format:\@;'>$idx</td>
		<td style='mso-number-format:\@;'>$work_title</td>
		<td style='mso-number-format:\@;'>$user_name</td>
		<td style='mso-number-format:\@;'>$user_phone</td>
		<td style='mso-number-format:\@;'>$user_email</td>
		<td style='mso-number-format:\@;'>[$zipcode] $addr1 $addr2</td>
		<td style='mso-number-format:\@;'>$reg_date</td>
	</tr>";
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>



