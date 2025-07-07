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
											<?php
												if($_POST['searchby']){
													$searchby = $_POST['searchby'];
													$keyword = $_POST['keyword'];
												}else{
													$searchby = $_GET['searchby'];
													$keyword = $_GET['keyword'];
												}
												if($searchby =="author"){
													$search_sql = "and author like '%$keyword%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="searchby">
												<option value="author" <?php if{?>selected="selected"<?php }?>>작가명</option>
											</select></label><input type="text" name="keyword" value="<?=$keyword?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th style="text-align: center;">작가명</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from $iw[publishing_author_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' $search_sql";
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

										$sql = "select * from $iw[publishing_author_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' $search_sql order by Author limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$AuthorID = $row["AuthorID"];
											$cg_code = $row["cg_code"];
											$Author = stripslashes($row["Author"]);
											$RegDate = substr($row["RegDate"], 0, 8);
											$mb_code = $row["mb_code"];
											$Hit = $row["Hit"];
											$author_display = $row["author_display"];
											$gp_code = $row["gp_code"];
											$author_recommend = $row["author_recommend"];
									?>
										<tr>
											<td data-title="작가명"><a href="javascript:selectAuthor('<?=$AuthorID?>','<?=$Author?>');"><?=$Author?></a></td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td align='center'>검색된 작가가 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-12">
										<div class="dataTable-option-right" style="text-align: center;">
											<ul class="pagination">
											<?php
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
	function selectAuthor(code, name){
		opener.selectAuthor(code, name);
		window.close();
	}
</script>

</body>
</html>



