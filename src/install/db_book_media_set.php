<?php

$sql = "CREATE TABLE $iw[book_media_table](
bm_no int(11) NOT NULL auto_increment,
bd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
bm_order int(11) NOT NULL default '0',
bm_image varchar(255) NOT NULL default '',
bm_type	tinyint(4) NOT NULL default '0',
bm_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bm_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>



