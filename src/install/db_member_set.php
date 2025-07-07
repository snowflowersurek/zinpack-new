<?php

$sql = "CREATE TABLE $iw[member_table](
mb_no int(11) NOT NULL auto_increment,
mb_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
mb_mail	varchar(255) NOT NULL default '',
mb_password	varchar(255) NOT NULL default '',
mb_name	varchar(255) NOT NULL default '',
mb_nick	varchar(255) NOT NULL default '',
mb_tel varchar(255) NOT NULL default '',
mb_sub_mail	varchar(255) NOT NULL default '',
mb_zip_code	varchar(255) NOT NULL default '',
mb_address varchar(255) NOT NULL default '',
mb_address_sub varchar(255) NOT NULL default '',
mb_address_city varchar(255) NOT NULL default '',
mb_address_state varchar(255) NOT NULL default '',
mb_address_country varchar(10) NOT NULL default '',
mb_point float(13,2) NOT NULL default '0',
mb_ip varchar(255) NOT NULL default '',
mb_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
mb_display tinyint(4) NOT NULL default '0',
mb_level tinyint(4) NOT NULL default '0',
PRIMARY KEY (mb_no),
UNIQUE KEY mb_code (mb_code)
) DEFAULT CHARSET=utf8";

?>



