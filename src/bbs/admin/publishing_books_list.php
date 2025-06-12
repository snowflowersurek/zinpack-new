<?php
include_once("_common.php");
if ($iw[type] != "publishing" || ($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]";
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">도서관리</li>
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
			도서관리
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
								<!--설명-->
							</div>
							<div class="table-set-mobile dataTable-wrapper">
								<div class="row">
									<div class="col-sm-4">
										<div class="dataTable-option">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/publishing_books_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
												<i class="fa fa-download"></i>
												엑셀저장
											</button>
										</div>
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
												if($search =="bookName"){
													$search_sql = "and bookName like '%$searchs%'";
												}else if($search =="author"){
													$search_sql = "and author like '%$searchs%'";
												}else if($search =="isbn"){
													$search_sql = "and isbn like '%$searchs%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: 
												<select name="search">
													<option value="bookName" <?if($search == "bookName"){?>selected="selected"<?}?>>도서명</option>
													<!--<option value="author" <?if($search == "author"){?>selected="selected"<?}?>>작가명</option>-->
													<option value="isbn" <?if($search == "isbn"){?>selected="selected"<?}?>>ISBN</option>
												</select>
											</label>
											<input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>도서코드</th>
											<th>표지</th>
											<th>도서분류</th>
											<th>도서명</th>
											<th>출판일</th>
											<th>조회</th>
											<th>추천</th>
											<th>댓글</th>
											<th>상태</th>
											<th>링크</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select BookID from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' $category_sql $search_sql";
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

										$sql = "select * from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' $category_sql $search_sql order by CAST(SUBSTR(BookID, 2) AS UNSIGNED) desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$BookID = $row["BookID"];
											$cg_code = $row["cg_code"];
											$BookImage = stripslashes($row["BookImage"]);
											$BookName = stripslashes($row["BookName"]);
											$PubDate = $row["PubDate"];
											$mb_code = $row["mb_code"];
											$Hit = $row["Hit"];
											$book_display = $row["book_display"];
											$gp_code = $row["gp_code"];
											$book_recommend = $row["book_recommend"];

											$sql2 = "select cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
											$row2 = sql_fetch($sql2);
											$book_category = $row2["cg_name"];
											
											$row2 = sql_fetch("select count(cm_code) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$gp_code' and state_sort = '$iw[type]' and cm_code = '$BookID' and cm_display = 1");
											$reply_count = number_format($row2[cnt]);
									?>
										<tr>
											<td data-title="도서코드"><?=$BookID?></td>
											<td data-title="표지">
											<?php if ($BookImage) {?>
												<img src="<?=$iw[path].$upload_path."/book/".$BookImage?>" style="max-width:84px;" />
											<?php } ?>
											</td>
											<td data-title="도서분류"><?=$book_category?></td>
											<td data-title="도서명"><a href="<?=$iw['admin_path']?>/publishing_books_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&id=<?=$BookID?>"><?=$BookName?></a></td>
											<td data-title="출판일"><?=$PubDate?></td>
											<td data-title="조회"><?=$Hit?></td>
											<td data-title="추천"><?=$book_recommend?></td>
											<td data-title="댓글"><?=$reply_count?></td>
											<td data-title="상태">
												<?if($book_display==1){?>
													<span class="label label-sm label-success">노출</span>
												<?}else{?>
													<span class="label label-sm label-warning">숨김</span>
												<?}?>											
											</td>
											<td data-title="링크"><a href="<?=$iw['m_path']?>/publishing_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$gp_code?>&item=<?=$BookID?>"><span class="label label-sm label-default">바로가기</span></a></td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='10' align='center'>검색된 게시글이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/publishing_books_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
												<i class="fa fa-pencil"></i>
												신규 등록
											</button>
										</div>
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