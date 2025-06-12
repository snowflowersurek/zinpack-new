<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$ts_no = trim(mysql_real_escape_string($_POST[ts_no]));
$ts_body_back_color = trim(mysql_real_escape_string($_POST[ts_body_back_color]));
$ts_menu_border_radius = trim(mysql_real_escape_string($_POST[ts_menu_border_radius]));
$ts_menu_border_width = trim(mysql_real_escape_string($_POST[ts_menu_border_width]));
$ts_menu_back_opacity = trim(mysql_real_escape_string($_POST[ts_menu_back_opacity]));
$ts_menu_back_color = trim(mysql_real_escape_string($_POST[ts_menu_back_color]));
$ts_menu_font_color = trim(mysql_real_escape_string($_POST[ts_menu_font_color]));
$ts_menu_border_color = trim(mysql_real_escape_string($_POST[ts_menu_border_color]));
$ts_menu_back_opacity_over = trim(mysql_real_escape_string($_POST[ts_menu_back_opacity_over]));
$ts_menu_back_color_over = trim(mysql_real_escape_string($_POST[ts_menu_back_color_over]));
$ts_menu_font_color_over = trim(mysql_real_escape_string($_POST[ts_menu_font_color_over]));
$ts_menu_border_color_over = trim(mysql_real_escape_string($_POST[ts_menu_border_color_over]));
$ts_navi_border_radius = trim(mysql_real_escape_string($_POST[ts_navi_border_radius]));
$ts_navi_border_width = trim(mysql_real_escape_string($_POST[ts_navi_border_width]));
$ts_navi_back_opacity = trim(mysql_real_escape_string($_POST[ts_navi_back_opacity]));
$ts_navi_back_color = trim(mysql_real_escape_string($_POST[ts_navi_back_color]));
$ts_navi_font_color = trim(mysql_real_escape_string($_POST[ts_navi_font_color]));
$ts_navi_border_color = trim(mysql_real_escape_string($_POST[ts_navi_border_color]));
$ts_box_border_radius = trim(mysql_real_escape_string($_POST[ts_box_border_radius]));
$ts_box_border_width = trim(mysql_real_escape_string($_POST[ts_box_border_width]));
$ts_box_back_opacity = trim(mysql_real_escape_string($_POST[ts_box_back_opacity]));
$ts_box_back_color = trim(mysql_real_escape_string($_POST[ts_box_back_color]));
$ts_box_font_color = trim(mysql_real_escape_string($_POST[ts_box_font_color]));
$ts_box_border_color = trim(mysql_real_escape_string($_POST[ts_box_border_color]));
$ts_footer_border_radius = trim(mysql_real_escape_string($_POST[ts_footer_border_radius]));
$ts_footer_border_width = trim(mysql_real_escape_string($_POST[ts_footer_border_width]));
$ts_footer_back_opacity = trim(mysql_real_escape_string($_POST[ts_footer_back_opacity]));
$ts_footer_back_color = trim(mysql_real_escape_string($_POST[ts_footer_back_color]));
$ts_footer_font_color = trim(mysql_real_escape_string($_POST[ts_footer_font_color]));
$ts_footer_border_color = trim(mysql_real_escape_string($_POST[ts_footer_border_color]));
$ts_button_border_radius = trim(mysql_real_escape_string($_POST[ts_button_border_radius]));
$ts_button_border_width = trim(mysql_real_escape_string($_POST[ts_button_border_width]));
$ts_button_back_opacity = trim(mysql_real_escape_string($_POST[ts_button_back_opacity]));
$ts_button_back_color = trim(mysql_real_escape_string($_POST[ts_button_back_color]));
$ts_button_font_color = trim(mysql_real_escape_string($_POST[ts_button_font_color]));
$ts_button_border_color = trim(mysql_real_escape_string($_POST[ts_button_border_color]));
$ts_button_back_opacity_over = trim(mysql_real_escape_string($_POST[ts_button_back_opacity_over]));
$ts_button_back_color_over = trim(mysql_real_escape_string($_POST[ts_button_back_color_over]));
$ts_button_font_color_over = trim(mysql_real_escape_string($_POST[ts_button_font_color_over]));
$ts_button_border_color_over = trim(mysql_real_escape_string($_POST[ts_button_border_color_over]));
$ts_page_border_radius = trim(mysql_real_escape_string($_POST[ts_page_border_radius]));
$ts_page_border_width = trim(mysql_real_escape_string($_POST[ts_page_border_width]));
$ts_page_back_opacity = trim(mysql_real_escape_string($_POST[ts_page_back_opacity]));
$ts_page_back_color = trim(mysql_real_escape_string($_POST[ts_page_back_color]));
$ts_page_font_color = trim(mysql_real_escape_string($_POST[ts_page_font_color]));
$ts_page_border_color = trim(mysql_real_escape_string($_POST[ts_page_border_color]));
$ts_page_back_opacity_over = trim(mysql_real_escape_string($_POST[ts_page_back_opacity_over]));
$ts_page_back_color_over = trim(mysql_real_escape_string($_POST[ts_page_back_color_over]));
$ts_page_font_color_over = trim(mysql_real_escape_string($_POST[ts_page_font_color_over]));
$ts_page_border_color_over = trim(mysql_real_escape_string($_POST[ts_page_border_color_over]));
$ts_box_padding = trim(mysql_real_escape_string($_POST[ts_box_padding]));

