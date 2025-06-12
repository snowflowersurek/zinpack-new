<?php

$sql = "CREATE TABLE $iw[book_thesis_table](
bt_no int(11) NOT NULL auto_increment,
bd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
bt_order int(11) NOT NULL default '0',
bt_title_kr varchar(1024) NOT NULL default '',
bt_title_us varchar(1024) NOT NULL default '',
bt_sub_kr varchar(1024) NOT NULL default '',
bt_sub_us varchar(1024) NOT NULL default '',
bt_person varchar(1024) NOT NULL default '',
bt_page	int(11) NOT NULL default '0',
bt_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bt_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>


