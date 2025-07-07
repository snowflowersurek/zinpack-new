<?php
include_once("_common.php");
include_once("_head_sub.php");

$row = sql_fetch("select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]/book";

$BookID = $_GET["item"];

// 도서정보
$sql = "select * from $iw[publishing_books_table] where ep_code = '$iw[store]' and BookID = '$BookID'";
$row = sql_fetch($sql);
if (!$row["BookID"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$BookName = stripslashes($row["BookName"]);
$sub_title = stripslashes($row["sub_title"]);
$original_title = stripslashes($row["original_title"]);
$stock_status = $row["stock_status"];
$default_cg_code = $row["cg_code"];
$brand_cg_code = $row["brand_cg_code"];
$Price = $row["Price"];
$BookSize = $row["BookSize"];
$pages = $row["pages"];
$PubDate = $row["PubDate"];
$Isbn = $row["Isbn"];
$SIsbn = $row["SIsbn"];
$bookGubun = $row["bookGubun"];
if ($bookGubun != "") {
	$arrBookGubun = explode("-", $bookGubun);
}
$organ = stripslashes(str_replace("\n", "<br/>", $row["organ"]));
$rowTag = stripslashes($row["Tag"]);
if(trim($rowTag)==""){
	$Tag = "";
}else{
	$tagary = explode(",",$rowTag);
	$Tag = "";
	for($i=0; $i<count($tagary); $i++){
		$Tag .= "#". trim($tagary[$i])." ";
	}
}
$kyobo_shop_link = stripslashes($row["kyobo_shop_link"]);
$yes24_shop_link = stripslashes($row["yes24_shop_link"]);
$aladin_shop_link = stripslashes($row["aladin_shop_link"]);

$Intro = stripslashes(str_replace("\n", "<br/>", $row["Intro"]));
$BookList = stripslashes(str_replace("\n", "<br/>", $row["BookList"]));
$PubReview = stripslashes($row["PubReview"]);
$readmore = $row["readmore"];
$themabook = $row["themabook"];
$strPoint = $row["strPoint"];
$soldout = $row["soldout"];
$award = $row["award"];
$BookImage = $row["BookImage"];
$Hit = $row["Hit"];
$book_recommend = $row["book_recommend"];
$ebook_yn = $row["ebook_yn"];
$audiobook_yn = $row["audiobook_yn"];
$book_display = $row["book_display"];

// 작가정보
$author_result = sql_query("select A.authorType, A.authorID, B.Author, B.original_name, B.ProFile, B.content_type from $iw[publishing_books_author_table] A inner join $iw[publishing_author_table] B on A.ep_code = B.ep_code and A.authorID = B.AuthorID where A.ep_code = '$iw[store]' and BookID = '$BookID' order by authorType asc");
while($row = @sql_fetch_array($author_result)){
	if ($row["authorType"] == "1") {
		$authorName[] = ($row["original_name"]) ? $row["Author"]."(".$row["original_name"].")" : $row["Author"];
		if ($row[content_type] == 1) {
			$authorProfile[] = stripslashes(str_replace("\n", "<br/>", $row["ProFile"]));
		} else {
			$authorProfile[] = stripslashes($row["ProFile"]);
		}
	} else if ($row["authorType"] == "2") {
		$translateName = $row["Author"];
		if ($row[content_type] == 1) {
			$translateProfile = stripslashes(str_replace("\n", "<br/>", $row["ProFile"]));
		} else {
			$translateProfile = stripslashes($row["ProFile"]);
		}
	} else if ($row["authorType"] == "3") {
		$painterName = $row["Author"];
		if ($row[content_type] == 1) {
			$painterProfile = stripslashes(str_replace("\n", "<br/>", $row["ProFile"]));
		} else {
			$painterProfile = stripslashes($row["ProFile"]);
		}
	} else if ($row["authorType"] == "4") {
		$editorName = $row["Author"];
		if ($row[content_type] == 1) {
			$editorProfile = stripslashes(str_replace("\n", "<br/>", $row["ProFile"]));
		} else {
			$editorProfile = stripslashes($row["ProFile"]);
		}
	}
}

// 십진분류
$ddc_large_row = sql_fetch("select strLargeName from $iw[publishing_books_ddc_table] where strLargeCode = '$arrBookGubun[0]'");
$ddcLargeName = $ddc_large_row["strLargeName"];
$ddc_small_row = sql_fetch("select strSmallName, strSmallCode from $iw[publishing_books_ddc_table] where strLargeCode = '$arrBookGubun[0]' and strSmallCode = '$arrBookGubun[1]'");
$ddcSmallName = $ddc_small_row["strSmallName"];
$ddcSmallCode = $ddc_small_row["strSmallCode"];

$kakaoContent = strip_tags($Intro);
$kakaoContent = str_replace("&amp;", "＆",$kakaoContent);
$kakaoContent = str_replace("&quot;", "“",$kakaoContent);
$kakaoContent = str_replace("&#39;", "‘",$kakaoContent);
$kakaoContent = str_replace("&nbsp;", " ",$kakaoContent);
$kakaoContent = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",cut_str(str_replace("\r\n", "",$kakaoContent),200));

$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$default_cg_code'";
$row2 = sql_fetch($sql2);
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

$kakaoImg = $iw[url].$upload_path.'/'.$BookImage;
$kakaoURL = get_shortURL(urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));

$fbTitleQ = $BookName;
$fbTitle = strip_tags($BookName);
$fbTitle = str_replace("&amp;", "＆",$fbTitle);
$fbTitle = str_replace("&quot;", "“",$fbTitle);
$fbTitle = str_replace("&#39;", "‘",$fbTitle);
$fbTitle = str_replace("&nbsp;", " ",$fbTitle);
$fbTitle = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",$fbTitle);
$fbDescription = $kakaoContent;
$fbImage = $kakaoImg;
$fbUrl = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$BookID' and cm_display = 1";
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
				<?php
					$row2 = sql_fetch(" select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$default_cg_code'");
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
			</span>
		</div>

		<!-- 우측에 이미지가 있는 상세 글 페이지 -->
		
		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}' style="font-size:16px;">
			<div class="grid-sizer"></div>

			<div class="masonry-item w-3 h-full">
				<div class="box box-media br-theme">
					<?php if ($BookImage) {?>
					<img class="media-object img-responsive" src="<?=$upload_path.'/'.$BookImage?>" style='width:100%;'>
					<?php } else {?>
					<img class="media-object img-responsive" src="/design/img/no_image_book.png" style='width:100%;'>
					<?php } ?>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			<div class="masonry-item w-9 h-full">
				<div class="box br-theme">
					<h3 class="media-heading" style="font-weight:bold;">
						<?=$BookName if ($sub_title) echo " <span class='small'>(".$sub_title.")</span>"; ?>
					</h3>
					<div class="content-info">
						<ul class="list-inline">
							<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?php } ?><?php if($cg_comment_view==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend_view==1){?><li><i class="fa fa-thumbs-up"></i> <span id="recommend_board_1"><?=$book_recommend?></span></li><?php }?>
						</ul>
					</div>
					<?php
						if ($original_title) {
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; ".national_language($iw[language],"a0323","원서명")."</strong> : ".$original_title."</div>";
						}
						if (count($authorName) > 0) {
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; ".national_language($iw[language],"a0310","지은이")."</strong> : ".implode(", ", $authorName)."</div>";
						}
						if ($translateName) {
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; ".national_language($iw[language],"a0311","옮긴이")."</strong> : ".$translateName."</div>";
						}
						if ($painterName) {
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; ".national_language($iw[language],"a0312","그린이")."</strong> : ".$painterName."</div>";
						}
						if ($editorName) {
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; ".national_language($iw[language],"a0313","엮은이")."</strong> : ".$editorName."</div>";
						}
						if ($brand_cg_code) {
							$brand_row = sql_fetch(" select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = 'publishing_brand' and cg_code = '$brand_cg_code'");
							$brnad_cg_name = $brand_row[cg_name];
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; ".national_language($iw[language],"a0324","출판사")."</strong> : ".$brnad_cg_name."</div>";
						}
						if ($Price) {
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; ".national_language($iw[language],"a0314","가격")."</strong> : ".number_format($Price).national_language($iw[language],"a0143","원")."</div>";
						}
					?>
					<div class="content-info"><strong class='content-info-head <?=$iw[language]?>'>&bull; <?=national_language($iw[language],"a0315","책꼴/쪽수");?></strong> : 
						<?php 
							if ($BookSize && $pages) {
								echo $BookSize.", ".$pages.national_language($iw[language],"a0322","쪽");
							} else {
								if ($BookSize) {
									echo $BookSize;
								}
								if ($pages) {
									echo $pages.national_language($iw[language],"a0322","쪽");
								}
							}
						?>
					</div>
					<div class="content-info"><strong class='content-info-head <?=$iw[language]?>'>&bull; <?=national_language($iw[language],"a0316","펴낸날");?></strong> : <?=$PubDate?></div>
					<div class="content-info"><strong class='content-info-head <?=$iw[language]?>'>&bull; ISBN</strong> : <?=$Isbn if ($SIsbn) echo ", ".$SIsbn."(세트)"; ?></div>
					<?php
						if($ddcLargeName){
							echo"<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; ".national_language($iw[language],"a0317","십진분류")."</strong> : $ddcLargeName > $ddcSmallName ($ddcSmallCode)</div>";
						}
						if ($stock_status != null) {
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; 도서상태</strong> : ";
							switch ($stock_status) {
								case 0:
									echo "정상";
									break;
								case 1:
									echo "품절";
									break;
								case 2:
									echo "절판";
									break;
							}
							echo "</div>";
						}
						if ($organ) {
							echo "<div class='content-info'><strong class='content-info-head $iw[language]'>&bull; 추천기관</strong> : <div style='padding-left:134px;margin-top:-20px'>$organ</div></div>";
						}
						if ($Tag) {
							echo "<div class='content-info'><strong class='content-info-head'>&bull; 태그</strong> : $Tag</div>";
						}
						if ($ebook_yn) {
							echo "<div class='content-info'><strong class='content-info-head'>&bull; 전자책</strong> : 있음</div>";
						}
						if ($audiobook_yn) {
							echo "<div class='content-info'><strong class='content-info-head'>&bull; 오디오북</strong> : 있음</div>";
						}
					?>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->

			<div class="clearfix"></div>

			<div class="masonry-item w-<?=$hm_view_size?> h-full">
				<?php if($kyobo_shop_link || $yes24_shop_link || $aladin_shop_link) { ?>
				<div class="box br-default">
					<h4 class="box-title">&bull; 도서구매 사이트</h4>
					<hr/>
					<div class="row">
						<div class="col-xs-4 col-lg-2">
							<?php
							if ($kyobo_shop_link) {
								echo "<a href='".$kyobo_shop_link."' target='_blank'><img src='/design/img/logo_kyobo.png' class='img-responsive' /></a>";
							} else {
								echo "<img src='/design/img/logo_kyobo.png' class='img-responsive' style='filter:grayscale(100%);opacity:.5' />";
							}
							?>
						</div>
						<div class="col-xs-4 col-lg-2">
							<?php
							if ($yes24_shop_link) {
								echo "<a href='".$yes24_shop_link."' target='_blank'><img src='/design/img/logo_yes24.png' class='img-responsive' /></a>";
							} else {
								echo "<img src='/design/img/logo_yes24.png' class='img-responsive' style='filter:grayscale(100%);opacity:.5' />";
							}
							?>
						</div>
						<div class="col-xs-4 col-lg-2">
							<?php
							if ($aladin_shop_link) {
								echo "<a href='".$aladin_shop_link."' target='_blank'><img src='/design/img/logo_aladin.png' class='img-responsive' /></a>";
							} else {
								echo "<img src='/design/img/logo_aladin.png' class='img-responsive' style='filter:grayscale(100%);opacity:.5' />";
							}
							?>
						</div>
					</div>
					<hr/>
				</div>
				<?php } ?><?php if($authorName || $translateName || $painterName || $editorName) { ?>
				<div class="box br-default">
					<h3 class="box-title showhide"><i class="fa fa-chevron-circle-down"> </i> <?=national_language($iw[language],"a0318","저자소개");?></h3>
					<div class="content-info image-container">
					<?php
						for ($i=0; $i<count($authorName); $i++) {
							echo "<h4 class='content-info' style='margin-top:20px;'>".national_language($iw[language],"a0310","지은이")." : ".$authorName[$i]."</h4>";
							echo "<div class='content-info'>".$authorProfile[$i]."</div>";
						}
						if ($translateName) {
							echo "<h4 class='content-info' style='margin-top:20px;'>".national_language($iw[language],"a0311","옮긴이")." : ".$translateName."</h4>";
							echo "<div class='content-info'>".$translateProfile."</div>";
						}
						if ($painterName) {
							echo "<h4 class='content-info' style='margin-top:20px;'>".national_language($iw[language],"a0312","그린이")." : ".$painterName."</h4>";
							echo "<div class='content-info'>".$painterProfile."</div>";
						}
						if ($editorName) {
							echo "<h4 class='content-info' style='margin-top:20px;'>".national_language($iw[language],"a0313","엮은이")." : ".$editorName."</h4>";
							echo "<div class='content-info'>".$editorProfile."</div>";
						}
					?>
					</div>
				</div>
				<?php } ?><?php if($Intro){ ?>
				<div class="box br-default">
					<h3 class="box-title showhide"><i class="fa fa-chevron-circle-down"></i> <?=national_language($iw[language],"a0319","책정보 및 내용요약");?></h3>
					<div class="content-info image-container"><?=$Intro?></div>
				</div>
				<?php } ?><?php if($BookList){ ?>
				<div class="box br-default">
					<h3 class="box-title showhide"><i class="fa fa-chevron-circle-down"></i> <?=national_language($iw[language],"a0320","목차");?></h3>
					<div class="content-info image-container"><?=$BookList?></div>
				</div>
				<?php } ?><?php if($PubReview){ ?>
				<div class="box br-default">
					<h3 class="box-title showhide"><i class="fa fa-chevron-circle-down"></i> <?=national_language($iw[language],"a0321","편집자 추천글");?></h3>
					<div class="content-info image-container"><?=$PubReview?></div>
				</div> <!-- /.box -->
				<?php }?>
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

		</div> <!-- /.masonry -->

	</div> <!-- .row -->
</div> <!-- .content -->

<script type="text/javascript">
$(document).ready(function() {
	$(".showhide").on("click", function() {
		if ($(this).next().is(':visible')) {
			$(this).next().hide();
			$(this).children(".fa").removeClass("fa fa-chevron-circle-down").addClass("fa fa-chevron-circle-right");
		} else {
			$(this).next().show();
			$(this).children(".fa").removeClass("fa fa-chevron-circle-right").addClass("fa fa-chevron-circle-down");
		}
		$('.masonry').masonry();
	});
});
</script>

<?php
$sql = "update $iw[publishing_books_table] set
		Hit = Hit+1
		where ep_code = '$iw[store]' and BookID = '$BookID'";
sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_hit = td_hit+1
		where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and td_code = '$BookID'";
sql_query($sql);

include_once("_tail.php");
?>



