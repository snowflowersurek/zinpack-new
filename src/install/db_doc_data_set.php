<?php

$sql = "CREATE TABLE $iw[doc_data_table](
dd_no int(11) NOT NULL auto_increment,
dd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
cg_code varchar(30) NOT NULL default '',
dd_image varchar(255) NOT NULL default '',
dd_file varchar(255) NOT NULL default '',
dd_file_name varchar(255) NOT NULL default '',
dd_file_size varchar(255) NOT NULL default '',
dd_subject varchar(255) NOT NULL default '',
dd_amount int(11) NOT NULL default '0',
dd_type tinyint(4) NOT NULL default '0',
dd_price int(11) NOT NULL default '0',
dd_tag varchar(255) NOT NULL default '',
dd_download int(11) NOT NULL default '0',
dd_content text NOT NULL default '',
dd_sell	int(11) NOT NULL default '0',
dd_hit int(11) NOT NULL default '0',
dd_recommend int(11) NOT NULL default '0',
dd_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
dd_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (dd_no),
UNIQUE KEY dd_code (dd_code)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>


