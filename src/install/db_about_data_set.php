<?php

$sql = "CREATE TABLE $iw[about_data_table](
ad_no int(11) NOT NULL auto_increment,
ad_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
ad_navigation varchar(255) NOT NULL default '',
ad_subject varchar(255) NOT NULL default '',
ad_content text NOT NULL default '',
ad_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
ad_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (ad_no),
UNIQUE KEY ad_code (ad_code)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>