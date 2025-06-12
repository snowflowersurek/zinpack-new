<?php

$sql = "CREATE TABLE $iw[lgd_cancel_table](
lgdc_no	int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
lgd_oid	varchar(30) NOT NULL default '',
lgdc_tid varchar(29) NOT NULL default '',
lgdc_respcode varchar(9) NOT NULL default '',
lgdc_respmsg varchar(517) NOT NULL default '',
lgdc_mid varchar(20) NOT NULL default '',
lgdc_datetime datetime NOT NULL default '0000-00-00 00:00:00',
lgdc_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (lgdc_no),
UNIQUE KEY lgd_oid (lgd_oid)
) DEFAULT CHARSET=utf8";

?>