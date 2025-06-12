<?php

$sql = "CREATE TABLE $iw[shop_delivery_table](
sy_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
sy_code	varchar(30) NOT NULL default '',
sy_name	varchar(255) NOT NULL default '',
sy_price int(11) NOT NULL default '0',
sy_max int(11) NOT NULL default '0',
sy_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (sy_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>