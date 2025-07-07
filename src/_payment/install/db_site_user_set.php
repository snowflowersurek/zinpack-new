<?php
include("../_config.php");

$sql = "CREATE TABLE payment_site_user (
	`ps_no` int(11) NOT NULL auto_increment,
	`ps_corporate` varchar(100) NOT NULL,
	`ps_domain` varchar(100) NOT NULL,
	`ps_datetime` datetime NOT NULL,
	`ps_display` int(1) NOT NULL default '1',
	PRIMARY KEY  (`ps_no`)
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

echo "payment_site_user table created successfully";
mysqli_close($connect_db);
?>






