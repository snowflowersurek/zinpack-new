<?php

$sql = "CREATE TABLE $iw[book_blog_table](
bg_no int(11) NOT NULL auto_increment,
bd_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
bg_order int(11) NOT NULL default '0',
bg_image varchar(255) NOT NULL default '',
bg_page	int(11) NOT NULL default '0',
bg_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bg_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>



