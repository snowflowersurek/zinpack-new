<?php

$sql = "CREATE TABLE $iw[alipay_table](
ap_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
ap_out_trade_no varchar(30) NOT NULL default '',
ap_currency varchar(20) NOT NULL default '',
ap_total_fee varchar(20) NOT NULL default '',
ap_trade_no varchar(30) NOT NULL default '',
ap_trade_status varchar(30) NOT NULL default '',
ap_item_name varchar(255) NOT NULL default '',
ap_datetime datetime NOT NULL default '0000-00-00 00:00:00',
ap_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (ap_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>