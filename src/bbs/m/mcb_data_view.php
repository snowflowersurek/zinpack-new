<?php
include_once("_common.php");
include_once("_head_sub.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]";

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}

$md_code = $_GET["item"];

// 게시글 정보 조회
$sql_mcb = "select * from {$iw['mcb_data_table']} where ep_code = ? and gp_code = ? and md_display = 1 and md_code = ?";
$stmt_mcb = mysqli_prepare($db_conn, $sql_mcb);
mysqli_stmt_bind_param($stmt_mcb, "sss", $iw['store'], $iw['group'], $md_code);
mysqli_stmt_execute($stmt_mcb);
$result_mcb = mysqli_stmt_get_result($stmt_mcb);
$row = mysqli_fetch_assoc($result_mcb);
mysqli_stmt_close($stmt_mcb);

if (!$row["md_no"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$md_no = $row["md_no"];
$mb_code = $row["mb_code"];
$cg_code_view = $row["cg_code"];
$md_type = $row["md_type"];
$md_subject = stripslashes($row["md_subject"]);
$md_youtube = $row["md_youtube"];
$md_attach = $row["md_attach"];
$md_attach_name = $row["md_attach_name"];
$md_ip = $row["md_ip"];
$md_hit = number_format($row["md_hit"]);
$md_recommend = $row["md_recommend"];
$md_datetime = $row["md_datetime"];
$md_padding = $row["md_padding"];
$md_secret = $row["md_secret"];

if($md_secret==1 && !($mb_code == $iw[member] || ($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all"))){
	alert("비밀글입니다.","");
}

$kakaoContent = strip_tags($row["md_content"]);
$kakaoContent = str_replace("&amp;", "＆",$kakaoContent);
$kakaoContent = str_replace("&quot;", "“",$kakaoContent);
$kakaoContent = str_replace("&#39;", "‘",$kakaoContent);
$kakaoContent = str_replace("&nbsp;", " ",$kakaoContent);
$kakaoContent = preg_replace("/[^A-Za-z90-9가-힣＆"“'']/i"," ",cut_str(str_replace("\n", "",$kakaoContent),200));
$kakaoURL = get_shortURL(urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));

if($md_type == 1){
	$md_content = str_replace("\n", "<br/>", $row["md_content"]);
	$pattern = "/(mms|http|HTTP|https|HTTPS|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/"; 
	$md_content = preg_replace( $pattern, "<a href=\"\\1://\\2\" target=\"_blank\">\\1://\\2</a>", $md_content );

	if(strstr($md_youtube, "youtu.be")){
		$youtube = explode("youtu.be/",$md_youtube);
		if(strstr($youtube[1], "?")){
			$youtube2 = explode("?",$youtube[1]);
			$youtube_code = $youtube2[0];
		}else{
			$youtube_code = $youtube[1];
		}
	}else if(strstr($md_youtube, "youtube.com")){
		$youtube = explode("v=",$md_youtube);
		if(strstr($youtube[1], "&")){
			$youtube2 = explode("&",$youtube[1]);
			$youtube_code = $youtube2[0];
		}else{
			$youtube_code = $youtube[1];
		}
	}

	if($row["md_file_1"]){
		$kakaoImg = $iw[url].$upload_path."/".$md_code."/".$row["md_file_1"];
	}else if($youtube_code){
		$kakaoImg = "http://img.youtube.com/vi/".$youtube_code."/0.jpg";
	}
}else if($md_type == 2){
	$md_content = $row["md_content"];
	preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($md_content),$md_images);
	if($md_images[1][0]){
		$kakaoImg = $iw[url].htmlspecialchars($md_images[1][0]);
	}
}

// 카테고리 정보 조회
$sql_cat = "select * from {$iw['category_table']} where ep_code = ? and gp_code= ? and state_sort = ? and cg_code = ?";
$stmt_cat = mysqli_prepare($db_conn, $sql_cat);
mysqli_stmt_bind_param($stmt_cat, "ssss", $iw['store'], $iw['group'], $iw['type'], $cg_code_view);
mysqli_stmt_execute($stmt_cat);
$result_cat = mysqli_stmt_get_result($stmt_cat);
$row2 = mysqli_fetch_assoc($result_cat);
mysqli_stmt_close($stmt_cat);
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
}else if($row2[cg_level_read] != 10 && $iw[mb_level] < $row2[cg_level_read] && $mb_code != $iw[member]){
	alert(national_language($iw[language],"a0169","해당 카테고리에 읽기 권한이 없습니다."),"");
}

// 작성자 닉네임 조회
$sql_nick = "select mb_nick from {$iw['member_table']} where ep_code = ? and mb_code = ?";
$stmt_nick = mysqli_prepare($db_conn, $sql_nick);
mysqli_stmt_bind_param($stmt_nick, "ss", $iw['store'], $mb_code);
mysqli_stmt_execute($stmt_nick);
$result_nick = mysqli_stmt_get_result($stmt_nick);
$row2_nick = mysqli_fetch_assoc($result_nick);
mysqli_stmt_close($stmt_nick);

if($iw['anonymity']==0){
	$mb_nick = $row2_nick["mb_nick"];
}else{
	$mb_nick = cut_str($row2_nick["mb_nick"],4,"")."*****";
}

$fbTitleQ = $md_subject;
$fbTitle = strip_tags($md_subject);
$fbTitle = str_replace("&amp;", "＆",$fbTitle);
$fbTitle = str_replace("&quot;", "“",$fbTitle);
$fbTitle = str_replace("&#39;", "‘",$fbTitle);
$fbTitle = str_replace("&nbsp;", " ",$fbTitle);
$fbTitle = preg_replace("/[^A-Za-z90-9가-힣＆"“'']/i"," ",$fbTitle);
$fbDescription = $kakaoContent;
$fbImage = $kakaoImg;
$fbUrl = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

// 댓글 수 조회
$sql_reply = "select count(*) as cnt from {$iw['comment_table']} where ep_code = ? and gp_code= ? and state_sort = ? and cm_code = ? and cm_display = 1";
$stmt_reply = mysqli_prepare($db_conn, $sql_reply);
mysqli_stmt_bind_param($stmt_reply, "ssss", $iw['store'], $iw['group'], $iw['type'], $md_code);
mysqli_stmt_execute($stmt_reply);
$result_reply = mysqli_stmt_get_result($stmt_reply);
$row_reply = mysqli_fetch_assoc($result_reply);
mysqli_stmt_close($stmt_reply);
$reply_count = number_format($row_reply['cnt']);

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
			<ol class="breadcrumb">
				<?php
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
				<a class="btn btn-theme" href="javascript:rss_feed('<?=$iw[cookie_domain]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');" title="RSS Feed"><i class="fa fa-rss fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw['m_path']?>/mcb_data_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$hm_code?>" title="<?=national_language($iw[language],"a0198","글쓰기");?>"><i class="fa fa-pencil fa-lg"></i></a>
			</span>
		</div>

		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
		<div class="grid-sizer"></div>
		
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<h3 class="media-heading"><?=$md_subject if{?> <i class="fa fa-lock"></i><?php }?></h3>
					<div class="content-info">
						<ul class="list-inline">
							<?php if($cg_date==1){?><li><i class="fa fa-calendar"></i> <?=$md_datetime?></li><?php } ?><?php if($cg_writer==1){?><li><i class="fa fa-user"></i> <?=$mb_nick?></li><?php } ?><?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$md_hit?></li><?php } ?><?php if($cg_comment_view==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend_view==1){?><li><i class="fa fa-thumbs-up"></i> <span id="recommend_board_1"><?=$md_recommend?></span></li><?php } ?><?php if($md_attach){?>
								<li><i class="fa fa-file"></i> <a href="javascript:downloadFile('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$md_code?>','<?=$cg_code_view?>');"><?=$md_attach_name?></a> </li>
							<?php }?>
						</ul>
						<ul class="list-inline">
							<?php if($cg_point_btn==1){?>
								<li><a class="btn btn-theme btn-sm" href="<?=$iw[m_path]?>/mcb_point_give.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&seller=<?=$mb_code?>"><?=national_language($iw[language],"a0199","PV 후원하기");?></a></li>
							<?php }?>
						</ul>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->	
		
			<div class="masonry-item w-<?=$hm_view_size?> h-full">
				<div class="box br-default <?php if{?>box-padding-pc<?php }?>">
				<?php
					if($md_type == 1){
						$sql = "select * from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and md_display = 1 and md_code = '$md_code'";
						$row = sql_fetch($sql);
						for ($i=1; $i<=10; $i++) {
							if($row["md_file_".$i]){
								?><div class="image-container"><img src="<?=$upload_path?>/<?=$md_code?>/<?=$row["md_file_".$i]?>" /></div><?php }
						}
						if($youtube_code){
				?>
							<div class="video-container">
								<iframe type="text/html" width="1000" height="400" src="//www.youtube.com/embed/<?=$youtube_code?>?autoplay=1" frameborder="0"/></iframe>
							</div>
				<?php
						}
						echo stripslashes($md_content);
					}else if($md_type == 2){
						echo "<div class='image-container'>".stripslashes($md_content)."</div>";
					}
				?>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			
			<?php if($hm_view_scrap_mobile==1){?>
				<style>
					@media (min-width:768px){
						.scrap-wrap	{display:;}
					}
					@media (max-width:767px){
						.scrap-wrap	{display:none;}
					}
				</style>
			<?php } ?><?php if($hm_view_scrap==1){
					$scrap_type = "view";
					include_once("all_home_scrap.php");
				}
			?>

		
			<div class="masonry-item w-full h-full">
				<a href="<?=$iw['m_path']?>/all_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$hm_code?>" class="btn btn-theme"><?=national_language($iw[language],"a0260","목록");?></a>
				<?php if($mb_code == $iw[member] || ($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all")){?>
					<a href="<?=$iw['m_path']?>/mcb_data_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$md_code?>" class="btn btn-theme"><?=national_language($iw[language],"a0256","수정");?></a>
					<a href="javascript:board_delete('<?=$iw[type]?>', '<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>','<?=$md_code?>');" class="btn btn-theme"><?=national_language($iw[language],"a0257","삭제");?></a>
				<?php } ?><?php if(($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all")){?>
					<a href="<?=$iw['m_path']?>/mcb_data_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$hm_code?>" class="btn btn-theme"><?=national_language($iw[language],"a0258","글쓰기");?></a>
				<?php }?>
			</div>
	
		<div class="clearfix"></div>

		</div> <!-- /.masonry -->

		<?php if($cg_comment_view==1){?>
		<!-- /댓글 -->
		<div class="grid-sizer"></div>
		<!-- / 베스트댓글 리스트 -->
		<?php
			$sql_best = "SELECT a.*, b.mb_nick FROM {$iw['comment_table']} a LEFT JOIN {$iw['member_table']} b ON a.mb_code = b.mb_code
						 WHERE a.ep_code = ? AND a.gp_code = ? AND a.state_sort = ? AND a.cm_code = ? 
						 AND a.cm_display = 1 AND a.cm_recomment = 0 AND a.cm_recommend > 2 
						 ORDER BY a.cm_recommend desc LIMIT 3";
			$stmt_best = mysqli_prepare($db_conn, $sql_best);
            mysqli_stmt_bind_param($stmt_best, "ssss", $iw['store'], $iw['group'], $iw['type'], $md_code);
            mysqli_stmt_execute($stmt_best);
			$result_best = mysqli_stmt_get_result($stmt_best);

			$i=0;
			while($row2 = mysqli_fetch_assoc($result_best)){
				$cm_no = $row2["cm_no"];
                $mb_nick = ($iw['anonymity']==0) ? $row2["mb_nick"] : cut_str($row2["mb_nick"],4,"")."*****";
		?>
			<div id="best_comment_<?=$i?>" class="masonry-item w-full h-full">
				<div class="box br-theme">
					<h4 class="media-heading box-title">
						<i class="fa fa-trophy"></i>
						<?=$mb_nick?>
						<small><?=$row2["cm_datetime"]?></small>
						<a href="javascript:recommend_click('<?=$iw['type']?>','<?=$iw['store']?>','<?=$iw['group']?>','comment','<?=$cm_no?>');"><i class="fa fa-thumbs-up"></i> <span id="recommend_comment_<?=$cm_no?>"><?=$row2["cm_recommend"]?></span></a>
						<?php if($row2["cm_secret"]==1){?> <i class="fa fa-lock"></i> <?php }?>
					</h4>
					<p><?php if{?><?=stripslashes($row2["cm_content"])?><?php }else{?>비밀글입니다.<?php }?></p>
					<?php if((($iw['gp_level'] != "gp_guest" && $iw['group'] != "all") || ($iw['level'] != "guest" && $iw['group'] == "all")) && ($iw['mb_level'] >= $cg_level_comment)){?>
						<p><a href="javascript:comment_move('#best_comment_<?=$i?>','<?=$cm_no?>');">[<?=national_language($iw['language'],"a0266","댓글");?>]</a></p>
					<?php }?>
				</div>
			</div>
		<?php
			$i++;
			}
            mysqli_stmt_close($stmt_best);
		?>
		<!-- / 댓글 쓰기 -->
		<?php if((($iw['gp_level'] != "gp_guest" && $iw['group'] != "all") || ($iw['level'] != "guest" && $iw['group'] == "all")) && ($iw['mb_level'] >= $cg_level_comment)){?>
			<form id="cm_form" name="cm_form" action="<?=$iw['m_path']?>/all_data_comment.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<input type="hidden" name="cm_code" value="<?=$md_code?>" />
					<input type="hidden" name="cm_recomment" value="0" />
					<textarea name="cm_content" rows="4" class="form-control"></textarea>
					<div class="btn-list">
						<a href="javascript:check_form();" class="btn btn-theme"><?=national_language($iw['language'],"a0266","댓글");?></a>
						<label class="middle">
							<input type="checkbox" name="cm_secret" value="1">
							<span class="lbl"> <?=national_language($iw['language'],"a0020","비밀글");?></span>
						</label>
					</div>
				</div>
			</div>
			</form>
		<?php }else{
				if($iw['mb_level'] < $cg_level_comment) $comment_login = "덧글쓰기 권한이 없습니다.";
				if($iw['gp_level'] == "gp_guest" && $iw['group'] != "all") $comment_login = "그룹 가입 후  댓글 작성이 가능합니다.";
				if($iw['level'] == "guest" && $iw['group'] == "all") $comment_login = "로그인 후 댓글 작성이 가능합니다.";
			?>
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<textarea name="cm_content" rows="4" class="form-control" onclick="javascript:comment_login('<?=$comment_login?>');"></textarea>
					<div class="btn-list">
						<a href="javascript:comment_login();" class="btn btn-theme"><?=national_language($iw['language'],"a0266","댓글");?></a>
					</div>
				</div>
			</div>
		<?php }?>
		
		<form id="re_form" name="re_form" action="<?=$iw['m_path']?>/all_data_comment.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" style="display:none;">
			<div class="box br-theme">
				<input type="hidden" name="cm_code" value="<?=$md_code?>" />
				<input type="hidden" name="cm_recomment" value="" />
				<textarea name="cm_content" rows="2" class="form-control"></textarea>
				<div class="btn-list">
					<a href="javascript:re_check_form();" class="btn btn-theme"><?=national_language($iw['language'],"a0266","댓글");?></a>
					<label class="middle">
						<input type="checkbox" name="cm_secret" value="1">
						<span class="lbl"> <?=national_language($iw['language'],"a0020","비밀글");?></span>
					</label>
				</div>
			</div>
		</form>

		<!-- / 댓글 리스트 -->
		<?php
			$sql_comment = "SELECT a.*, b.mb_nick FROM {$iw['comment_table']} a LEFT JOIN {$iw['member_table']} b ON a.mb_code = b.mb_code
                            WHERE a.ep_code = ? AND a.gp_code = ? AND a.state_sort = ? AND a.cm_code = ? 
                            AND a.cm_display = 1 AND a.cm_recomment = 0 ORDER BY a.cm_no desc";
			$stmt_comment = mysqli_prepare($db_conn, $sql_comment);
            mysqli_stmt_bind_param($stmt_comment, "ssss", $iw['store'], $iw['group'], $iw['type'], $md_code);
            mysqli_stmt_execute($stmt_comment);
			$result2 = mysqli_stmt_get_result($stmt_comment);

			$i=0;
			while($row2 = mysqli_fetch_assoc($result2)){
				$cm_no_parent = $row2["cm_no"];
				$mb_nick = ($iw['anonymity']==0) ? $row2["mb_nick"] : cut_str($row2["mb_nick"],4,"")."*****";
		?>
			<div id="comment_<?=$i?>" class="masonry-item w-full h-full">
				<div class="box br-theme">
					<h4 class="media-heading box-title">
						<?=$mb_nick?>
						<small><?=$row2["cm_datetime"]?></small>
						<a href="javascript:recommend_click('<?=$iw['type']?>','<?=$iw['store']?>','<?=$iw['group']?>','comment','<?=$cm_no_parent?>');"><i class="fa fa-thumbs-up"></i> <span id="recommend_comment_<?=$cm_no_parent?>"><?=$row2["cm_recommend"]?></span></a>
						<?php if($row2["mb_code"] == $iw['member']){?>
							<a href="javascript:comment_delete('<?=$iw['type']?>','<?=$iw['store']?>','<?=$iw['group']?>','<?=$md_code?>','<?=$cm_no_parent?>');" class="text-danger"><i class="fa fa-times-circle"></i></a>
						<?php } ?><?php if($row2["cm_secret"]==1){?> <i class="fa fa-lock"></i> <?php }?>
					</h4>
					<p><?php if{?><?=stripslashes($row2["cm_content"])?><?php }else{?>비밀글입니다.<?php }?></p>
					<?php if((($iw['gp_level'] != "gp_guest" && $iw['group'] != "all") || ($iw['level'] != "guest" && $iw['group'] == "all")) && ($iw['mb_level'] >= $cg_level_comment)){?>
						<p><a href="javascript:comment_move('#comment_<?=$i?>','<?=$cm_no_parent?>');">[<?=national_language($iw['language'],"a0266","댓글");?>]</a></p>
					<?php }
						$sql_re = "SELECT a.*, b.mb_nick FROM {$iw['comment_table']} a LEFT JOIN {$iw['member_table']} b ON a.mb_code = b.mb_code
                                   WHERE a.ep_code = ? AND a.gp_code = ? AND a.state_sort = ? AND a.cm_code = ? 
                                   AND a.cm_display = 1 AND a.cm_recomment = ? ORDER BY a.cm_no asc";
						$stmt_re = mysqli_prepare($db_conn, $sql_re);
                        mysqli_stmt_bind_param($stmt_re, "ssssi", $iw['store'], $iw['group'], $iw['type'], $md_code, $cm_no_parent);
                        mysqli_stmt_execute($stmt_re);
						$result4 = mysqli_stmt_get_result($stmt_re);

						while($row4 = mysqli_fetch_assoc($result4)){
							$cm_no = $row4["cm_no"];
							$mb_nick_re = ($iw['anonymity']==0) ? $row4["mb_nick"] : cut_str($row4["mb_nick"],4,"")."*****";
					?>
						<div class="box br-theme">
							<h4 class="media-heading box-title">
								<?=$mb_nick_re?>
								<small><?=$row4["cm_datetime"]?></small>
								<?php if($row4["mb_code"] == $iw['member']){?>
									<a href="javascript:comment_delete('<?=$iw['type']?>','<?=$iw['store']?>','<?=$iw['group']?>','<?=$md_code?>','<?=$cm_no?>');" class="text-danger"><i class="fa fa-times-circle"></i></a>
								<?php } ?><?php if($row4["cm_secret"]==1){?> <i class="fa fa-lock"></i> <?php }?>
							</h4>
							<p><?php if{?><?=stripslashes($row4["cm_content"])?><?php }else{?>비밀글입니다.<?php }?></p>
						</div>
					<?php
						}
                        mysqli_stmt_close($stmt_re);
					?>
				</div>
			</div>
		<?php
			$i++;
			}
            mysqli_stmt_close($stmt_comment);
		 }?>
		<div class="clearfix"></div>
	</div>
</div>

<script type="text/javascript">
	function board_delete(type,ep,gp,menu,item) { 
		if (confirm("<?=national_language($iw[language],'a0196','정말 삭제하시겠습니까?');?>")) {
			location.href="mcb_data_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu+"&item="+item;
		}
	}
	
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

	function downloadFile(type, ep, gp, no, cg) {
		location.href="mcb_data_download.php?type="+type+"&ep="+ep+"&gp="+gp+"&mcb="+no+"&cg="+cg;
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

<?php
// 조회수 업데이트
$sql_hit_mcb = "update {$iw['mcb_data_table']} set md_hit = md_hit+1 where ep_code = ? and gp_code= ? and md_code = ?";
$stmt_hit_mcb = mysqli_prepare($db_conn, $sql_hit_mcb);
mysqli_stmt_bind_param($stmt_hit_mcb, "sss", $iw['store'], $iw['group'], $md_code);
mysqli_stmt_execute($stmt_hit_mcb);
mysqli_stmt_close($stmt_hit_mcb);

$sql_hit_total = "update {$iw['total_data_table']} set td_hit = td_hit+1 where ep_code = ? and gp_code = ? and state_sort = ? and td_code = ?";
$stmt_hit_total = mysqli_prepare($db_conn, $sql_hit_total);
mysqli_stmt_bind_param($stmt_hit_total, "ssss", $iw['store'], $iw['group'], $iw['type'], $md_code);
mysqli_stmt_execute($stmt_hit_total);
mysqli_stmt_close($stmt_hit_total);

include_once("_tail.php");
?>




