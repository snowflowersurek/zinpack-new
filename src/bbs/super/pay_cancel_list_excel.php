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
header( "Content-Disposition: attachment; filename=LGU+취소내역_".date('Ymd_His')."(".$start_date."~".$end_date.").xls" );

$search = $_GET['search'];
$searchs = $_GET['searchs'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

$search_sql = " WHERE (a.lgdc_datetime >= ? AND a.lgdc_datetime <= ?) ";
$params = [$now_start, $now_end];
$types = "ss";

if($searchs) {
    $keyword_param = "%{$searchs}%";
    if($search == "a"){
        $search_sql .= " AND a.ep_code LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "b"){
        $search_sql .= " AND a.lgdc_tid LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "c"){
        $search_sql .= " AND a.lgd_oid LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }
}

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr>
<td>번호</td>
<td>업체명</td>
<td>사이트코드</td>
<td>회원코드</td>
<td>주문번호</td>
<td>결제응답</td>
<td>LG유플러스 거래번호</td>
<td>LG유플러스 발급 아이디</td>
<td>취소금액</td>
<td>결제수단</td>
<td>구매자명</td>
<td>구매내역</td>
<td>결제기관명</td>
<td>신용카드번호</td>
<td>결제휴대폰번호</td>
<td>등록일</td>
</tr>
";

$sql = "SELECT a.*, b.lgd_oid, b.lgd_amount, b.lgd_buyer, b.lgd_paytype, c.ep_corporate
        FROM {$iw['lgd_cancel_table']} a
        LEFT JOIN {$iw['lgd_table']} b ON a.lgdc_tid = b.lgd_tid
        LEFT JOIN {$iw['enterprise_table']} c ON a.ep_code = c.ep_code
        {$search_sql} 
        ORDER BY a.lgdc_no desc";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$i = 1;
while($row = mysqli_fetch_assoc($result)){
	$lgdc_no = $row["lgdc_no"];
	$ep_code = $row["ep_code"];
	$mb_code = $row["mb_code"];
	$lgd_oid = $row["lgd_oid"];
	$lgdc_tid = $row["lgdc_tid"];
	$lgdc_respmsg = $row["lgdc_respmsg"];
	$lgdc_mid = $row["lgdc_mid"];
	$lgdc_datetime = $row["lgdc_datetime"];

	if($row["lgd_paytype"]=="SC0010"){
		$lgd_paytype = "신용카드";
	}else if($row["lgd_paytype"]=="SC0030"){
		$lgd_paytype = "계좌이체";
	}else if($row["lgd_paytype"]=="SC0060"){
		$lgd_paytype = "휴대폰";
	}

	$rows = sql_fetch("select * from $iw[lgd_table] where lgd_tid = '$lgdc_tid' and lgd_display = 2");

	$lgd_amount = $rows["lgd_amount"];
	$lgd_financeauthnum = $rows["lgd_financeauthnum"];
	$lgd_buyer = $rows["lgd_buyer"];
	$lgd_paytype = $rows["lgd_paytype"];
	$lgd_amount = $rows["lgd_amount"];
	$lgd_paytype = $rows["lgd_paytype"];
	$lgd_buyer = $rows["lgd_buyer"];
	$lgd_productinfo = $rows["lgd_productinfo"];
	$lgd_financename = $rows["lgd_financename"];
	$lgd_cardnum = $rows["lgd_cardnum"];
	$lgd_telno = $rows["lgd_telno"];

	if($lgd_paytype=="SC0010"){
		$lgd_paytype = "신용카드";
	}else if($lgd_paytype=="SC0030"){
		$lgd_paytype = "계좌이체";
	}else if($lgd_paytype=="SC0060"){
		$lgd_paytype = "휴대폰";
	}

	$row2 = sql_fetch("select ep_corporate from $iw[enterprise_table] where ep_code = '$ep_code'");
	$ep_corporate = $row2["ep_corporate"];

	echo "
	<tr>
	<td style='mso-number-format:\@;'>$lgdc_no</td>
	<td style='mso-number-format:\@;'>$ep_corporate</td>
	<td style='mso-number-format:\@;'>$ep_code</td>
	<td style='mso-number-format:\@;'>$mb_code</td>
	<td style='mso-number-format:\@;'>$lgd_oid</td>
	<td style='mso-number-format:\@;'>$lgdc_respmsg</td>
	<td style='mso-number-format:\@;'>$lgdc_tid</td>
	<td style='mso-number-format:\@;'>$lgdc_mid</td>
	<td>$lgd_amount</td>
	<td style='mso-number-format:\@;'>$lgd_paytype</td>
	<td style='mso-number-format:\@;'>$lgd_buyer</td>
	<td style='mso-number-format:\@;'>$lgd_productinfo</td>
	<td style='mso-number-format:\@;'>$lgd_financename</td>
	<td style='mso-number-format:\@;'>$lgd_cardnum</td>
	<td style='mso-number-format:\@;'>$lgd_telno</td>
	<td style='mso-number-format:\@;'>$lgdc_datetime</td>
	</tr>";
}
mysqli_stmt_close($stmt);

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>