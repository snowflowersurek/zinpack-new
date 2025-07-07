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
				블로그 스타일
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
										<?php if($bn_logo){?>
											<img src="<?=$iw["path"].$upload_path."/".$bn_logo?>" />
										<?php }else{?>
											<img src="<?=$iw["bbs_img_path"]."/book_top_logo.png"?>" />
										<?php }?>
									</a>
								</div>
								<div class="main_list_btn">
									<img src="<?=$iw["bbs_img_path"]."/book_main_list.png"?>" />
								</div>
								<div class="main_sub_title" style="color:<?=$bn_font?>;">
									<?=$bn_sub_title?>
								</div>
								<div class="book_thum_wrap">
									<?php
										$sql = "select * from $iw[book_blog_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
										$result = sql_query($sql);
										$total_line = mysql_num_rows($result);

										$max_line = 9;
										$max_page = 10;
											
										$page = $_GET["page"];
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

										$sql = "select * from $iw[book_blog_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]' order by bg_order asc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$bg_no = $row["bg_no"];
											$bg_order = $row["bg_order"];
											$bg_image = $row["bg_image"];
											$bg_page = $row["bg_page"];
											$bg_display = $row["bg_display"];
									?>
										<div class="thum_image">
											<a href="javascript:bookFramePageEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$bg_no?>');">
												<img src="<?=$iw["path"].$upload_path."/".$row["bg_image"]?>" />
											</a>
										</div>
									<?php
											$i++;
										}
										for ($a=$i; $a<9; $a++) {
									?>
										<div class="thum_image">
											<?php if($bn_logo){?>
												<img src="<?=$iw["path"].$upload_path."/".$bn_thum?>" />
											<?php }else{?>
												<img src="<?=$iw["bbs_img_path"]."/book_main_thum.png"?>" />
											<?php }?>
										</div>
									<?php
										}
									?>
								</div>
							</div>
						</div>
						<div class="dataTable-option-right">
							<ul class="pagination">
							<?php
								if($total_page!=0){
									if($page>$total_page) { $page=$total_page; }
									$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
									$end_page = $start_page+$max_page-1;
								 
									if($end_page>$total_page) {$end_page=$total_page;}
								 
									if($page>$max_page) {
										$pre = $start_page - 1;
										echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code&page=$pre'><i class='fa fa-angle-double-left'></i></a></li>";
									} else {
										echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
									}
									
									for($i=$start_page;$i<=$end_page;$i++) {
										if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
										else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code&page=$i'>$i</a></li>";
									}
								 
									if($end_page<$total_page) {
										$next = $end_page + 1;
										echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code&page=$next'><i class='fa fa-angle-double-right'></i></a></li>";
									} else {
										echo "<li class='next disabled'><a href='#'><i class='fa fa-angle-double-right'></i></a></li>";
									}
								}
							?>
							</ul>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<iframe name="bookFrame" width="100%" height="750px" frameborder="0" scrolling="no" src="">
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
		bookFrame.location.href="blog_main_edit.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
	}
	function bookFramePage(type,ep,gp,idx){
		bookFrame.location.href="blog_page_write.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
	}
	function bookFramePageEdit(type,ep,gp,idx,no){
		bookFrame.location.href="blog_page_edit.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx+"&no="+no;
	}
</script>

<?php
include_once($iw['admin_path']."/_tail.php");
?>



