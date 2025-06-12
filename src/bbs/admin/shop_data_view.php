<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$sd_code = $_GET["idx"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = '/shop/'.$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$sd_code;

$sql = "select * from $iw[shop_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and sd_code = '$sd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["sd_no"]) alert("잘못된 접근입니다!","");

$content = stripslashes($row["sd_content"]);
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-shopping-cart"></i>
			쇼핑몰
		</li>
		<li class="active">상품관리</li>
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
			상품관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				상품정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">상품코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["sd_code"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">조회수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["sd_hit"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["sd_sell"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">추천수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["sd_recommend"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">카테고리</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?
									$cg_code = $row["cg_code"];

									$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
									$row2 = sql_fetch($sql2);
									$hm_upper_code = $row2["hm_upper_code"];
									$sd_category = $row2["hm_name"];
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$sd_category = $row2["hm_name"]." > ".$sd_category;
									}
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$sd_category = $row2["hm_name"]." > ".$sd_category;
									}
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$sd_category = $row2["hm_name"]." > ".$sd_category;
									}
									echo "$sd_category";
								?>
							</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">소비자가</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=national_money($iw[language], $row["sd_price"]);?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매가격</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=national_money($iw[language], $row["sd_sale"]);?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">상품명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=stripslashes($row["sd_subject"])?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">상품태그</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$row["sd_tag"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">최대수량</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=number_format($row["sd_max"])?> 개 이하</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">배송코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
							<?
								$row2 = sql_fetch(" select * from $iw[shop_delivery_table] where ep_code = '$iw[store]' and mb_code = '$row[mb_code]' and sy_code='$row[sy_code]' ");
								$sy_code = $row2["sy_code"];
								$sy_price = $row2["sy_price"];
								$sy_max = $row2["sy_max"];
								$sy_display = $row2["sy_display"];
							?>
								[<?=$sy_code?>] <?=national_money($iw[language], $sy_price);?> (
								<?if($sy_display == 1){?>
									<?=national_money($iw[language], $sy_max);?> 이상 무료배송
								<?}else{?>
									<?=$sy_max?> <?=national_language($iw[language],"a0215","개");?> 이하 묶음배송
								<?}?>)
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품정보</label>
						<div class="col-sm-11">
							<?=str_replace("\r\n", "<br/>", $row["sd_information"]);?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품설명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 form-control-static"><div class="html_edit_wrap" style="max-width:1000px;"><?=$content?></div></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품메인</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><div class="html_edit_wrap" style="max-width:450px;"><img src="<?=$iw["path"].$upload_path."/".$row["sd_image"]?>"/></div></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품옵션</label>
						<div class="col-sm-11">
							<p class="col-xs-12 form-control-static">
								<div style="max-width:1000px;">
								<table class="table table-striped table-bordered table-hover dataTable">
									<tr><td align="center">No</td><td align="center">옵션명</td><td align="center">수량</td><td align="center">가격</td><td align="center">과세</td></tr>
							<?
								$sql2 = "select * from $iw[shop_option_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and sd_code = '$sd_code' order by so_no asc";
								$result2 = sql_query($sql2);
								$i=0;
								while($row2 = @sql_fetch_array($result2)){
							?>
								 <tr><td><?=$i+1?></td><td><?=$row2["so_name"]?></td><td align="right"><?=number_format($row2["so_amount"])?> 개</td><td align="right"><?=national_money($iw[language], $row2["so_price"]);?></td><td align="center"><?if($row2["so_taxfree"]==1){?>면세상품<?}else{?>부가세포함<?}?></td></tr>
							<?		
									$i++;
								}
							?>
								</table>
								</div>
							</p>
						</div>
					</div>

				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" onclick="location='<?=$iw['admin_path']?>/shop_data_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$sd_code?>'">
							<i class="fa fa-check"></i>
							수정
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/shop_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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

<?
include_once("_tail.php");
?>