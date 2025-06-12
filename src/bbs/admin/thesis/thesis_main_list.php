<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once($iw['admin_path']."/_head.php");

$bd_code = $_GET["idx"];

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("잘못된 접근입니다!","");

$row = sql_fetch(" select count(*) as cnt from $iw[book_main_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'");
if (!$row[cnt]) {
	$sql = "insert into $iw[book_main_table] set
			bd_code = '$bd_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			bn_color = '#ffffff',
			bn_font = '#000000',
			bn_display = 0
			";
	sql_query($sql);
}

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select * from $iw[book_main_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
$bn_logo = $row["bn_logo"];
$bn_thum = $row["bn_thum"];
$bn_sub_title = stripslashes($row["bn_sub_title"]);
$bn_color = $row["bn_color"];
$bn_font = $row["bn_font"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			이북몰
		</li>
		<li class="active">이북 관리</li>
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
			이북 관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				논문 스타일
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<div class="row">
					<div class="col-xs-12 col-sm-5">
						<button class='btn btn-success' type='button' onclick="javascript:bookFramePage('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>');"><i class="fa fa-plus"></i> 추가</button>
						<div class="book_main_wrap" style="background-color:<?=$bn_color?>;">
							<div class="wrap">
								<div class="top_logo">
									<a href="javascript:bookFrameMain('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>');">
										<?if($bn_logo){?>
											<img src="<?=$iw["path"].$upload_path."/".$bn_logo?>" />
										<?}else{?>
											<img src="<?=$iw["bbs_img_path"]."/book_top_logo.png"?>" />
										<?}?>
									</a>
								</div>
								<div class="main_list_btn">
									<img src="<?=$iw["bbs_img_path"]."/book_main_list.png"?>" />
								</div>
								<div class="main_sub_title" style="color:<?=$bn_font?>;">
									<?=$bn_sub_title?>
								</div>
								<div class="book_list_wrap">
									<ul>
									<?
										$sql = "select * from $iw[book_thesis_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]' order by bt_order asc";
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
											<a href="javascript:bookFramePageEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$bt_no?>');" style="color:<?=$bn_font?>;text-decoration:none;">
												<?if($bt_title_kr){?><p class="title_kr"><?=$i+1?>. <?=$bt_title_kr?></p><?}?>
												<?if($bt_sub_kr){?><p class="title_kr">- <?=$bt_sub_kr?></p><?}?>
												<?if($bt_title_us){?><p class="title_us"><?=$bt_title_us?></p><?}?>
												<?if($bt_sub_us){?><p class="title_us">- <?=$bt_sub_us?></p><?}?>
												<?if($bt_person){?><p class="person"><?=$bt_person?></p><?}?>
											</a>
										</li>
									<?
											$i++;
										}
									?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<iframe name="bookFrame" width="100%" height="650px" frameborder="0" scrolling="no" src="">
						</iframe>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div>
</div><!-- /end .page-content -->

<script language="javascript">
	function bookFrameMain(type,ep,gp,idx){
		bookFrame.location.href="thesis_main_edit.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
	}
	function bookFramePage(type,ep,gp,idx){
		bookFrame.location.href="thesis_page_write.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
	}
	function bookFramePageEdit(type,ep,gp,idx,no){
		bookFrame.location.href="thesis_page_edit.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx+"&no="+no;
	}
</script>

<?
include_once($iw['admin_path']."/_tail.php");
?>