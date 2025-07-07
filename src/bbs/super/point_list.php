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
		<li class="active">포인트 사용내역</li>
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
			포인트 사용내역
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
									<?php
										$search = $_REQUEST['search'] ?? '';
										$searchs = $_REQUEST['searchs'] ?? '';
										$search_sql = '';

										if(!empty($searchs)) {
											if($search =="a"){
												$search_sql = "where ep_code like '%$searchs%'";
											}else if($search =="b"){
												$search_sql = "where mb_code like '%$searchs%'";
											}else if($search =="c"){
												$search_sql = "where pt_content like '%$searchs%'";
											}
										}

										$row = sql_fetch(" select count(*) as cnt from {$iw['point_table']} $search_sql ");
										$total_count = $row['cnt'] ?? 0;
										$row = sql_fetch(" select sum(pt_deposit) as cnt from {$iw['point_table']} $search_sql ");
										$total_deposit = $row['cnt'] ?? 0;
										$row = sql_fetch(" select sum(pt_withdraw) as cnt from {$iw['point_table']} $search_sql ");
										$total_withdraw = $row['cnt'] ?? 0;
									?>
									<div class="col-sm-6">
										(건수 : <?php echo number_format($total_count); ?>) (적립 : <?php echo number_format($total_deposit); ?>) (사용 : <?php echo number_format($total_withdraw); ?>) (잔액 : <?php echo number_format($total_deposit - $total_withdraw); ?>)
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right text-end">
											<form name="search_form" id="search_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>" method="post" class="text-end" style="margin-bottom:0;">
												<label style="font-size:1.2rem; font-weight:500;">검색: 
													<select name="search" class="form-select form-select-lg" style="font-size:1.1rem; display:inline-block; width:12rem; min-width:180px; height:120%; min-height:3.2rem; vertical-align:middle;">
														<option value="a" <?php if($search == "a"){ echo 'selected="selected"'; }?>>업체코드</option>
														<option value="b" <?php if($search == "b"){ echo 'selected="selected"'; }?>>회원코드</option>
														<option value="c" <?php if($search == "c"){ echo 'selected="selected"'; }?>>내용</option>
													</select>
												</label>
												<input type="text" name="searchs" value="<?php echo htmlspecialchars($searchs, ENT_QUOTES); ?>" class="form-control form-control-lg" style="font-size:1.1rem; width:auto; display:inline-block; vertical-align:middle; margin-left:8px; height:120%; min-height:2.5rem;">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th class="text-center">날짜</th>
											<th class="text-center">업체명</th>
											<th class="text-center">회원코드</th>
											<th class="text-center">닉네임</th>
											<th class="text-center">내용</th>
											<th class="text-center">적립</th>
											<th class="text-center">사용</th>
											<th class="text-center">잔액</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from {$iw['point_table']} $search_sql";
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

										$sql = "select * from {$iw['point_table']} $search_sql order by pt_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$pt_deposit = $row["pt_deposit"];
											$pt_withdraw = $row["pt_withdraw"];
											$pt_balance = $row["pt_balance"];
											$pt_content = $row["pt_content"];
											$pt_datetime = $row["pt_datetime"];
											$mb_code = $row["mb_code"];
											$ep_code = $row["ep_code"];


											$row2 = sql_fetch("select * from {$iw['member_table']} where mb_code = '$mb_code'");
											$mb_nick = $row2["mb_nick"] ?? '';

											$row2 = sql_fetch("select ep_corporate from {$iw['enterprise_table']} where ep_code = '$ep_code'");
											$ep_corporate = $row2["ep_corporate"] ?? '';
									?>
										<tr>
											<td class="text-center" data-title="날짜"><?php echo $pt_datetime; ?></td>
											<td class="text-center" data-title="업체명"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&search=a&searchs=<?php echo $ep_code; ?>" ><?php echo $ep_corporate; ?></a></td>
											<td class="text-center" data-title="회원코드"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&search=b&searchs=<?php echo $mb_code; ?>" ><?php echo $mb_code; ?></a></td>
											<td class="text-center" data-title="닉네임"><?php echo $mb_nick; ?></td>
											<td class="text-center" data-title="내용"><?php echo $pt_content; ?></td>
											<td class="text-end" data-title="적립"><?php echo $pt_deposit; ?></td>
											<td class="text-end" data-title="사용"><?php echo $pt_withdraw; ?></td>
											<td class="text-end" data-title="잔액"><?php echo $pt_balance; ?></td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>포인트 내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info"><!--페이지/전체--></div>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right text-end">
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



