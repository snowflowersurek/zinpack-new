<?php
include_once("_common.php");
if ($iw[type] != "doc" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$dd_code = $_GET["idx"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = '/doc/'.$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$dd_code;

$sql = "select * from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and dd_code = '$dd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["dd_no"]) alert("잘못된 접근입니다!","");

$content = stripslashes($row["dd_content"]);
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-inbox"></i>
			컨텐츠몰
		</li>
		<li class="active">컨텐츠 관리</li>
	</ul><!-- .breadcrumb -->

	<!--<div class="nav-search" id="nav-search">
		<form class="form-search">
			<span class="input-icon">
				<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off">
				<i class="fa fa-search"></i>
			</span>
		</form>
	</div>--><!-- #nav-search -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			컨텐츠 관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				컨텐츠 정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["dd_code"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">조회수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["dd_hit"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["dd_sell"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">추천수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["dd_recommend"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">카테고리</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?php
									$cg_code = $row["cg_code"];

									$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
									$row2 = sql_fetch($sql2);
									$hm_upper_code = $row2["hm_upper_code"];
									$dd_category = $row2["hm_name"];
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$dd_category = $row2["hm_name"]." > ".$dd_category;
									}
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$dd_category = $row2["hm_name"]." > ".$dd_category;
									}
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$dd_category = $row2["hm_name"]." > ".$dd_category;
									}
									echo "$dd_category";
								?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠 파일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<a href="javascript:file_download('<?=$iw['admin_path']?>/doc_data_download.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$dd_code?>', '<?=urlencode($row[dd_file_name])?>');"><?=$row["dd_file_name"]?></a> ( <?=number_format($row["dd_file_size"]/1024/1000, 1)?> MB )
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠 표지</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?><img src="<?=$iw["path"].$upload_path."/".$row["dd_image"]?>" width="300px" height="400px" /><?php }else{?>없음<?php }?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=stripslashes($row["dd_subject"])?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠 분량</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["dd_amount"] if{?>쪽<?php }else{?>분<?php }?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매가격</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=number_format($row["dd_price"])?> Point</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">다운로드 유효기간</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>무제한<?php }else{?><?=$row["dd_download"]?> 일<?php }?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">검색태그</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["dd_tag"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠설명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><div class="html_edit_wrap" style="max-width:1000px;"><?=$content?></div></p>
						</div>
					</div>
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" onclick="location='<?=$iw['admin_path']?>/doc_data_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$dd_code?>'">
							<i class="fa fa-check"></i>
							수정
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/doc_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
							<i class="fa fa-undo"></i>
							목록
						</button>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<script type="text/javascript">
	function file_download(link, file) {
		document.location.href=link;
	}
</script>

<?php
include_once("_tail.php");
?>



