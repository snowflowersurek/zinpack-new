<?php
include_once("_common.php");
include_once("$iw[include_path]/lib/lib_image_resize.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$sql = "select BookID from $iw[publishing_books_table] where ep_code = '$iw[store]' order by CAST(SUBSTR(BookID, 2) AS UNSIGNED) desc limit 1";
$row = sql_fetch($sql);
if ($row["BookID"]) {
	$num = (int) substr($row["BookID"], 1);
	$BookID = "B".sprintf("%04d", $num + 1);
} else {
	$BookID = "B0001";
}

$upload_path = $_POST[upload_path];
$ep_upload_size = trim(mysql_real_escape_string($_POST[ep_upload_size]));
$cg_code = trim(mysql_real_escape_string($_POST[cg_code]));
$sub_cg_code = trim(mysql_real_escape_string($_POST[sub_cg_code]));
$brand_cg_code = trim(mysql_real_escape_string($_POST[brand_cg_code]));
$stock_status = trim(mysql_real_escape_string($_POST[stock_status]));
$BookName = trim(mysql_real_escape_string($_POST[BookName]));
$sub_title = trim(mysql_real_escape_string($_POST[sub_title]));
$original_title = trim(mysql_real_escape_string($_POST[original_title]));
$authorID = $_POST[authorID];
$translateID = trim(mysql_real_escape_string($_POST[translateID]));
$painterID = trim(mysql_real_escape_string($_POST[painterID]));
$editorID = trim(mysql_real_escape_string($_POST[editorID]));
$Price = trim(mysql_real_escape_string($_POST[Price]));
$BookSize = trim(mysql_real_escape_string($_POST[BookSize]));
$pages = trim(mysql_real_escape_string($_POST[pages]));
$PubDate = trim(mysql_real_escape_string($_POST[PubDate]));
$Isbn = trim(mysql_real_escape_string($_POST[Isbn]));
$SIsbn = trim(mysql_real_escape_string($_POST[SIsbn]));
$bookGubun1 = trim(mysql_real_escape_string($_POST[bookGubun1]));
$bookGubun2 = trim(mysql_real_escape_string($_POST[bookGubun2]));
$bookGubun = $bookGubun1."-".$bookGubun2;
$organ = mysql_real_escape_string($_POST[organ]);
$Intro = mysql_real_escape_string($_POST[Intro]);
$BookList = mysql_real_escape_string($_POST[BookList]);
$PubReview = mysql_real_escape_string($_POST[PubReview]);
$readmore = trim(mysql_real_escape_string($_POST[readmore]));
$themabook = trim(mysql_real_escape_string($_POST[themabook]));
$strPoint = trim(mysql_real_escape_string($_POST[strPoint]));
$soldout = trim(mysql_real_escape_string($_POST[soldout]));
$award = trim(mysql_real_escape_string($_POST[award]));
$Tag = trim(mysql_real_escape_string($_POST[Tag]));
$kyobo_shop_link = trim(mysql_real_escape_string($_POST[kyobo_shop_link]));
$yes24_shop_link = trim(mysql_real_escape_string($_POST[yes24_shop_link]));
$aladin_shop_link = trim(mysql_real_escape_string($_POST[aladin_shop_link]));
$BookImage = trim(mysql_real_escape_string($_POST[BookImage]));
$ebook_yn = trim(mysql_real_escape_string($_POST[ebook_yn]));
$audiobook_yn = trim(mysql_real_escape_string($_POST[audiobook_yn]));
$book_display = trim(mysql_real_escape_string($_POST[book_display]));

$abs_dir = $iw[path].$upload_path;

$RegDate = date("Y-m-d H:i:s");

if($_FILES["NewBookImage"]["name"] && $_FILES["NewBookImage"]["size"] > 1024*1024*$ep_upload_size){
	alert("파일첨부의 최대크기는 {$ep_upload_size}MB 입니다.","");
}else{
	if($_FILES["NewBookImage"]["name"] && $_FILES["NewBookImage"]["size"]>0){
		@mkdir($abs_dir, 0707);
		@chmod($abs_dir, 0707);
		
		$filename = $BookID.".".preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES["NewBookImage"]["name"]);
		
		if(is_file($abs_dir."/".$filename) == true){
			unlink($abs_dir."/".$filename);
		}
		
		if(move_uploaded_file($_FILES["NewBookImage"]["tmp_name"], "{$abs_dir}/{$filename}")){
			$img_size = GetImageSize($abs_dir."/".$filename);
			
			if($img_size[0] > 360){
				$image = new SimpleImage();
				$image->load($abs_dir."/".$filename);
				$image->resizeToWidth(360);
				$image->save($abs_dir."/".$filename);
			}
		} else {
			alert("이미지 첨부에러!","");
			exit;
		}
	}
	
	$sql = "insert into $iw[publishing_books_table] set
			BookID = '$BookID',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			cg_code = '$cg_code',
			sub_cg_code = '$sub_cg_code',
			brand_cg_code = '$brand_cg_code',
			stock_status = '$stock_status',
			BookName = '$BookName',
			sub_title = '$sub_title',
			original_title = '$original_title',
			BookImage = '$filename',
			PubDate = '$PubDate',
			Price = '$Price',
			BookSize = '$BookSize',
			pages = '$pages',
			Isbn = '$Isbn',
			SIsbn = '$SIsbn',
			bookGubun = '$bookGubun',
			organ = '$organ',
			Tag = '$Tag',
			kyobo_shop_link = '$kyobo_shop_link',
			yes24_shop_link = '$yes24_shop_link',
			aladin_shop_link = '$aladin_shop_link',
			Intro = '$Intro',
			BookList = '$BookList',
			PubReview = '$PubReview',
			readmore = '$readmore',
			themabook = '$themabook',
			strPoint = '$strPoint',
			soldout = '$soldout',
			award = '$award',
			strRegDate = '$RegDate',
			Hit = 0,
			book_recommend = 0,
			ebook_yn = '$ebook_yn',
			audiobook_yn = '$audiobook_yn',
			book_display = '$book_display'";
	sql_query($sql);
	
	for ($i=0; $i<count($authorID); $i++) {
		$sql = "insert into $iw[publishing_books_author_table] set
				ep_code = '$iw[store]',
				BookID = '$BookID',
				authorID = '$authorID[$i]',
				authorType = 1";
		sql_query($sql);
	}
	
	if ($translateID != "") {
		$sql = "insert into $iw[publishing_books_author_table] set
				ep_code = '$iw[store]',
				BookID = '$BookID',
				authorID = '$translateID',
				authorType = 2";
		sql_query($sql);
	}
	
	if ($painterID != "") {
		$sql = "insert into $iw[publishing_books_author_table] set
				ep_code = '$iw[store]',
				BookID = '$BookID',
				authorID = '$painterID',
				authorType = 3";
		sql_query($sql);
	}
	
	if ($editorID != "") {
		$sql = "insert into $iw[publishing_books_author_table] set
				ep_code = '$iw[store]',
				BookID = '$BookID',
				authorID = '$editorID',
				authorType = 4";
		sql_query($sql);
	}
	
	if ($authorID == "" && $translateID == "" && $painterID == "" && $editorID == "") {
		$sql = "insert into $iw[publishing_books_author_table] set
		ep_code = '$iw[store]',
		BookID = '$BookID',
		authorID = ''";
		sql_query($sql);
	}
	
	$cg_code_array[] = $cg_code;
	
	if ($sub_cg_code != "") {
		$cg_code_array[] = $sub_cg_code; // Add one element to the array
	}
	
	if ($brand_cg_code != "") {
		$cg_code_array[] = $brand_cg_code; // Add one element to the array
	}
	
	$sql = "insert into $iw[total_data_table] set
			td_code = '$BookID',
			cg_code = '".implode("|", $cg_code_array)."',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			state_sort = 'publishing',
			td_title = '$BookName',
			td_datetime = '$PubDate',
			td_edit_datetime = '$RegDate',
			td_display = '$book_display'
			";
	sql_query($sql);
	
	alert("신규 도서가 추가되었습니다.","$iw[admin_path]/publishing_books_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}
?>