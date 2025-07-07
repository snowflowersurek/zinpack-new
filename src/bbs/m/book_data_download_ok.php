<?php
include_once("_common.php");
if (($iw['gp_level'] == "gp_guest" && $iw['group'] != "all") || ($iw['level'] == "guest" && $iw['group'] == "all")) alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

$bd_code = $_GET["book"] ?? '';
$bd_support = (int)$_GET["support"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = $iw[path].'/'.$iw[type].'/'.$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

// 구매 이력 확인
$sql_buy_check = "select count(*) as cnt from {$iw['book_buy_table']} where ep_code = ? and gp_code= ? and mb_code = ? and bd_code = ?";
$stmt_buy_check = mysqli_prepare($db_conn, $sql_buy_check);
mysqli_stmt_bind_param($stmt_buy_check, "ssss", $iw['store'], $iw['group'], $iw['member'], $bd_code);
mysqli_stmt_execute($stmt_buy_check);
$result_buy_check = mysqli_stmt_get_result($stmt_buy_check);
$row_buy_check = mysqli_fetch_assoc($result_buy_check);
mysqli_stmt_close($stmt_buy_check);
$bd_buy = ($row_buy_check['cnt']) ? "YES" : "NO";

// 이북 정보 조회
$sql_file = "select * from {$iw['book_data_table']} where ep_code = ? and gp_code= ? and bd_display = 1 and bd_code = ?";
$stmt_file = mysqli_prepare($db_conn, $sql_file);
mysqli_stmt_bind_param($stmt_file, "sss", $iw['store'], $iw['group'], $bd_code);
mysqli_stmt_execute($stmt_file);
$result_file = mysqli_stmt_get_result($stmt_file);
$file = mysqli_fetch_assoc($result_file);
mysqli_stmt_close($stmt_file);
if (!$file["bd_no"]) alert(national_language($iw['language'],"a0161","파일 정보가 존재하지 않습니다."));
$bd_price = $file['bd_price'];

// 사용자 포인트 조회
$sql_point = "select mb_point from {$iw['member_table']} where ep_code = ? and mb_code = ?";
$stmt_point = mysqli_prepare($db_conn, $sql_point);
mysqli_stmt_bind_param($stmt_point, "ss", $iw['store'], $iw['member']);
mysqli_stmt_execute($stmt_point);
$result_point = mysqli_stmt_get_result($stmt_point);
$row_point = mysqli_fetch_assoc($result_point);
mysqli_stmt_close($stmt_point);

if (($bd_buy != "YES" && $row_point['mb_point'] < $bd_price + $bd_support) || ($bd_buy == "YES" && $row_point['mb_point'] < $bd_support))
    alert(national_language($iw['language'],"a0162","포인트 잔액이 부족합니다."),"{$iw['m_path']}/all_point_charge.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");

$bb_datetime = date("Y-m-d H:i:s");

if($bd_buy != "YES" && $bd_price > 0){
	$sql_ent = "select ep_point_seller, ep_point_site, ep_point_super, mb_code from {$iw['enterprise_table']} where ep_code = ?";
    $stmt_ent = mysqli_prepare($db_conn, $sql_ent); mysqli_stmt_bind_param($stmt_ent, "s", $iw['store']); mysqli_stmt_execute($stmt_ent);
    $row_ent = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_ent)); mysqli_stmt_close($stmt_ent);
	$db_price_seller = $file['bd_price'] * $row_ent['ep_point_seller']/100;
	$db_price_site = $file['bd_price'] * $row_ent['ep_point_site']/100;
	$db_price_super = $file['bd_price'] * $row_ent['ep_point_super']/100;
	$site_admin = $row_ent['mb_code'];

	//회원 포인트 차감
	$sql_up_mem = "update {$iw['member_table']} set mb_point = mb_point - ? where ep_code = ? and mb_code = ?";
    $stmt_up_mem = mysqli_prepare($db_conn, $sql_up_mem); mysqli_stmt_bind_param($stmt_up_mem, "iss", $file['bd_price'], $iw['store'], $iw['member']); mysqli_stmt_execute($stmt_up_mem); mysqli_stmt_close($stmt_up_mem);
		
	//포인트 로그 기록
	$pt_content = "[이북]".$file['bd_subject'];
	$sql_ins_pt = "insert into {$iw['point_table']} set ep_code = ?, mb_code = ?, state_sort = ?, pt_withdraw = ?, pt_balance = ? - ?, pt_content = ?, pt_datetime = ?";
    $stmt_ins_pt = mysqli_prepare($db_conn, $sql_ins_pt); mysqli_stmt_bind_param($stmt_ins_pt, "sssiisss", $iw['store'], $iw['member'], $iw['type'], $file['bd_price'], $row_point['mb_point'], $file['bd_price'], $pt_content, $bb_datetime); mysqli_stmt_execute($stmt_ins_pt); mysqli_stmt_close($stmt_ins_pt);

	//판매량 업데이트
	$sql_up_book = "update {$iw['book_data_table']} set bd_sell = bd_sell + 1 where ep_code = ? and bd_code = ?";
    $stmt_up_book = mysqli_prepare($db_conn, $sql_up_book); mysqli_stmt_bind_param($stmt_up_book, "ss", $iw['store'], $bd_code); mysqli_stmt_execute($stmt_up_book); mysqli_stmt_close($stmt_up_book);

	//구매 내역 기록
	$bb_divice = explode("(", $_SERVER['HTTP_USER_AGENT']); $bb_divice = explode(")", $bb_divice[1]); $bb_divice = $bb_divice[0];
	$sql_ins_buy = "insert into {$iw['book_buy_table']} set bd_code = ?, ep_code = ?, gp_code = ?, mb_code = ?, seller_mb_code = ?, bb_subject = ?, bb_price = ?, bb_ip = ?, bb_divice = ?, bb_datetime = ?, bb_price_seller = ?, bb_price_site = ?, bb_price_super = ?, bb_display = 1";
    $stmt_ins_buy = mysqli_prepare($db_conn, $sql_ins_buy); mysqli_stmt_bind_param($stmt_ins_buy, "ssssssisssddd", $file['bd_code'], $iw['store'], $iw['group'], $iw['member'], $file['mb_code'], $file['bd_subject'], $file['bd_price'], $_SERVER['REMOTE_ADDR'], $bb_divice, $bb_datetime, $db_price_seller, $db_price_site, $db_price_super); mysqli_stmt_execute($stmt_ins_buy); mysqli_stmt_close($stmt_ins_buy);
}

