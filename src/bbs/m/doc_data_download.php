<?php
include_once("_common.php");
if (($iw[gp_level] == "gp_guest" && $iw[group] != "all") || ($iw[level] == "guest" && $iw[group] == "all")) alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");

$dd_code = $_GET["item"];
$dd_support = $_GET["support"];
$sql = "select * from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'";
$row = sql_fetch($sql);
$mb_point = $row["mb_point"];

$sql = "select * from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and dd_display = 1 and dd_code = '$dd_code'";
$row = sql_fetch($sql);
if (!$row["dd_no"]) alert(national_language($iw[language],"a0148","컨텐츠를 판매하지 않거나 존재하지 않습니다!"),"");

$dd_code = $row["dd_code"];
$dd_file_name = $row["dd_file_name"];
$dd_file_size = $row["dd_file_size"];
$dd_subject = stripslashes($row["dd_subject"]);
$dd_amount = $row["dd_amount"];
$dd_price = $row["dd_price"];
$dd_download = $row["dd_download"];

$file = sql_fetch(" select count(*) as cnt from $iw[doc_buy_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and mb_code = '$iw[member]' and dd_code = '$dd_code' and (date(db_end_datetime) >= date(now()) or date(db_end_datetime) = date(db_datetime))");
if ($file[cnt]){
	$dd_price = 0;
}
?>

<div class="content">
	<div class="breadcrumb-box input-group">
		<ol class="breadcrumb">
			<li><a href="#"><?=$st_doc_name?> <?=national_language($iw[language],"a0149","컨텐츠 다운로드");?></a></li>
		</ol>
		<span class="input-group-btn">
			<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
			<a class="btn btn-theme" href="<?=$iw[m_path]?>/doc_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-cloud-download fa-lg"></i></a>
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
							<li><a href="javascript:downloadDoc('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$dd_code?>','<?=$dd_support?>');" class="btn btn-theme btn-sm"><?=national_language($iw[language],"a0159","다운로드 받기");?></a></li>
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
							<li><?=national_language($iw[language],"a0150","자료명");?> : <?=$dd_subject?></li>
							<li><?=national_language($iw[language],"a0151","자료가격");?> : <?=number_format($dd_price)?> Point</li>
							<li><?=national_language($iw[language],"a0152","후원금액");?> : <?=number_format($dd_support)?> Point</li>
							<li><?=national_language($iw[language],"a0153","차감액");?> : <?=number_format($dd_price+$dd_support)?> Point</li>
							<li><?=national_language($iw[language],"a0154","파일명");?> : <?=$row["dd_file_name"]?></li>
							<li><?=national_language($iw[language],"a0155","파일크기");?> : <?=number_format($row["dd_file_size"]/1024/1000, 1)?> MB</li>
							<li><?=national_language($iw[language],"a0156","다운로드 유효기간");?> : <?if($dd_download=="0"){?><?=national_language($iw[language],"a0157","무제한");?><?}else{?><?=$dd_download?> <?=national_language($iw[language],"a0158","일");?><?}?></li>
						</ul>
					</div>
				</div>
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
	</div> <!-- /.masonry -->
</div>

<script type="text/javascript">
	function downloadDoc(type, ep, gp, no, support) {
		if (confirm("<?=national_language($iw[language],'a0160','컨텐츠를 다운로드 하시겠습니까?');?>")) {
			location.href="doc_data_download_ok.php?type="+type+"&ep="+ep+"&gp="+gp+"&doc="+no+"&support="+support;
		}
	}
</script>

<?
include_once("_tail.php");
?>