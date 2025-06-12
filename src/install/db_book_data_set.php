<?php

$sql = "CREATE TABLE $iw[book_data_table](
bd_no int(11) NOT NULL auto_increment,
bd_code varchar(30) NOT NULL default '',
ep_code varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
mb_code varchar(30) NOT NULL default '',
cg_code varchar(30) NOT NULL default '',
bd_image varchar(255) NOT NULL default '',
bd_file varchar(255) NOT NULL default '',
bd_sample varchar(255) NOT NULL default '',
bd_type tinyint(4) NOT NULL default '0',
bd_subject varchar(255) NOT NULL default '',
bd_author varchar(255) NOT NULL default '',
bd_publisher varchar(255) NOT NULL default '',
bd_price int(11) NOT NULL default '0',
bd_tag varchar(255) NOT NULL default '',
bd_content text NOT NULL default '',
bd_sell int(11) NOT NULL default '0',
bd_hit int(11) NOT NULL default '0',
bd_recommend int(11) NOT NULL default '0',
bd_datetime datetime NOT NULL default '0000-00-00 00:00:00',
bd_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bd_no),
UNIQUE KEY bd_code (bd_code)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>