<?php

$sql = "CREATE TABLE $iw[enterprise_table](
ep_no int(11) NOT NULL auto_increment,
ep_code	varchar(30) NOT NULL default '',
mb_code	varchar(30) NOT NULL default '',
ep_nick	varchar(255) NOT NULL default '',
ep_corporate varchar(255) NOT NULL default '',
ep_permit_number varchar(255) NOT NULL default '',
ep_state_mcb tinyint(4) NOT NULL default '0',
ep_state_doc tinyint(4) NOT NULL default '0',
ep_state_shop tinyint(4) NOT NULL default '0',
ep_state_book tinyint(4) NOT NULL default '0',
ep_exposed tinyint(4) NOT NULL default '0',
ep_autocode	varchar(255) NOT NULL default '',
ep_jointype	tinyint(4) NOT NULL default '0',
ep_language varchar(10) NOT NULL default '',
ep_footer text NOT NULL default '',
ep_anonymity tinyint(4) NOT NULL default '0',
ep_domain varchar(100) NOT NULL default '',
ep_upload tinyint(4) NOT NULL default '0',
ep_upload_size int(11) NOT NULL default '0',
ep_point_seller	int(11) NOT NULL default '0',
ep_point_site int(11) NOT NULL default '0',
ep_point_super int(11) NOT NULL default '0',
ep_policy_email text NOT NULL default '',
ep_policy_private text NOT NULL default '',
ep_policy_agreement text NOT NULL default '',
ep_copy_off tinyint(4) NOT NULL default '0',
ep_datetime	datetime NOT NULL default '0000-00-00 00:00:00',
ep_display tinyint(4) NOT NULL default '0',
ep_expiry_date datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY (ep_no),
UNIQUE KEY ep_code (ep_code)
) DEFAULT CHARSET=utf8";

?>