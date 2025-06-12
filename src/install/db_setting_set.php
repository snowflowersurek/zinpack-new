<?php

$sql = "CREATE TABLE $iw[setting_table](
st_no int(11) NOT NULL auto_increment,
ep_code varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
hm_code varchar(30) NOT NULL default '',
st_title varchar(255) NOT NULL default '',
st_top_img varchar(255) NOT NULL default '',
st_top_align varchar(30) NOT NULL default '',
st_background varchar(255) NOT NULL default '',
st_favicon varchar(255) NOT NULL default '',
st_mcb_name	varchar(100) NOT NULL default '',
st_doc_name	varchar(100) NOT NULL default '',
st_shop_name varchar(100) NOT NULL default '',
st_book_name varchar(100) NOT NULL default '',
st_menu_position tinyint(4) NOT NULL default '0',
st_navigation tinyint(4) NOT NULL default '0',
st_slide_size tinyint(4) NOT NULL default '0',
st_sns_share tinyint(4) NOT NULL default '0',
st_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
st_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (st_no)
) DEFAULT CHARSET=utf8";

?>