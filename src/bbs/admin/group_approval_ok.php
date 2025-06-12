<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$gp_no = $_GET[idx];
$gp_display = $_GET[dis];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]' ");
if (!$row["ep_nick"]) alert("잘못된 접근입니다!","");
$ep_nick = $row["ep_nick"];

$row = sql_fetch("select * from $iw[group_table] where ep_code = '$iw[store]' and gp_display = 0 and gp_no = '$gp_no'");
if (!$row["gp_no"]) alert("잘못된 접근입니다!","");
$gp_code = $row["gp_code"];
$mb_code = $row["mb_code"];
$gp_nick = $row["gp_nick"];
$gp_subject = $row["gp_subject"];

$row = sql_fetch("select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'");
if (!$row["mb_no"]) alert("잘못된 접근입니다!","");

$mb_mail = $row["mb_mail"];
$mb_name = $row["mb_name"];
$mb_nick = $row["mb_nick"];
$mb_tel = $row["mb_tel"];
$mb_zip_code = $row["mb_zip_code"];
$mb_address = $row["mb_address"];
$mb_address_sub = $row["mb_address_sub"];

// 디렉토리 생성
$dir_arr = array ("$iw[path]/about/$ep_nick/$gp_nick",
				  "$iw[path]/mcb/$ep_nick/$gp_nick",
				  "$iw[path]/shop/$ep_nick/$gp_nick",
				  "$iw[path]/doc/$ep_nick/$gp_nick",
				  "$iw[path]/book/$ep_nick/$gp_nick",
				  "$iw[path]/main/$ep_nick/$gp_nick",
				  "$iw[path]/main/$ep_nick/$gp_nick/_board",
				  "$iw[path]/main/$ep_nick/$gp_nick/_images");
for ($i=0; $i<count($dir_arr); $i++) 
{
	@mkdir($dir_arr[$i], 0707);
	@chmod($dir_arr[$i], 0707);
}

$file = "$iw[path]/about/$ep_nick/$gp_nick/index.html";
$f = @fopen($file, "w");
fwrite($f, "<script>\n");
fwrite($f, "location.href='../$iw[bbs_path]/m/about_main.php?type=about&ep=$iw[store]&gp=$gp_code';\n");
fwrite($f, "</script>");
fclose($f);
@chmod($file, 0606);

$file = "$iw[path]/mcb/$ep_nick/$gp_nick/index.html";
$f = @fopen($file, "w");
fwrite($f, "<script>\n");
fwrite($f, "location.href='../$iw[bbs_path]/m/mcb_main.php?type=mcb&ep=$iw[store]&gp=$gp_code';\n");
fwrite($f, "</script>");
fclose($f);
@chmod($file, 0606);

$file = "$iw[path]/shop/$ep_nick/$gp_nick/index.html";
$f = @fopen($file, "w");
fwrite($f, "<script>\n");
fwrite($f, "location.href='../$iw[bbs_path]/m/shop_main.php?type=shop&ep=$iw[store]&gp=$gp_code';\n");
fwrite($f, "</script>");
fclose($f);
@chmod($file, 0606);

$file = "$iw[path]/doc/$ep_nick/$gp_nick/index.html";
$f = @fopen($file, "w");
fwrite($f, "<script>\n");
fwrite($f, "location.href='../$iw[bbs_path]/m/doc_main.php?type=doc&ep=$iw[store]&gp=$gp_code';\n");
fwrite($f, "</script>");
fclose($f);
@chmod($file, 0606);

$file = "$iw[path]/book/$ep_nick/$gp_nick/index.html";
$f = @fopen($file, "w");
fwrite($f, "<script>\n");
fwrite($f, "location.href='../$iw[bbs_path]/m/book_main.php?type=book&ep=$iw[store]&gp=$gp_code';\n");
fwrite($f, "</script>");
fclose($f);
@chmod($file, 0606);

$file = "$iw[path]/main/$ep_nick/$gp_nick/index.html";
$f = @fopen($file, "w");
fwrite($f, "<script>\n");
fwrite($f, "location.href='../$iw[bbs_path]/m/main.php?type=main&ep=$iw[store]&gp=$gp_code';\n");
fwrite($f, "</script>");
fclose($f);
@chmod($file, 0606);

$sql = "update $iw[group_table] set
		gp_display = '$gp_display'
		where gp_no = '$gp_no' and ep_code = '$iw[store]'
		";

sql_query($sql);

$gm_datetime = date("Y-m-d H:i:s");

$sql = "insert into $iw[group_member_table] set
		ep_code = '$iw[store]',
		gp_code = '$gp_code',
		mb_code = '$mb_code',
		gm_datetime = '$gm_datetime',
		gm_display = 9
		";

sql_query($sql);

$state_sort = "main";
$st_mcb_name = "NEWS";
$st_publishing_name = "PUBLISHING";
$st_doc_name = "CONTENTS";
$st_shop_name = "SHOPPING";
$st_book_name = "E-BOOK";

$sql = "insert into $iw[setting_table] set
		ep_code = '$iw[store]',
		gp_code = '$gp_code',
		hm_code = '$state_sort',
		st_title = '$gp_subject',
		st_mcb_name = '$st_mcb_name',
		st_publishing_name = '$st_publishing_name',
		st_doc_name = '$st_doc_name',
		st_shop_name = '$st_shop_name',
		st_book_name = '$st_book_name',
		st_datetime = '$gm_datetime',
		st_top_align = 'center',
		st_menu_position = '0',
		st_navigation = '1',
		st_slide_size = '0',
		st_sns_share = '0',
		st_display = 1
		";
