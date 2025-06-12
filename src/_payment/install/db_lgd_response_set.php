<?php

$sql = "CREATE TABLE $payment[lgd_response_table](
lgd_no int(11) NOT NULL auto_increment,
payment_domain varchar(100) NOT NULL default '',
payment_device varchar(10) NOT NULL default '',
payment_type varchar(10) NOT NULL default '',
ep_code varchar(30) NOT NULL default '',
mb_code varchar(30) NOT NULL default '',
state_sort varchar(10) NOT NULL default '',
lgd_oid varchar(30) NOT NULL default '',
lgd_tid varchar(29) NOT NULL default '',
lgd_respcode varchar(9) NOT NULL default '',
lgd_respmsg varchar(517) NOT NULL default '',
lgd_mid varchar(20) NOT NULL default '',
lgd_amount varchar(17) NOT NULL default '',
lgd_paytype varchar(11) NOT NULL default '',
lgd_paydate varchar(19) NOT NULL default '',
lgd_hashdata varchar(255) NOT NULL default '',
lgd_timestamp varchar(19) NOT NULL default '',
lgd_buyer varchar(15) NOT NULL default '',
lgd_productinfo varchar(133) NOT NULL default '',
lgd_buyeremail varchar(45) NOT NULL default '',
lgd_financename varchar(25) NOT NULL default '',
lgd_financeauthnum varchar(25) NOT NULL default '',
lgd_cashreceiptnum varchar(15) NOT NULL default '',
lgd_cashreceiptselfyn varchar(6) NOT NULL default '',
lgd_cashreceiptkind	varchar(9) NOT NULL default '',
lgd_cardnum varchar(25) NOT NULL default '',
lgd_cardinstallmonth varchar(7) NOT NULL default '',
lgd_cardnointyn varchar(6) NOT NULL default '',
lgd_cardgubun1 varchar(6) NOT NULL default '',
lgd_cardgubun2 varchar(6) NOT NULL default '',
lgd_accountnum varchar(25) NOT NULL default '',
lgd_accountowner varchar(45) NOT NULL default '',
lgd_payer varchar(45) NOT NULL default '',
lgd_castamount varchar(17) NOT NULL default '',
lgd_cascamount varchar(1024) NOT NULL default '',
lgd_casflag	varchar(15) NOT NULL default '',
lgd_casseqno varchar(8) NOT NULL default '',
lgd_saowner varchar(15) NOT NULL default '',
lgd_telno varchar(16) NOT NULL default '',
lgd_datetime datetime NOT NULL default '0000-00-00 00:00:00',
lgd_display tinyint(4) NOT NULL default '0',
PRIMARY KEY (lgd_no)
) DEFAULT CHARSET=utf8";

mysql_query($sql) or die(mysql_error());
?>


