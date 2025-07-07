<?php
include("../_config.php");

$sql = "CREATE TABLE payment_lgd_response (
	`lgd_no` int(11) NOT NULL auto_increment,
	`payment_domain` varchar(255) NOT NULL,
	`ep_code` varchar(30) NOT NULL,
	`mb_code` varchar(30) NOT NULL,
	`state_sort` varchar(30) NOT NULL,
	`lgd_oid` varchar(40) NOT NULL,
	`lgd_tid` varchar(50) NOT NULL,
	`lgd_respcode` varchar(4) NOT NULL,
	`lgd_respmsg` varchar(50) NOT NULL,
	`lgd_mid` varchar(10) NOT NULL,
	`lgd_amount` int(11) NOT NULL,
	`lgd_paytype` varchar(10),
	`lgd_paydate` varchar(20) NOT NULL,
	`lgd_hashdata` varchar(500),
	`lgd_timestamp` varchar(20) NOT NULL,
	`lgd_buyer` varchar(30) NOT NULL,
	`lgd_productinfo` varchar(100) NOT NULL,
	`lgd_buyeremail` varchar(100) NOT NULL,
	`lgd_financename` varchar(30),
	`lgd_financeauthnum` varchar(20),
	`lgd_cashreceiptnum` varchar(20),
	`lgd_cashreceiptselfyn` varchar(1),
	`lgd_cashreceiptkind` varchar(1),
	`lgd_cardnum` varchar(20),
	`lgd_cardinstallmonth` varchar(2),
	`lgd_cardnointyn` varchar(1),
	`lgd_cardgubun1` varchar(30),
	`lgd_cardgubun2` varchar(30),
	`lgd_accountnum` varchar(20),
	`lgd_accountowner` varchar(20),
	`lgd_payer` varchar(20),
	`lgd_payauthcode` varchar(20),
	`lgd_payauthtype` varchar(1),
	`lgd_paybankcode` varchar(3),
	`lgd_paymethod` varchar(1),
	`lgd_paymethodcode` varchar(2),
	`lgd_paymethodcategory` varchar(1),
	`lgd_paymethodname` varchar(10),
	`lgd_mobileno` varchar(20),
	`lgd_ipaddr` varchar(20),
	`lgd_paykey` varchar(100),
	`lgd_datetime` datetime NOT NULL,
	`lgd_display` int(1) NOT NULL default '1',
	PRIMARY KEY  (`lgd_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

// DB 연결 설정
$connect_db = sql_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$connect_db) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($connect_db, $sql);
if (!$result) {
    die("Error creating table: " . mysqli_error($connect_db));
}

echo "payment_lgd_response table created successfully";
mysqli_close($connect_db);
?>






