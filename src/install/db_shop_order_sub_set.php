<?php

$sql = "CREATE TABLE $iw[shop_order_sub_table](
srs_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
sr_code	varchar(30) NOT NULL default '',
seller_mb_code varchar(30) NOT NULL default '',
sd_code	varchar(30) NOT NULL default '',
so_no int(11) NOT NULL default '0',
srs_subject	varchar(255) NOT NULL default '',
srs_name varchar(255) NOT NULL default '',
srs_amount int(11) NOT NULL default '0',
srs_price int(11) NOT NULL default '0',
srs_delivery_type tinyint(4) NOT NULL default '0',
srs_delivery_price int(11) NOT NULL default '0',
srs_bundle varchar(30) NOT NULL default '',
srs_delivery varchar(255) NOT NULL default '',
srs_delivery_num varchar(255) NOT NULL default '',
srs_taxfree	tinyint(4) NOT NULL default '0',
srs_display	tinyint(4) NOT NULL default '0',
PRIMARY KEY (srs_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>