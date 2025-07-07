<?php

$sql = "CREATE TABLE $iw[total_data_table](
td_no int(11) NOT NULL auto_increment,
td_code	varchar(30) NOT NULL default '',
cg_code varchar(30) NOT NULL default '',
ep_code varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
td_hit int(11) NOT NULL default '0',
td_datetime datetime NOT NULL default '0000-00-00 00:00:00',
td_edit_datetime datetime NOT NULL default '0000-00-00 00:00:00',
td_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (td_no),
UNIQUE KEY td_code (td_code)
) DEFAULT CHARSET=utf8";

sql_query($sql);
?>



