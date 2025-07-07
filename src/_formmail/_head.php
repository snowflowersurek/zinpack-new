<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>신청서</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="summary here">
    <meta name="author" content="">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Le styles -->
	<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/bootstrap-colorpicker.min.css" />
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/book_admin.css" />

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
		<script src="<?=$iw[design_path]?>/js/html5shiv.js"></script>
		<script src="<?=$iw[design_path]?>/js/respond.min.js"></script>
    <![endif]-->
	
	<!--[if lt IE 8]>
		<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
	<![endif]-->

	<script language="JavaScript"> //링크 클릭시 테두리 제거.
		function bluring(){
			if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus();
		}
		document.onfocusin=bluring;
	</script>
</head>
<body>



