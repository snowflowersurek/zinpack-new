<?php

$sql = "CREATE TABLE $iw[popup_layer_table](
pl_no int(11) NOT NULL auto_increment,
ep_code varchar(30) NOT NULL default '',
gp_code varchar(30) NOT NULL default '',
pl_content text NOT NULL default '',
pl_subject varchar(255) NOT NULL default '',
pl_stime datetime NOT NULL default '0000-00-00 00:00:00',
pl_etime datetime NOT NULL default '0000-00-00 00:00:00',
pl_width int(11) NOT NULL default '0',
pl_height int(11) NOT NULL default '0',
pl_top int(11) NOT NULL default '0',
pl_left int(11) NOT NULL default '0',
pl_state tinyint(4) NOT NULL default '0',
pl_dayback varchar(30) NOT NULL default '',
pl_dayfont varchar(30) NOT NULL default '',
pl_line varchar(30) NOT NULL default '',
pl_date datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY (pl_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>