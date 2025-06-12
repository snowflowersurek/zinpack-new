<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가
?>

<style type="text/css">
	.fb-page-wrap-<?=$hs_no?>{
		-moz-border-radius:<?=$hs_border_radius?>px;
		-webkit-border-radius:<?=$hs_border_radius?>px;
		border-radius:<?=$hs_border_radius?>px;
		border:<?=$hs_border_width?>px solid <?=$hs_border_color?>;
		background:rgba(<?=$hs_bg_color[red]?>,<?=$hs_bg_color[green]?>,<?=$hs_bg_color[blue]?>,1);
	}
</style>

<script>
$(function() {
	$("#fb-page-<?=$hs_no?>").attr("data-height", (unit_h * <?=$hs_size_height?>) - (2 * <?=$hs_border_width?>) - (2 * <?=$hs_box_padding?>));
});
</script>

<div class="masonry-item w-<?=$hs_size_width?> h-<?=$hs_size_height?>-scrap scrap-wrap" style="padding:<?=$hs_box_padding?>px">
	<div class="box box-img text-center fb-page-wrap-<?=$hs_no?>">
		<div id="fb-page-<?=$hs_no?>" class="fb-page" data-href="<?=$hs_sns_url?>" data-tabs="timeline" data-width="500" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
			<blockquote cite="<?=$hs_sns_url?>" class="fb-xfbml-parse-ignore"></blockquote>
		</div>
	</div>
</div> <!-- /.masonry-item -->
<div class="clearfix"></div>
