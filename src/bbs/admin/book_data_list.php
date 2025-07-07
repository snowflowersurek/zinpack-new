<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-newspaper-o"></i>
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
				목록
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<div class="row">
					<div class="col-xs-12">
						<h4 class="lighter"><!--제목--></h4>
						<section class="content-box">
							<div class="table-header">
								<!--게시판설명-->
							</div>
							<div class="table-set-mobile dataTable-wrapper">
								<div class="row">
									<div class="col-sm-6">
										<!--<div class="dataTable-option">
											<label>Display <select size="1">
												<option value="10" selected="selected">10</option>
												<option value="25">25</option>
												<option value="50">50</option>
												<option value="100">100</option>
											</select> records</label>
										</div>-->
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<?php
												if($_POST['search']){
													$search = $_POST['search'];
													$searchs = $_POST['searchs'];
												}else{
													$search = $_GET['search'];
													$searchs = $_GET['searchs'];
												}
												if($search =="a"){
													$search_sql = "and bd_code like '%$searchs%'";
												}else if($search =="b"){
													$search_sql = "and bd_subject like '%$searchs%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?php if($search == "a"){?>selected="selected"<?php }?>>이북코드</option>
												<option value="b" <?php if($search == "b"){?>selected="selected"<?php }?>>제목</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>이북코드</th>
											<th>분류</th>
											<th>제목</th>
											<th>가격</th>
											<th>판매수</th>
											<th>스타일</th>
											<th>미리보기</th>
											<th>관리</th>
											<th>노출</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code='$iw[member]' $search_sql";
										$result = sql_query($sql);
										$total_line = mysqli_num_rows($result);

										$max_line = 10;
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

										$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code='$iw[member]' $search_sql order by bd_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$bd_no = $row["bd_no"];
											$bd_code = $row["bd_code"];
											$bd_type = $row["bd_type"];
											$cg_code = $row["cg_code"];
											$bd_subject = stripslashes($row["bd_subject"]);
											$bd_price = number_format($row["bd_price"]);
											$bd_sell = number_format($row["bd_sell"]);
											$bd_display = $row["bd_display"];

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

											if($bd_type == 1){
												$bd_style = "PDF";
												$bd_url = $iw['admin_path']."/pdf/pdf_data_edit.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&idx=".$bd_code;
											}else if($bd_type == 2){
												$bd_style = "미디어";
												$bd_url = $iw['admin_path']."/media/media_main_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&idx=".$bd_code;
											}else if($bd_type == 3){
												$bd_style = "블로그";
												$bd_url = $iw['admin_path']."/blog/blog_main_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&idx=".$bd_code;
											}else if($bd_type == 4){
												$bd_style = "논문";
												$bd_url = $iw['admin_path']."/thesis/thesis_main_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&idx=".$bd_code;
											}
									?>
										<tr>
											<td data-title="이북코드"><a href="<?=$iw['admin_path']?>/book_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>"><?=$bd_code?></a></td>
											<td data-title="분류"><?=$bd_category?></td>
											<td data-title="제목"><?=$bd_subject?></td>
											<td data-title="가격"><?=$bd_price?> point</td>
											<td data-title="판매수"><?=$bd_sell?></td>
											<td data-title="스타일"><?=$bd_style?></td>
											<td data-title="미리보기">
												<a class="label label-sm label-primary"  href="javascript:win_viewer('<?=$iw[type]?>', '<?=$iw[store]?>', '<?=$iw[group]?>', '<?=$bd_code?>', '<?=$bd_type?>');">미리보기</a>
											<?php if($bd_type == 1){?>
												<a class="label label-sm label-success"  href="javascript:win_viewer('<?=$iw[type]?>', '<?=$iw[store]?>', '<?=$iw[group]?>', '<?=$bd_code?>', '9');">샘플</a>
											<?php }?>
											</td>
											<td data-title="관리"><a href="<?=$bd_url?>"><span class="label label-sm label-info">편집</span></a></td>
											<td data-title="노출">
												<?php if($bd_display == 1){?>
													노출
												<?php }else if($bd_display == 2){?>
													<span class="label label-sm label-warning">심사중</span>
												<?php }else{?>
													<a href="<?=$iw['admin_path']?>/book_data_display_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>"><span class="label label-sm label-success">요청하기</span></a>
												<?php }?>
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='9' align='center'>검색된 이북이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/book_data_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
												<i class="fa fa-check"></i>
												이북 등록
											</button>
										</div>
									</div>
									<div class="col-sm-6">
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
														echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&search=$search&searchs=$searchs'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&search=$search&searchs=$searchs'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&search=$search&searchs=$searchs'><i class='fa fa-angle-double-right'></i></a></li>";
													} else {
														echo "<li class='next disabled'><a href='#'><i class='fa fa-angle-double-right'></i></a></li>";
													}
												}
											?>
											</ul>
										</div>
									</div>
								</div>
							</div><!-- /.table-responsive -->
						</section>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

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
		}else if(view == 9){
			url = "/bbs/viewer/pdf_sample.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
		}
        win_open(url, "e-Book", "left=50,top=50,width=768,height=1024,menubar=no,status=no,titlebar=no,scrollbars=yes,resizable=yes");
    }
</script>
<?php
include_once("_tail.php");
?>



