<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

	$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
	$favicon_path = "/main/$row[ep_nick]";

	if ($iw[group] == "all"){
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
	<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/bootstrap-colorpicker.min.css" />
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/style.css?ver=20240823" />
	<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/book_admin.css" />

	<script src="//code.jquery.com/jquery.min.js"></script>
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/bootstrap-colorpicker.min.js"></script>
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/jquery.nestable.min.js"></script>
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/site.js"></script>

	<link rel="stylesheet" href="<?=$iw[design_path]?>/css/ladda.css">
	<script src="<?=$iw[design_path]?>/js/spin.js"></script>
	<script src="<?=$iw[design_path]?>/js/ladda.js"></script>
    
	<?=$st_favicon?>

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

	<style>
		.html_edit_wrap img	{ max-width:100%; height:auto;}
	</style>
</head>
<body>