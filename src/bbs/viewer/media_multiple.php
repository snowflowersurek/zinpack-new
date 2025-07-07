<?php
include_once("_common.php");
include_once("_head.php");

$bd_code = $_GET["idx"];
$bm_no = $_GET["no"];

$sql = "select * from $iw[book_main_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code'";
$row = sql_fetch($sql);
$bn_color = $row["bn_color"];
$bn_font = $row["bn_font"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select * from $iw[book_media_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and bm_no = '$bm_no'";
$row = sql_fetch($sql);
if (!$row["bm_no"]) alert("잘못된 접근입니다!","");
$bm_order = $row['bm_order'];

$sql = "select * from $iw[book_media_detail_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and bm_order = '$bm_order' and bmd_order = 0";
$row = sql_fetch($sql);
if (!$row["bmd_no"]) alert("잘못된 접근입니다!","");
$reporter = stripslashes($row['bmd_image']);
$bmd_type = stripslashes($row['bmd_type']);
$content = str_replace("\r\n", "<br/>", stripslashes($row["bmd_content"]));
?>

<div class="book_main_wrap" style="background-color:<?=$bn_color?>;">
	<div class="wrap">
		<div class="top_back" style="border-bottom:2px solid <?=$bn_font?>;">
			<a href="<?=$iw['viewer_path']?>/media_main.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>" style="color:<?=$bn_font?>;">< BACK</a>
		</div>
		<div class="top_title" style="color:<?=$bn_font?>;">
			<?=$bmd_type?>
		</div>
		<div class="multiple_slide">
			<div class="flicking">
				<ul>
				<?php
					$totalthum = 0;
					$sql = "select * from $iw[book_media_detail_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and bm_order = '$bm_order' and bmd_order <> 0 order by bmd_order asc";
					$result = sql_query($sql);
					while($row = @sql_fetch_array($result)){
						$bmd_no = $row['bmd_no'];
						$bmd_type = $row['bmd_type'];
						$bmd_image = $row["bmd_image"];
						$bmd_content = $row["bmd_content"];

						if($bmd_type == 3){
							$link_url = "$bmd_content";
						}
						if($bmd_image){
							?>
							<li>
								<div class="box_type"> 
									<div class="cell"> 
										<div class="inner">
											<a href="javascript:image_click('<?=$totalthum?>','<?=$bmd_type?>','<?=$link_url?>');">
												<img src="<?=$iw["path"].$upload_path."/".$row["bmd_image"]?>" />
											</a>
										</div>
									</div>
								</div>
								<?php if($bmd_type == 1 || $bmd_type == 3){?>
									<a class="multiple_expand" href="<?=$iw['viewer_path']?>/media_expand.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>&no=<?=$bm_no?>&order=<?=$bmd_no?>">
										<img src="<?=$iw["bbs_img_path"]."/book_expand.png"?>" />
									</a>
								<?php } ?><?php if($bmd_type == 1){?>
									<div class="multiple_caption" id="caption<?=$totalthum?>">
										<?=str_replace("\r\n", "<br/>", stripslashes($bmd_content))?>
									</div>
								<?php }else if($bmd_type == 2){?>
									<iframe class="multiple_youtube" style="display:none;" id="youtube<?=$totalthum?>" type="text/html" width="100%" height="100%" src="http://www.youtube.com/embed/<?=$bmd_content?>" frameborder="0"></iframe>
								<?php }?>
							</li>
							<?php
							$totalthum++;
						}
					}
				?>
		
				</ul>
			</div>
			<div class="flickPaging">
				<?php for($i=1; $i<=$totalthum; $i++) {?>
				<a href="#" style="color:<?=$bn_font?>;">&#8226;</a>
				<?php }?>
			</div>
		</div>
		<div class="multiple_text" style="border-top:1px solid <?=$bn_font?>;border-bottom:1px solid <?=$bn_font?>;">
			<b><?=$reporter?></b><br/><br/><?=$content?>
		</div>
	</div>
</div>


<script type="text/javascript" src="main_slide/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="main_slide/jquery.touchSlider.js"></script>
<script type="text/javascript">
	$(window).load(function(){
		$(document).ready(function() {

			//예시
			$(".multiple_slide").touchSlider({
				viewport: ".flicking",				//플리킹될 페이지리스트
				prev : ".prev",
				next : ".next",
				pagination : ".flickPaging > a",	//페이지버튼
				currentClass : "on",				//페이지 버튼 리스트 활성화 클래스
				duration: 500						//슬라이드속도
			});

		});
	});

	function image_click(num,type,url){
		if(type=="1"){
			if (document.getElementById('caption'+num).style.display == "none"){
				document.getElementById('caption'+num).style.display = "";
			}else{
				document.getElementById('caption'+num).style.display = "none";
			}
		}
		if(type=="2"){
			document.getElementById('youtube'+num).style.display = "";
		}
		if(type=="3"){
			location.href = url;
		}
	}
</script>
<?php
include_once("_tail.php");
?>



