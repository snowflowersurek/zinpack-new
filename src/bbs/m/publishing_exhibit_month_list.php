<?php
include_once("_common.php");

$picture_idx = $_GET["idx"];

$month_array = array();

$current_year = date("Y");
$current_month = date("n");

for ($i=$current_month; $i<=12; $i++) {
	$date_value = $current_year."-".$i;
	$date_text = $current_year."년 ".sprintf("%02d", $i)."월";
	
	$check_row = sql_fetch("select idx from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' and year = $current_year and month = $i");
	$check_idx = $check_row["idx"];
	
	if (!$check_idx) {
		$row_array['date_value'] = $date_value;
		$row_array['date_text'] = $date_text;
		array_push($month_array, $row_array);
	}
}

echo json_encode($month_array);
?>



