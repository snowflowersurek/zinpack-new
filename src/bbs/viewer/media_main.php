<?php
include_once("_common.php");
include_once("_head.php");

$bd_code = $_GET["idx"];
if ($iw[member] == "") {
	echo '<script>alert("로그인 해주시기 바랍니다!");close();</script>'; exit;
}else{
	$mb_id = $iw[member];
	$bdsql = "SELECT COUNT(*) AS cnt FROM $iw[book_buy_table] WHERE mb_code='$mb_id' AND bd_code='$bd_code' ";
	$bdcnt = sql_fetch($bdsql);
	if($bdcnt[cnt]==0){
		echo '<script>alert("먼저 이북을 구매해 주시기 바랍니다!");close();</script>'; exit;
	}
}

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
			<?php
				$sql = "select * from $iw[book_media_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' order by bm_order asc";
				$result = sql_query($sql);

				$i=1;
				while($row = @sql_fetch_array($result)){
					$bm_no = $row["bm_no"];
					$bm_order = $row["bm_order"];
					$bm_image = $row["bm_image"];
					$bm_type = $row["bm_type"];
					$bm_display = $row["bm_display"];
					if($bm_type == 1){
						$bm_type = "multiple";
					}else if($bm_type == 2){
						$bm_type = "image";
					}else if($bm_type == 3){
						$bm_type = "text";
					}else if($bm_type == 4){
						$bm_type = "panorama";
					}else if($bm_type == 5){
						$bm_type = "zoom";
					}else if($bm_type == 6){
						$bm_type = "rotation";
					}else if($bm_type == 7){
						$bm_type = "html";
					}
			 if($i == 1 || $i%9 == 1){?><li><?php }?>
						<div class="thum_image">
							<a href="javascript:bookPage('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$bm_no?>','<?=$bm_type?>');">
								<img src="<?=$iw["path"].$upload_path."/".$row["bm_image"]?>" />
							</a>
						</div>
					<?php if($i%9 == 0){?></li><?php }
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
			 if($i == 1){?><li><?php }?>
						<div class="thum_image">
							<img src="<?=$iw["path"].$upload_path."/".$bn_thum?>" />
						</div>
					<?php if($i%9 == 0){?></li><?php } ?><?php }
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

	function bookPage(type,ep,gp,idx,no,menu){
		location.href="media_"+menu+".php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx+"&no="+no;
	}
</script>
<?php
include_once("_tail.php");
?>



