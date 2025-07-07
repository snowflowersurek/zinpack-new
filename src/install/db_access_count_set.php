<?php

$sql = "CREATE TABLE $iw[access_count_table] (
ac_no int(11) NOT NULL auto_increment,
ac_name varchar(255) NOT NULL default '',
ac_ep varchar(30) NOT NULL default '',
ac_gp varchar(30) NOT NULL default '',
ac_mb varchar(30) NOT NULL default '',
ac_time datetime NOT NULL default '0000-00-00 00:00:00',
ac_ip varchar(255) NOT NULL default '',
PRIMARY KEY (ac_no),
KEY ac_name (ac_name,ac_ep,ac_gp,ac_mb)
) DEFAULT CHARSET=utf8";

global $db_conn;
mysqli_query($db_conn, $sql) or die(mysqli_error($db_conn));
?>



