<?php
include_once("_common.php");
if ($iw[type] != "publishing") alert("잘못된 접근입니다!","");

$excelFileName = iconv("UTF-8", "EUC-KR", "도서정보_").date('Ymd').".xls";

header( "Content-type: application/vnd.ms-excel;charset=UTF-8");
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
header( "Pragma: public" );
header( "Content-Disposition: attachment; filename=".$excelFileName );

echo "
<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>
<table border=1>
<tr style='background-color:#ccc; font-weight:bold;'>
<td>도서명</td>
<td>시리즈</td>
<td>분류</td>
<td>지은이</td>
<td>그린이</td>
<td>옮긴이</td>
<td>엮은이</td>
<td>펴낸날</td>
<td>가격</td>
<td>쪽수</td>
<td>개별ISBN</td>
<td>세트ISBN</td>
<td>십진분류</td>
<td>크기</td>
<td>추천기관</td>
<td>키워드</td>
<td>내용</td>
</tr>
";

$sql = "select * from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and book_display = 1 order by BookName asc";
$result = sql_query($sql);

while($row = @sql_fetch_array($result)){
	$BookID = $row["BookID"];
	
	if ($BookID != "") {
		$BookName = stripslashes($row["BookName"]);
		$cg_code = $row["cg_code"];
		$Price = $row["Price"];
		$BookSize = stripslashes($row["BookSize"]);
		$pages = $row["pages"];
		$PubDate = $row["PubDate"];
		$Isbn = $row["Isbn"];
		$SIsbn = $row["SIsbn"];
		$bookGubun = $row["bookGubun"];
		if ($bookGubun != "") {
			$arrBookGubun = explode("-", $bookGubun);
		}
		$organ = stripslashes($row["organ"]);
		$Intro = stripslashes($row["Intro"]);
		$BookImage = $row["BookImage"];
		$Tag = stripslashes($row["Tag"]);
		
		// 도서분류
		$row2 = sql_fetch("select cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'");
		$book_category = $row2["cg_name"];
		
		// 작가정보
		$authorName = array();
		$translateName = "";
		$painterName = "";
		$editorName = "";
		$author_result = sql_query("select A.authorType, A.authorID, B.Author from $iw[publishing_books_author_table] A inner join $iw[publishing_author_table] B on A.ep_code = B.ep_code and A.authorID = B.AuthorID where A.ep_code = '$iw[store]' and BookID = '$BookID' order by authorType asc");
		while($row = @sql_fetch_array($author_result)){
			if ($row["authorType"] == "1") {
				$authorName[] = $row["Author"];
			} else if ($row["authorType"] == "2") {
				$translateName = $row["Author"];
			} else if ($row["authorType"] == "3") {
				$painterName = $row["Author"];
			} else if ($row["authorType"] == "4") {
				$editorName = $row["Author"];
			}
		}
		
		// 십진분류
		$ddc_large_row = sql_fetch("select strLargeName from $iw[publishing_books_ddc_table] where strLargeCode = '$arrBookGubun[0]'");
		$ddcLargeName = $ddc_large_row["strLargeName"];
		$ddc_small_row = sql_fetch("select strSmallName, strSmallCode from $iw[publishing_books_ddc_table] where strLargeCode = '$arrBookGubun[0]' and strSmallCode = '$arrBookGubun[1]'");
		$ddcSmallName = $ddc_small_row["strSmallName"];
		$ddcSmallCode = $ddc_small_row["strSmallCode"];
		
		if ($ddcSmallCode != "") {
			$ddc = $ddcLargeName." > ".$ddcSmallName." (".$ddcSmallCode.")";
		} else {
			$ddc = $ddcLargeName;
		}
		
		echo "
		<tr>
		<td style='mso-number-format:\@;'>$BookName</td>
		<td style='mso-number-format:\@;'></td>
		<td style='mso-number-format:\@;'>$book_category</td>
		<td style='mso-number-format:\@;'>".implode(", ", $authorName)."</td>
		<td style='mso-number-format:\@;'>$painterName</td>
		<td style='mso-number-format:\@;'>$translateName</td>
		<td style='mso-number-format:\@;'>$editorName</td>
		<td style='mso-number-format:\@;'>$PubDate</td>
		<td style='mso-number-format:\#\,\#\#;'>$Price</td>
		<td style='mso-number-format:\@;'>$pages</td>
		<td style='mso-number-format:\@;'>$Isbn</td>
		<td style='mso-number-format:\@;'>$SIsbn</td>
		<td style='mso-number-format:\@;'>$ddc</td>
		<td style='mso-number-format:\@;'>$BookSize</td>
		<td style='mso-number-format:\@;'>$organ</td>
		<td style='mso-number-format:\@;'>$Tag</td>
		<td style='mso-number-format:\@;'>$Intro</td>
		</tr>";
	}
}

echo "
</table>";
die;

goto_url("$_SERVER[HTTP_REFERER]");

?>