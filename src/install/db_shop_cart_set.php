<?php

$sql = "CREATE TABLE $iw[shop_cart_table](
sc_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
seller_mb_code varchar(30) NOT NULL default '',
sd_code	varchar(255) NOT NULL default '',
sd_delivery_type tinyint(4) NOT NULL default '0',
so_no int(11) NOT NULL default '0',
sc_amount int(11) NOT NULL default '0',
PRIMARY KEY (sc_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>



