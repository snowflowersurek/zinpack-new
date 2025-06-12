<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
include_once("_head_sub.php");

$bd_code = $_GET["idx"];
$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if ($row["bd_no"]) $book_seller = "YES";

$file = sql_fetch(" select count(*) as cnt from $iw[book_buy_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and mb_code = '$iw[member]' and bd_code = '$bd_code'");
if ($file[cnt]){
	$book_buyer = "YES";
}

if($bd_code ="bd516624006534cdf6244f60" || $bd_code ="bd88246383954179a04877f7") $book_seller = "YES";

if ($iw[level] != "admin" && $iw[gp_level] != "gp_admin" && $book_seller != "YES" && $book_buyer != "YES") alert("잘못된 접근입니다!","");
?>

<div class="container"><!-- Main container -->