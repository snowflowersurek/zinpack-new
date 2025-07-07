<?php
include("../_config.php");

$sql = "CREATE TABLE payment_lgd_request (
	`lgd_no` int(11) NOT NULL auto_increment,
	`payment_domain` varchar(255) NOT NULL,
	`ep_code` varchar(30) NOT NULL,
	`mb_code` varchar(30) NOT NULL,
	`state_sort` varchar(30) NOT NULL,
	`lgd_oid` varchar(40) NOT NULL,
	`lgd_paykey` varchar(100),
	`lgd_amount` int(11) NOT NULL,
	`lgd_buyer` varchar(30) NOT NULL,
	`lgd_productinfo` varchar(100) NOT NULL,
	`lgd_buyeremail` varchar(100) NOT NULL,
	`lgd_timestamp` varchar(20) NOT NULL,
	`lgd_authdata` varchar(500),
	`lgd_hashdata` varchar(500),
	`lgd_custom_firstpay` varchar(20),
	`lgd_custom_ispay` varchar(10),
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

echo "payment_lgd_request table created successfully";
mysqli_close($connect_db);
?>







