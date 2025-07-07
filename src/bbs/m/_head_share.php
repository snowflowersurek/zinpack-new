<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html itemscope itemtype="http://schema.org/Article" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title><?=$fbTitleQ;?></title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<meta property="og:title"          content="<?=$fbTitleQ;?>"/>
		<meta property="og:description"    content="<?=$fbDescription;?>"/>
		<meta property="og:image"          content="<?=$fbImage;?>"/>
		<meta itemprop="name"		 	   content="<?=$fbTitleQ;?>"/> 
		<meta itemprop="description" 	   content="<?=$fbDescription;?>"/> 
		<meta itemprop="image" 			   content="<?=$fbImage;?>"/>
		
		<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/vendor.css?ver=20240520" />
		<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/site.css" />
		<link rel="stylesheet" type="text/css" href="<?=$iw[design_path]?>/css/theme.css" />
		<script src="//code.jquery.com/jquery-3.7.1.min.js"></script>
		<script type="text/javascript" src="<?=$iw[design_path]?>/js/modernizr.custom.js"></script>
		<script type="text/javascript" src="<?=$iw[design_path]?>/js/bootstrap.min.js"></script>
		<?php if($ep_copy_off == 1){?>
			<script type="text/javascript" src="<?=$iw[design_path]?>/js/copy_off.js"></script>
		<?php } include_once("_theme.php");?>

		<?=$st_favicon?>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<script type="text/javascript"> //링크 클릭시 테두리 제거.
			function bluring(){
				if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus();
			}
			document.onfocusin=bluring;

			function executeFaceBookLink(url)
			{
				window.open("https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent(url), "Facebook" , "status = 1, height = 300, width = 500, resizable = 1");
			}
			function executeTwitterLink(title,url)
			{
				window.open("https://twitter.com/intent/tweet?text="+encodeURIComponent(title)+"+++"+encodeURIComponent(url), "Twitter" , "status = 1, height = 300, width = 500, resizable = 1");
			}
			function executeGooglePlusLink(url)
			{
				window.open("https://plus.google.com/share?url="+encodeURIComponent(url), "Google+" , "status = 1, height = 470, width = 500, resizable = 1");
			}
			function executeLinkedInLink(title,content,url)
			{
				window.open("https://www.linkedin.com/shareArticle?mini=true&url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title)+"&summary="+encodeURIComponent(content), "Linkedin" , "status = 1, height = 470, width = 500, resizable = 1");
			}
			function executePinterestLink(title,url,img)
			{
				window.open("https://pinterest.com/pin/create/button/?url="+encodeURIComponent(url)+"&media="+encodeURIComponent(img)+"&description="+encodeURIComponent(title), "Pinterest" , "status = 1, height = 320, width = 750, resizable = 1");
			}
			function executeDeliciousLink(title,url)
			{
				window.open("http://del.icio.us/post?url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title), "Delicious" , "status = 1, height = 600, width = 750, resizable = 1");
			}
			function executeTumblrLink(title,url)
			{
				window.open("http://www.tumblr.com/share?v=3&u="+encodeURIComponent(url)+"&t="+encodeURIComponent(title), "Tumblr" , "status = 1, height = 400, width = 800, resizable = 1");
			}
			function executeDiggLink(title,url)
			{
				window.open("http://digg.com/submit?phase=2&url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title), "Digg" , "status = 1, height = 550, width = 750, resizable = 1");
			}
			function executeStumbleUponLink(title,url)
			{
				window.open("http://www.stumbleupon.com/submit?url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title), "StumbleUpon" , "status = 1, height = 320, width = 750, resizable = 1");
			}
			function executeRedditLink(title,url)
			{
				window.open("http://reddit.com/submit?phase=2&url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title), "Reddit" , "status = 1, height = 900, width = 600, resizable = 1");
			}
			function executeSinaWeiboLink(title,url)
			{
				window.open("http://service.weibo.com/share/share.php?url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title), "SinaWeibo" , "status = 1, height = 320, width = 750, resizable = 1");
			}
			function executeQZoneLink(title,url)
			{
				window.open("http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title), "QZone" , "status = 1, height = 500, width = 600, resizable = 1");
			}
			function executeRenrenLink(title,url)
			{
				window.open("http://share.renren.com/share/buttonshare.do?link="+encodeURIComponent(url)+"&title="+encodeURIComponent(title), "Renren" , "status = 1, height = 320, width = 750, resizable = 1");
			}
			function executeTencentWeiboLink(title,url)
			{
				window.open("http://v.t.qq.com/share/share.php?url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title), "TencentWeibo" , "status = 1, height = 320, width = 750, resizable = 1");
			}

			function executeKakaoTalkLinkImg(title, url, img, shorturl)
			{
				Kakao.init('6378606505f0b4e2d4a02401a0720bf8');
				Kakao.Link.createDefaultButton({
					container: '#kakao-link-btn',
					objectType: 'feed',
					content: {
						title: title,
						description: title,
						imageUrl: img,
						link: {
							mobileWebUrl: url,
						}
					},
				});
			}
			function executeKakaoTalkLink(title,url,shorturl)
			{
				Kakao.init('6378606505f0b4e2d4a02401a0720bf8');
				Kakao.Link.createTalkLinkButton({
					container: '#kakao-link-btn',
					label: title,
					webLink : {
						text: shorturl,
						url: url
					}
				});
			}
		</script>
		<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
		<?php
			if ($iw[store] == "ep136619553152f0a2d5928db") {
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



