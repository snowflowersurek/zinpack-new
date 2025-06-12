<?php

$sql = "CREATE TABLE $iw[group_table](
gp_no int(11) NOT NULL auto_increment,
gp_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
gp_nick	varchar(1024) NOT NULL default '',
gp_subject varchar(1024) NOT NULL default '',
gp_content text NOT NULL default '',
gp_autocode	varchar(255) NOT NULL default '',
gp_type	tinyint(4) NOT NULL default '0',
gp_closed tinyint(4) NOT NULL default '0',
gp_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
gp_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (gp_no),
UNIQUE KEY gp_code (gp_code)
) DEFAULT CHARSET=utf8";

?>


