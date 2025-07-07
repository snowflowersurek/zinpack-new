<?php

$sql = "CREATE TABLE $iw[notice_table](
nt_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
nt_type	tinyint(4) NOT NULL default '0',
nt_subject varchar(255) NOT NULL default '',
nt_content text NOT NULL default '',
nt_file	varchar(255) NOT NULL default '',
nt_hit int(11) NOT NULL default '0',
nt_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
nt_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (nt_no)
) DEFAULT CHARSET=utf8";

?>






