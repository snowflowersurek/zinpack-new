<?php

$sql = "CREATE TABLE $iw[doc_buy_table](
db_no int(11) NOT NULL auto_increment,
dd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
seller_mb_code varchar(30) NOT NULL default '',
db_subject varchar(255) NOT NULL default '',
db_price int(11) NOT NULL default '0',
db_price_seller float(13,2) NOT NULL default '0',
db_price_site float(13,2) NOT NULL default '0',
db_price_super float(13,2) NOT NULL default '0',
db_ip varchar(20) NOT NULL default '',
db_divice varchar(100) NOT NULL default '',
db_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
db_end_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
db_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (db_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>



