<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$bd_code = $_GET["idx"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = '/book/'.$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("잘못된 접근입니다!","");


if($row["bd_display"]==1){
	$bd_value = 2;
	$bd_btn = "숨김";
}else{
	$bd_value = 1;
	$bd_btn = "노출";
}

$content = stripslashes($row["bd_content"]);

$sqlm = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$row[mb_code]'";
$rowm = sql_fetch($sqlm);
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-newspaper-o"></i>
			이북몰
		</li>
		<li class="active">이북 노출 관리</li>
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
			이북 노출 관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				이북정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">이북코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["bd_code"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매자</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$rowm["mb_code"]?> ( <?=$rowm["mb_name"]?> / <?=$rowm["mb_mail"]?> )</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">조회수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["bd_hit"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["bd_sell"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">추천수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["bd_recommend"]?></p>
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
									$bd_category = $row2["hm_name"];
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$bd_category = $row2["hm_name"]." > ".$bd_category;
									}
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$bd_category = $row2["hm_name"]." > ".$bd_category;
									}
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$bd_category = $row2["hm_name"]." > ".$bd_category;
									}
									echo "$bd_category";
								?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">스타일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
							<?php
								if($row["bd_type"] == 1){
									echo "PDF";
								}else if($row["bd_type"] == 2){
									echo "미디어";
								}else if($row["bd_type"] == 3){
									echo "블로그";
								}else if($row["bd_type"] == 4){
									echo "논문";
								}
							?>	
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=stripslashes($row["bd_subject"])?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">저자</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["bd_author"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">출판사</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["bd_publisher"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">검색태그</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["bd_tag"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매가격</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=number_format($row["bd_price"])?> Point</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">이북 표지</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><img src="<?=$iw["path"].$upload_path."/".$row["bd_image"]?>" width="300px" height="400px" /></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이북설명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><div class="html_edit_wrap" style="max-width:700px;"><?=$content?></div></p>
						</div>
					</div>
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-primary" type="button" onclick="javascript:exposure_edit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$bd_value?>');">
							<i class="fa fa-undo"></i>
							<?=$bd_btn?> 변경
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/book_exposure_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
	function exposure_edit(type,ep,gp,no,dis) {
		if (dis == 1) {
			confirm_text = "해당 이북을 노출로 변경하시겠습니까?"
		}else{
			confirm_text = "해당 이북을 숨김으로 변경하시겠습니까?"
		}
		if (confirm(confirm_text)) {
			location.href="book_exposure_ok.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+no+"&dis="+dis;
		}
	}
</script>

<?php
include_once("_tail.php");
?>