$sql = "update $iw[theme_setting_table] set
		ts_body_back_color = '$ts_body_back_color',
		ts_menu_border_radius = '$ts_menu_border_radius',
		ts_menu_border_width = '$ts_menu_border_width',
		ts_menu_back_opacity = '$ts_menu_back_opacity',
		ts_menu_back_color = '$ts_menu_back_color',
		ts_menu_font_color = '$ts_menu_font_color',
		ts_menu_border_color = '$ts_menu_border_color',
		ts_menu_back_opacity_over = '$ts_menu_back_opacity_over',
		ts_menu_back_color_over = '$ts_menu_back_color_over',
		ts_menu_font_color_over = '$ts_menu_font_color_over',
		ts_menu_border_color_over = '$ts_menu_border_color_over',
		ts_navi_border_radius = '$ts_navi_border_radius',
		ts_navi_border_width = '$ts_navi_border_width',
		ts_navi_back_opacity = '$ts_navi_back_opacity',
		ts_navi_back_color = '$ts_navi_back_color',
		ts_navi_font_color = '$ts_navi_font_color',
		ts_navi_border_color = '$ts_navi_border_color',
		ts_box_border_radius = '$ts_box_border_radius',
		ts_box_border_width = '$ts_box_border_width',
		ts_box_back_opacity = '$ts_box_back_opacity',
		ts_box_back_color = '$ts_box_back_color',
		ts_box_font_color = '$ts_box_font_color',
		ts_box_border_color = '$ts_box_border_color',
		ts_footer_border_radius = '$ts_footer_border_radius',
		ts_footer_border_width = '$ts_footer_border_width',
		ts_footer_back_opacity = '$ts_footer_back_opacity',
		ts_footer_back_color = '$ts_footer_back_color',
		ts_footer_font_color = '$ts_footer_font_color',
		ts_footer_border_color = '$ts_footer_border_color',
		ts_button_border_radius = '$ts_button_border_radius',
		ts_button_border_width = '$ts_button_border_width',
		ts_button_back_opacity = '$ts_button_back_opacity',
		ts_button_back_color = '$ts_button_back_color',
		ts_button_font_color = '$ts_button_font_color',
		ts_button_border_color = '$ts_button_border_color',
		ts_button_back_opacity_over = '$ts_button_back_opacity_over',
		ts_button_back_color_over = '$ts_button_back_color_over',
		ts_button_font_color_over = '$ts_button_font_color_over',
		ts_button_border_color_over = '$ts_button_border_color_over',
		ts_page_border_radius = '$ts_page_border_radius',
		ts_page_border_width = '$ts_page_border_width',
		ts_page_back_opacity = '$ts_page_back_opacity',
		ts_page_back_color = '$ts_page_back_color',
		ts_page_font_color = '$ts_page_font_color',
		ts_page_border_color = '$ts_page_border_color',
		ts_page_back_opacity_over = '$ts_page_back_opacity_over',
		ts_page_back_color_over = '$ts_page_back_color_over',
		ts_page_font_color_over = '$ts_page_font_color_over',
		ts_page_border_color_over = '$ts_page_border_color_over',
		ts_box_padding = '$ts_box_padding'
		where ts_no = '$ts_no' and ep_code = '$iw[store]' and gp_code = '$iw[group]'
		";

sql_query($sql);

alert("테마디자인 설정이 수정되었습니다.","$iw[admin_path]/design_theme_edit.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>