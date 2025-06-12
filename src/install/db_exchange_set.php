<?php

$sql = "CREATE TABLE $iw[exchange_table](
ec_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
ec_bank varchar(50) NOT NULL default '',
ec_number varchar(50) NOT NULL default '',
ec_holder varchar(50) NOT NULL default '',
ec_point int(11) NOT NULL default '0',
ec_amount	int(11) NOT NULL default '0',
ec_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
ec_give_datetime datetime NOT NULL default '0000-00-00 00:00:00',
ec_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (ec_no)
) DEFAULT CHARSET=utf8";

?>