sql_query($sql);

for ($i=0; $i<10; $i++) {
	$sql = "insert into $iw[group_level_table] set
			ep_code = '$iw[store]',
			gp_code = '$gp_code',
			gl_name = '$i',
			gl_level = '$i',
			gl_display = 1
			";
	sql_query($sql);
}

$sql = "insert into $iw[category_table] set
		cg_name = '기본',
		cg_code = '$gp_code',
		ep_code = '$iw[store]',
		gp_code = '$gp_code',
		state_sort = 'main',
		cg_level_write = '1',
		cg_level_comment = '1',
		cg_level_read = '1',
		cg_level_upload = '1',
		cg_level_download = '1',
		cg_date = '1',
		cg_writer = '1',
		cg_hit = '1',
		cg_comment = '1',
		cg_recommend = '1',
		cg_point_btn = '1',
		cg_comment_view = '1',
		cg_recommend_view = '1',
		cg_url = '1',
		cg_facebook = '1',
		cg_twitter = '1',
		cg_googleplus = '1',
		cg_pinterest = '1',
		cg_linkedin = '1',
		cg_delicious = '1',
		cg_tumblr = '1',
		cg_digg = '1',
		cg_stumbleupon = '1',
		cg_reddit = '1',
		cg_sinaweibo = '1',
		cg_qzone = '1',
		cg_renren = '1',
		cg_tencentweibo = '1',
		cg_kakaotalk = '1',
		cg_line = '1',
		cg_display = '1'
		";
sql_query($sql);

$sqls = "select * from $iw[theme_data_table] where td_code = 'theme1'";
$rows = sql_fetch($sqls);
$sql = "insert into $iw[theme_setting_table] set
		ep_code = '$iw[store]',
		gp_code = '$gp_code',
		ts_body_back_color = '$row[td_body_back_color]',
		ts_menu_border_radius = '$row[td_menu_border_radius]',
		ts_menu_border_width = '$row[td_menu_border_width]',
		ts_menu_back_opacity = '$row[td_menu_back_opacity]',
		ts_menu_back_color = '$row[td_menu_back_color]',
		ts_menu_font_color = '$row[td_menu_font_color]',
		ts_menu_border_color = '$row[td_menu_border_color]',
		ts_menu_back_opacity_over = '$row[td_menu_back_opacity_over]',
		ts_menu_back_color_over = '$row[td_menu_back_color_over]',
		ts_menu_font_color_over = '$row[td_menu_font_color_over]',
		ts_menu_border_color_over = '$row[td_menu_border_color_over]',
		ts_navi_border_radius = '$row[td_navi_border_radius]',
		ts_navi_border_width = '$row[td_navi_border_width]',
		ts_navi_back_opacity = '$row[td_navi_back_opacity]',
		ts_navi_back_color = '$row[td_navi_back_color]',
		ts_navi_font_color = '$row[td_navi_font_color]',
		ts_navi_border_color = '$row[td_navi_border_color]',
		ts_box_border_radius = '$row[td_box_border_radius]',
		ts_box_border_width = '$row[td_box_border_width]',
		ts_box_back_opacity = '$row[td_box_back_opacity]',
		ts_box_back_color = '$row[td_box_back_color]',
		ts_box_font_color = '$row[td_box_font_color]',
		ts_box_border_color = '$row[td_box_border_color]',
		ts_footer_border_radius = '$row[td_footer_border_radius]',
		ts_footer_border_width = '$row[td_footer_border_width]',
		ts_footer_back_opacity = '$row[td_footer_back_opacity]',
		ts_footer_back_color = '$row[td_footer_back_color]',
		ts_footer_font_color = '$row[td_footer_font_color]',
		ts_footer_border_color = '$row[td_footer_border_color]',
		ts_button_border_radius = '$row[td_button_border_radius]',
		ts_button_border_width = '$row[td_button_border_width]',
		ts_button_back_opacity = '$row[td_button_back_opacity]',
		ts_button_back_color = '$row[td_button_back_color]',
		ts_button_font_color = '$row[td_button_font_color]',
		ts_button_border_color = '$row[td_button_border_color]',
		ts_button_back_opacity_over = '$row[td_button_back_opacity_over]',
		ts_button_back_color_over = '$row[td_button_back_color_over]',
		ts_button_font_color_over = '$row[td_button_font_color_over]',
		ts_button_border_color_over = '$row[td_button_border_color_over]',
		ts_page_border_radius = '$row[td_page_border_radius]',
		ts_page_border_width = '$row[td_page_border_width]',
		ts_page_back_opacity = '$row[td_page_back_opacity]',
		ts_page_back_color = '$row[td_page_back_color]',
		ts_page_font_color = '$row[td_page_font_color]',
		ts_page_border_color = '$row[td_page_border_color]',
		ts_page_back_opacity_over = '$row[td_page_back_opacity_over]',
		ts_page_back_color_over = '$row[td_page_back_color_over]',
		ts_page_font_color_over = '$row[td_page_font_color_over]',
		ts_page_border_color_over = '$row[td_page_border_color_over]',
		ts_box_padding = '$row[td_box_padding]'
		";
sql_query($sql);

alert("신규그룹이 승인되었습니다.","$iw[admin_path]/group_approval_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

?>