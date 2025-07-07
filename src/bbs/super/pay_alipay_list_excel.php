<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","{$iw['super_path']}/login.php?type=dashboard&ep={$iw['store']}&gp={$iw['group']}");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=ALIPAY결제내역_".date('Ymd_His')."(".$start_date."~".$end_date.").xls" );

$search = $_GET['search'];
$searchs = $_GET['searchs'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

$search_sql = " WHERE (a.ap_datetime >= ? AND a.ap_datetime <= ?) ";
$params = [$now_start, $now_end];
$types = "ss";

if($searchs) {
    $keyword_param = "%{$searchs}%";
    if($search == "a"){
        $search_sql .= " AND a.ep_code LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "b"){
        $search_sql .= " AND a.ap_trade_no LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "c"){
        $search_sql .= " AND a.ap_buyer_email LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }
}

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr>
<td>번호</td>
<td>사이트코드</td>
<td>회원코드</td>
<td>주문번호</td>
<td>결제응답</td>
<td>ALIPAY 거래번호</td>
<td>결제금액</td>
<td>구매내역</td>
<td>등록일</td>
</tr>
";

$sql = "SELECT a.*, b.ep_corporate 
        FROM {$iw['alipay_table']} a
        LEFT JOIN {$iw['enterprise_table']} b ON a.ep_code = b.ep_code
        {$search_sql} ORDER BY a.ap_no DESC";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$i = 1;
while($row = mysqli_fetch_assoc($result)){
	$pp_no = $row["ap_no"];
	$ep_code = $row["ep_code"];
	$mb_code = $row["mb_code"];
	$pp_invoice = $row["ap_out_trade_no"];
	$pp_txn_id = $row["ap_trade_no"];
	$pp_payment_status = $row["ap_trade_status"];
	$pp_mc_gross = $row["ap_total_fee"];
	$pp_item_name = $row["ap_item_name"];
	$pp_datetime = $row["ap_datetime"];

	echo "
	<tr>
	<td style='mso-number-format:\@;'>$pp_no</td>
	<td style='mso-number-format:\@;'>$ep_code</td>
	<td style='mso-number-format:\@;'>$mb_code</td>
	<td style='mso-number-format:\@;'>$pp_invoice</td>
	<td style='mso-number-format:\@;'>$pp_payment_status</td>
	<td style='mso-number-format:\@;'>$pp_txn_id</td>
	<td>".national_money("en", $pp_mc_gross)."</td>
	<td style='mso-number-format:\@;'>$pp_item_name</td>
	<td style='mso-number-format:\@;'>$pp_datetime</td>
	</tr>";
}
mysqli_stmt_close($stmt);

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>



