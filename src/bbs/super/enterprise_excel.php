<?php
include_once("_common.php");
if ($iw[level] != "super") alert("잘못된 접근입니다!","$iw[super_path]/login.php?type=dashboard&ep=$iw[store]&gp=$iw[group]");

$excelFileName = iconv("UTF-8", "EUC-KR", "ZINPACK_사용업체정보_").date('Ymd').".xls";

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=".$excelFileName );

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr style='background-color:#ccc; font-weight:bold;'>
	<td>업체명</td>
	<td>가입일</td>
	<td>사업자번호</td>
	<td>사이트코드</td>
	<td>포워딩ID</td>
	<td>도메인</td>
	<td>언어설정</td>
	<td>게시판 기능</td>
	<td>출판도서 기능</td>
	<td>쇼핑몰 기능</td>
	<td>컨텐츠몰 기능</td>
	<td>이북몰 기능</td>
	<td>정산비율</td>
	<td>복제방지</td>
	<td>파일업로드</td>
	<td>노출설정</td>
	<td>가입방식</td>
	<td>닉네임 공개</td>
	<td>담당자</td>
	<td>이메일</td>
	<td>전화번호</td>
	<td>주소</td>
</tr>
";

$sql = "select * from $iw[enterprise_table] where ep_code<>'$iw[store]'";
$result = sql_query($sql);

$i = 0;
while($row = @sql_fetch_array($result)){
	$ep_code = $row["ep_code"];
	$mb_code = $row["mb_code"];
	$ep_nick = $row["ep_nick"];
	$ep_corporate = $row["ep_corporate"];
	$ep_permit_number = $row["ep_permit_number"];
	$ep_state_mcb = $row["ep_state_mcb"];
	if ($ep_state_mcb==1){
		$ep_state_mcb = "사용";
	} else {
		$ep_state_mcb = "미사용";
	}
	$ep_state_publishing = $row["ep_state_publishing"];
	if ($ep_state_publishing==1){
		$ep_state_publishing = "사용";
	} else {
		$ep_state_publishing = "미사용";
	}
	$ep_state_doc = $row["ep_state_doc"];
	if ($ep_state_doc==1){
		$ep_state_doc = "사용";
	} else {
		$ep_state_doc = "미사용";
	}
	$ep_state_shop = $row["ep_state_shop"];
	if ($ep_state_shop==1){
		$ep_state_shop = "사용";
	} else {
		$ep_state_shop = "미사용";
	}
	$ep_state_book = $row["ep_state_book"];
	if ($ep_state_book==1){
		$ep_state_book = "사용";
	} else {
		$ep_state_book = "미사용";
	}
	$ep_datetime = $row["ep_datetime"];
	$ep_exposed = $row["ep_exposed"];
	if($ep_exposed==1){
		$ep_exposed = "회원에게만 노출";
	}else{
		$ep_exposed = "비회원에게도 노출";
	}
	$ep_autocode = $row["ep_autocode"];
	$ep_jointype = $row["ep_jointype"];
	if ($ep_jointype==1) {
		$ep_jointype = "가입신청 > 관리자승인";
	} else if ($ep_jointype==2) {
		$ep_jointype = "무조건 가입";
	} else if ($ep_jointype==4) {
		$ep_jointype = "초대후 가입";
	} else if ($ep_jointype==5) {
		$ep_jointype = "가입코드 입력후 자동승인 (".$ep_autocode.")";
	} else {
		$ep_jointype = "가입불가";
	}
	$ep_language = $row["ep_language"];
	if ($ep_language=="en"){
		$ep_language = "영문";
	} else {
		$ep_language = "한글";
	}
	$ep_anonymity = $row["ep_anonymity"];
	if ($ep_anonymity==0) {
		$ep_anonymity = "공개";
	} else {
		$ep_anonymity = "비공개";
	}
	$ep_domain = $row["ep_domain"];
	$ep_upload = $row["ep_upload"];
	$ep_upload_size = $row["ep_upload_size"];
	if($ep_upload==1){
		$ep_upload = "가능 (".$ep_upload_size." MB)";
	}else{
		$ep_upload = "불가능";
	}
	$ep_point_seller = $row["ep_point_seller"];
	$ep_point_site = $row["ep_point_site"];
	$ep_point_super = $row["ep_point_super"];
	$ep_copy_off = $row["ep_copy_off"];
	if($ep_copy_off==1){
		$ep_copy_off = "ON";
	}else{
		$ep_copy_off = "OFF";
	}
	
	$sql = " select * from $iw[member_table] where ep_code = '$ep_code' and mb_code = '$mb_code'";
	$row = sql_fetch($sql);
	
	$mb_name = $row["mb_name"];
	$mb_mail = $row["mb_mail"];
	$mb_tel = $row["mb_tel"];
	$mb_zip_code = $row["mb_zip_code"];
	$mb_address = $row["mb_address"];
	$mb_address_sub = $row["mb_address_sub"];
	
	echo "
		<tr>
		<td style='mso-number-format:\@;'>".$ep_corporate."</td>
		<td style='mso-number-format:\@;'>".$ep_datetime."</td>
		<td style='mso-number-format:\@;'>".$ep_permit_number."</td>
		<td style='mso-number-format:\@;'>".$ep_code."</td>
		<td style='mso-number-format:\@;'>".$ep_nick."</td>
		<td style='mso-number-format:\@;'>".$ep_domain."</td>
		<td style='mso-number-format:\@;'>".$ep_language."</td>
		<td style='mso-number-format:\@;'>".$ep_state_mcb."</td>
		<td style='mso-number-format:\@;'>".$ep_state_publishing."</td>
		<td style='mso-number-format:\@;'>".$ep_state_shop."</td>
		<td style='mso-number-format:\@;'>".$ep_state_doc."</td>
		<td style='mso-number-format:\@;'>".$ep_state_book."</td>
		<td style='mso-number-format:\@;'>위즈윈디지털(".$ep_point_super."%), 사이트(".$ep_point_site."%), 판매자(".$ep_point_seller."%)</td>
		<td style='mso-number-format:\@;'>".$ep_copy_off."</td>
		<td style='mso-number-format:\@;'>".$ep_upload."</td>
		<td style='mso-number-format:\@;'>".$ep_exposed."</td>
		<td style='mso-number-format:\@;'>".$ep_jointype."</td>
		<td style='mso-number-format:\@;'>".$ep_anonymity."</td>
		<td style='mso-number-format:\@;'>".$mb_name."</td>
		<td style='mso-number-format:\@;'>".$mb_mail."</td>
		<td style='mso-number-format:\@;'>".$mb_tel."</td>
		<td style='mso-number-format:\@;'>[".$mb_zip_code."] ".$mb_address." ".$mb_address_sub."</td>
		</tr>
		";
	$i ++;
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>