//후원 시작
if ($bd_support > 0 && $bd_support){
	$sql_up_mem = "update {$iw['member_table']} set mb_point = mb_point - ? where ep_code = ? and mb_code = ?";
    $stmt_up_mem = mysqli_prepare($db_conn, $sql_up_mem); mysqli_stmt_bind_param($stmt_up_mem, "iss", $bd_support, $iw['store'], $iw['member']); mysqli_stmt_execute($stmt_up_mem); mysqli_stmt_close($stmt_up_mem);

	$pt_content = "[후원]".$file['bd_subject'];
	$sql_ins_pt = "insert into {$iw['point_table']} set ep_code = ?, mb_code = ?, state_sort = ?, pt_withdraw = ?, pt_balance = ? - ?, pt_content = ?, pt_datetime = ?";
    $stmt_ins_pt = mysqli_prepare($db_conn, $sql_ins_pt); mysqli_stmt_bind_param($stmt_ins_pt, "sssiisss", $iw['store'], $iw['member'], $iw['type'], $bd_support, $row_point['mb_point'], $bd_support, $pt_content, $bb_datetime); mysqli_stmt_execute($stmt_ins_pt); mysqli_stmt_close($stmt_ins_pt);

	//받는 사람
	$sql_sel_mb = "select mb_nick from {$iw['member_table']} where mb_code = ? and ep_code = ?";
    $stmt_sel_mb = mysqli_prepare($db_conn, $sql_sel_mb); mysqli_stmt_bind_param($stmt_sel_mb, "ss", $iw['member'], $iw['store']); mysqli_stmt_execute($stmt_sel_mb);
    $row_sel_mb = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_sel_mb)); mysqli_stmt_close($stmt_sel_mb);
	$pt_content = $row_sel_mb['mb_nick']." 님의 이북 후원";

	$sql_up_mem = "update {$iw['member_table']} set mb_point = mb_point + ? where ep_code = ? and mb_code = ?";
    $stmt_up_mem = mysqli_prepare($db_conn, $sql_up_mem); mysqli_stmt_bind_param($stmt_up_mem, "iss", $bd_support, $iw['store'], $file['mb_code']); mysqli_stmt_execute($stmt_up_mem); mysqli_stmt_close($stmt_up_mem);

	$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$file[mb_code]'");
	$sql_ins_pt = "insert into {$iw['point_table']} set ep_code = ?, mb_code = ?, state_sort = ?, pt_deposit = ?, pt_balance = ? + ?, pt_content = ?, pt_datetime = ?";
    $stmt_ins_pt = mysqli_prepare($db_conn, $sql_ins_pt); mysqli_stmt_bind_param($stmt_ins_pt, "sssiisss", $iw['store'], $file['mb_code'], $iw['type'], $bd_support, $row['mb_point'], $bd_support, $pt_content, $bb_datetime); mysqli_stmt_execute($stmt_ins_pt); mysqli_stmt_close($stmt_ins_pt);

	$db_divice = explode("(", $_SERVER['HTTP_USER_AGENT']);
	$db_divice = explode(")", $db_divice[1]);
	$db_divice = $db_divice[0];
	$sql_ins_bs = "insert into {$iw['book_support_table']} set bd_code = ?, ep_code = ?, gp_code = ?, mb_code = ?, seller_mb_code = ?, bs_subject = ?, bs_price = ?, bs_ip = ?, bs_divice = ?, bs_datetime = ?, bs_display = 1";
    $stmt_ins_bs = mysqli_prepare($db_conn, $sql_ins_bs); mysqli_stmt_bind_param($stmt_ins_bs, "ssssssisss", $file['bd_code'], $iw['store'], $iw['group'], $iw['member'], $file['mb_code'], $file['bd_subject'], $bd_support, $_SERVER['REMOTE_ADDR'], $db_divice, $bb_datetime, 1); mysqli_stmt_execute($stmt_ins_bs); mysqli_stmt_close($stmt_ins_bs);
}
//후원 종료

goto_url("{$iw['m_path']}/book_buy_list.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />



