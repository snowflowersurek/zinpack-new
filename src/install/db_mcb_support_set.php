<?php

$sql = "CREATE TABLE $iw[mcb_support_table](
ms_no int(11) NOT NULL auto_increment,
md_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
seller_mb_code varchar(30) NOT NULL default '',
ms_subject varchar(255) NOT NULL default '',
ms_price int(11) NOT NULL default '0',
ms_ip varchar(20) NOT NULL default '',
ms_divice varchar(100) NOT NULL default '',
ms_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
ms_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (ms_no)
) DEFAULT CHARSET=utf8";

global $db_conn;
mysqli_query($db_conn, $sql) or die(mysqli_error($db_conn));
?>



