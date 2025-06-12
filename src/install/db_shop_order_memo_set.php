<?php

$sql = "CREATE TABLE $iw[shop_order_memo_table](
srm_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
sr_code	varchar(30) NOT NULL default '',
seller_mb_code	varchar(30) NOT NULL default '',
srm_content varchar(2048) NOT NULL default '',
PRIMARY KEY (srm_no)
) DEFAULT CHARSET=utf8";

sql_query($sql);
?>