<?php
include_once("_common.php");
if (($iw[gp_level] == "gp_guest" && $iw[group] != "all") || ($iw[level] == "guest" && $iw[group] == "all")) alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]";

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
?>
<div class="content">
	<div class="breadcrumb-box input-group">
		<ol class="breadcrumb">
			<li><a href="<?=$iw[m_path]?>/book_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=$st_book_name?> <?=national_language($iw[language],"a0005","구매자료");?></a></li>
		</ol>
		<span class="input-group-btn">
			<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
			<a class="btn btn-theme" href="<?=$iw[m_path]?>/book_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-book fa-lg"></i></a>
		</span>
	</div>

	<div class="masonry">
		<div class="grid-sizer"></div>
			<?php
				$sql = "select * from $iw[book_buy_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
				$result = sql_query($sql);
				$total_line = mysql_num_rows($result);

				$max_line = 18;
				$max_page = 5;
					
				$page = $_GET["page"] ?? 1;
				if(!$page) $page=1;
				$start_line = ($page-1)*$max_line;
				$total_page = ceil($total_line/$max_line);
				
				if($total_line < $max_line) {
					$end_line = $total_line;
				} else if($page==$total_page) {
					$end_line = $total_line - ($max_line*($total_page-1));
				} else {
					$end_line = $max_line;
				}

				$sql = "select * from $iw[book_buy_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' order by bb_no desc limit $start_line, $end_line";
				$result = sql_query($sql);

				$i=0;
				while($row = @sql_fetch_array($result)){
					$bb_subject = stripslashes($row["bb_subject"]);
					$bd_code = $row["bd_code"];
					$gp_code = $row["gp_code"];
					$bb_date = date("Y.m.d", strtotime($row["bb_datetime"]));

					$sql2 = " select * from $iw[book_data_table] where ep_code = '$iw[store]'  and bd_code = '$bd_code'";
					$row2 = sql_fetch($sql2);
					$bd_type = $row2["bd_type"];
					$bd_image = $row2["bd_image"];
			?>
				<div class="masonry-item w-2 h-full">
					<div class="box box-gallery br-transparent center-block fixed-width">
						<a href="javascript:win_viewer('<?=$iw[type]?>', '<?=$iw[store]?>', '<?=$gp_code?>', '<?=$bd_code?>', '<?=$bd_type?>');">
							<img class="img-responsive bordered" src="<?=$iw[path]."/".$upload_path."/".$bd_code."/".$bd_image?>" alt="">
							<h4><?=$bb_subject?></h4>
							<p>구매일: <?=$bb_date?></p>
						</a>
					</div>
				</div>
			<?php
				$i++;
				}
				if($i==0) echo "<tr><td colspan='3' align='center'>".national_language($iw[language],"a0146","구매자료 내역이 없습니다.")."</td></tr>";
			?>
	</div>

	<div class="pagContainer text-center">
		<ul class="pagination">
			<?php
				if($total_page!=0){
					if($page>$total_page) { $page=$total_page; }
					$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
					$end_page = $start_page+$max_page-1;
				 
					if($end_page>$total_page) {$end_page=$total_page;}
				 
					if($page>$max_page) {
						$pre = $start_page - 1;
						echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre'>&laquo;</a></li>";
					} else {
						echo "<li><a href='#'>&laquo;</a></li>";
					}
					
					for($i=$start_page;$i<=$end_page;$i++) {
						if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
						else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i'>$i</a></li>";
					}
				 
					if($end_page<$total_page) {
						$next = $end_page + 1;
						echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next'>&raquo;</a></li>";
					} else {
						echo "<li><a href='#'>&raquo;</a></li>";
					}
				}
			?>
		</ul>
	</div>
</div>

<script type="text/javascript">
	function win_open(url, name, option)
    {
        var popup = window.open(url, name, option);
        popup.focus();
    }

    function win_viewer(type, ep, gp, idx, view)
    {
		if(view == 1){
			url = "/bbs/viewer/pdf_view.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
		}else if(view == 2){
			url = "/bbs/viewer/media_main.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
		}else if(view == 3){
			url = "/bbs/viewer/blog_main.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
		}else if(view == 4){
			url = "/bbs/viewer/thesis_main.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
		}
        win_open(url, "e-Book", "left=50,top=50,width=768,height=1024,menubar=no,status=no,titlebar=no,scrollbars=yes,resizable=yes");
    }
</script>

<?php
include_once("_tail.php");
?>



