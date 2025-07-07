<?php
include_once("_common.php");
if ($iw['level']=="guest") alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$userName = trim($_POST['userName']);
$strgubun = trim($_POST['strgubun']);
$strgubunTxt = trim($_POST['strgubunTxt']);
$strOrgan = trim($_POST['strOrgan']);
$userTel1 = trim($_POST['userTel1']);
$userTel2 = trim($_POST['userTel2']);
$userTel3 = trim($_POST['userTel3']);
$userTel = $userTel1."-".$userTel2."-".$userTel3;
$userPhone1 = trim($_POST['userPhone1']);
$userPhone2 = trim($_POST['userPhone2']);
$userPhone3 = trim($_POST['userPhone3']);
$userPhone = $userPhone1."-".$userPhone2."-".$userPhone3;
$userEmail = trim($_POST['userEmail']);
$zipcode = trim($_POST['zipcode']);
$addr1 = trim($_POST['addr1']);
$addr2 = trim($_POST['addr2']);
$homepage = trim($_POST['homepage']);
$picture_idx = trim($_POST['picture_idx']);
//$picture_name = trim(mysql_real_escape_string($_POST[picture_name]));
$exhibitDate = trim($_POST['exhibitDate']);
$arrayExhibitDate = explode("-", $exhibitDate);
$else_txt = trim($_POST['else_txt']);

// 중복 확인
$sql_check = "select idx from {$iw['publishing_exhibit_status_table']} where ep_code = ? and picture_idx = ? and year = ? and month = ?";
$stmt_check = mysqli_prepare($db_conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ssii", $iw['store'], $picture_idx, $arrayExhibitDate[0], $arrayExhibitDate[1]);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
$check_row = mysqli_fetch_assoc($result_check);
mysqli_stmt_close($stmt_check);

if (!$check_row["idx"]) {
	$md_date = date("Y-m-d H:i:s");
	
    // 전시명 조회
    $sql_pic = "select picture_name from {$iw['publishing_exhibit_table']} where ep_code = ? and picture_idx = ?";
    $stmt_pic = mysqli_prepare($db_conn, $sql_pic);
    mysqli_stmt_bind_param($stmt_pic, "si", $iw['store'], $picture_idx);
    mysqli_stmt_execute($stmt_pic);
    $result_pic = mysqli_stmt_get_result($stmt_pic);
	$row_pic = mysqli_fetch_assoc($result_pic);
    mysqli_stmt_close($stmt_pic);
	$picture_name = $row_pic["picture_name"];
	
    // 신청 정보 삽입
	$sql_insert = "insert into {$iw['publishing_exhibit_status_table']} set
			ep_code = ?, gp_code = ?, mb_code = ?, userName = ?, strgubun = ?,
			strgubunTxt = ?, strOrgan = ?, userTel = ?, userPhone = ?, userEmail = ?,
			zipcode = ?, addr1 = ?, addr2 = ?, homepage = ?, picture_idx = ?,
			picture_name = ?, year = ?, month = ?, else_txt = ?, stat = '1', md_date = ?";
    $stmt_insert = mysqli_prepare($db_conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssssssssssssssisiss", 
        $iw['store'], $iw['group'], $iw['member'], $userName, $strgubun, $strgubunTxt,
        $strOrgan, $userTel, $userPhone, $userEmail, $zipcode, $addr1, $addr2,
        $homepage, $picture_idx, $picture_name, $arrayExhibitDate[0], $arrayExhibitDate[1],
        $else_txt, $md_date);
	mysqli_stmt_execute($stmt_insert);
    mysqli_stmt_close($stmt_insert);
	
	alert("그림전시 신청이 완료되었습니다.","{$iw['m_path']}/publishing_exhibit_status.php?ep={$iw['store']}&gp={$iw['group']}");
} else {
	alert("선택하신 일정은 이미 다른 기관에서 신청되었습니다.","{$iw['m_path']}/publishing_exhibit_status.php?ep={$iw['store']}&gp={$iw['group']}");
}
?>



