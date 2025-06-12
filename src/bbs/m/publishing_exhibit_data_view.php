<?php
include_once("_common.php");
include_once("_head_sub.php");

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 후 이용 가능합니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");

$row = sql_fetch("select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$book_path = "/publishing/$row[ep_nick]/book";

$picture_idx = $_GET["item"];

$sql = "select * from $iw[publishing_exhibit_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx'";
$row = sql_fetch($sql);
if (!$row["picture_idx"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$picture_name = stripslashes($row["picture_name"]);
$special = stripslashes($row["special"]);
$how_many = $row["how_many"];
$size = $row["size"];
$book_id = $row["book_id"];
$contents = stripslashes($row["contents"]);

if ($book_id != "") {
	// 도서정보
	$sql = "select * from $iw[publishing_books_table] where ep_code = '$iw[store]' and BookID = '$book_id'";
	$row = sql_fetch($sql);
	$BookName = stripslashes($row["BookName"]);
	$book_cg_code = $row["cg_code"];
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
	$Intro = $row["Intro"];
	$BookList = $row["BookList"];
	$PubReview = $row["PubReview"];
	$readmore = $row["readmore"];
	$themabook = $row["themabook"];
	$strPoint = $row["strPoint"];
	$soldout = $row["soldout"];
	$award = $row["award"];
	$BookImage = $row["BookImage"];
	
	if ($BookImage != "") {
		$picture_photo = $iw[path].$book_path."/".$BookImage;
	}
	
	// 작가정보
	$author_result = sql_query("select A.authorType, A.authorID, B.Author, B.ProFile, B.content_type from $iw[publishing_books_author_table] A inner join $iw[publishing_author_table] B on A.ep_code = B.AuthorID and A.authorID = B.AuthorID where A.ep_code = '$iw[store]' and BookID = '$book_id' order by authorType asc");
	while($row = @sql_fetch_array($author_result)){
		if ($row["authorType"] == "1") {
			$authorName[] = $row["Author"];
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
}

// SNS
$kakaoContent = strip_tags($ProFile);
$kakaoContent = str_replace("&amp;", "＆",$kakaoContent);
$kakaoContent = str_replace("&quot;", "“",$kakaoContent);
$kakaoContent = str_replace("&#39;", "‘",$kakaoContent);
$kakaoContent = str_replace("&nbsp;", " ",$kakaoContent);
$kakaoContent = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",cut_str(str_replace("\r\n", "",$kakaoContent),200));

$kakaoImg = $picture_photo;
$kakaoURL = get_shortURL(urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));

$fbTitleQ = $picture_name;
$fbTitle = strip_tags($picture_name);
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
	.sd_information td { padding:0 20px 0 0;}
</style>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<?
					$row2 = sql_fetch(" select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = 'exhibit'");
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
		
		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
			<div class="grid-sizer"></div>

			<?php if ($picture_photo != "") {?>
			<div class="masonry-item w-3 h-full">
				<div class="box box-media br-theme">
					<img class="media-object img-responsive" src="<?=$picture_photo?>" style='width:100%;'>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			<div class="masonry-item w-9 h-full">
			<?php } else {?>
			<div class="masonry-item w-full h-full">
			<?php } ?>
				<div class="box br-theme">
					<h3 class="media-heading" style="font-weight:bold;">
						<?=$picture_name?>
					</h3>
					<?php
						if (count($authorName) > 0) {
							echo "<div class='content-info'>지은이 : ".implode(", ", $authorName)."</div>";
						}
						if ($translateName) {
							echo "<div class='content-info'>옮긴이 : ".$translateName."</div>";
						}
						if ($painterName) {
							echo "<div class='content-info'>그린이 : ".$painterName."</div>";
						}
						if ($editorName) {
							echo "<div class='content-info'>엮은이 : ".$editorName."</div>";
						}
					?>
					<div class="content-info">액자수(점) : <?=$how_many?></div>
					<div class="content-info">액자크기 : <?=$size?></div>
					<br>
					<div class="content-info"><a href="publishing_exhibit_application.php?ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$picture_idx?>&name=<?=$picture_name?>" class="btn btn-theme">그림전시 신청하기</a></div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->

			<div class="clearfix"></div>

			<div class="masonry-item w-<?=$hm_view_size?> h-full">
				<div class="box br-default">
					<div class="content-info image-container">
						<table class="table table-bordered">
							<colgroup>
								<col width="16.66%">
								<col width="16.66%">
								<col width="16.66%">
								<col width="16.66%">
								<col width="16.66%">
								<col width="16.66%">
							</colgroup>
							<?php 
							$current_year = date("Y");
							$current_month = date("n");
							?>
							<tr>
								<td class="text-center success media-heading" colspan="6"><h4><strong><?=$current_year?>년 전시일정</strong></h4></td>
							</tr>
							<tr>
							<?php 
							for ($i=1; $i<=12; $i++) {
								$check_row = sql_fetch("select strOrgan from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' and year = $current_year and month = $i");
								$strOrgan = $check_row["strOrgan"];
							?>
								<td style="padding:0;">
									<div class="w-full text-center" style="padding:6px;"><h4><strong><?=$i?>월</strong></h4></div>
									<div class="w-full text-center" style="padding:10px 4px; min-height:60px; border-top:1px solid #ddd;">
								<?php
								if ($i < $current_month) {
									if ($strOrgan == "") {
										echo "&nbsp;";
									} else {
										echo "<span style='display:inline-block; padding:6px 0'>$strOrgan</span>";
									}
								} else {
									if ($strOrgan == "") {
										echo "<a href='publishing_exhibit_application.php?ep=$iw[store]&gp=$iw[group]&idx=$picture_idx&name=$picture_name&month=$i' class='btn btn-theme'>신청하기</a>";
									} else {
										echo "<span style='display:inline-block; padding:6px 0'>$strOrgan</span>";
									}
								}
								?>
									</div>
								</td>
							<?php
								if ($i == 6) {
									echo "</tr><tr>";
								}
							}
							?>
							</tr>
						</table>
					</div>
				</div>
			</div> <!-- /.masonry-item -->

			<div class="masonry-item w-<?=$hm_view_size?> h-full">
				<?php if ($book_id != "") {?>
				<div class="box br-default">
					<h3 class="box-title">저자소개</h3>
					<div class="content-info image-container">
					<?php
						for ($i=0; $i<count($authorName); $i++) {
							echo "<h4 class='content-info' style='margin-top:20px;'>지은이 : ".$authorName[$i]."</h4>";
							echo "<div class='content-info'>".$authorProfile[$i]."</div>";
						}
						if ($translateName) {
							echo "<h4 class='content-info' style='margin-top:20px;'>옮긴이 : ".$translateName."</h4>";
							echo "<div class='content-info'>".$translateProfile."</div>";
						}
						if ($painterName) {
							echo "<h4 class='content-info' style='margin-top:20px;'>그린이 : ".$painterName."</h4>";
							echo "<div class='content-info'>".$painterProfile."</div>";
						}
						if ($editorName) {
							echo "<h4 class='content-info' style='margin-top:20px;'>엮은이 : ".$editorName."</h4>";
							echo "<div class='content-info'>".$editorProfile."</div>";
						}
					?>
					</div>
				</div>
				
				<div class="box br-default">
					<h3 class="box-title">도서 소개</h3>
					<div class="content-info image-container"><?=$Intro?></div>
				</div>
				<?php }?>
				
				<?php if ($contents) { ?>
				<div class="box br-default">
					<h3 class="box-title">그림전시 소개</h3>
					<div class="content-info image-container"><?=$contents?></div>
				</div>
				<?php } ?>
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

			<div class="clearfix"></div>

		</div> <!-- /.masonry -->
	</div> <!-- .row -->
</div> <!-- .content -->

<?
include_once("_tail.php");
?>