<?php

$sql = "CREATE TABLE $iw[point_table](
pt_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
pt_deposit float(13,2) NOT NULL default '0',
pt_withdraw float(13,2) NOT NULL default '0',
pt_balance float(13,2) NOT NULL default '0',
pt_content varchar(255),
pt_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
pt_display tinyint(4) NOT NULL default '0',
lgd_oid	varchar(30) NOT NULL default '',
po_rel_id varchar(20) NOT NULL default '',
po_rel_action varchar(255) NOT NULL default '',
PRIMARY KEY (pt_no)
) DEFAULT CHARSET=utf8";

sql_query($sql);
?>



