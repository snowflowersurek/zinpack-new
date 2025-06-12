<?php
include_once("_common_guest.php");
include_once("_head_sub.php");

$nt_no = $_GET["idx"];

$sql = "select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'main'";
$row = sql_fetch($sql);
$cg_date = $row[cg_date];
$cg_hit = $row[cg_hit];
$cg_url = $row[cg_url];
$cg_facebook = $row[cg_facebook];
$cg_twitter = $row[cg_twitter];
$cg_googleplus = $row[cg_googleplus];
$cg_pinterest = $row[cg_pinterest];
$cg_linkedin = $row[cg_linkedin];
$cg_delicious = $row[cg_delicious];
$cg_tumblr = $row[cg_tumblr];
$cg_digg = $row[cg_digg];
$cg_stumbleupon = $row[cg_stumbleupon];
$cg_reddit = $row[cg_reddit];
$cg_sinaweibo = $row[cg_sinaweibo];
$cg_qzone = $row[cg_qzone];
$cg_renren = $row[cg_renren];
$cg_tencentweibo = $row[cg_tencentweibo];
$cg_kakaotalk = $row[cg_kakaotalk];
$cg_line = $row[cg_line];

$sql = "select * from $iw[notice_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and nt_display = 1 and nt_no = '$nt_no'";
$row = sql_fetch($sql);
if (!$row["nt_no"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$nt_content = stripslashes($row["nt_content"]);
$nt_subject = stripslashes($row["nt_subject"]);
$nt_datetime = date("Y.m.d", strtotime($row["nt_datetime"]));
$nt_hit = $row["nt_hit"];

$kakaoContent = strip_tags($row["nt_content"]);
$kakaoContent = str_replace("&amp;", "＆",$kakaoContent);
$kakaoContent = str_replace("&quot;", "“",$kakaoContent);
$kakaoContent = str_replace("&#39;", "‘",$kakaoContent);
$kakaoContent = str_replace("&nbsp;", " ",$kakaoContent);
$kakaoContent = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",cut_str(str_replace("\r\n", "",$kakaoContent),200));
$kakaoURL = get_shortURL(urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));

preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($nt_content),$nt_images);
if($nt_images[1][0]){
	$kakaoImg = $iw[url].htmlspecialchars($nt_images[1][0]);
}

$fbTitleQ = $nt_subject;
$fbTitle = strip_tags($nt_subject);
$fbTitle = str_replace("&amp;", "＆",$fbTitle);
$fbTitle = str_replace("&quot;", "“",$fbTitle);
$fbTitle = str_replace("&#39;", "‘",$fbTitle);
$fbTitle = str_replace("&nbsp;", " ",$fbTitle);
$fbTitle = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",$fbTitle);
$fbDescription = $kakaoContent;
$fbImage = $kakaoImg;
$fbUrl = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
include_once("_head_share.php");
?>

<style>
	.image-container		{ position:relative; max-width: 1100px;}
	.image-container img	{ max-width:100%; height: auto; overflow: hidden;}
	.video-container		{ position:relative; padding-bottom:56.25%; padding-top:30px; height:0; overflow: hidden;}
	.video-container video	{ max-width: 1100px; height: auto;}
	.video-container iframe, .video-container object, .video-container embed { position:absolute; top:0; left:0; width:100%; height:100%;}
