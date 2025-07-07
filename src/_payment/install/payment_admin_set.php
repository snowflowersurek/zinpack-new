<?php
// 이 파일은 _payment/_common.php 에서 include 되므로, $db_conn 변수를 사용할 수 있습니다.

$hashed_password = password_hash("infoway123", PASSWORD_BCRYPT);
$sdate = date("Y-m-d H:i:s");
$edate = '2030-12-31 23:59:59';
$display = 9;

$sql = "insert into {$payment['site_user_table']} (ps_domain, ps_corporate, ps_name, ps_sort, ps_sdate, ps_edate, ps_display) values (?, ?, ?, ?, ?, ?, ?)";

// 1. info-way.co.kr
$stmt = mysqli_prepare($db_conn, $sql);
$domain1 = 'www.info-way.co.kr';
$name1 = '최고관리자';
$sort1 = 'charge';
mysqli_stmt_bind_param($stmt, "ssssssi", $domain1, $hashed_password, $name1, $sort1, $sdate, $edate, $display);
mysqli_stmt_execute($stmt);

// 2. wizwindigital.com
$domain2 = 'www.wizwindigital.com';
$sort2 = 'product';
mysqli_stmt_bind_param($stmt, "ssssssi", $domain2, $hashed_password, $name1, $sort2, $sdate, $edate, $display);
mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
?>



