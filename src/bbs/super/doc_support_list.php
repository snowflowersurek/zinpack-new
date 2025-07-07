<?php
include_once("_common.php");
include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-line-chart"></i>
			매출내역
		</li>
		<li class="active">컨텐츠몰 후원내역</li>
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
			컨텐츠몰 후원내역
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
										<div class="dataTable-option-right text-end">
											<?php
												$search = $_REQUEST['search'] ?? '';
												$searchs = $_REQUEST['searchs'] ?? '';
												$search_sql = '';
												if(!empty($searchs)) {
													if($search =="a"){
														$search_sql = "where ep_code like '%$searchs%'";
													}else if($search =="b"){
														$search_sql = "where seller_mb_code like '%$searchs%'";
													}else if($search =="c"){
														$search_sql = "where dd_code like '%$searchs%'";
													}else if($search =="d"){
														$search_sql = "where mb_code like '%$searchs%'";
													}else if($search =="e"){
														$search_sql = "where ds_subject like '%$searchs%'";
													}
												}
											?>
											<form name="search_form" id="search_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>" method="post" class="text-end mb-3" style="margin-bottom:0;">
											<label style="font-size:1.2rem; font-weight:500;">검색:
												<select name="search" class="form-select form-select-lg d-inline-block" style="font-size:1.1rem; width:12rem; min-width:180px; height:120%; min-height:3.2rem; vertical-align:middle;">
													<option value="a" <?php if($search == "a"){ echo 'selected="selected"'; }?>>업체코드</option>
													<option value="b" <?php if($search == "b"){ echo 'selected="selected"'; }?>>판매자코드</option>
													<option value="c" <?php if($search == "c"){ echo 'selected="selected"'; }?>>컨텐츠코드</option>
													<option value="d" <?php if($search == "d"){ echo 'selected="selected"'; }?>>후원회원코드</option>
													<option value="e" <?php if($search == "e"){ echo 'selected="selected"'; }?>>컨텐츠명</option>
												</select>
											</label>
											<input type="text" name="searchs" value="<?php echo htmlspecialchars($searchs, ENT_QUOTES); ?>" class="form-control form-control-lg d-inline-block" style="font-size:1.1rem; width:auto; min-width:180px; margin-left:8px; height:120%; min-height:2.5rem; vertical-align:middle;">
											<button type="submit" class="btn btn-lg btn-primary ms-2">검색</button>
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable align-middle">
									<thead>
										<tr>
											<th class="text-center">업체명</th>
											<th class="text-center">판매자코드</th>
											<th class="text-center">컨텐츠코드</th>
											<th class="text-center">후원회원코드</th>
											<th class="text-center">컨텐츠명</th>
											<th class="text-end">후원금액</th>
											<th class="text-center">날짜</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from {$iw['doc_support_table']} $search_sql";
										$result = sql_query($sql);
										$total_line = mysqli_num_rows($result);

										$max_line = 10;
										$max_page = 10;
											
										$page = $_GET["page"] ?? 1;
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

										$sql = "select * from {$iw['doc_support_table']} $search_sql order by ds_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$ds_subject = $row["ds_subject"] ?? '';
											$dd_code = $row["dd_code"] ?? '';
											$ep_code = $row["ep_code"] ?? '';
											$mb_code = $row["mb_code"] ?? '';
											$ds_datetime = $row["ds_datetime"] ?? '';
											$ds_price = $row["ds_price"] ?? 0;
											$seller_mb_code = $row["seller_mb_code"] ?? '';

											$row2 = sql_fetch("select ep_corporate from {$iw['enterprise_table']} where ep_code = '$ep_code'");
											$ep_corporate = $row2["ep_corporate"] ?? '';
									?>
										<tr>
											<td class="text-center align-middle" data-title="업체명"><?php echo $ep_corporate; ?></td>
											<td class="text-center align-middle" data-title="판매자코드"><?php echo $seller_mb_code; ?></td>
											<td class="text-center align-middle" data-title="컨텐츠코드"><?php echo $dd_code; ?></td>
											<td class="text-center align-middle" data-title="후원회원코드"><?php echo $mb_code; ?></td>
											<td class="text-center align-middle" data-title="컨텐츠명"><?php echo $ds_subject; ?></td>
											<td class="text-end align-middle" data-title="후원금액"><?php echo number_format($ds_price); ?> Point</td>
											<td class="text-center align-middle" data-title="판매일"><?php echo $ds_datetime; ?></td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='7' align='center'>후원내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info"><!--페이지/전체--></div>
									</div>
									<div class="col-sm-6">
										<div class="d-flex justify-content-end">
											<ul class="pagination justify-content-end" style="gap:4px;">
											<?php
												if($total_page!=0){
													if($page>$total_page) { $page=$total_page; }
													$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
													$end_page = $start_page+$max_page-1;
												 
													if($end_page>$total_page) {$end_page=$total_page;}
												 
													if($page>$max_page) {
														$pre = $start_page - 1;
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm' href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&page=$pre&search=$search&searchs=$searchs'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm disabled' href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='page-item'><a class='btn btn-secondary btn-sm active' href='#'>$i</a></li>";
														else          echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm' href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&page=$i&search=$search&searchs=$searchs'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm' href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&page=$next&search=$search&searchs=$searchs'><i class='fa fa-angle-double-right'></i></a></li>";
													} else {
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm disabled' href='#'><i class='fa fa-angle-double-right'></i></a></li>";
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



