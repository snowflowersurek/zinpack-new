<?php

$sql = "CREATE TABLE $payment[site_user_table](
ps_no int(11) NOT NULL auto_increment,
ps_domain varchar(100) NOT NULL default '',
ps_corporate varchar(255) NOT NULL default '',
ps_datetime datetime NOT NULL default '0000-00-00 00:00:00',
ps_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (ps_no),
UNIQUE KEY ps_domain (ps_domain)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>


