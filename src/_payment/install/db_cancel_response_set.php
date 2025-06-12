<?php

$sql = "CREATE TABLE $payment[cancel_response_table](
cancel_no int(11) NOT NULL auto_increment,
payment_domain varchar(100) NOT NULL default '',
ep_code varchar(30) NOT NULL default '',
mb_code varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
lgd_oid varchar(30) NOT NULL default '',
lgd_tid varchar(29) NOT NULL default '',
lgd_respcode varchar(9) NOT NULL default '',
lgd_respmsg varchar(517) NOT NULL default '',
lgd_mid varchar(20) NOT NULL default '',
lgd_datetime datetime NOT NULL default '0000-00-00 00:00:00',
lgd_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (cancel_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>