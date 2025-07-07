<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$row[ep_nick]";
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">그림전시 신청내역</li>
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
			그림전시 신청내역
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
									<div class="col-xs-3">
										<div class="dataTable-option">
											<button class="btn btn-success btn-sm" type="button" onclick="location.href='<?=$iw['admin_path']?>/publishing_exhibit_status_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
												<i class="fa fa-download"></i>
												엑셀저장
											</button>
										</div>
									</div>
									<div class="col-xs-9">
										<div class="dataTable-option-right">
											<?php
												if($_POST['searchyear'] || $_POST['searchby'] || $_POST['keyword']){
													$searchyear = $_POST['searchyear'];
													$searchby = $_POST['searchby'];
													$keyword = $_POST['keyword'];
												}else{
													$searchyear = $_GET['searchyear'];
													$searchby = $_GET['searchby'];
													$keyword = $_GET['keyword'];
												}
												
												$search_sql = "";
												
												if ($searchyear != "") {
													$search_sql .= " and md_date between '$searchyear-01-01 00:00:00' and '$searchyear-12-31 23:59:59'";
												}
												
												if ($keyword != "") {
													if($searchby =="phone"){
														$search_sql .= " and (userTel like '%$keyword%' or userPhone like '%$keyword%')";
													} else {
														$search_sql .= " and $searchby like '%$keyword%'";
													}
												}
											?>
											<form name="search_form" id="search_form" class="form-inline" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
												<select name="searchyear" class="form-control" style="display:inline-block; width:80px; height:30px; padding:4px;">
													<option value="">전체</option>
													<?php
														for ($i=date("Y"); $i>=2015; $i--) {
															if ($i == $searchyear) {
																echo '<option value="'.$i.'" selected>'.$i.'년</option>';
															} else {
																echo '<option value="'.$i.'">'.$i.'년</option>';
															}
														}
													?>
												</select>
												<select name="searchby" class="form-control" style="display:inline-block; width:90px; height:30px; padding:4px;">
													<option value="picture_name" <?php if{?>selected="selected"<?php }?>>그림전시명</option>
													<option value="userName" <?php if{?>selected="selected"<?php }?>>신청자</option>
													<option value="strOrgan" <?php if{?>selected="selected"<?php }?>>기관명</option>
													<option value="phone" <?php if{?>selected="selected"<?php }?>>연락처</option>
												</select>
												<input type="text" name="keyword" class="form-control" value="<?=$keyword?>" style="display:inline-block; width:100px; padding:5px;" placeholder="검색어" />
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>번호</th>
											<th>신청자</th>
											<th>전시기관명</th>
											<th>그림전시명</th>
											<th>전시일정</th>
											<th>연락처</th>
											<th>신청일</th>
											<th>신청현황</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' $search_sql";
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
										
										$sql = "select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' $search_sql order by idx desc limit $start_line, $end_line";
										$result = sql_query($sql);
										
										$i=0;
										while($row = @sql_fetch_array($result)){
											$idx = $row["idx"];
											$stat = $row["stat"];
											$picture_name = $row["picture_name"];
											$year = $row["year"];
											$month = $row["month"];
											$userName = $row["userName"];
											$strOrgan = $row["strOrgan"];
											$userTel = $row["userTel"];
											$userPhone = $row["userPhone"];
											$homepage = $row["homepage"];
											$md_date = substr($row["md_date"], 0, 16);
											
											if ($year != "" && $month != "") {
												$exhibit_month = sprintf("%04d", $year)."년 ".sprintf("%02d", $month)."월";
											} else {
												$exhibit_month = "";
											}
									?>
										<tr>
											<td data-title="번호"><?=($total_line - $start_line - $i)?></td>
											<td data-title="신청자"><a href="<?=$iw['admin_path']?>/publishing_exhibit_status_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&id=<?=$idx?>"><?=$userName?></a></td>
											<td data-title="전시기관명"><?=$strOrgan?></td>
											<td data-title="그림전시명"><?=$picture_name?></td>
											<td data-title="전시일정"><?=$exhibit_month?></td>
											<td data-title="연락처"><?=$userTel?></td>
											<td data-title="신청일"><?=$md_date?></td>
											<td data-title="신청현황">
												<?php if($stat == "1"){?>
													<span class="label label-sm label-default">대기 중</span>
												<?php }else if($stat == "2"){?>
													<span class="label label-sm label-success">전시확정</span>
												<?php }else if($stat == "3"){?>
													<span class="label label-sm label-warning">보류</span>
												<?php }else if($stat == "4"){?>
													<span class="label label-sm label-danger">전시연기</span>
												<?php }?>
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>검색된 게시글이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-4">
										<div class="dataTable-info">
											
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
														echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&searchyear=$searchyear&searchby=$searchby&keyword=$keyword'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&searchyear=$searchyear&searchby=$searchby&keyword=$keyword'>$i</a></li>";
													}
												 	
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&searchyear=$searchyear&searchby=$searchby&keyword=$keyword'><i class='fa fa-angle-double-right'></i></a></li>";
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



