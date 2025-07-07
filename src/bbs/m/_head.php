<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
include_once("_head_sub.php");
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
		<meta name="description" content="<?=$st_content;?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">
		<meta property="og:title" content="<?=$st_title;?>">
		<meta property="og:description" content="<?=$st_content;?>">
		
		<title><?=$st_title;?></title>
		
		<link rel="stylesheet" type="text/css" href="<?=$iw['design_path']?>/css/vendor.css?v=20240520" />
		<link rel="stylesheet" type="text/css" href="<?=$iw['design_path']?>/css/site.css?v=20181210" />
		<link rel="stylesheet" type="text/css" href="<?=$iw['design_path']?>/css/theme.css" />
		<script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
		<script type="text/javascript" src="<?=$iw['design_path']?>/js/modernizr.custom.js"></script>
		<script type="text/javascript" src="<?=$iw['design_path']?>/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=$iw['design_path']?>/css/mCustomScrollbar.css" />
		<script type="text/javascript" src="<?=$iw['design_path']?>/js/mCustomScrollbar.min.js"></script>
		<?php if($ep_copy_off == 1){?>
			<script type="text/javascript" src="<?=$iw['design_path']?>/js/copy_off.js"></script>
		<?php } include_once("_theme.php");?>
				
		<?=$st_favicon?>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<script type="text/javascript">
			// Custom Scroll
			$(document).ready(function(){
				scrollCont('.cbp-hrsub');
			});
			function scrollCont(scrollWrap){
				$(scrollWrap).mCustomScrollbar({
					theme:"minimal-dark",
					contentTouchScroll:true, 
					autoScrollOnFocus:true 
				});
			};

			//링크 클릭시 테두리 제거.
			function bluring(){
				if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus();
			}
			document.onfocusin=bluring;
		</script>
		<?php
			if ($iw['store'] == "ep136619553152f0a2d5928db") {
				echo '<script data-ad-client="ca-pub-2101214503488635" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
			}
		?>
	</head>

	<body class="zinpack-style">
<?php
	if($st_menu_position==0){
		include_once("_head_navi.php");
	}else if($st_menu_position==1){
		include_once("_head_navi_2.php");
	}else if($st_menu_position==2){
		include_once("_head_navi_3.php");
	}
?>



