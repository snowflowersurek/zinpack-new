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
		<div class="book_list_wrap">
			<ul>
			<?php
				$sql = "select * from $iw[book_thesis_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' order by bt_order asc";
				$result = sql_query($sql);

				$i=0;
				while($row = @sql_fetch_array($result)){
					$bt_no = $row["bt_no"];
					$bt_title_kr = stripslashes($row["bt_title_kr"]);
					$bt_title_us = stripslashes($row["bt_title_us"]);
					$bt_sub_kr = stripslashes($row["bt_sub_kr"]);
					$bt_sub_us = stripslashes($row["bt_sub_us"]);
					$bt_person = stripslashes($row["bt_person"]);
			?>
				<li>
					<a href="javascript:bookPage('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$bt_no?>');" style="color:<?=$bn_font?>;text-decoration:none;">
						<?php if($bt_title_kr){?><p class="title_kr"><?=$i+1?>. <?=$bt_title_kr?></p><?php } ?><?php if($bt_sub_kr){?><p class="title_kr">- <?=$bt_sub_kr?></p><?php } ?><?php if($bt_title_us){?><p class="title_us"><?=$bt_title_us?></p><?php } ?><?php if($bt_sub_us){?><p class="title_us">- <?=$bt_sub_us?></p><?php } ?><?php if($bt_person){?><p class="person"><?=$bt_person?></p><?php }?>
					</a>
				</li>

			<?php
				}
			?>
			</ul>
		</div>
	</div>
</div>

<script type="text/javascript">
	function bookPage(type,ep,gp,idx,no){
		location.href="thesis_view.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx+"&no="+no;
	}
</script>
<?php
include_once("_tail.php");
?>



