<?php

$sql = "CREATE TABLE $iw[book_support_table] (
bs_no int(11) NOT NULL auto_increment,
bd_code varchar(30) NOT NULL default '',
ep_code varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
mb_code varchar(30) NOT NULL default '',
bs_amount int(11) NOT NULL default '0',
bs_datetime datetime NOT NULL default '0000-00-00 00:00:00',
bs_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (bs_no)
) DEFAULT CHARSET=utf8";

global $db_conn;
mysqli_query($db_conn, $sql) or die(mysqli_error($db_conn));
?>



