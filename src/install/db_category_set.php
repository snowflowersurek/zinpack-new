<?php

$sql = "CREATE TABLE $iw[category_table](
cg_no int(11) NOT NULL auto_increment,
cg_code	varchar(30) NOT NULL default '',
ep_code	varchar(30) NOT NULL default '',
gp_code	varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
cg_name varchar(255) NOT NULL default '',
cg_display tinyint(4) NOT NULL default '0',
cg_level_write tinyint(4) NOT NULL default '0',
cg_level_comment tinyint(4) NOT NULL default '0',
cg_level_read tinyint(4) NOT NULL default '0',
cg_level_upload tinyint(4) NOT NULL default '0',
cg_level_download tinyint(4) NOT NULL default '0',
cg_date tinyint(4) NOT NULL default '0',
cg_writer tinyint(4) NOT NULL default '0',
cg_hit tinyint(4) NOT NULL default '0',
cg_comment tinyint(4) NOT NULL default '0',
cg_recommend tinyint(4) NOT NULL default '0',
cg_point_btn tinyint(4) NOT NULL default '0',
cg_comment_view tinyint(4) NOT NULL default '0',
cg_recommend_view tinyint(4) NOT NULL default '0',
cg_url tinyint(4) NOT NULL default '0',
cg_facebook tinyint(4) NOT NULL default '0',
cg_twitter tinyint(4) NOT NULL default '0',
cg_googleplus tinyint(4) NOT NULL default '0',
cg_pinterest tinyint(4) NOT NULL default '0',
cg_linkedin tinyint(4) NOT NULL default '0',
cg_delicious tinyint(4) NOT NULL default '0',
cg_tumblr tinyint(4) NOT NULL default '0',
cg_digg tinyint(4) NOT NULL default '0',
cg_stumbleupon tinyint(4) NOT NULL default '0',
cg_reddit tinyint(4) NOT NULL default '0',
cg_sinaweibo tinyint(4) NOT NULL default '0',
cg_qzone tinyint(4) NOT NULL default '0',
cg_renren tinyint(4) NOT NULL default '0',
cg_tencentweibo tinyint(4) NOT NULL default '0',
cg_kakaotalk tinyint(4) NOT NULL default '0',
cg_line tinyint(4) NOT NULL default '0',
PRIMARY KEY (cg_no),
UNIQUE KEY cg_code (cg_code)
) DEFAULT CHARSET=utf8";

sql_query($sql);
?>



