<?php
include_once("_common.php");
if ($iw[level] != "super") alert("잘못된 접근입니다!","$iw[super_path]/login.php?type=dashboard&ep=$iw[store]&gp=$iw[group]");

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=book_sale_list_".date("Y_m_d_His").".xls" );

$search = $_GET[search];
$searchs = $_GET[searchs];
$start_date = $_GET[start_date];
$end_date = $_GET[end_date];

if($search == "a"){
	$search_sql = " AND a.ep_code LIKE '%".$searchs."%'";
}else if($search == "b"){
	$search_sql = " AND a.gp_code LIKE '%".$searchs."%'";
}else if($search == "c"){
	$search_sql = " AND a.seller_mb_code LIKE '%".$searchs."%'";
}else if($search == "d"){
	$search_sql = " AND a.mb_code LIKE '%".$searchs."%'";
}else if($search == "e"){
	$search_sql = " AND a.bd_code LIKE '%".$searchs."%'";
}else if($search == "f"){
	$search_sql = " AND a.bb_subject LIKE '%".$searchs."%'";
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
</tr>
";

$sql = "SELECT a.*,b.ep_corporate,c.gp_subject,d.mb_name,d.mb_nick,e.mb_name as member_name,e.mb_nick as member_nick FROM ".$iw[book_buy_table]." a LEFT JOIN ".$iw[enterprise_table]." b ON a.ep_code = b.ep_code LEFT JOIN ".$iw[group_table]." c ON a.gp_code = c.gp_code LEFT JOIN ".$iw[member_table]." d ON a.seller_mb_code = d.mb_code LEFT JOIN ".$iw[member_table]." e ON a.mb_code = e.mb_code WHERE a.bb_datetime >= '".$now_start."' AND a.bb_datetime <= '".$now_end."'".$search_sql." ORDER BY bb_no DESC";
$result = sql_query($sql);

$i = 1;
while($row = @sql_fetch_array($result)){
	$bb_subject = $row[bb_subject];
	$bd_code = $row[bd_code];
	$bb_datetime = $row[bb_datetime];
	$bb_price = $row[bb_price];
	$bb_price_seller = $row[bb_price_seller];
	$bb_price_site = $row[bb_price_site];
	$bb_price_super = $row[bb_price_super];
	$ep_code = $row[ep_code];
	$ep_corporate = $row[ep_corporate];
	$gp_code = $row[gp_code];
	$gp_subject = $row[gp_subject];
	$seller_mb_code = $row[seller_mb_code];
	$mb_name = $row[mb_name];
	$mb_nick = $row[mb_nick];
	$mb_code = $row[mb_code];
	$member_name = $row[member_name];
	$member_nick = $row[member_nick];

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
		<td style='mso-number-format:\@;'>".$bb_datetime."</td>
		<td style='mso-number-format:\@;'>".$bd_code."</td>
		<td style='mso-number-format:\@;'>".$bb_subject."</td>
		<td>".$bb_price."</td>
		<td>".$bb_price_super."</td>
		<td>".$bb_price_site."</td>
		<td>".$bb_price_seller."</td>
		</tr>
		";
	$i ++;
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>



