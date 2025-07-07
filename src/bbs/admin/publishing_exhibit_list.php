<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">그림전시관리</li>
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
			그림전시관리
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
											
										</div>
									</div>
									<div class="col-sm-8">
										<div class="dataTable-option-right">
											<?php
												if($_POST['searchby']){
													$searchby = $_POST['searchby'];
													$keyword = $_POST['keyword'];
												}else{
													$searchby = $_GET['searchby'];
													$keyword = $_GET['keyword'];
												}
												if($searchby =="name"){
													$search_sql = "and picture_name like '%$keyword%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="searchby">
												<option value="name" <?php if{?>selected="selected"<?php }?>>그림전시명</option>
											</select></label><input type="text" name="keyword" value="<?=$keyword?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>번호</th>
											<th>그림전시명</th>
											<th>특별기획전</th>
											<th>액자수(점)</th>
											<th>액자크기</th>
											<th>대여가능</th>
											<th>도서정보</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from $iw[publishing_exhibit_table] where ep_code = '$iw[store]' $search_sql";
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
										
										$sql = "select * from $iw[publishing_exhibit_table] where ep_code = '$iw[store]' $search_sql order by picture_idx desc limit $start_line, $end_line";
										$result = sql_query($sql);
										
										$i=0;
										while($row = @sql_fetch_array($result)){
											$picture_idx = $row["picture_idx"];
											$gp_code = $row["gp_code"];
											$picture_name = stripslashes($row["picture_name"]);
											$special = stripslashes($row["special"]);
											$how_many = $row["how_many"];
											$size = $row["size"];
											$can_rent = $row["can_rent"];
											$book_id = $row["book_id"];
									?>
										<tr>
											<td data-title="번호"><?=($total_line - $start_line - $i)?></td>
											<td data-title="그림전시명"><a href="<?=$iw['admin_path']?>/publishing_exhibit_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&id=<?=$picture_idx?>"><?=$picture_name?></a></td>
											<td data-title="특별기획전"><?=$special?></td>
											<td data-title="액자수(점)"><?=$how_many?></td>
											<td data-title="액자크기"><?=$size?></td>
											<td data-title="대여가능">
												<?php if($can_rent == "Y"){?>
													<span class="label label-sm label-success">가능</span>
												<?php }else if($can_rent == "M"){?>
													<span class="label label-sm label-warning">점검</span>
												<?php }else{?>
													<span class="label label-sm label-danger">불가</span>
												<?php }?>
											</td>
											<td data-title="도서정보">
												<?php if($book_id != ""){?>
													<a href="<?=$iw['m_path']?>/publishing_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$gp_code?>&item=<?=$book_id?>" target="_blank"><span class="label label-sm label-default">바로가기</span></a>
												<?php }else{?>
													&nbsp;
												<?php }?>
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='7' align='center'>검색된 게시글이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-4">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/publishing_exhibit_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
												<i class="fa fa-pencil"></i>
												신규 등록
											</button>
										</div>
									</div>
									<div class="col-sm-8">
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

<?php
include_once("_tail.php");
?>



