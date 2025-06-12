<?php

$sql = "CREATE TABLE $iw[book_support_table](
bs_no int(11) NOT NULL auto_increment,
bd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
seller_mb_code varchar(30) NOT NULL default '',
bs_subject varchar(255) NOT NULL default '',
bs_price int(11) NOT NULL default '0',
bs_ip varchar(20) NOT NULL default '',
bs_divice varchar(100) NOT NULL default '',
bs_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
bs_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bs_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>