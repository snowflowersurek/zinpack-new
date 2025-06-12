<?php

$sql = "CREATE TABLE $iw[book_buy_table](
bb_no int(11) NOT NULL auto_increment,
bd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
seller_mb_code varchar(30) NOT NULL default '',
bb_subject varchar(255) NOT NULL default '',
bb_price int(11) NOT NULL default '0',
bb_price_seller float(13,2) NOT NULL default '0',
bb_price_site float(13,2) NOT NULL default '0',
bb_price_super float(13,2) NOT NULL default '0',
bb_ip varchar(20) NOT NULL default '',
bb_divice varchar(100) NOT NULL default '',
bb_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
bb_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bb_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>