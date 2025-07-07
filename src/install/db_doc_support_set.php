<?php

$sql = "CREATE TABLE $iw[doc_support_table](
ds_no int(11) NOT NULL auto_increment,
dd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
seller_mb_code varchar(30) NOT NULL default '',
ds_subject varchar(255) NOT NULL default '',
ds_price int(11) NOT NULL default '0',
ds_ip varchar(20) NOT NULL default '',
ds_divice varchar(100) NOT NULL default '',
ds_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
ds_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (ds_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>



