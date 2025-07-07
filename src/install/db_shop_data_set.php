<?php

$sql = "CREATE TABLE $iw[shop_data_table](
sd_no int(11) NOT NULL auto_increment,
sd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
cg_code varchar(30) NOT NULL default '',
sd_image varchar(255) NOT NULL default '',
sd_subject varchar(255) NOT NULL default '',
sd_price int(11) NOT NULL default '0',
sd_sale	int(11) NOT NULL default '0',
sd_information text NOT NULL default '',
sd_tag varchar(255) NOT NULL default '',
sd_max int(11) NOT NULL default '0',
sd_content text NOT NULL default '',
sy_code	varchar(30) NOT NULL default '',
sd_sell	int(11) NOT NULL default '0',
sd_hit int(11) NOT NULL default '0',
sd_recommend int(11) NOT NULL default '0',
sd_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
sd_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (sd_no),
UNIQUE KEY sd_code (sd_code)
) DEFAULT CHARSET=utf8";

?>



