<?php

$sql = "CREATE TABLE $iw[account_table](
ac_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
ac_bank varchar(50) NOT NULL default '',
ac_number varchar(50) NOT NULL default '',
ac_holder varchar(50) NOT NULL default '',
ac_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
ac_calculate datetime NOT NULL default '0000-00-00 00:00:00',
ac_display tinyint(4) NOT NULL default '0',
ac_bank_name varchar(255) NOT NULL default '',
ac_bank_account varchar(255) NOT NULL default '',
PRIMARY KEY  (ac_no)
) DEFAULT CHARSET=utf8";

?>