</style>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="breadcrumb-box input-group">
				<ol class="breadcrumb ">
					<li><a href="<?=$iw[m_path]?>/all_notice_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0012","공지사항");?></a></li>
				</ol>
			</div>
			<!-- 공지사항 상세페이지-->
			<div class="masonry">
				<div class="grid-sizer"></div>

				<div class="masonry-item w-full h-full">
					<div class="box br-theme">
						<h3 class="box-title"><?=$nt_subject?></h3>
						<ul class="list-inline">
							<?if($cg_date==1){?><li><i class="fa fa-calendar"></i> <?=$nt_datetime?></li><?}?>
							<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$nt_hit?></li><?}?>
						</ul>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
				
				<div class="masonry-item w-full h-full">
					<div class="box br-default">
						<div class='image-container'><?=$nt_content?></div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			</div> <!-- /.masonry -->

			<div class="masonry-item w-full h-full">
				<div class="box br-theme text-right">
					<?if($cg_url==1){?><a class="btn btn-theme btn-sm" href="javascript:copy_trackback();" title="<?=national_language($iw[language],"a0301","URL 복사");?>"><i class="fa fa-link fa-lg"></i></a><?}?>
				<?if($st_sns_share==1){?>
					<?if($cg_facebook==1){?><a class="btn btn-theme btn-sm" href="javascript:executeFaceBookLink('<?=$fbUrl?>');" title="<?=national_language($iw[language],"a0305","페이스북");?>"><i class="fa fa-facebook fa-lg"></i></a><?}?>
					<?if($cg_twitter==1){?><a class="btn btn-theme btn-sm" href="javascript:executeTwitterLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$kakaoURL?>');" title="<?=national_language($iw[language],"a0306","트위터");?>"><i class="fa fa-twitter fa-lg"></i></a><?}?>
					<?if($cg_googleplus==1){?><a class="btn btn-theme btn-sm" href="javascript:executeGooglePlusLink('<?=$fbUrl?>');" title="Google+"><i class="fa fa-google-plus fa-lg"></i></a><?}?>
					<?if($cg_pinterest==1){?><a class="btn btn-theme btn-sm" href="javascript:executePinterestLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>','<?=$kakaoImg?>');" title="Pinterest"><i class="fa fa-pinterest fa-lg"></i></a><?}?>
					<?if($cg_linkedin==1){?><a class="btn btn-theme btn-sm" href="javascript:executeLinkedInLink('<?=$fbTitle?>','<?=$kakaoContent?>','<?=$fbUrl?>');" title="Linkedin"><i class="fa fa-linkedin fa-lg"></i></a><?}?>
					<?if($cg_delicious==1){?><a class="btn btn-theme btn-sm" href="javascript:executeDeliciousLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Delicious"><i class="fa fa-delicious fa-lg"></i></a><?}?>
					<?if($cg_tumblr==1){?><a class="btn btn-theme btn-sm" href="javascript:executeTumblrLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Tumblr"><i class="fa fa-tumblr fa-lg"></i></a><?}?>
					<?if($cg_digg==1){?><a class="btn btn-theme btn-sm" href="javascript:executeDiggLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Digg"><i class="fa fa-digg fa-lg"></i></a><?}?>
					<?if($cg_stumbleupon==1){?><a class="btn btn-theme btn-sm" href="javascript:executeStumbleUponLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="StumbleUpon"><i class="fa fa-stumbleupon fa-lg"></i></a><?}?>
					<?if($cg_reddit==1){?><a class="btn btn-theme btn-sm" href="javascript:executeRedditLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Reddit"><i class="fa fa-reddit fa-lg"></i></a><?}?>
					<?if($cg_sinaweibo==1){?><a class="btn btn-theme btn-sm" href="javascript:executeSinaWeiboLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="新浪微博"><i class="fa fa-weibo fa-lg"></i></a><?}?>
					<?if($cg_qzone==1){?><a class="btn btn-theme btn-sm" href="javascript:executeQZoneLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="QQ 空间"><i class="fa fa-qq fa-lg"></i></a><?}?>
					<?if($cg_renren==1){?><a class="btn btn-theme btn-sm" href="javascript:executeRenrenLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="人人网"><i class="fa fa-renren fa-lg"></i></a><?}?>
					<?if($cg_tencentweibo==1){?><a class="btn btn-theme btn-sm" href="javascript:executeTencentWeiboLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="腾讯微博"><i class="fa fa-tencent-weibo fa-lg"></i></a><?}?>
				<?}else{?>
					<?if($cg_facebook==1){?><a href="javascript:executeFaceBookLink('<?=$fbUrl?>');" title="<?=national_language($iw[language],"a0305","페이스북");?>"><img src="<?=$iw[design_path]?>/img/btn_facebook.png" alt=""></a><?}?>
					<?if($cg_twitter==1){?><a href="javascript:executeTwitterLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$kakaoURL?>');" title="<?=national_language($iw[language],"a0306","트위터");?>"><img src="<?=$iw[design_path]?>/img/btn_twitter.png" alt=""></a><?}?>
					<?if($cg_googleplus==1){?><a href="javascript:executeGooglePlusLink('<?=$fbUrl?>');" title="Google+"><img src="<?=$iw[design_path]?>/img/btn_googleplus.png" alt=""></a><?}?>
					<?if($cg_pinterest==1){?><a href="javascript:executePinterestLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>','<?=$kakaoImg?>');" title="Pinterest"><img src="<?=$iw[design_path]?>/img/btn_pinterest.png" alt=""></a><?}?>
					<?if($cg_linkedin==1){?><a href="javascript:executeLinkedInLink('<?=$fbTitle?>','<?=$kakaoContent?>','<?=$fbUrl?>');" title="Linkedin"><img src="<?=$iw[design_path]?>/img/btn_linkedin.png" alt=""></a><?}?>
					<?if($cg_delicious==1){?><a href="javascript:executeDeliciousLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Delicious"><img src="<?=$iw[design_path]?>/img/btn_delicious.png" alt=""></a><?}?>
					<?if($cg_tumblr==1){?><a href="javascript:executeTumblrLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Tumblr"><img src="<?=$iw[design_path]?>/img/btn_tumblr.png" alt=""></a><?}?>
					<?if($cg_digg==1){?><a href="javascript:executeDiggLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Digg"><img src="<?=$iw[design_path]?>/img/btn_digg.png" alt=""></a><?}?>
					<?if($cg_stumbleupon==1){?><a href="javascript:executeStumbleUponLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="StumbleUpon"><img src="<?=$iw[design_path]?>/img/btn_stumbleupon.png" alt=""></a><?}?>
					<?if($cg_reddit==1){?><a href="javascript:executeRedditLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Reddit"><img src="<?=$iw[design_path]?>/img/btn_reddit.png" alt=""></a><?}?>
					<?if($cg_sinaweibo==1){?><a href="javascript:executeSinaWeiboLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="新浪微博"><img src="<?=$iw[design_path]?>/img/btn_weibo.png" alt=""></a><?}?>
					<?if($cg_qzone==1){?><a href="javascript:executeQZoneLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="QQ 空间"><img src="<?=$iw[design_path]?>/img/btn_qq.png" alt=""></a><?}?>
					<?if($cg_renren==1){?><a href="javascript:executeRenrenLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="人人网"><img src="<?=$iw[design_path]?>/img/btn_renren.png" alt=""></a><?}?>
					<?if($cg_tencentweibo==1){?><a href="javascript:executeTencentWeiboLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="腾讯微博"><img src="<?=$iw[design_path]?>/img/btn_tencentweibo.png" alt=""></a><?}?>
				<?}?>
					<?if (preg_match('/iPhone|iPod|iPad|BlackBerry|Android|Windows CE|LG|MOT|SAMSUNG|SonyEricsson|IEMobile|Mobile|lgtelecom|PPC|opera mobi|opera mini|nokia|webos/',$_SERVER['HTTP_USER_AGENT']) ){?>
						<?if($cg_kakaotalk==1){?>
						<a id="kakao-link-btn" href="javascript:;" title="<?=national_language($iw[language],"a0303","카카오톡");?>"><img src="<?=$iw[design_path]?>/img/btn_kakaotalk.png" alt=""></a>
						<script type="text/javascript">
							<?if($kakaoImg){?>
								executeKakaoTalkLinkImg("[<?=$fbTitle?>]\n<?=$kakaoContent?>","<?='http://'.$iw[default_domain].$_SERVER[REQUEST_URI]?>","<?=$kakaoImg?>","<?=$kakaoURL?>");
							<?}else{?>
								executeKakaoTalkLink("[<?=$fbTitle?>]\n<?=$kakaoContent?>","<?='http://'.$iw[default_domain].$_SERVER[REQUEST_URI]?>","<?=$kakaoURL?>");
							<?}?>
						</script>
						<?}?>
						<?if($cg_line==1){?>
						<span class="btn" style="padding:0; height:30px;">
							<script type="text/javascript" src="//media.line.me/js/line-button.js?v=20140411" ></script>
							<script type="text/javascript">
								new media_line_me.LineButton({"pc":false,"lang":"en","type":"c","text":"[<?=$fbTitle?>]<?=$kakaoContent?>","withUrl":true});
							</script>
						</span>
						<?}?>
					<?}?>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->

			<div class="btn-list">
				<a href="<?=$iw['m_path']?>/all_notice_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" class="btn btn-theme"><?=national_language($iw[language],"a0260","목록");?></a>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?=$iw[include_path]?>/js/kakao.link.js"></script>
<?
$sql = "update $iw[notice_table] set
		nt_hit = nt_hit+1
		where ep_code = '$iw[store]' and gp_code = '$iw[group]' and nt_display = 1 and nt_no = '$nt_no'";
sql_query($sql);

include_once("_tail.php");
?>