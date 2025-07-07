<?php

$sql = "CREATE TABLE $iw[shop_option_table](
so_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
sd_code	varchar(30) NOT NULL default '',
so_name	varchar(255) NOT NULL default '',
so_amount int(11) NOT NULL default '0',
so_price int(11) NOT NULL default '0',
so_taxfree tinyint(4) NOT NULL default '0',
PRIMARY KEY (so_no)
) DEFAULT CHARSET=utf8";

?>



