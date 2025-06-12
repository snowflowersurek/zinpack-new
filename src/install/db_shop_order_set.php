<?php

$sql = "CREATE TABLE $iw[shop_order_table](
sr_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
sr_code	varchar(30) NOT NULL default '',
sr_product varchar(255) NOT NULL default '',
sr_buy_name	varchar(255) NOT NULL default '',
sr_buy_phone varchar(255) NOT NULL default '',
sr_buy_mail	varchar(255) NOT NULL default '',
sr_name	varchar(255) NOT NULL default '',
sr_phone varchar(255) NOT NULL default '',
sr_phone_sub varchar(255) NOT NULL default '',
sr_zip_code	varchar(255) NOT NULL default '',
sr_address varchar(255) NOT NULL default '',
sr_address_sub varchar(255) NOT NULL default '',
sr_address_city varchar(255) NOT NULL default '',
sr_address_state varchar(255) NOT NULL default '',
sr_address_country varchar(10) NOT NULL default '',
sr_request varchar(255) NOT NULL default '',
sr_sum int(11) NOT NULL default '0',
sr_point int(11) NOT NULL default '0',
sr_ip varchar(255) NOT NULL default '',
sr_pay varchar(30) NOT NULL default '',
sr_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
sr_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (sr_no),
UNIQUE KEY sr_code (sr_code)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>