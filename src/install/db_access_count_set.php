<?php

$sql = "CREATE TABLE $iw[access_count_table](
ac_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
ac_page_count int(11) NOT NULL default '0',
ac_ip_count	int(11) NOT NULL default '0',
ac_date datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY (ac_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>