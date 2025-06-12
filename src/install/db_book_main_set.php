<?php

$sql = "CREATE TABLE $iw[book_main_table](
bn_no int(11) NOT NULL auto_increment,
bd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
bn_logo varchar(255) NOT NULL default '',
bn_sub_title varchar(100) NOT NULL default '',
bn_thum varchar(255) NOT NULL default '',
bn_text_logo varchar(255) NOT NULL default '',
bn_file varchar(255) NOT NULL default '',
bn_color varchar(100) NOT NULL default '',
bn_font varchar(100) NOT NULL default '',
bn_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bn_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>