<?php
header('Content-Type: text/html; charset=utf-8');
$iw_path = "../../.."; // common.php 의 상대 경로
include_once("$iw_path/include/common.php");

$oidtxt = trim($_POST['oid']);
$oval = $_POST['oval'];
$oid = str_replace("in_","",$oidtxt);

$restxt = "";
$sql = "SELECT COUNT(*) AS cnt FROM $iw[lgd_table] WHERE lgd_no = '".$oid."'";
//echo "sql: ".$sql."<br>";
$row = sql_fetch($sql);
if($row['cnt']){
	$usql = "UPDATE $iw[lgd_table] SET lgd_in_date = '".$oval."' WHERE lgd_no = '".$oid."'";
	//echo "upsql: ".$usql."<br>"; exit;
	$upresult = sql_query($usql);
	if($upresult) $restxt = "ok";
	else $restxt = "fail";
}else{
	$restxt = "none";
}
echo $restxt;
?>