<?php

$sql = "CREATE TABLE $iw[theme_setting_table](
ts_no int(11) NOT NULL auto_increment,
ep_code varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
ts_body_back_color varchar(10) NOT NULL default '',
ts_menu_border_radius int(5) NOT NULL default '0',
ts_menu_border_width int(5) NOT NULL default '0',
ts_menu_back_opacity int(5) NOT NULL default '0',
ts_menu_back_color varchar(10) NOT NULL default '',
ts_menu_font_color varchar(10) NOT NULL default '',
ts_menu_border_color varchar(10) NOT NULL default '',
ts_menu_back_opacity_over int(5) NOT NULL default '0',
ts_menu_back_color_over varchar(10) NOT NULL default '',
ts_menu_font_color_over varchar(10) NOT NULL default '',
ts_menu_border_color_over varchar(10) NOT NULL default '',
ts_navi_border_radius int(5) NOT NULL default '0',
ts_navi_border_width int(5) NOT NULL default '0',
ts_navi_back_opacity int(5) NOT NULL default '0',
ts_navi_back_color varchar(10) NOT NULL default '',
ts_navi_font_color varchar(10) NOT NULL default '',
ts_navi_border_color varchar(10) NOT NULL default '',
ts_box_border_radius int(5) NOT NULL default '0',
ts_box_border_width int(5) NOT NULL default '0',
ts_box_back_opacity int(5) NOT NULL default '0',
ts_box_back_color varchar(10) NOT NULL default '',
ts_box_font_color varchar(10) NOT NULL default '',
ts_box_border_color varchar(10) NOT NULL default '',
ts_footer_border_radius int(5) NOT NULL default '0',
ts_footer_border_width int(5) NOT NULL default '0',
ts_footer_back_opacity int(5) NOT NULL default '0',
ts_footer_back_color varchar(10) NOT NULL default '',
ts_footer_font_color varchar(10) NOT NULL default '',
ts_footer_border_color varchar(10) NOT NULL default '',
ts_button_border_radius int(5) NOT NULL default '0',
ts_button_border_width int(5) NOT NULL default '0',
ts_button_back_opacity int(5) NOT NULL default '0',
ts_button_back_color varchar(10) NOT NULL default '',
ts_button_font_color varchar(10) NOT NULL default '',
ts_button_border_color varchar(10) NOT NULL default '',
ts_button_back_opacity_over int(5) NOT NULL default '0',
ts_button_back_color_over varchar(10) NOT NULL default '',
ts_button_font_color_over varchar(10) NOT NULL default '',
ts_button_border_color_over varchar(10) NOT NULL default '',
ts_page_border_radius int(5) NOT NULL default '0',
ts_page_border_width int(5) NOT NULL default '0',
ts_page_back_opacity int(5) NOT NULL default '0',
ts_page_back_color varchar(10) NOT NULL default '',
ts_page_font_color varchar(10) NOT NULL default '',
ts_page_border_color varchar(10) NOT NULL default '',
ts_page_back_opacity_over int(5) NOT NULL default '0',
ts_page_back_color_over varchar(10) NOT NULL default '',
ts_page_font_color_over varchar(10) NOT NULL default '',
ts_page_border_color_over varchar(10) NOT NULL default '',
ts_box_padding int(5) NOT NULL default '0',
PRIMARY KEY (ts_no)
) DEFAULT CHARSET=utf8";

sql_query($sql);
?>






