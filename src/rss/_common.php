<?php $iw_path = ".."; // common.php 의 상대 경로
include_once("$iw_path/include/common.php");

if ($_GET['cg']){
	$iw['category'] = $_GET['cg'];
}else{
	$iw['category'] = "all";
}

$start_line = 0;
if ($_GET['limit']){
	if($_GET['limit']>100){
		$end_line = 100;
	}else{
		$end_line = $_GET['limit'];
	}
}else{
	$end_line = 10;
}
?>



