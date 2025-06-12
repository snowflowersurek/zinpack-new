<?php

$sql = "CREATE TABLE $iw[recommend_table](
rc_no int(11) NOT NULL auto_increment,
ep_code varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
mb_code varchar(30) NOT NULL default '',
rc_type tinyint(4) NOT NULL default '0',
rc_code varchar(30) NOT NULL default '',
rc_date datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY (rc_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>