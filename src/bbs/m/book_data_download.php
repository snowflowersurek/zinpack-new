<?php
include_once("_common.php");
if (($iw[gp_level] == "gp_guest" && $iw[group] != "all") || ($iw[level] == "guest" && $iw[group] == "all")) alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");

$bd_code = $_GET["item"];
$bd_support = $_GET["support"];
$sql = "select * from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
$mb_point = $row["mb_point"];

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and bd_display = 1 and bd_code = '$bd_code'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("이북을 판매하지 않거나 존재하지 않습니다!","");

$bd_code = $row["bd_code"];
$bd_subject = stripslashes($row["bd_subject"]);
$bd_price = $row["bd_price"];
$bd_download = $row["bd_download"];

$file = sql_fetch(" select count(*) as cnt from $iw[book_buy_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and mb_code = '$iw[member]' and bd_code = '$bd_code'");
if ($file[cnt]){
	$bd_price = 0;
}
?>

<div class="content">
	<div class="breadcrumb-box input-group">
		<ol class="breadcrumb">
			<li><a href="#"><?=$st_book_name?> <?=national_language($iw[language],"a0210","구매상세");?></a></li>
		</ol>
		<span class="input-group-btn">
			<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
			<a class="btn btn-theme" href="<?=$iw[m_path]?>/book_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-book fa-lg"></i></a>
		</span>
	</div>
	<div class="masonry">
		<div class="grid-sizer"></div>
		<div class="masonry-item w-full h-full">
			<div class="box br-theme">
				<div class="row">
					<div class="col-sm-9">
						<ul class="list-inline">
							<li><?=national_language($iw[language],"a0137","잔여포인트");?> : <?=$mb_point?> Point</li>
						</ul>
					</div>
					<div class="col-sm-3">
						<ul class="list-inline text-right">
							<li><a href="javascript:downloadBook('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$bd_support?>');" class="btn btn-theme btn-sm"><?=national_language($iw[language],"a0251","바로 결제하기");?></a></li>
						</ul>
					</div>
				</div> <!-- /.row -->
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
		<div class="masonry-item w-full h-full">
			<div class="box br-theme">
				<div class="row">
					<div class="col-sm-12">
						<ul class="list-unstyled">
							<li><?=national_language($iw[language],"a0150","자료명");?> : <?=$bd_subject?></li>
							<li><?=national_language($iw[language],"a0151","자료가격");?> : <?=number_format($bd_price)?> Point</li>
							<li><?=national_language($iw[language],"a0152","후원금액");?> : <?=number_format($bd_support)?> Point</li>
							<li><?=national_language($iw[language],"a0153","차감액");?> : <?=number_format($bd_price+$bd_support)?> Point</li>
						</ul>
					</div>
				</div>
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
	</div> <!-- /.masonry -->
</div>

<script type="text/javascript">
	function downloadBook(type, ep, gp, no, support) {
		if (confirm("이북을 구매하시겠습니까?")) {
			location.href="book_data_download_ok.php?type="+type+"&ep="+ep+"&gp="+gp+"&book="+no+"&support="+support;
		}
	}
</script>

<?php
include_once("_tail.php");
?>



