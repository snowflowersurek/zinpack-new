<?php
include_once("_common.php");
include_once("_head_sub.php");

$row = sql_fetch("select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/publishing/$row[ep_nick]/author";

$AuthorID = $_GET["item"];

$sql = "select * from $iw[publishing_author_table] where ep_code = '$iw[store]' and AuthorID = '$AuthorID'";
$row = sql_fetch($sql);
if (!$row["AuthorID"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$Author = stripslashes($row["Author"]);
$original_name = stripslashes($row["original_name"]);
if ($row[content_type] == 1) {
	$ProFile = stripslashes(str_replace("\n", "<br/>", $row["ProFile"]));
} else {
	$ProFile = stripslashes($row["ProFile"]);
}
$phone = $row["phone"];
$Photo = $row["Photo"];
$Hit = $row["Hit"];
$author_recommend = $row["author_recommend"];
$author_display = $row["author_display"];

$kakaoContent = strip_tags($ProFile);
$kakaoContent = str_replace("&amp;", "＆",$kakaoContent);
$kakaoContent = str_replace("&quot;", "“",$kakaoContent);
$kakaoContent = str_replace("&#39;", "‘",$kakaoContent);
$kakaoContent = str_replace("&nbsp;", " ",$kakaoContent);
$kakaoContent = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",cut_str(str_replace("\r\n", "",$kakaoContent),200));

$kakaoImg = $iw[url].$upload_path.'/'.$Photo;
$kakaoURL = get_shortURL(urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));

$fbTitleQ = $Author;
$fbTitle = strip_tags($Author);
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
</style>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<?
					$row2 = sql_fetch(" select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = 'author'");
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
					<?php if ($Photo) {?>
					<img class="media-object img-responsive" src="<?=$iw[path].$upload_path."/".$Photo?>" style='width:100%;'>
					<?php } else {?>
					<img class="media-object img-responsive" src="/design/img/no_image_profile.png" style='width:100%;'>
					<?php } ?>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			<div class="masonry-item w-9 h-full">
				<div class="box br-theme">
					<h3 class="media-heading" style="font-weight:bold;">
						<?=$Author?>
						<?php if ($original_name) echo "(".$original_name.")"; ?>
					</h3>
					<br/>
					<h3 class="box-title">작가약력</h3>
					<div class="content-info image-container"><?=$ProFile?></div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->

			<div class="masonry-item w-full" style="height:40px;"></div>

		</div> <!-- /.masonry -->

		<?
		$sql = "SELECT
					B.cg_code, B.BookID, B.BookImage, B.BookName, B.PubDate, B.Hit, B.book_recommend, T.AuthorIDs
				FROM $iw[publishing_books_table] AS B
					LEFT JOIN (
						SELECT
							BA.ep_code, BA.bookID, GROUP_CONCAT(BA.AuthorID) AS AuthorIDs
						FROM $iw[publishing_books_author_table] AS BA
							LEFT JOIN $iw[publishing_author_table] AS A
							ON (BA.ep_code = A.ep_code AND BA.authorID = A.AuthorID) 
						WHERE BA.ep_code = '$iw[store]' AND BA.AuthorID = '$AuthorID'
                        GROUP BY BA.bookID
					) AS T 
					ON (B.BookID = T.bookID AND B.ep_code = T.ep_code)
				WHERE B.ep_code = '$iw[store]' AND B.gp_code = '$iw[group]' AND B.book_display = 1 AND T.AuthorIDs IS NOT NULL
				ORDER BY B.PubDate DESC";
		$result = sql_query($sql);
		
		$i=0;
		while($row = @sql_fetch_array($result)){
			$BookID = $row["BookID"];
			$cg_code = $row["cg_code"];
			$BookImage = stripslashes($row["BookImage"]);
			$BookName = stripslashes($row["BookName"]);
		?>
		<div class="<?if($i%6==0){?> clearfix-6<?}?><?if($i%4==0){?> clearfix-4<?}?><?if($i%6!=0&&$i%4!=0&&$i%2==0){?> clearfix-2<?}?>"></div>
		<div class="masonry-item w-2 h-full">
			<div class="box br-theme box-media">
				<div class="media">
					<a href="<?=$iw['m_path']?>/publishing_data_view.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$BookID?>">
						<div>
							<?php if ($BookImage) {?>
							<img class="media-object img-responsive" src="<?=$iw[path]."/publishing".$book_path."/".$BookImage?>" alt="">
							<?php } else { ?>
							<img class="media-object img-responsive" src="/design/img/no_image_book.png" alt="">
							<?php } ?>
						</div>
						<div class="media-body">
							<h4 class="media-heading"><?=$BookName?></h4>
							<p><?=$authorName?></p>
						</div>
					</a>
				</div>
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
		<?
			$i++;
		}
		?>

		<div class="clearfix"></div>

		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
		<div class="grid-sizer"></div>

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
		</div> <!-- /.masonry -->

		<div class="clearfix"></div>
	</div> <!-- .row -->
</div> <!-- .content -->

<?
$sql = "update $iw[publishing_author_table] set
		Hit = Hit+1
		where ep_code = '$iw[store]' and AuthorID = '$AuthorID'";
sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_hit = td_hit+1
		where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and td_code = '$AuthorID'";
sql_query($sql);

include_once("_tail.php");
?>