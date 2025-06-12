<?php
include_once("_common.php");

$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=LGU+결제내역_".date('Ymd_His')."(".$start_date."~".$end_date.").xls" );

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

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
<td>결제금액</td>
<td>입금액</td>
<td>결제수단</td>
<td>구매자명</td>
<td>구매내역</td>
<td>결제기관명</td>
<td>신용카드번호</td>
<td>결제휴대폰번호</td>
<td>등록일</td>
</tr>
";

$sql = "select * from $iw[lgd_table] where (lgd_datetime >= '$now_start' and lgd_datetime <= '$now_end') order by lgd_no desc";
$result = sql_query($sql);

while($row = @sql_fetch_array($result)){
	$lgd_no = $row["lgd_no"];
	$ep_code = $row["ep_code"];
	$mb_code = $row["mb_code"];
	$lgd_oid = $row["lgd_oid"];
	$lgd_tid = $row["lgd_tid"];
	$lgd_mid = $row["lgd_mid"];
	$lgd_amount = $row["lgd_amount"];
	$lgd_paytype = $row["lgd_paytype"];
	$lgd_buyer = $row["lgd_buyer"];
	$lgd_productinfo = $row["lgd_productinfo"];
	$lgd_financename = $row["lgd_financename"];
	$lgd_cardnum = $row["lgd_cardnum"];
	$lgd_telno = $row["lgd_telno"];
	$lgd_datetime = $row["lgd_datetime"];
	$lgd_respmsg = $row["lgd_respmsg"];
	$lgd_castamount = $row["lgd_castamount"];

	if($lgd_paytype=="SC0010"){
		$lgd_paytype = "신용카드";
	}else if($lgd_paytype=="SC0030"){
		$lgd_paytype = "계좌이체";
	}else if($lgd_paytype=="SC0060"){
		$lgd_paytype = "휴대폰";
	}else if($lgd_paytype=="SC0040"){
		$lgd_paytype = "가상계좌";
	}
	
	$row2 = sql_fetch("select ep_corporate from $iw[enterprise_table] where ep_code = '$ep_code'");
	$ep_corporate = $row2["ep_corporate"];
	echo "
	<tr>
	<td style='mso-number-format:\@;'>$lgd_no</td>
	<td style='mso-number-format:\@;'>$ep_corporate</td>
	<td style='mso-number-format:\@;'>$ep_code</td>
	<td style='mso-number-format:\@;'>$mb_code</td>
	<td style='mso-number-format:\@;'>$lgd_oid</td>
	<td style='mso-number-format:\@;'>$lgd_respmsg</td>
	<td style='mso-number-format:\@;'>$lgd_tid</td>
	<td style='mso-number-format:\@;'>$lgd_mid</td>
	<td>$lgd_amount</td>
	<td>$lgd_castamount</td>
	<td style='mso-number-format:\@;'>$lgd_paytype</td>
	<td style='mso-number-format:\@;'>$lgd_buyer</td>
	<td style='mso-number-format:\@;'>$lgd_productinfo</td>
	<td style='mso-number-format:\@;'>$lgd_financename</td>
	<td style='mso-number-format:\@;'>$lgd_cardnum</td>
	<td style='mso-number-format:\@;'>$lgd_telno</td>
	<td style='mso-number-format:\@;'>$lgd_datetime</td>
	</tr>";
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>