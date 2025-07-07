<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

array_multisort($hs_file_sort, SORT_ASC, $hs_file, $hs_link);

$carousel_count = 1;
$carousel_out = "";
for($i=0; $i<10; $i++){
	if($hs_file[$i]!=""){
		if($carousel_count === 1){
			$carousel_out .= '<div class="item active">';	
		}else{
			$carousel_out .= '<div class="item">';	
		}

		if ($hs_link[$i] == "#" || $hs_link[$i] == "") {
			$carousel_out .= '<img src="'.$iw["path"].$upload_path_list.'/'.$hs_file[$i].'" alt="" />';
		} else {
			$carousel_out .= '<a href="'.$hs_link[$i].'"><img src="'.$iw["path"].$upload_path_list.'/'.$hs_file[$i].'" alt="" /></a>';
		}
		$carousel_out .= '<div class="carousel-caption">';
		$carousel_out .= '</div></div>';
		$carousel_count++;
	}
}
 if($carousel_count!=1){ if($st_slide_size == 1){?>
				</div> <!-- /.masonry -->
			</div> <!-- /.row -->
		</div> <!-- /.content -->
	</div>
	<?php }?>

	<div class="masonry-item w-full h-full scrap-wrap" style="padding:<?=$hs_box_padding?>px">
		<div class="box-img">
			<div id="carousel-home<?=$hs_no?>" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				<?php if($carousel_count >2){?>
					<ol class="carousel-indicators">
						<li data-target="#carousel-home<?=$hs_no?>" data-slide-to="0" class="active"></li>
						<?php for($i=1;$i<$carousel_count-1;$i++) {?>
							<li data-target="#carousel-home<?=$hs_no?>" data-slide-to="<?=$i?>"></li>
						<?php }?>
					</ol>
				<?php }?>

				<!-- Wrapper for slides -->
				<div class="carousel-inner">
					<?=$carousel_out;?>
				</div>

				<?php if($carousel_count >2){?>
				<!-- Controls -->
				<a class="left carousel-control" href="#carousel-home<?=$hs_no?>" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left"></span>
				</a>
				<a class="right carousel-control" href="#carousel-home<?=$hs_no?>" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right"></span>
				</a>
				<?php }?>
			</div> <!-- /#carousel-home -->
		</div> <!-- /.box -->
	</div> <!-- /.masonry-item -->
	<div class="clearfix"></div>

	<?php if($st_slide_size == 1){?>
	<div class="container">
		<div class="content">
			<div class="row">
				<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
				<div class="grid-sizer"></div>
	<?php } ?><?php }?>



