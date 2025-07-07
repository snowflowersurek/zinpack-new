<?php

$sql = "CREATE TABLE $iw[group_member_table](
gm_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
gm_datetime_drop datetime NOT NULL default '0000-00-00 00:00:00',
gm_drop_reason varchar(255) NOT NULL default '',
gm_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
gm_display tinyint(4) NOT NULL default '0',
gm_level tinyint(4) NOT NULL default '0',
PRIMARY KEY (gm_no)
) DEFAULT CHARSET=utf8";

?>






