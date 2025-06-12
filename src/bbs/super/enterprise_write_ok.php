<?php
include_once("_common.php");
if ($iw['level'] != "super") alert("잘못된 접근입니다!","");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$mb_email = trim($_POST['mb_email']);
$mb_password = trim($_POST['mb_password']);
$mb_name = trim($_POST['mb_name']);
$mb_nick = trim($_POST['mb_nick']);
$mb_tel_1 = trim($_POST['mb_tel_1']);
$mb_tel_2 = trim($_POST['mb_tel_2']);
$mb_tel_3 = trim($_POST['mb_tel_3']);
$mb_zip_code = trim($_POST['mb_zip_code']);
$mb_address = trim($_POST['mb_address']);
$mb_address_sub = trim($_POST['mb_address_sub']);
$ep_nick = trim($_POST['ep_nick']);
$ep_corporate = trim($_POST['ep_corporate']);
$ep_permit_number = trim($_POST['ep_permit_number']);
$ep_state_mcb = trim($_POST['ep_state_mcb']);
$ep_state_publishing = trim($_POST['ep_state_publishing']);
$ep_state_doc = trim($_POST['ep_state_doc']);
$ep_state_shop = trim($_POST['ep_state_shop']);
$ep_state_book = trim($_POST['ep_state_book']);
$ep_exposed = trim($_POST['ep_exposed']);
$ep_autocode = trim($_POST['ep_autocode']);
$ep_jointype = trim($_POST['ep_jointype']);
$ep_language = trim($_POST['ep_language']);
$ep_anonymity = trim($_POST['ep_anonymity']);
$ep_domain = trim($_POST['ep_domain']);
$ep_upload = trim($_POST['ep_upload']);
$ep_upload_size = trim($_POST['ep_upload_size']);
$ep_point_seller = trim($_POST['ep_point_seller']);
$ep_point_site = trim($_POST['ep_point_site']);
$ep_point_super = trim($_POST['ep_point_super']);
$ep_copy_off = trim($_POST['ep_copy_off']);
$ep_expiry_date = trim($_POST['ep_expiry_date']);
$ep_charge = str_replace(",", "", trim($_POST['ep_charge']));

$sql = "SELECT count(*) as cnt FROM {$iw['enterprise_table']} WHERE ep_nick = ?";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $ep_nick);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$rowdomain = null;
if ($ep_domain) {
    $sql = "SELECT count(*) as cnt FROM {$iw['enterprise_table']} WHERE ep_domain = ?";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $ep_domain);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowdomain = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

