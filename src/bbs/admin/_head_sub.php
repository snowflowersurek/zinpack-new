<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

	$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
	$favicon_path = "/main/$row[ep_nick]";

	if ($iw['group'] == "all"){
		$favicon_path .= "/all";
	}else{
		$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
		$favicon_path .= "/$row[gp_nick]";
	}
	$favicon_path .= "/_images";

	$row = sql_fetch("select * from $iw[setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$st_favicon = $row["st_favicon"];

	if($st_favicon){
		$st_favicon = "<link rel='shortcut icon' href='".$favicon_path."/".$st_favicon."' />";
	}else{
		$st_favicon = "";
	}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>관리페이지</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="summary here">
    <meta name="author" content="">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Le styles -->
	<link href="/favicon.ico" rel="icon">
	<link rel="stylesheet" type="text/css" href="../../design/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="../../design/css/style.css?ver=20240823" />
	<link rel="stylesheet" type="text/css" href="../../design/css/book_admin.css" />
	<link rel="stylesheet" type="text/css" href="../../design/css/font-awesome.min.css" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet'> 

	<script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
	<script type="text/javascript" src="../../design/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../../design/js/bootstrap-colorpicker.min.js"></script>

	<script type="text/javascript" src="../../design/js/jquery.nestable.min.js"></script>
	<script type="text/javascript" src="../../design/js/site.js"></script>

	<link rel="stylesheet" href="../../design/css/ladda.css">
	<script src="../../design/js/spin.js"></script>
	<script src="../../design/js/ladda.js"></script>

	<?php echo $st_favicon; ?>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
		<script src="../../design/js/html5shiv.js"></script>
		<script src="../../design/js/respond.min.js"></script>
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
		body > div.ladda-overlay {
			z-index: 10000 !important;
		}
	</style>
</head>
<body>



