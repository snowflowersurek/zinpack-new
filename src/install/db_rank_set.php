<?php

$sql = "CREATE TABLE $iw[rank_table](
rk_no int(11) NOT NULL auto_increment,
rk_month datetime NOT NULL default '0000-00-00 00:00:00',
rk_day datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY (rk_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());


$sql = "CREATE TABLE $iw[rank_month_table](
rm_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
rm_price int(11) NOT NULL,
rm_total int(11) NOT NULL,
PRIMARY KEY (rm_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());

$sql = "CREATE TABLE $iw[rank_day_table](
rd_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
rd_price int(11) NOT NULL,
rd_total int(11) NOT NULL,
PRIMARY KEY (rd_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>