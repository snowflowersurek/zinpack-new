<?php

$sql = "CREATE TABLE $iw[book_data_table] (
bd_no int(11) NOT NULL auto_increment,
bd_code varchar(30) NOT NULL default '',
bd_category varchar(255) NOT NULL default '',
bd_brand varchar(255) NOT NULL default '',
ep_code varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
mb_code varchar(30) NOT NULL default '',
bd_title varchar(255) NOT NULL default '',
bd_intro varchar(255) NOT NULL default '',
bd_content text NOT NULL,
bd_video varchar(255) NOT NULL default '',
bd_adult tinyint(4) NOT NULL default '0',
bd_allow tinyint(4) NOT NULL default '0',
bd_index tinyint(4) NOT NULL default '0',
bd_exposure tinyint(4) NOT NULL default '0',
bd_view int(11) NOT NULL default '0',
bd_good int(11) NOT NULL default '0',
bd_bad int(11) NOT NULL default '0',
bd_datetime datetime NOT NULL default '0000-00-00 00:00:00',
bd_ip varchar(255) NOT NULL default '',
bd_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bd_no),
UNIQUE KEY bd_code (bd_code)
) DEFAULT CHARSET=utf8";

global $db_conn;
mysqli_query($db_conn, $sql) or die(mysqli_error($db_conn));
?>



