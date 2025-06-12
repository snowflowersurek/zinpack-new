<?php

$sql = "CREATE TABLE $iw[home_menu_table](
hm_no int(11) NOT NULL auto_increment,
hm_code	varchar(30) NOT NULL default '',
cg_code varchar(30) NOT NULL default '',
ep_code varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
hm_name varchar(255) NOT NULL default '',
hm_order int(11) NOT NULL default '0',
hm_deep tinyint(4) NOT NULL default '0',
hm_upper_code varchar(30) NOT NULL default '',
hm_list_scrap tinyint(4) NOT NULL default '0',
hm_view_scrap tinyint(4) NOT NULL default '0',
hm_list_style tinyint(4) NOT NULL default '0',
hm_link varchar(1024) NOT NULL default '',
hm_view_size tinyint(4) NOT NULL default '0',
hm_view_scrap_mobile tinyint(4) NOT NULL default '0',
hm_list_order tinyint(4) NOT NULL default '0',
hm_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (hm_no),
UNIQUE KEY hm_code (hm_code)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>