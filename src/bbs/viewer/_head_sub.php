<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="">
		
		<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/book_viewer.css" />

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<script language="JavaScript"> //링크 클릭시 테두리 제거.
			function bluring(){
				if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus();
			}
			document.onfocusin=bluring;
		</script>
	</head>

	<body>