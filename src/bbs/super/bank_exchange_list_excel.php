<?php
include_once("_common.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=".date('Ymd His').".xls" );

$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr>
<td>번호</td>
<td>신청일</td>
<td>업체명</td>
<td>사이트코드</td>
<td>닉네임</td>
<td>회원코드</td>
<td>포인트(Point)</td>
<td>금액(원)</td>
<td>은행</td>
<td>계좌번호</td>
<td>예금주</td>
<td>지급일</td>
<td>처리내역</td>
</tr>
";

$sql = "SELECT a.*, b.mb_nick, c.ep_corporate 
        FROM {$iw['exchange_table']} a
        LEFT JOIN {$iw['member_table']} b ON a.ep_code = b.ep_code AND a.mb_code = b.mb_code
        LEFT JOIN {$iw['enterprise_table']} c ON a.ep_code = c.ep_code
        WHERE (a.ec_datetime >= ? AND a.ec_datetime <= ?) 
        ORDER BY a.ec_no desc";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $now_start, $now_end);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while($row = mysqli_fetch_assoc($result)){
	$ec_no = $row["ec_no"];
	$ep_code = $row["ep_code"];
	$ec_bank = $row["ec_bank"];
	$ec_number = $row["ec_number"];
	$ec_holder = $row["ec_holder"];
	$ec_point = number_format($row["ec_point"]);
	$ec_amount = number_format($row["ec_amount"]);
	$ec_display = $row["ec_display"];

	if($ec_display == 1){
		$ec_display = "처리완료";
		$ec_give_datetime = date("Y-m-d", strtotime($row["ec_give_datetime"]));
	}else{
		$ec_display = "입금대기";
		$ec_give_datetime = "";
	}

	$ec_datetime = date("Y-m-d", strtotime($row["ec_datetime"]));
	$mb_code = $row["mb_code"];
	$mb_nick = $row["mb_nick"];
	$ep_corporate = $row["ep_corporate"];

	echo "
	<tr>
	<td style='mso-number-format:\@;'>$ec_no</td>
	<td style='mso-number-format:\@;'>$ec_datetime</td>
	<td style='mso-number-format:\@;'>$ep_corporate</td>
	<td style='mso-number-format:\@;'>$ep_code</td>
	<td style='mso-number-format:\@;'>$mb_nick</td>
	<td style='mso-number-format:\@;'>$mb_code</td>
	<td>$ec_point</td>
	<td>$ec_amount</td>
	<td style='mso-number-format:\@;'>$ec_bank</td>
	<td style='mso-number-format:\@;'>$ec_number</td>
	<td style='mso-number-format:\@;'>$ec_holder</td>
	<td style='mso-number-format:\@;'>$ec_give_datetime</td>
	<td style='mso-number-format:\@;'>$ec_display</td>
	</tr>";
}
mysqli_stmt_close($stmt);

echo "
</table>";

goto_url($_SERVER['HTTP_REFERER']);

?>



