<?php

$sql = "CREATE TABLE $iw[group_level_table](
gl_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
gl_name varchar(255) NOT NULL default '',
gl_content varchar(1024) NOT NULL default '',
gl_level tinyint(4) NOT NULL default '0',
gl_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (gl_no)
) DEFAULT CHARSET=utf8";

?>