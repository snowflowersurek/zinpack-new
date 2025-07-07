<?php
include_once("_common.php");
if ($iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

$excelFileName = iconv("UTF-8", "EUC-KR", "회원리스트_").date('Ymd').".xls";

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=".$excelFileName );

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr style='background-color:#ccc; font-weight:bold;'>
<td>회원등급</td>
<td>이름</td>
<td>닉네임</td>
<td>이메일</td>
<td>휴대전화</td>
<td>주소</td>
<td>가입일시</td>
</tr>
";

$level_array = array(); 
$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
$result = sql_query($sql);
while($row = @sql_fetch_array($result)){
	array_push($level_array, $row["gl_name"]);
}

$sql = "select 
			mb_mail, 
			mb_name, 
			mb_nick, 
			mb_tel, 
			mb_zip_code, 
			mb_address, 
			mb_address_sub, 
			mb_level, 
			mb_datetime
		from $iw[member_table] 
			where ep_code = '$iw[store]' 
			order by mb_datetime desc";
$result = sql_query($sql);

while($row = @sql_fetch_array($result)){
	$mb_mail = $row["mb_mail"];
	$mb_name = $row["mb_name"];
	$mb_nick = $row["mb_nick"];
	$mb_tel = $row["mb_tel"];
	$mb_zip_code = $row["mb_zip_code"];
	$mb_address = $row["mb_address"];
	$mb_address_sub = $row["mb_address_sub"];
	$mb_level = $row["mb_level"];
	$mb_datetime = $row["mb_datetime"];
	
	echo "
	<tr>
	<td style='mso-number-format:\@;'>$level_array[$mb_level]</td>
	<td style='mso-number-format:\@;'>$mb_name</td>
	<td style='mso-number-format:\@;'>$mb_nick</td>
	<td style='mso-number-format:\@;'>$mb_mail</td>
	<td style='mso-number-format:\@;'>$mb_tel</td>
	<td style='mso-number-format:\@;'>[$mb_zip_code] $mb_address $mb_address_sub</td>
	<td style='mso-number-format:\@;'>$mb_datetime</td>
	</tr>";
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>



