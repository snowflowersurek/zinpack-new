<?php
include_once("_common.php");
if (($iw['gp_level'] == "gp_guest" && $iw['group'] != "all") || ($iw['level'] == "guest" && $iw['group'] == "all")) alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

$dd_code = $_GET["doc"];
$dd_support = (int)$_GET["support"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = $iw[path].'/'.$iw[type].'/'.$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$dd_code;

$sql_buy_check = "select count(*) as cnt from {$iw['doc_buy_table']} where ep_code = ? and gp_code= ? and mb_code = ? and dd_code = ? and (date(db_end_datetime) >= date(now()) or date(db_end_datetime) = date(db_datetime))";
$stmt_buy_check = mysqli_prepare($db_conn, $sql_buy_check);
mysqli_stmt_bind_param($stmt_buy_check, "ssss", $iw['store'], $iw['group'], $iw['member'], $dd_code);
mysqli_stmt_execute($stmt_buy_check);
$result_buy_check = mysqli_stmt_get_result($stmt_buy_check);
$row_buy_check = mysqli_fetch_assoc($result_buy_check);
mysqli_stmt_close($stmt_buy_check);
$dd_buy = ($row_buy_check['cnt']) ? "YES" : "NO";

$sql_file = "select * from {$iw['doc_data_table']} where ep_code = ? and gp_code= ? and dd_display = 1 and dd_code = ?";
$stmt_file = mysqli_prepare($db_conn, $sql_file);
mysqli_stmt_bind_param($stmt_file, "sss", $iw['store'], $iw['group'], $dd_code);
mysqli_stmt_execute($stmt_file);
$result_file = mysqli_stmt_get_result($stmt_file);
$file = mysqli_fetch_assoc($result_file);
mysqli_stmt_close($stmt_file);

if (!$file["dd_file"]) alert(national_language($iw['language'],"a0161","파일 정보가 존재하지 않습니다."));
$dd_price = $file['dd_price'];

$sql_point = "select mb_point from {$iw['member_table']} where ep_code = ? and mb_code = ?";
$stmt_point = mysqli_prepare($db_conn, $sql_point);
mysqli_stmt_bind_param($stmt_point, "ss", $iw['store'], $iw['member']);
mysqli_stmt_execute($stmt_point);
$result_point = mysqli_stmt_get_result($stmt_point);
$row_point = mysqli_fetch_assoc($result_point);
mysqli_stmt_close($stmt_point);

if (($dd_buy != "YES" && $row_point['mb_point'] < $dd_price + $dd_support) || ($dd_buy == "YES" && $row_point['mb_point'] < $dd_support))
    alert(national_language($iw['language'],"a0162","포인트 잔액이 부족합니다."),"{$iw['m_path']}/all_point_charge.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");

$filepath = "$upload_path/$file[dd_file]";
$filepath = addslashes($filepath);
if (!is_file($filepath) || !file_exists($filepath))
    alert(national_language($iw['language'],"a0163","파일이 존재하지 않습니다."));

$db_datetime = date("Y-m-d H:i:s");
if($file[dd_download]!=0){
	$dd_download= date("Y-m-d H:i:s", strtotime($db_datetime.' + '.$file[dd_download].'days')); 
}else{
	$dd_download= $db_datetime; 
}

if($dd_buy != "YES" && $dd_price > 0){
	$sql_ent_info = "select ep_point_seller, ep_point_site, ep_point_super, mb_code from {$iw['enterprise_table']} where ep_code = ?";
    $stmt_ent_info = mysqli_prepare($db_conn, $sql_ent_info);
    mysqli_stmt_bind_param($stmt_ent_info, "s", $iw['store']);
    mysqli_stmt_execute($stmt_ent_info);
    $result_ent_info = mysqli_stmt_get_result($stmt_ent_info);
	$row_ent = mysqli_fetch_assoc($result_ent_info);
    mysqli_stmt_close($stmt_ent_info);
	$db_price_seller = $file[dd_price] * $row_ent[ep_point_seller]/100;
	$db_price_site = $file[dd_price] * $row_ent[ep_point_site]/100;
	$db_price_super = $file[dd_price] * $row_ent[ep_point_super]/100;
	$site_admin = $row_ent[mb_code];

	//회원정보 포인트 차감
	$sql_up_mem = "update {$iw['member_table']} set mb_point = mb_point - ? where ep_code = ? and mb_code = ?";
    $stmt_up_mem = mysqli_prepare($db_conn, $sql_up_mem);
    mysqli_stmt_bind_param($stmt_up_mem, "iss", $file['dd_price'], $iw['store'], $iw['member']);
	mysqli_stmt_execute($stmt_up_mem);
    mysqli_stmt_close($stmt_up_mem);
		
	$pt_content = "[컨텐츠]".$file['dd_subject'];
	$sql_ins_pt = "insert into {$iw['point_table']} set ep_code = ?, mb_code = ?, state_sort = ?, pt_withdraw = ?, pt_balance = ? - ?, pt_content = ?, pt_datetime = ?";
    $stmt_ins_pt = mysqli_prepare($db_conn, $sql_ins_pt);
    mysqli_stmt_bind_param($stmt_ins_pt, "sssiisss", $iw['store'], $iw['member'], $iw['type'], $file['dd_price'], $row_point['mb_point'], $file['dd_price'], $pt_content, $db_datetime);
	mysqli_stmt_execute($stmt_ins_pt);
    mysqli_stmt_close($stmt_ins_pt);

	//판매량 업데이트
	$sql_up_doc = "update {$iw['doc_data_table']} set dd_sell = dd_sell+1 where ep_code = ? and dd_code = ?";
    $stmt_up_doc = mysqli_prepare($db_conn, $sql_up_doc);
    mysqli_stmt_bind_param($stmt_up_doc, "ss", $iw['store'], $dd_code);
	mysqli_stmt_execute($stmt_up_doc);
    mysqli_stmt_close($stmt_up_doc);

	// 구매내역 기록
	$sql_ins_buy = "insert into {$iw['doc_buy_table']} set dd_code = ?, ep_code = ?, gp_code = ?, mb_code = ?, seller_mb_code = ?, db_subject = ?, db_price = ?, db_ip = ?, db_divice = ?, db_datetime = ?, db_end_datetime = ?, db_price_seller = ?, db_price_site = ?, db_price_super = ?, db_display = 1";
    $stmt_ins_buy = mysqli_prepare($db_conn, $sql_ins_buy);
    mysqli_stmt_bind_param($stmt_ins_buy, "sssssssssssss", $file['dd_code'], $iw['store'], $iw['group'], $iw['member'], $file['mb_code'], $file['dd_subject'], $file['dd_price'], $_SERVER['REMOTE_ADDR'], $db_divice, $db_datetime, $dd_download, $db_price_seller, $db_price_site, $db_price_super, 1);
    mysqli_stmt_execute($stmt_ins_buy);
    mysqli_stmt_close($stmt_ins_buy);
}

//후원 시작
if ($dd_support > 0){
	// 후원자 포인트 차감
	$sql_sup_w = "update {$iw['member_table']} set mb_point = mb_point - ? where ep_code = ? and mb_code = ?";
    $stmt_sup_w = mysqli_prepare($db_conn, $sql_sup_w);
    mysqli_stmt_bind_param($stmt_sup_w, "iss", $dd_support, $iw['store'], $iw['member']);
	mysqli_stmt_execute($stmt_sup_w);
    mysqli_stmt_close($stmt_sup_w);

	// 후원자 포인트 로그
	$pt_content = "[후원]".$file['dd_subject'];
	$sql_sup_log_w = "insert into {$iw['point_table']} set ep_code = ?, mb_code = ?, state_sort = ?, pt_withdraw = ?, pt_balance = ? - ?, pt_content = ?, pt_datetime = ?";
    $stmt_sup_log_w = mysqli_prepare($db_conn, $sql_sup_log_w);
    mysqli_stmt_bind_param($stmt_sup_log_w, "sssiisss", $iw['store'], $iw['member'], $iw['type'], $dd_support, $row_point['mb_point'], $dd_support, $pt_content, $db_datetime);
	mysqli_stmt_execute($stmt_sup_log_w);
    mysqli_stmt_close($stmt_sup_log_w);

	// 판매자 포인트 지급
	$sql_sup_d = "update {$iw['member_table']} set mb_point = mb_point + ? where ep_code = ? and mb_code = ?";
    $stmt_sup_d = mysqli_prepare($db_conn, $sql_sup_d);
    mysqli_stmt_bind_param($stmt_sup_d, "iss", $dd_support, $file['mb_code'], $file['mb_code']);
	mysqli_stmt_execute($stmt_sup_d);
    mysqli_stmt_close($stmt_sup_d);

	// 판매자 포인트 로그
    $sql_seller_info = "select mb_nick, mb_point from {$iw['member_table']} where mb_code = ? and ep_code = ?";
    $stmt_seller_info = mysqli_prepare($db_conn, $sql_seller_info);
    mysqli_stmt_bind_param($stmt_seller_info, "ss", $file['mb_code'], $iw['store']);
    mysqli_stmt_execute($stmt_seller_info);
    $result_seller_info = mysqli_stmt_get_result($stmt_seller_info);
    $row_seller = mysqli_fetch_assoc($result_seller_info);
    mysqli_stmt_close($stmt_seller_info);
	$pt_content = $row_seller["mb_nick"]." 님의 컨텐츠 후원";
	$sql_sup_log_d = "insert into {$iw['point_table']} set ep_code = ?, mb_code = ?, state_sort = ?, pt_deposit = ?, pt_balance = ?, pt_content = ?, pt_datetime = ?";
    $stmt_sup_log_d = mysqli_prepare($db_conn, $sql_sup_log_d);
    mysqli_stmt_bind_param($stmt_sup_log_d, "sssiiss", $iw['store'], $file['mb_code'], $iw['type'], $dd_support, $row_seller['mb_point'], $pt_content, $db_datetime);
	mysqli_stmt_execute($stmt_sup_log_d);
    mysqli_stmt_close($stmt_sup_log_d);

	// 후원 내역 기록
	$sql_ins_sup = "insert into {$iw['doc_support_table']} set dd_code = ?, ep_code = ?, gp_code = ?, mb_code = ?, seller_mb_code = ?, ds_subject = ?, ds_price = ?, ds_ip = ?, ds_divice = ?, ds_datetime = ?, ds_display = 1";
    $stmt_ins_sup = mysqli_prepare($db_conn, $sql_ins_sup);
    mysqli_stmt_bind_param($stmt_ins_sup, "sssssssssss", $file['dd_code'], $iw['store'], $iw['group'], $iw['member'], $file['mb_code'], $file['dd_subject'], $dd_support, $_SERVER['REMOTE_ADDR'], $db_divice, $db_datetime, 1);
	mysqli_stmt_execute($stmt_ins_sup);
    mysqli_stmt_close($stmt_ins_sup);
}
//후원 종료

$dd_file = explode(".",$file["dd_file_name"]);

if(preg_match('/(iPhone|iPad|iPod)/i', $_SERVER['HTTP_USER_AGENT'])){
	if(strtoupper($dd_file[1])=="MP3")
		$original = urlencode($file[dd_file_name]);
	else
		$original = $file[dd_file_name];
}else{
	if (preg_match("/^utf/i", $iw['charset']))
		$original = urlencode($file[dd_file_name]);
	else
		$original = $file[dd_file_name];
}

if(preg_match("/msie/i", $_SERVER[HTTP_USER_AGENT]) && preg_match("/5\.5/", $_SERVER[HTTP_USER_AGENT])) {
    header("content-type: doesn/matter");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-transfer-encoding: binary");
} else {
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-description: php generated data");
}
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen("$filepath", "rb");

$download_rate = 10;

while(!feof($fp)) {
    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose ($fp);
flush();
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />