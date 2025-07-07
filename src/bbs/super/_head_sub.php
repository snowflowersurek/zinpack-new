<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>ZINPACK 관리페이지</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="summary here">
    <meta name="author" content="">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Le styles -->
	<link href="/favicon.ico" rel="icon">
	<!-- Bootstrap 5.3.3 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="<?php echo $iw['design_path']; ?>/css/bootstrap-colorpicker.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $iw['design_path']; ?>/css/style.css?ver=20170410" />
	<!-- Font Awesome 6.5.2 (스타일 충돌 방지를 위해 마지막에 로드) -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- Bootstrap 5.3.3 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	
	<!-- 기존 스크립트 (jQuery 의존성으로 인해 일부는 작동하지 않을 수 있음) -->
	<script type="text/javascript" src="<?php echo $iw['design_path']; ?>/js/jquery-3.7.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $iw['design_path']; ?>/js/bootstrap-colorpicker.min.js"></script>
	<script type="text/javascript" src="<?php echo $iw['design_path']; ?>/js/jquery.nestable.min.js"></script>
	<script type="text/javascript" src="<?php echo $iw['design_path']; ?>/js/site.js"></script>

	<link rel="stylesheet" href="<?php echo $iw['design_path']; ?>/css/ladda.css">
	<script src="<?php echo $iw['design_path']; ?>/js/spin.js"></script>
	<script src="<?php echo $iw['design_path']; ?>/js/ladda.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
		<script src="<?php echo $iw['design_path']; ?>/js/html5shiv.js"></script>
		<script src="<?php echo $iw['design_path']; ?>/js/respond.min.js"></script>
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

	<style>
		.html_edit_wrap img	{ max-width:100%; height:auto;}
	</style>
	
	<style>
	  /* active 및 non-active 메뉴의 Font Awesome 아이콘 깨짐 방지 */
	  .nav-list > li > a > i.fa-fw,
	  .nav-list > li > .submenu > li > a > i {
		font-family: "Font Awesome 6 Free" !important;
		font-weight: 900 !important;
	  }
	</style>
</head>
<body>



