<?php
include_once("_common.php");
if ($iw[type] != "mcb") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-clipboard"></i>
			게시판
		</li>
		<li class="active">게시글 관리</li>
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
			게시글 관리
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
													$search_sql = "and md_code like '%$searchs%'";
												}else if($search =="b"){
													$search_sql = "and md_subject like '%$searchs%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?if($search == "a"){?>selected="selected"<?}?>>게시글코드</option>
												<option value="b" <?if($search == "b"){?>selected="selected"<?}?>>제목</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>게시글코드</th>
											<th>게시글분류</th>
											<th>제목</th>
											<th>조회</th>
											<th>추천</th>
											<th>댓글</th>
											<th>상태</th>
											<th>링크</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select * from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code='$iw[member]' $search_sql";
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

										$sql = "select * from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code='$iw[member]' $search_sql order by md_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$md_no = $row["md_no"];
											$md_code = $row["md_code"];
											$cg_code = $row["cg_code"];
											$md_subject = stripslashes($row["md_subject"]);
											$mb_code = $row["mb_code"];
											$md_hit = $row["md_hit"];
											$md_display = $row["md_display"];
											$md_recommend = $row["md_recommend"];

											$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
											$row2 = sql_fetch($sql2);
											$hm_upper_code = $row2["hm_upper_code"];
											$md_category = $row2["hm_name"];
											if ($hm_upper_code != ""){
												$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
												$row2 = sql_fetch($sql2);
												$hm_upper_code = $row2["hm_upper_code"];
												$md_category = $row2["hm_name"]." > ".$md_category;
											}
											if ($hm_upper_code != ""){
												$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
												$row2 = sql_fetch($sql2);
												$hm_upper_code = $row2["hm_upper_code"];
												$md_category = $row2["hm_name"]." > ".$md_category;
											}
											if ($hm_upper_code != ""){
												$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_code = '$hm_upper_code'";
												$row2 = sql_fetch($sql2);
												$hm_upper_code = $row2["hm_upper_code"];
												$md_category = $row2["hm_name"]." > ".$md_category;
											}
											$row2 = sql_fetch("select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$md_code' and cm_display = 1");
											$reply_count = number_format($row2[cnt]);
									?>
										<tr>
											<td data-title="게시글코드"><?=$md_code?></td>
											<td data-title="게시글분류"><?=$md_category?></td>
											<td data-title="제목"><?=$md_subject?></td>
											<td data-title="조회수"><?=$md_hit?></td>
											<td data-title="추천"><?=$md_recommend?></td>
											<td data-title="댓글"><?=$reply_count?></td>
											<td data-title="상태">
												<?if($md_display==1){?>
													<span class="label label-sm label-success">노출</span>
												<?}else{?>
													<span class="label label-sm label-warning">숨김</span>
												<?}?>											
											</td>
											<td data-title="링크"><a href="<?=$iw['m_path']?>/mcb_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$md_code?>"><span class="label label-sm label-default">바로가기</span></a></td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>검색된 게시글이 없습니다.</td></tr>";
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