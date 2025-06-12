<?php
include_once("_common.php");

if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) exit;

$BookID = $_GET["BookID"];
$Isbn = $_GET[Isbn];

$row = sql_fetch("select BookID, Isbn from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and Isbn = '$Isbn'");

if (!$row["BookID"] || $row["BookID"] == $BookID) {
	echo "OK";
} else {
	echo "DUP";
}
?>