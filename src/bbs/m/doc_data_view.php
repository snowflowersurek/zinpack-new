<?php
include_once("_common.php");
include_once("_head_sub.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]";

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}

$dd_code = $_GET["item"];

$sql = "select * from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and dd_display = 1 and dd_code = '$dd_code'";
$row = sql_fetch($sql);
if (!$row["dd_no"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$dd_no = $row["dd_no"];
$dd_code = $row["dd_code"];
$cg_code_view = $row["cg_code"];
$mb_code = $row["mb_code"];
$dd_image = $row["dd_image"];
$dd_file = explode(".",$row["dd_file_name"]);
$dd_file = strtoupper($dd_file[count($dd_file)-1]);
$dd_file_size = $row["dd_file_size"];
$dd_subject = stripslashes($row["dd_subject"]);
$dd_amount = $row["dd_amount"];
$dd_type = $row["dd_type"];
$dd_price = number_format($row["dd_price"]);
$dd_download = $row["dd_download"];
$dd_content = stripslashes($row["dd_content"]);
$dd_hit = number_format($row["dd_hit"]);
$dd_recommend = $row["dd_recommend"];
$dd_datetime = date("Y-m-d", strtotime($row["dd_datetime"]));

$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code_view'";
$row2 = sql_fetch($sql2);
$cg_date = $row2[cg_date];
$cg_writer = $row2[cg_writer];
$cg_hit = $row2[cg_hit];
$cg_comment = $row2[cg_comment];
$cg_recommend = $row2[cg_recommend];
$cg_point_btn = $row2[cg_point_btn];
$cg_comment_view = $row2[cg_comment_view];
$cg_recommend_view = $row2[cg_recommend_view];
$cg_url = $row2[cg_url];
$cg_facebook = $row2[cg_facebook];
$cg_twitter = $row2[cg_twitter];
$cg_googleplus = $row2[cg_googleplus];
$cg_pinterest = $row2[cg_pinterest];
$cg_linkedin = $row2[cg_linkedin];
$cg_delicious = $row2[cg_delicious];
$cg_tumblr = $row2[cg_tumblr];
$cg_digg = $row2[cg_digg];
$cg_stumbleupon = $row2[cg_stumbleupon];
$cg_reddit = $row2[cg_reddit];
$cg_sinaweibo = $row2[cg_sinaweibo];
$cg_qzone = $row2[cg_qzone];
$cg_renren = $row2[cg_renren];
$cg_tencentweibo = $row2[cg_tencentweibo];
$cg_kakaotalk = $row2[cg_kakaotalk];
$cg_line = $row2[cg_line];
$cg_level_comment = $row2[cg_level_comment];

if ($row2[cg_level_read] != 10 && $iw[level]=="guest"){
	alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
}else if($row2[cg_level_read] != 10 && $iw[mb_level] < $row2[cg_level_read]){
	alert(national_language($iw[language],"a0169","해당 카테고리에 읽기 권한이 없습니다."),$iw[m_path]."/doc_data_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$cg_code_view);
}

$kakaoContent = strip_tags($row["dd_content"]);
$kakaoContent = str_replace("&amp;", "＆",$kakaoContent);
$kakaoContent = str_replace("&quot;", "“",$kakaoContent);
$kakaoContent = str_replace("&#39;", "‘",$kakaoContent);
$kakaoContent = str_replace("&nbsp;", " ",$kakaoContent);
$kakaoContent = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",cut_str(str_replace("\r\n", "",$kakaoContent),200));
if($row["dd_image"]){
	$kakaoImg = $iw[url].$upload_path."/".$dd_code."/".$row["dd_image"];
}
$kakaoURL = get_shortURL(urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));

$sql2 = "select mb_nick from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'";
$row2 = sql_fetch($sql2);

if($iw['anonymity']==0){
	$mb_nick = $row2["mb_nick"];
}else{
	$mb_nick = cut_str($row2["mb_nick"],4,"")."*****";
}

$fbTitleQ = $dd_subject;
$fbTitle = strip_tags($dd_subject);
$fbTitle = str_replace("&amp;", "＆",$fbTitle);
$fbTitle = str_replace("&quot;", "“",$fbTitle);
$fbTitle = str_replace("&#39;", "‘",$fbTitle);
$fbTitle = str_replace("&nbsp;", " ",$fbTitle);
$fbTitle = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",$fbTitle);
$fbDescription = $kakaoContent;
$fbImage = $kakaoImg;
$fbUrl = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$dd_code' and cm_display = 1";
$result2 = sql_fetch($sql2);
$reply_count = number_format($result2[cnt]);

include_once("_head_share.php");
?>
<style>
	.image-container		{ position:relative; max-width: 1100px;}
	.image-container img	{ max-width:100%; height: auto; overflow: hidden;}
	.video-container		{ position:relative; padding-bottom:56.25%; padding-top:30px; height:0; overflow: hidden;}
	.video-container video	{ max-width: 1100px; height: auto;}
	.video-container iframe, .video-container object, .video-container embed { position:absolute; top:0; left:0; width:100%; height:100%;}
	.sd_information td { padding:0 20px 0 0;}
</style>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<?
					$row2 = sql_fetch(" select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code_view'");
					$hm_code = $row2[hm_code];
					$hm_view_scrap = $row2[hm_view_scrap];
					$hm_view_size = $row2[hm_view_size];
					$hm_view_scrap_mobile = $row2[hm_view_scrap_mobile];
					if($hm_view_size==12){$hm_view_size = "full";}
					if ($hm_view_scrap!=1){$hm_view_size = "full";}
					if (preg_match('/iPad/',$_SERVER['HTTP_USER_AGENT']) ){$hm_view_size = "full";}

					$navi_cate = "<li><a href='".$iw[m_path]."/all_data_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".stripslashes($row2[hm_name])."</a></li>";
					echo $navi_cate;
				?>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/doc_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-cloud-download fa-lg"></i></a>
			</span>
		</div>
		<!-- 우측에 이미지가 있는 상세 글 페이지 -->
		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
		<div class="grid-sizer"></div>

			<div class="masonry-item w-4 h-full">
				<div class="box box-media br-theme">
					<?if($dd_image){?>
						<img class="media-object img-responsive" src="<?=$upload_path?>/<?=$dd_code?>/<?=$dd_image?>" alt="">
					<?}?>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			
			<div class="masonry-item w-8 h-full">
				<div class="box br-theme">
					<h3 class="media-heading"><?=$dd_subject?></h3>
					<br>
					<div class="content-info">
						<ul class="list-inline">
							<li><i class="fa fa-file"></i> <?=$dd_file?> ( <?=number_format($dd_file_size/1024/1000, 1)?> MB )</li>
							<?if($cg_date==1){?><li><i class="fa fa-calendar"></i> <?=$dd_datetime?></li><?}?>
							<?if($cg_writer==1){?><li><i class="fa fa-user"></i> <?=$mb_nick?></li><?}?>
							<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$dd_hit?></li><?}?>
							<?if($cg_comment_view==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
							<?if($cg_recommend_view==1){?><li><i class="fa fa-thumbs-up"></i> <span id="recommend_board_1"><?=$dd_recommend?></span></li><?}?>
						</ul>
						<ul class="list-inline">
							<li><strong><?=national_language($iw[language],"a0174","파일분량");?> : </strong><span class="label label-info"><?=$dd_amount?> <?if($dd_type==1){?><?=national_language($iw[language],"a0167","쪽");?><?}else if($dd_type==2){?><?=national_language($iw[language],"a0168","분");?><?}?></span></li>
							<li><strong><?=national_language($iw[language],"a0156","다운로드 유효기간");?> : </strong><span class="label label-info"><?if($dd_download=="0"){?><?=national_language($iw[language],"a0157","무제한");?><?}else{?><?=$dd_download?> <?=national_language($iw[language],"a0158","일");?><?}?></span></li>
							<li><strong><?=national_language($iw[language],"a0151","자료가격");?> : </strong><span class="label label-info"><?if($dd_price=="0"){?><?=national_language($iw[language],"a0265","무료");?><?}else{?><?=$dd_price?> Point<?}?></span></li>
						</ul>
					</div>
					<div class="col-md-6">
					<?if($cg_point_btn==1){?>
						<div class="input-group">
							<input type="text" class="form-control" id="support_point" maxlength="9" value="0" onKeyUp="onlyNum();">
							<span class="input-group-addon"><?=national_language($iw[language],"a0199","Point 후원하기");?></span>
						</div>
						<br>
					<?}?>
					</div>
					<div class="col-md-6">
						<div class="pull-right">
							<a href="javascript:downloadDoc('doc_data_download.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$dd_code?>')" class="btn btn-theme"><?=national_language($iw[language],"a0145","다운로드");?></a>
						</div>
					</div>
					<div class="clearfix"></div>
					
					
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			
			
			<div class="masonry-item w-<?=$hm_view_size?> h-full">
				<div class="box br-default">
					<div class='image-container'><?=$dd_content?></div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->

			<?if($hm_view_scrap_mobile==1){?>
				<style>
					@media (min-width:768px){
						.scrap-wrap	{display:;}
					}
					@media (max-width:767px){
						.scrap-wrap	{display:none;}
					}
				</style>
			<?}?>
			<?
				if ($hm_view_scrap==1){
					$scrap_type = "view";
					include_once("all_home_scrap.php");
				}
			?>
			
			<div class="masonry-item w-full h-full">
				<div class="box br-theme text-right">
					<?if($cg_recommend_view==1){?><a class="btn btn-theme btn-sm" href="javascript:recommend_click('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','board','<?=$dd_no?>');" title="추천"><i class="fa fa-thumbs-up fa-lg"></i> <span id="recommend_board_2"><?=$dd_recommend?></span></a><?}?>
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

		<div class="clearfix"></div>

		</div> <!-- /.masonry -->

		<?if($cg_comment_view==1){?>
		<!-- /댓글 -->
		<div class="grid-sizer"></div>
		<!-- / 베스트댓글 리스트 -->
		<?
			$sql2 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$dd_code' and cm_display = 1 and cm_recomment = 0 and cm_recommend > 2 order by cm_recommend desc limit 0, 3";
			$result2 = sql_query($sql2);

			$i=0;
			while($row2 = @sql_fetch_array($result2)){
				$cm_no = $row2["cm_no"];
				$sql3 = "select mb_nick from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$row2[mb_code]'";
				$row3 = sql_fetch($sql3);
		?>
			<div id="best_comment_<?=$i?>" class="masonry-item w-full h-full">
				<div class="box br-theme">
					<h4 class="media-heading box-title">
						<i class="fa fa-trophy"></i>
						<?if($iw['anonymity']==0){
							echo $row3["mb_nick"];
						}else{
							echo cut_str($row3["mb_nick"],4,"")."*****";
						}?>
						<small><?=$row2["cm_datetime"]?></small>
						<a href="javascript:recommend_click('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','comment','<?=$cm_no?>');"><i class="fa fa-thumbs-up"></i> <span id="recommend_comment_<?=$cm_no?>"><?=$row2["cm_recommend"]?></span></a>
						<?if($row2["cm_secret"]==1){?>
							<i class="fa fa-lock"></i>
						<?}?>
					</h4>
					<p><?if($mb_code == $iw[member] || ($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row2["cm_secret"]!=1 || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row2["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
					<?if ((($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all")) && ($iw[mb_level] >= $row2[cg_level_comment])){?>
						<p><a href="javascript:comment_move('#best_comment_<?=$i?>','<?=$cm_no?>');">[<?=national_language($iw[language],"a0266","댓글");?>]</a></p>
					<?}?>
				</div>
			</div>
		<?
			$i++;
			}
		?>
		<!-- / 댓글 쓰기 -->
		<?if ((($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all")) && ($iw[mb_level] >= $row2[cg_level_comment])){?>
			<form id="cm_form" name="cm_form" action="<?=$iw['m_path']?>/all_data_comment.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<input type="hidden" name="cm_code" value="<?=$dd_code?>" />
					<input type="hidden" name="cm_recomment" value="0" />
					<textarea name="cm_content" rows="4" class="form-control"></textarea>
					<div class="btn-list">
						<a href="javascript:check_form();" class="btn btn-primary"><?=national_language($iw[language],"a0266","댓글");?></a>
						<label class="middle">
							<input type="checkbox" name="cm_secret" value="1">
							<span class="lbl"> 비밀글</span>
						</label>
					</div>
				</div>
			</div>
			</form>
		<?}else{?>
			<?
				if($iw[mb_level] < $row2[cg_level_comment]) $comment_login = "덧글쓰기 권한이 없습니다.";
				if($iw[gp_level] == "gp_guest" && $iw[group] != "all") $comment_login = "그룹 가입 후  댓글 작성이 가능합니다.";
				if($iw[level] == "guest" && $iw[group] == "all") $comment_login = "로그인 후 댓글 작성이 가능합니다.";
			?>
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<textarea name="cm_content" rows="4" class="form-control" onclick="javascript:comment_login('<?=$comment_login?>');"></textarea>
					<div class="btn-list">
						<a href="javascript:comment_login();" class="btn btn-primary"><?=national_language($iw[language],"a0266","댓글");?></a>
					</div>
				</div>
			</div>
		<?}?>
		
		<form id="re_form" name="re_form" action="<?=$iw['m_path']?>/all_data_comment.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" style="display:none;">
			<div class="box br-theme">
				<input type="hidden" name="cm_code" value="<?=$dd_code?>" />
				<input type="hidden" name="cm_recomment" value="" />
				<textarea name="cm_content" rows="2" class="form-control"></textarea>
				<div class="btn-list">
					<a href="javascript:re_check_form();" class="btn btn-primary"><?=national_language($iw[language],"a0266","댓글");?></a>
					<label class="middle">
						<input type="checkbox" name="cm_secret" value="1">
						<span class="lbl"> 비밀글</span>
					</label>
				</div>
			</div>
		</form>

		<!-- / 댓글 리스트 -->
		<?
			$sql2 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$dd_code' and cm_display = 1 and cm_recomment = 0 order by cm_no desc";
			$result2 = sql_query($sql2);

			$i=0;
			while($row2 = @sql_fetch_array($result2)){
				$cm_no = $row2["cm_no"];
				$sql3 = "select mb_nick from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$row2[mb_code]'";
				$row3 = sql_fetch($sql3);
		?>
			<div id="comment_<?=$i?>" class="masonry-item w-full h-full">
				<div class="box br-theme">
					<h4 class="media-heading box-title">
						<?if($iw['anonymity']==0){
							echo $row3["mb_nick"];
						}else{
							echo cut_str($row3["mb_nick"],4,"")."*****";
						}?>
						<small><?=stripslashes($row2["cm_datetime"])?></small>
						<a href="javascript:recommend_click('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','comment','<?=$cm_no?>');"><i class="fa fa-thumbs-up"></i> <span id="recommend_comment_<?=$cm_no?>"><?=$row2["cm_recommend"]?></span></a>
						<?if($row2["mb_code"] == $iw[member]){?>
							<a href="javascript:comment_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$dd_code?>','<?=$cm_no?>');" class="text-danger"><i class="fa fa-times-circle"></i></a>
						<?}?>
						<?if($row2["cm_secret"]==1){?>
							<i class="fa fa-lock"></i>
						<?}?>
					</h4>
					<p><?if($mb_code == $iw[member] || ($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row2["cm_secret"]!=1 || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row2["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
					<?if ((($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all")) && ($iw[mb_level] >= $row2[cg_level_comment])){?>
						<p><a href="javascript:comment_move('#comment_<?=$i?>','<?=$cm_no?>');">[<?=national_language($iw[language],"a0266","댓글");?>]</a></p>
					<?}?>
					<?
						$sql4 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$dd_code' and cm_display = 1 and cm_recomment = '$cm_no' order by cm_no asc";
						$result4 = sql_query($sql4);

						while($row4 = @sql_fetch_array($result4)){
							$cm_no = $row4["cm_no"];
							$sql3 = "select mb_nick from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$row4[mb_code]'";
							$row3 = sql_fetch($sql3);
					?>
						<div class="box br-theme">
							<h4 class="media-heading box-title">
								<?if($iw['anonymity']==0){
									echo $row3["mb_nick"];
								}else{
									echo cut_str($row3["mb_nick"],4,"")."*****";
								}?>
								<small><?=$row4["cm_datetime"]?></small>
								<?if($row4["mb_code"] == $iw[member]){?>
									<a href="javascript:comment_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$dd_code?>','<?=$cm_no?>');" class="text-danger"><i class="fa fa-times-circle"></i></a>
								<?}?>
								<?if($row4["cm_secret"]==1){?>
									<i class="fa fa-lock"></i>
								<?}?>
							</h4>
							<p><?if($mb_code == $iw[member] || ($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row4["cm_secret"]!=1 || $row4["mb_code"] == $iw[member] || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row4["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
						</div>
					<?
						}
					?>
				</div>
			</div>
		<?
			$i++;
			}
		?>
		<?}?>
		<div class="clearfix"></div>
	</div> <!-- .row -->
</div> <!-- .content -->

<script type="text/javascript">
	function comment_login(txt){
		alert(txt);
		return;
	}
	function comment_delete(type,ep,gp,item,co) { 
		if (confirm("<?=national_language($iw[language],'a0197','댓글을 삭제하시겠습니까?');?>")) {
			location.href="all_data_comment_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&item="+item+"&co="+co;
		}
	}
	function check_form() {
		if (cm_form.cm_content.value == "") {
			alert("<?=national_language($iw[language],'a0177','내용을 입력하여 주십시오.');?>");
			cm_form.cm_content.focus();
			return;
		}
		cm_form.submit();
	}

	function re_check_form() {
		if (re_form.cm_content.value == "") {
			alert("<?=national_language($iw[language],'a0177','내용을 입력하여 주십시오.');?>");
			re_form.cm_content.focus();
			return;
		}
		re_form.submit();
	}
	function comment_move(comment,re) {
		re_form.style.display = "";
		re_form.cm_content.value = "";
		re_form.cm_recomment.value = re;
		$('#re_form').appendTo(comment);
		re_form.cm_content.focus();
	}

	function onlyNum(){
		var e1 = event.srcElement;
		var num ="0123456789";
		event.returnValue = true;
	   
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
			if(i == 0 && e1.value.charAt(i) == "0")
			event.returnValue = false;
		}
		if (!event.returnValue)
			e1.value="0";
	}
	function downloadDoc(url) {
		if(document.getElementById('support_point')){
			location.href=url+"&support="+document.getElementById('support_point').value;
		}else{
			location.href=url+"&support=0";
		}
	}

	function recommend_click(type,ep,gp,board,idx) { 

		if (window.XMLHttpRequest)
		{ // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{ // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if (board == "board"){
					document.getElementById("recommend_board_1").innerHTML = xmlhttp.responseText;
					document.getElementById("recommend_board_2").innerHTML = xmlhttp.responseText;
				}else{
					document.getElementById("recommend_comment_"+idx).innerHTML =xmlhttp.responseText;
				}
			}
		}
		xmlhttp.open("GET","all_data_recommend.php?type="+type+"&ep="+ep+"&gp="+gp+"&board="+board+"&idx="+idx,true);
		xmlhttp.send();
	}
</script>

<?
$sql = "update $iw[doc_data_table] set
		dd_hit = dd_hit+1
		where ep_code = '$iw[store]' and gp_code='$iw[group]' and dd_display = 1 and dd_code = '$dd_code'";
sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_hit = td_hit+1
		where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and td_code = '$dd_code'";
sql_query($sql);

include_once("_tail.php");
?>