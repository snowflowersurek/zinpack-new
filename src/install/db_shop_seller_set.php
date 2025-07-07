<?php

$sql = "CREATE TABLE $iw[shop_seller_table](
ss_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
ss_name	varchar(255) NOT NULL default '',
ss_tel varchar(255) NOT NULL default '',
ss_zip_code	varchar(255) NOT NULL default '',
ss_address varchar(255) NOT NULL default '',
ss_address_sub varchar(255) NOT NULL default '',
ss_content text NOT NULL default '',
ss_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
ss_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (ss_no),
UNIQUE KEY mb_code (mb_code)
) DEFAULT CHARSET=utf8";

?>



