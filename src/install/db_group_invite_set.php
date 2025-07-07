<?php

$sql = "CREATE TABLE $iw[group_invite_table](
gi_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
gi_name	varchar(255) NOT NULL default '',
gi_name_check tinyint(4) NOT NULL default '0',
gi_mail	varchar(255) NOT NULL default '',
gi_mail_check tinyint(4) NOT NULL default '0',
gi_tel varchar(255) NOT NULL default '',
gi_tel_check tinyint(4) NOT NULL default '0',
gi_message varchar(255) NOT NULL default '',
gi_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
gi_datetime_end	datetime NOT NULL default '0000-00-00 00:00:00',
gi_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (gi_no)
) DEFAULT CHARSET=utf8";

?>



