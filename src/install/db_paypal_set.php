<?php

$sql = "CREATE TABLE $iw[paypal_table](
pp_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
pp_invoice	varchar(30) NOT NULL default '',
pp_txn_id varchar(100) NOT NULL default '',
pp_payment_status varchar(255) NOT NULL default '',
pp_business varchar(100) NOT NULL default '',
pp_mc_gross varchar(20) NOT NULL default '',
pp_item_name varchar(255) NOT NULL default '',
pp_datetime datetime NOT NULL default '0000-00-00 00:00:00',
pp_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (pp_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>