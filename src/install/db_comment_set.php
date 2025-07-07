<?php

$sql = "CREATE TABLE $iw[comment_table](
cm_no int(11) NOT NULL auto_increment,
cm_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
cm_recomment int(11) NOT NULL default '0',
cm_content varchar(1024) NOT NULL default '',
cm_ip varchar(20) NOT NULL default '',
cm_recommend int(11) NOT NULL default '0',
cm_secret tinyint(4) NOT NULL default '0',
cm_sns_type varchar(10) NOT NULL default '',
cm_sns_image varchar(255) NOT NULL default '',
cm_sns_name varchar(100) NOT NULL default '',
cm_sns_id varchar(100) NOT NULL default '',
cm_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
cm_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (cm_no)
) DEFAULT CHARSET=utf8";

?>