if ($row['cnt']) {
	alert("포워딩주소가 이미 존재합니다.","");
}else if ($ep_domain && $rowdomain['cnt']) {
	alert("도메인이 이미 존재합니다.","");
}else{
	$ep_code = "ep".uniqid(rand());
	$mb_code = "mb".uniqid(rand());
	$gp_code = "all";

	$ep_datetime = date("Y-m-d H:i:s");
	$mb_datetime = date("Y-m-d H:i:s");

	$mb_tel = $mb_tel_1."-".$mb_tel_2."-".$mb_tel_3;

	// 디렉토리 생성
	$dir_arr = array ("$iw[path]/about/$ep_nick",
					  "$iw[path]/mcb/$ep_nick",
					  "$iw[path]/publishing/$ep_nick",
					  "$iw[path]/shop/$ep_nick",
					  "$iw[path]/doc/$ep_nick",
					  "$iw[path]/book/$ep_nick",
					  "$iw[path]/main/$ep_nick",
					  "$iw[path]/about/$ep_nick/all",
					  "$iw[path]/mcb/$ep_nick/all",
					  "$iw[path]/publishing/$ep_nick/author",
					  "$iw[path]/publishing/$ep_nick/book",
					  "$iw[path]/publishing/$ep_nick/editor",
					  "$iw[path]/shop/$ep_nick/all",
					  "$iw[path]/doc/$ep_nick/all",
					  "$iw[path]/book/$ep_nick/all",
					  "$iw[path]/main/$ep_nick/all",
					  "$iw[path]/main/$ep_nick/all/_board",
					  "$iw[path]/main/$ep_nick/all/_images");
	for ($i=0; $i<count($dir_arr); $i++) 
	{
		@mkdir($dir_arr[$i], 0707);
		@chmod($dir_arr[$i], 0707);
	}
	
	$file = "$iw[path]/about/$ep_nick/index.html";
	$f = @fopen($file, "w");
	fwrite($f, "<script>\n");
	fwrite($f, "location.href='$iw[bbs_path]/m/about_main.php?type=about&ep=$ep_code&gp=$gp_code';\n");
	fwrite($f, "</script>");
	fclose($f);
	@chmod($file, 0606);

	$file = "$iw[path]/mcb/$ep_nick/index.html";
	$f = @fopen($file, "w");
	fwrite($f, "<script>\n");
	fwrite($f, "location.href='$iw[bbs_path]/m/mcb_main.php?type=mcb&ep=$ep_code&gp=$gp_code';\n");
	fwrite($f, "</script>");
	fclose($f);
	@chmod($file, 0606);

	$file = "$iw[path]/publishing/$ep_nick/index.html";
	$f = @fopen($file, "w");
	fwrite($f, "<script>\n");
	fwrite($f, "location.href='$iw[bbs_path]/m/main.php?type=main&ep=$ep_code&gp=$gp_code';\n");
	fwrite($f, "</script>");
	fclose($f);
	@chmod($file, 0606);

	$file = "$iw[path]/shop/$ep_nick/index.html";
	$f = @fopen($file, "w");
	fwrite($f, "<script>\n");
	fwrite($f, "location.href='$iw[bbs_path]/m/shop_main.php?type=shop&ep=$ep_code&gp=$gp_code';\n");
	fwrite($f, "</script>");
	fclose($f);
	@chmod($file, 0606);

	$file = "$iw[path]/doc/$ep_nick/index.html";
	$f = @fopen($file, "w");
	fwrite($f, "<script>\n");
	fwrite($f, "location.href='$iw[bbs_path]/m/doc_main.php?type=doc&ep=$ep_code&gp=$gp_code';\n");
	fwrite($f, "</script>");
	fclose($f);
	@chmod($file, 0606);

	$file = "$iw[path]/book/$ep_nick/index.html";
	$f = @fopen($file, "w");
	fwrite($f, "<script>\n");
	fwrite($f, "location.href='$iw[bbs_path]/m/book_main.php?type=book&ep=$ep_code&gp=$gp_code';\n");
	fwrite($f, "</script>");
	fclose($f);
	@chmod($file, 0606);

	$file = "$iw[path]/main/$ep_nick/index.html";
	$f = @fopen($file, "w");
	fwrite($f, "<script>\n");
	fwrite($f, "location.href='$iw[bbs_path]/m/main.php?type=main&ep=$ep_code&gp=$gp_code';\n");
	fwrite($f, "</script>");
	fclose($f);
	@chmod($file, 0606);

	$sql2 = "select * from {$iw['master_table']} where ma_no = 1";
	$result2 = mysqli_query($db_conn, $sql2);
	$row2 = mysqli_fetch_assoc($result2);
	$ma_policy_agreement = $row2["ma_policy_agreement"];
	$ma_policy_private = $row2["ma_policy_private"];
	$ma_policy_email = $row2["ma_policy_email"];

	$sql = "insert into {$iw['enterprise_table']} set
			ep_code = ?, ep_mb_code = ?, ep_nick = ?, ep_corporate = ?, ep_permit_number = ?,
			ep_state_mcb = ?, ep_state_publishing = ?, ep_state_doc = ?, ep_state_shop = ?, ep_state_book = ?,
			ep_language = ?, ep_exposed = ?, ep_autocode = ?, ep_jointype = ?, ep_anonymity = ?,
			ep_domain = ?, ep_upload = ?, ep_upload_size = ?, ep_policy_agreement = ?, ep_policy_private = ?,
			ep_policy_email = ?, ep_datetime = ?, ep_point_seller = ?, ep_point_site = ?, ep_point_super = ?,
			ep_copy_off = ?, ep_terms_display = '1', ep_display = 1, ep_charge = ?, ep_expiry_date = ?";
	$stmt = mysqli_prepare($db_conn, $sql);
	mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssss", 
		$ep_code, $mb_code, $ep_nick, $ep_corporate, $ep_permit_number, $ep_state_mcb, $ep_state_publishing, 
		$ep_state_doc, $ep_state_shop, $ep_state_book, $ep_language, $ep_exposed, $ep_autocode, $ep_jointype, 
		$ep_anonymity, $ep_domain, $ep_upload, $ep_upload_size, $ma_policy_agreement, $ma_policy_private, 
		$ma_policy_email, $ep_datetime, $ep_point_seller, $ep_point_site, $ep_point_super, $ep_copy_off, 
		$ep_charge, $ep_expiry_date);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	$hashed_password = password_hash($mb_password, PASSWORD_BCRYPT);
	$sql = "insert into {$iw['member_table']} set
			mb_code = ?, ep_code = ?, mb_mail = ?, mb_password = ?, mb_name = ?,
			mb_nick = ?, mb_tel = ?, mb_zip_code = ?, mb_address = ?, mb_address_sub = ?,
			mb_datetime = ?, mb_display = 7";
	$stmt = mysqli_prepare($db_conn, $sql);
	mysqli_stmt_bind_param($stmt, "ssssssssssss", 
		$mb_code, $ep_code, $mb_mail, $hashed_password, $mb_name, $mb_nick, $mb_tel, 
		$mb_zip_code, $mb_address, $mb_address_sub, $mb_datetime);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
	$state_sort = "main";
	$st_mcb_name = "게시판";
	$st_publishing_name = "출판도서";
	$st_doc_name = "컨텐츠몰";
	$st_shop_name = "쇼핑몰";
	$st_book_name = "E-BOOK";
	
	$sql = "insert into {$iw['setting_table']} set
			ep_code = ?, gp_code = ?, hm_code = ?, st_title = ?, st_mcb_name = ?,
			st_publishing_name = ?, st_doc_name = ?, st_shop_name = ?, st_book_name = ?, st_datetime = ?,
			st_top_align = 'center', st_menu_position = '0', st_menu_mobile = '0', st_navigation = '1', st_slide_size = '0',
			st_sns_share = '0', st_display = 1";
	$stmt = mysqli_prepare($db_conn, $sql);
	mysqli_stmt_bind_param($stmt, "ssssssssss", 
		$ep_code, $gp_code, $state_sort, $ep_corporate, $st_mcb_name, $st_publishing_name, 
		$st_doc_name, $st_shop_name, $st_book_name, $mb_datetime);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	for ($i=0; $i<10; $i++) {
		$sql = "insert into {$iw['group_level_table']} set
				ep_code = ?, gp_code = ?, gl_name = ?, gl_level = ?, gl_display = 1";
		$stmt = mysqli_prepare($db_conn, $sql);
		mysqli_stmt_bind_param($stmt, "sssi", $ep_code, $gp_code, $i, $i);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}

	$sql = "insert into {$iw['category_table']} set
			cg_name = '기본', cg_code = ?, ep_code = ?, gp_code = ?, state_sort = 'main',
			cg_level_write = '1', cg_level_comment = '1', cg_level_read = '1', cg_level_upload = '1', cg_level_download = '1',
			cg_date = '1', cg_writer = '1', cg_hit = '1', cg_comment = '1', cg_recommend = '1',
			cg_point_btn = '1', cg_comment_view = '1', cg_recommend_view = '1', cg_url = '1', cg_facebook = '1',
			cg_twitter = '1', cg_googleplus = '1', cg_pinterest = '1', cg_linkedin = '1', cg_delicious = '1',
			cg_tumblr = '1', cg_digg = '1', cg_stumbleupon = '1', cg_reddit = '1', cg_sinaweibo = '1',
			cg_qzone = '1', cg_renren = '1', cg_tencentweibo = '1', cg_kakaotalk = '1', cg_line = '1',
			cg_display = '1'";
	$stmt = mysqli_prepare($db_conn, $sql);
	mysqli_stmt_bind_param($stmt, "sss", $ep_code, $ep_code, $gp_code);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	$sqls = "select * from {$iw['theme_data_table']} where td_code = 'theme1'";
	$results = mysqli_query($db_conn, $sqls);
	$rows = mysqli_fetch_assoc($results);
	$sql = "insert into {$iw['theme_setting_table']} set
			ep_code = ?, gp_code = ?, ts_body_back_color = ?, ts_menu_border_radius = ?, ts_menu_border_width = ?,
			ts_menu_back_opacity = ?, ts_menu_back_color = ?, ts_menu_font_color = ?, ts_menu_border_color = ?, ts_menu_back_opacity_over = ?,
			ts_menu_back_color_over = ?, ts_menu_font_color_over = ?, ts_menu_border_color_over = ?, ts_navi_border_radius = ?, ts_navi_border_width = ?,
			ts_navi_back_opacity = ?, ts_navi_back_color = ?, ts_navi_font_color = ?, ts_navi_border_color = ?, ts_box_border_radius = ?,
			ts_box_border_width = ?, ts_box_back_opacity = ?, ts_box_back_color = ?, ts_box_font_color = ?, ts_box_border_color = ?,
			ts_footer_border_radius = ?, ts_footer_border_width = ?, ts_footer_back_opacity = ?, ts_footer_back_color = ?, ts_footer_font_color = ?,
			ts_footer_border_color = ?, ts_button_border_radius = ?, ts_button_border_width = ?, ts_button_back_opacity = ?, ts_button_back_color = ?,
			ts_button_font_color = ?, ts_button_border_color = ?, ts_button_back_opacity_over = ?, ts_button_back_color_over = ?, ts_button_font_color_over = ?,
			ts_button_border_color_over = ?, ts_page_border_radius = ?, ts_page_border_width = ?, ts_page_back_opacity = ?, ts_page_back_color = ?,
			ts_page_font_color = ?, ts_page_border_color = ?, ts_page_back_opacity_over = ?, ts_page_back_color_over = ?, ts_page_font_color_over = ?,
			ts_page_border_color_over = ?, ts_box_padding = ?";
	
	$stmt = mysqli_prepare($db_conn, $sql);
	mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssssssssssssssssssssssssssss",
		$ep_code, $gp_code, $rows['td_body_back_color'], $rows['td_menu_border_radius'], $rows['td_menu_border_width'],
		$rows['td_menu_back_opacity'], $rows['td_menu_back_color'], $rows['td_menu_font_color'], $rows['td_menu_border_color'], $rows['td_menu_back_opacity_over'],
		$rows['td_menu_back_color_over'], $rows['td_menu_font_color_over'], $rows['td_menu_border_color_over'], $rows['td_navi_border_radius'], $rows['td_navi_border_width'],
		$rows['td_navi_back_opacity'], $rows['td_navi_back_color'], $rows['td_navi_font_color'], $rows['td_navi_border_color'], $rows['td_box_border_radius'],
		$rows['td_box_border_width'], $rows['td_box_back_opacity'], $rows['td_box_back_color'], $rows['td_box_font_color'], $rows['td_box_border_color'],
		$rows['td_footer_border_radius'], $rows['td_footer_border_width'], $rows['td_footer_back_opacity'], $rows['td_footer_back_color'], $rows['td_footer_font_color'],
		$rows['td_footer_border_color'], $rows['td_button_border_radius'], $rows['td_button_border_width'], $rows['td_button_back_opacity'], $rows['td_button_back_color'],
		$rows['td_button_font_color'], $rows['td_button_border_color'], $rows['td_button_back_opacity_over'], $rows['td_button_back_color_over'], $rows['td_button_font_color_over'],
		$rows['td_button_border_color_over'], $rows['td_page_border_radius'], $rows['td_page_border_width'], $rows['td_page_back_opacity'], $rows['td_page_back_color'],
		$rows['td_page_font_color'], $rows['td_page_border_color'], $rows['td_page_back_opacity_over'], $rows['td_page_back_color_over'], $rows['td_page_font_color_over'],
		$rows['td_page_border_color_over'], $rows['td_box_padding']
	);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	alert("신규 사이트가 등록되었습니다.","{$iw['super_path']}/enterprise_list.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
}
?>