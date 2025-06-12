<?php

$sql = "CREATE TABLE $iw[book_media_detail_table](
bmd_no int(11) NOT NULL auto_increment,
bd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
bm_order int(11) NOT NULL default '0',
bmd_order int(11) NOT NULL default '0',
bmd_image varchar(1024) NOT NULL default '',
bmd_big_image varchar(255) NOT NULL default '',
bmd_type varchar(255) NOT NULL default '0',
bmd_content	text NOT NULL default '',
bmd_display	tinyint(4) NOT NULL default '0',
PRIMARY KEY (bmd_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>