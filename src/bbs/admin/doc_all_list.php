<?php
include_once("_common.php");
if ($iw[type] != "doc" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-inbox"></i>
			컨텐츠몰
		</li>
		<li class="active">전체 컨텐츠 조회</li>
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
			전체 컨텐츠 조회
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
											<?
												if($_POST['search']){
													$search = $_POST['search'];
													$searchs = $_POST['searchs'];
												}else{
													$search = $_GET['search'];
													$searchs = $_GET['searchs'];
												}
												if($search =="a"){
													$search_sql = "and dd_code like '%$searchs%'";
												}else if($search =="b"){
													$search_sql = "and mb_code like '%$searchs%'";
												}else if($search =="c"){
													$search_sql = "and dd_subject like '%$searchs%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?if($search == "a"){?>selected="selected"<?}?>>컨텐츠코드</option>
												<option value="b" <?if($search == "b"){?>selected="selected"<?}?>>판매자코드</option>
												<option value="c" <?if($search == "c"){?>selected="selected"<?}?>>컨텐츠명</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>컨텐츠코드</th>
											<th>판매자코드</th>
											<th>닉네임</th>
											<th>컨텐츠분류</th>
											<th>컨텐츠명</th>
											<th>형식</th>
											<th>크기</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select * from $iw[doc_data_table] where ep_code = '$iw[store]' $search_sql";
										$result = sql_query($sql);
										$total_line = mysql_num_rows($result);

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

										$sql = "select * from $iw[doc_data_table] where ep_code = '$iw[store]' $search_sql order by dd_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$dd_no = $row["dd_no"];
											$dd_code = $row["dd_code"];
											$cg_code = $row["cg_code"];
											$dd_subject = stripslashes($row["dd_subject"]);
											$dd_file = explode(".",$row["dd_file_name"]);
											$dd_file_size = number_format($row["dd_file_size"]/1024/1000, 1);
											$mb_code = $row["mb_code"];
											$gp_code = $row["gp_code"];

											$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
											$row2 = sql_fetch($sql2);
											$hm_upper_code = $row2["hm_upper_code"];
											$dd_category = $row2["hm_name"];
											if ($hm_upper_code != ""){
												$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and hm_code = '$hm_upper_code'";
												$row2 = sql_fetch($sql2);
												$hm_upper_code = $row2["hm_upper_code"];
												$dd_category = $row2["hm_name"]." > ".$dd_category;
											}
											if ($hm_upper_code != ""){
												$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and hm_code = '$hm_upper_code'";
												$row2 = sql_fetch($sql2);
												$hm_upper_code = $row2["hm_upper_code"];
												$dd_category = $row2["hm_name"]." > ".$dd_category;
											}
											if ($hm_upper_code != ""){
												$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and hm_code = '$hm_upper_code'";
												$row2 = sql_fetch($sql2);
												$hm_upper_code = $row2["hm_upper_code"];
												$dd_category = $row2["hm_name"]." > ".$dd_category;
											}
											$row2 = sql_fetch("select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'");
											$mb_nick = $row2["mb_nick"];
									?>
										<tr>
											<td data-title="컨텐츠코드"><a href="<?=$iw['admin_path']?>/doc_all_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$dd_code?>"><?=$dd_code?></a></td>
											<td data-title="판매자코드"><?=$mb_code?></td>
											<td data-title="닉네임"><?=$mb_nick?></td>
											<td data-title="컨텐츠분류"><?=$dd_category?></td>
											<td data-title="컨텐츠명"><?=$dd_subject?></td>
											<td data-title="형식"><?=$dd_file[1]?></td>
											<td data-title="크기"><?=$dd_file_size?> M</td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='7' align='center'>검색된 컨텐츠가 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info"><!--페이지/전체--></div>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<ul class="pagination">
											<?
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

<?
include_once("_tail.php");
?>