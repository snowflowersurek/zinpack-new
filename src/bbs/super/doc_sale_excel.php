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
header( "Content-Disposition: attachment; filename=book_sale_list_".date("Y_m_d_His").".xls" );

$search = $_GET['search'];
$searchs = $_GET['searchs'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$search_sql = " WHERE a.db_datetime >= ? AND a.db_datetime <= ? ";
$params = [$now_start, $now_end];
$types = "ss";

if($searchs) {
    $keyword_param = "%{$searchs}%";
    if($search == "a"){
        $search_sql .= " AND a.ep_code LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "b"){
        $search_sql .= " AND a.gp_code LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "c"){
        $search_sql .= " AND a.seller_mb_code LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "d"){
        $search_sql .= " AND a.mb_code LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "e"){
        $search_sql .= " AND a.dd_code LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }else if($search == "f"){
        $search_sql .= " AND a.db_subject LIKE ? ";
        $params[] = $keyword_param; $types .= "s";
    }
}

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr>
	<td>번호</td>
	<td>사이트</td>
	<td>사이트코드</td>
	<td>그룹</td>
	<td>그룹코드</td>
	<td>판매자</td>
	<td>판매자코드</td>
	<td>판매자닉네임</td>
	<td>구매자</td>
	<td>구매자코드</td>
	<td>구매자닉네임</td>
	<td>판매일자</td>
	<td>상품코드</td>
	<td>제목</td>
	<td>판매(point)</td>
	<td>위즈윈디지털(point)</td>
	<td>사이트(point)</td>
	<td>판매자(point)</td>
	<td>유효기한</td>
</tr>
";

$sql = "SELECT a.*,b.ep_corporate,c.gp_subject,d.mb_name,d.mb_nick,e.mb_name as member_name,e.mb_nick as member_nick 
        FROM {$iw['doc_buy_table']} a 
        LEFT JOIN {$iw['enterprise_table']} b ON a.ep_code = b.ep_code 
        LEFT JOIN {$iw['group_table']} c ON a.gp_code = c.gp_code 
        LEFT JOIN {$iw['member_table']} d ON a.seller_mb_code = d.mb_code 
        LEFT JOIN {$iw['member_table']} e ON a.mb_code = e.mb_code 
        {$search_sql} ORDER BY db_no DESC";

$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$i = 1;
while($row = mysqli_fetch_assoc($result)){
	$db_subject = $row['db_subject'];
	$dd_code = $row['dd_code'];
	$db_datetime = $row['db_datetime'];
	$db_end_datetime = $row['db_end_datetime'];
	$db_price = $row['db_price'];
	$db_price_seller = $row['db_price_seller'];
	$db_price_site = $row['db_price_site'];
	$db_price_super = $row['db_price_super'];
	$ep_code = $row['ep_code'];
	$ep_corporate = $row['ep_corporate'];
	$gp_code = $row['gp_code'];
	$gp_subject = $row['gp_subject'];
	$seller_mb_code = $row['seller_mb_code'];
	$mb_name = $row['mb_name'];
	$mb_nick = $row['mb_nick'];
	$mb_code = $row['mb_code'];
	$member_name = $row['member_name'];
	$member_nick = $row['member_nick'];

	if($db_datetime == $db_end_datetime){
		$db_date = "제한없음";
	}else{
		$db_date = date("Y.m.d (H:i)", strtotime($db_end_datetime));
	}

	echo "
		<tr>
		<td>".$i."</td>
		<td style='mso-number-format:\@;'>".$ep_corporate."</td>
		<td style='mso-number-format:\@;'>".$ep_code."</td>
		<td style='mso-number-format:\@;'>".$gp_subject."</td>
		<td style='mso-number-format:\@;'>".$gp_code."</td>
		<td style='mso-number-format:\@;'>".$mb_name."</td>
		<td style='mso-number-format:\@;'>".$seller_mb_code."</td>
		<td style='mso-number-format:\@;'>".$mb_name."</td>
		<td style='mso-number-format:\@;'>".$member_name."</td>
		<td style='mso-number-format:\@;'>".$mb_nick."</td>
		<td style='mso-number-format:\@;'>".$member_nick."</td>
		<td style='mso-number-format:\@;'>".$db_datetime."</td>
		<td style='mso-number-format:\@;'>".$dd_code."</td>
		<td style='mso-number-format:\@;'>".$db_subject."</td>
		<td>".$db_price."</td>
		<td>".$db_price_super."</td>
		<td>".$db_price_site."</td>
		<td>".$db_price_seller."</td>
		<td>".$db_date."</td>
		</tr>
		";
	$i ++;
}
mysqli_stmt_close($stmt);

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>