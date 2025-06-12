<?php

$sql = "CREATE TABLE $payment[lgd_request_table](
lgd_no int(11) NOT NULL auto_increment,
payment_domain varchar(100) NOT NULL default '',
payment_device varchar(10) NOT NULL default '',
payment_type varchar(10) NOT NULL default '',
ep_code varchar(30) NOT NULL default '',
mb_code varchar(30) NOT NULL default '',
state_sort varchar(30) NOT NULL default '',
lgd_mid varchar(20) NOT NULL default '',
lgd_oid varchar(30) NOT NULL default '',
lgd_amount varchar(17) NOT NULL default '',
lgd_buyer varchar(15) NOT NULL default '',
lgd_productinfo varchar(133) NOT NULL default '',
lgd_buyeremail varchar(45) NOT NULL default '',
lgd_timestamp varchar(19) NOT NULL default '',
lgd_buyerip varchar(30) NOT NULL default '',
lgd_custom_firstpay varchar(11) NOT NULL default '',
lgd_datetime datetime NOT NULL default '0000-00-00 00:00:00',
lgd_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (lgd_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>



