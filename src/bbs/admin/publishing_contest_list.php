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
		<li class="active">공모전관리</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			공모전관리
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
												if($searchby =="subject"){
													$search_sql = "and subject like '%$keyword%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="searchby">
												<option value="subject" <?php if{?>selected="selected"<?php }?>>제목</option>
											</select></label><input type="text" name="keyword" value="<?=$keyword?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>번호</th>
											<th>분류</th>
											<th>제목</th>
											<th>공모기간</th>
											<th>공모상태</th>
											<th>등록일</th>
											<th>응모 작품수</th>
											<th>응모내역</th>
											<th>게시상태</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from iw_publishing_contest where ep_code = '$iw[store]' and gp_code = '$iw[group]' $search_sql";
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
										
										$sql = "select * from iw_publishing_contest where ep_code = '$iw[store]' and gp_code = '$iw[group]' $search_sql order by idx desc limit $start_line, $end_line";
										$result = sql_query($sql);
										
										$i=0;
										while($row = @sql_fetch_array($result)){
											$idx = $row["idx"];
											$gp_code = $row["gp_code"];
											$cg_code = $row["cg_code"];
											$contest_code = $row["contest_code"];
											$subject = stripslashes($row["subject"]);
											$start_date = substr($row["start_date"], 0, 10);
											$end_date = substr($row["end_date"], 0, 10);
											$reg_date = substr($row["reg_date"], 0, 10);
											$display = $row["display"];

											$sql2 = "select cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and state_sort = 'publishing_contest' and cg_code = '$cg_code'";
											$row2 = sql_fetch($sql2);
											$category_name = $row2["cg_name"];
											
											$sql3 = "select count(idx) as cnt from iw_publishing_contestant where ep_code = '$iw[store]' and gp_code = '$gp_code' and contest_code = '$contest_code'";
											$row3 = sql_fetch($sql3);
											$contestant_cnt = number_format($row3[cnt]);
									?>
										<tr>
											<td data-title="번호"><?=($total_line - $start_line - $i)?></td>
											<td data-title="분류"><?=$category_name?></td>
											<td data-title="제목"><a href="<?=$iw['admin_path']?>/publishing_contest_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&contest_code=<?=$contest_code?>"><?=$subject?></a></td>
											<td data-title="공모기간"><?=$start_date." ~ ".$end_date?></td>
											<td data-title="공모상태">
												<?php if(strtotime($end_date) - strtotime(date("Y-m-d")) > -1){?>
													<span class="label label-sm label-success">진행 중</span>
												<?php }else{?>
													<span class="label label-sm label-default">마감</span>
												<?php }?>
											</td>
											<td data-title="등록일"><?=$reg_date?></td>
											<td data-title="접수 작품수"><?=$contestant_cnt?></td>
											<td data-title="접수내역">
												<a href="<?=$iw['admin_path']?>/publishing_contestant_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$gp_code?>&contest_code=<?=$contest_code?>"><span class="label label-sm label-default">바로가기</span></a>
											</td>
											<td data-title="게시상태">
												<?php if($display==1){?>
													<span class="label label-sm label-success">노출</span>
												<?php }else{?>
													<span class="label label-sm label-warning">숨김</span>
												<?php }?>	
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='9' align='center'>데이터가 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-4">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/publishing_contest_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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



