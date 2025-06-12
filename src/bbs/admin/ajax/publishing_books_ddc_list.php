<?php
include_once("_common.php");

if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) exit;

$code = $_GET["code"];

$result = sql_query("select strSmallName, strSmallCode from $iw[publishing_books_ddc_table] where strLargeCode = '$code' order by strSmallCode asc");

$return_array = array();

while($row = @sql_fetch_array($result)){
	$row_array['strSmallName'] = $row['strSmallName'];
	$row_array['strSmallCode'] = $row['strSmallCode'];
	array_push($return_array, $row_array);
}

echo json_encode($return_array);
?>