<?php

$sql = "CREATE TABLE $iw[mcb_data_table](
md_no int(11) NOT NULL auto_increment,
md_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
cg_code varchar(30) NOT NULL default '',
md_type tinyint(4) NOT NULL default '0',
md_subject varchar(255) NOT NULL default '',
md_content text NOT NULL default '',
md_youtube varchar(1024) NOT NULL default '',
md_attach varchar(255) NOT NULL default '',
md_attach_name varchar(255) NOT NULL default '',
md_file_1 varchar(255) NOT NULL default '',
md_file_2 varchar(255) NOT NULL default '',
md_file_3 varchar(255) NOT NULL default '',
md_file_4 varchar(255) NOT NULL default '',
md_file_5 varchar(255) NOT NULL default '',
md_file_6 varchar(255) NOT NULL default '',
md_file_7 varchar(255) NOT NULL default '',
md_file_8 varchar(255) NOT NULL default '',
md_file_9 varchar(255) NOT NULL default '',
md_file_10 varchar(255) NOT NULL default '',
md_ip varchar(20) NOT NULL default '',
md_hit int(11) NOT NULL default '0',
md_recommend int(11) NOT NULL default '0',
md_padding tinyint(4) NOT NULL default '0',
md_secret tinyint(4) NOT NULL default '0',
md_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
md_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (md_no),
UNIQUE KEY md_code (md_code)
) DEFAULT CHARSET=utf8";

global $db_conn;
mysqli_query($db_conn, $sql) or die(mysqli_error($db_conn));
?>




