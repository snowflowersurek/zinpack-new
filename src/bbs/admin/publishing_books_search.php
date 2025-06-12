<?php
include_once("_common.php");
include_once("$iw[admin_path]/_head_sub.php");
?>

<div class="main-container" id="main-container">
	<div class="main-container-inner">
	<div class="main-content" id="main-content" style="margin:0;">

<div class="page-content">
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
									<div class="col-sm-12">
										<div class="dataTable-option-right">
											<?
												if($_POST['searchby']){
													$searchby = $_POST['searchby'];
													$keyword = $_POST['keyword'];
												}else{
													$searchby = $_GET['searchby'];
													$keyword = $_GET['keyword'];
												}
												if ($searchby =="author") {
													$search_sql = "and T.Author like '%$keyword%'";
												} else if ($searchby =="bookname") {
													$search_sql = "and B.BookName like '%$keyword%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="searchby">
												<option value="bookname" <?if($searchby == "bookname"){?>selected="selected"<?}?>>도서명</option>
												<option value="author" <?if($searchby == "author"){?>selected="selected"<?}?>>작가명</option>
											</select></label><input type="text" name="keyword" value="<?=$keyword?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th style="text-align: center;">도서명</th>
											<th style="text-align: center;">작가명</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "
											SELECT 
												B.BookID, B.BookName, T.AuthorID, T.Author 
											FROM $iw[publishing_books_table] B 
												LEFT JOIN (
													SELECT 
														BA.bookID, A.AuthorID, GROUP_CONCAT(A.Author SEPARATOR '.') as Author 
													FROM $iw[publishing_books_author_table] BA 
														LEFT JOIN $iw[publishing_author_table] A 
														on (BA.ep_code = A.ep_code and BA.authorID = A.AuthorID) 
													WHERE BA.ep_code = '$iw[store]' 
													GROUP BY BA.bookID 
												) as T 
												ON B.BookID = T.bookID 
											WHERE  B.ep_code = '$iw[store]' $search_sql 
											order by B.BookName
										";
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

										$sql = "
												SELECT 
													B.BookID, B.BookName, T.AuthorID, T.Author 
												FROM $iw[publishing_books_table] B 
													LEFT JOIN (
														SELECT 
															BA.bookID, A.AuthorID, GROUP_CONCAT(A.Author SEPARATOR '.') as Author 
														FROM $iw[publishing_books_author_table] BA 
															LEFT JOIN $iw[publishing_author_table] A 
															on (BA.ep_code = A.ep_code and BA.authorID = A.AuthorID) 
														WHERE BA.ep_code = '$iw[store]' 
														GROUP BY BA.bookID 
													) as T 
													ON B.BookID = T.bookID 
												where  B.ep_code = '$iw[store]' $search_sql 
												order by B.BookName 
												limit $start_line, $end_line
										";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$BookID = $row["BookID"];
											$BookName = stripslashes($row["BookName"]);
											$AuthorID = $row["AuthorID"];
											$Author = stripslashes($row["Author"]);
									?>
										<tr>
											<td data-title="도서명"><a href="javascript:selectBook('<?=$BookID?>','<?=$BookName?>');"><?=$BookName?></a></td>
											<td data-title="작가명"><?=$Author?></td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='2' align='center'>검색된 도서가 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-12">
										<div class="dataTable-option-right" style="text-align: center;">
											<ul class="pagination">
											<?
												if($total_page!=0){
													if($page>$total_page) { $page=$total_page; }
													$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
													$end_page = $start_page+$max_page-1;
												 	
													if($end_page>$total_page) {$end_page=$total_page;}
												 	
													if($page>$max_page) {
														$pre = $start_page - 1;
														echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&searchby=$searchby&keyword=$keyword'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&searchby=$searchby&keyword=$keyword'>$i</a></li>";
													}
												 	
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&searchby=$searchby&keyword=$keyword'><i class='fa fa-angle-double-right'></i></a></li>";
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
		</div><!-- /end .main-content -->
	</div><!-- /end .main-container-inner -->
</div><!-- /end  .main-container #main-container -->

<script type="text/javascript">
	function selectBook(code, name){
		opener.selectBook(code, name);
		window.close();
	}
</script>

</body>
</html>