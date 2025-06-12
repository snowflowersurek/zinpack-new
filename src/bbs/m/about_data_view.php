<?php
include_once("_common.php");
include_once("_head_sub.php");

$ad_code = $_GET["item"];

$sql = "select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'main'";
$row = sql_fetch($sql);
$cg_level_comment = $row[cg_level_comment];
$cg_comment_view = $row[cg_comment_view];
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

$sql = "select * from $iw[about_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and ad_display = 1 and ad_code = '$ad_code'";
$row = sql_fetch($sql);
if (!$row["ad_no"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$ad_navigation = stripslashes($row["ad_navigation"]);
$ad_subject = stripslashes($row["ad_subject"]);
$ad_content = stripslashes($row["ad_content"]);

$kakaoContent = strip_tags($row["ad_content"]);
$kakaoContent = str_replace("&amp;", "＆",$kakaoContent);
$kakaoContent = str_replace("&quot;", "“",$kakaoContent);
$kakaoContent = str_replace("&#39;", "‘",$kakaoContent);
$kakaoContent = str_replace("&nbsp;", " ",$kakaoContent);
$kakaoContent = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",cut_str(str_replace("\r\n", "",$kakaoContent),200));
$kakaoURL = get_shortURL(urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));

preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($ad_content),$ad_images);
if($ad_images[1][0]){
	$kakaoImg = $iw[url].htmlspecialchars($ad_images[1][0]);
}

$fbTitleQ = $ad_subject;
$fbTitle = strip_tags($ad_subject);
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
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="http://<?=$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]?>"><?=$ad_navigation?></a></li>
			</ol>
		</div>
		<div class="masonry">
			<div class="grid-sizer"></div>
			<div class="masonry-item w-full h-full">
				<div class="box br-default">
					<div class='image-container'><?=$ad_content?></div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->

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
		</div> <!-- /.masonry -->

		<?if($cg_comment_view==1){?>
		<!-- /댓글 -->
		<div class="grid-sizer"></div>
		<!-- / 베스트댓글 리스트 -->
		<?
			$sql2 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$ad_code' and cm_display = 1 and cm_recomment = 0 and cm_recommend > 2 order by cm_recommend desc";
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
					<p><?if(($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row2["cm_secret"]!=1 || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row2["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
					<?if ((($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all")) && ($iw[mb_level] >= $cg_level_comment)){?>
						<p><a href="javascript:comment_move('#best_comment_<?=$i?>','<?=$cm_no?>');">[<?=national_language($iw[language],"a0266","댓글");?>]</a></p>
					<?}?>
				</div>
			</div>
		<?
			$i++;
			}
		?>
		<!-- / 댓글 쓰기 -->
		<?if ((($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all"))){?>
			<form id="cm_form" name="cm_form" action="<?=$iw['m_path']?>/all_data_comment.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<input type="hidden" name="cm_code" value="<?=$ad_code?>" />
					<input type="hidden" name="cm_recomment" value="0" />
					<textarea name="cm_content" rows="4" class="form-control"></textarea>
					<div class="btn-list">
						<a href="javascript:check_form();" class="btn btn-theme"><?=national_language($iw[language],"a0266","댓글");?></a>
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
				if($iw[mb_level] < $cg_level_comment) $comment_login = "덧글쓰기 권한이 없습니다.";
				if($iw[gp_level] == "gp_guest" && $iw[group] != "all") $comment_login = "그룹 가입 후  댓글 작성이 가능합니다.";
				if($iw[level] == "guest" && $iw[group] == "all") $comment_login = "로그인 후 댓글 작성이 가능합니다.";
			?>
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<textarea name="cm_content" rows="4" class="form-control" onclick="javascript:comment_login('<?=$comment_login?>');"></textarea>
					<div class="btn-list">
						<a href="javascript:comment_login();" class="btn btn-theme"><?=national_language($iw[language],"a0266","댓글");?></a>
					</div>
				</div>
			</div>
		<?}?>

		<form id="re_form" name="re_form" action="<?=$iw['m_path']?>/all_data_comment.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" style="display:none;">
			<div class="box br-theme">
				<input type="hidden" name="cm_code" value="<?=$ad_code?>" />
				<input type="hidden" name="cm_recomment" value="" />
				<textarea name="cm_content" rows="2" class="form-control"></textarea>
				<div class="btn-list">
					<a href="javascript:re_check_form();" class="btn btn-theme"><?=national_language($iw[language],"a0266","댓글");?></a>
					<label class="middle">
						<input type="checkbox" name="cm_secret" value="1">
						<span class="lbl"> 비밀글</span>
					</label>
				</div>
			</div>
		</form>

		<!-- / 댓글 리스트 -->
		<?
			$sql2 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$ad_code' and cm_display = 1 and cm_recomment = 0 order by cm_no desc";
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
						<small><?=$row2["cm_datetime"]?></small>
						<a href="javascript:recommend_click('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','comment','<?=$cm_no?>');"><i class="fa fa-thumbs-up"></i> <span id="recommend_comment_<?=$cm_no?>"><?=$row2["cm_recommend"]?></span></a>
						<?if($row2["mb_code"] == $iw[member]){?>
							<a href="javascript:comment_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$ad_code?>','<?=$cm_no?>');" class="text-danger"><i class="fa fa-times-circle"></i></a>
						<?}?>
						<?if($row2["cm_secret"]==1){?>
							<i class="fa fa-lock"></i>
						<?}?>
					</h4>
					<p><?if(($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row2["cm_secret"]!=1 || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row2["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
					<p><a href="javascript:comment_move('#comment_<?=$i?>','<?=$cm_no?>');">[<?=national_language($iw[language],"a0266","댓글");?>]</a></p>
					<?
						$sql4 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$ad_code' and cm_display = 1 and cm_recomment = '$cm_no' order by cm_no asc";
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
									<a href="javascript:comment_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$ad_code?>','<?=$cm_no?>');" class="text-danger"><i class="fa fa-times-circle"></i></a>
								<?}?>
								<?if($row4["cm_secret"]==1){?>
									<i class="fa fa-lock"></i>
								<?}?>
							</h4>
							<p><?if(($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row4["cm_secret"]!=1 || $row4["mb_code"] == $iw[member] || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row4["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
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
	</div>
</div>

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
include_once("_tail.php");
?>
