<?php
include_once("_common.php");
include_once("_head.php");

$bd_code = $_GET["idx"];

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("잘못된 접근입니다!","");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select * from $iw[book_main_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code'";
$row = sql_fetch($sql);
$bn_logo = $row["bn_logo"];
$bn_thum = $row["bn_thum"];
$bn_sub_title = stripslashes($row["bn_sub_title"]);
$bn_color = $row["bn_color"];
$bn_font = $row["bn_font"];
?>

<div class="book_main_wrap" style="background-color:<?=$bn_color?>;">
	<div class="wrap">
		<div class="top_logo">
			<img src="<?=$iw["path"].$upload_path."/".$bn_logo?>" />
		</div>
		<div class="main_list_btn">
			<a href="javascript:window.close();"><img src="<?=$iw["bbs_img_path"]."/book_main_list.png"?>" /></a>
		</div>
		<div class="main_sub_title" style="color:<?=$bn_font?>;">
			<?=$bn_sub_title?>
		</div>
		<div class="book_thum_wrap">
			<div class="flicking">
				<ul>
			<?
				$sql = "select * from $iw[book_blog_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' order by bg_order asc";
				$result = sql_query($sql);

				$i=1;
				while($row = @sql_fetch_array($result)){
					$bg_no = $row["bg_no"];
			?>
					<?if($i == 1 || $i%9 == 1){?><li><?}?>
						<div class="thum_image">
							<a href="javascript:bookPage('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$bg_no?>');">
								<img src="<?=$iw["path"].$upload_path."/".$row["bg_image"]?>" />
							</a>
						</div>
					<?if($i%9 == 0){?></li><?}?>
			<?
					$i++;
				}
				$i--;
				if($i != 0 && $i%9 == 0){
					$totalthum = $i/9;
				}else{
					if($i == 0){
						$totalthum = 1;
					}else{
						$totalthum = ceil($i/9);
					}
					for ($a=$i; $a<$totalthum*9; $a++) {
			?>
					<?if($i == 1){?><li><?}?>
						<div class="thum_image">
							<img src="<?=$iw["path"].$upload_path."/".$bn_thum?>" />
						</div>
					<?if($i%9 == 0){?></li><?}?>
			<?
					}
				}
			?>
				</ul>
			</div>
			<div class="flickPaging">
				<?for ($i=1; $i<=$totalthum; $i++) {?>
				<a href="#" style="color:<?=$bn_font?>;">&#8226;</a>
				<?}?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="main_slide/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="main_slide/jquery.touchSlider.js"></script>
<script type="text/javascript">
	$(window).load(function(){
		$(document).ready(function() {

			//예시
			$(".book_thum_wrap").touchSlider({
				viewport: ".flicking",				//플리킹될 페이지리스트
				prev : ".prev",
				next : ".next",
				pagination : ".flickPaging > a",	//페이지버튼
				currentClass : "on",				//페이지 버튼 리스트 활성화 클래스
				duration: 500						//슬라이드속도
			});

		});
	});

	function bookPage(type,ep,gp,idx,no){
		location.href="blog_view.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx+"&no="+no;
	}
</script>
<?
include_once("_tail.php");
?>