<?php

$sql = "CREATE TABLE $iw[master_table](
ma_no int(11) NOT NULL auto_increment,
ma_userid varchar(255) NOT NULL default '',
ma_password varchar(255) NOT NULL default '',
ma_buy_rate int(11) NOT NULL default '0',
ma_sell_rate int(11) NOT NULL default '0',
ma_shop_rate int(11) NOT NULL default '0',
ma_exchange_point int(11) NOT NULL default '0',
ma_exchange_amount int(11) NOT NULL default '0',
ma_policy_email text NOT NULL default '',
ma_policy_private text NOT NULL default '',
ma_policy_agreement	text NOT NULL default '',
ma_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (ma_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>



