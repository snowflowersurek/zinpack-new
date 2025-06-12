<?php
include_once("_common.php");
include_once("_head.php");
if ($iw['level'] != "admin") alert("잘못된 접근입니다!","");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">포인트 내역</li>
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
			포인트 내역
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
									<?
										if($_POST['search']){
											$search = $_POST['search'];
											$searchs = $_POST['searchs'];
										}else{
											$search = $_GET['search'];
											$searchs = $_GET['searchs'];
										}
										if($search =="a"){
											$search_sql = "and mb_code like '%$searchs%'";
										}else if($search =="b"){
											$search_sql = "and pt_content like '%$searchs%'";
										}

										$row = sql_fetch(" select count(*) as cnt from $iw[point_table] where ep_code = '$iw[store]' $search_sql ");
										$total_count = $row[cnt];
										$row = sql_fetch(" select sum(pt_deposit) as cnt from $iw[point_table] where ep_code = '$iw[store]' $search_sql ");
										$total_deposit = $row[cnt];
										$row = sql_fetch(" select sum(pt_withdraw) as cnt from $iw[point_table] where ep_code = '$iw[store]' $search_sql ");
										$total_withdraw = $row[cnt];
									?>
									<div class="col-sm-6">
										(건수 : <?=number_format($total_count)?>) (적립 : <?=number_format($total_deposit)?>) (사용 : <?=number_format($total_withdraw)?>) (잔액 : <?=number_format($total_deposit - $total_withdraw)?>)
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?if($search == "a"){?>selected="selected"<?}?>>회원코드</option>
												<option value="b" <?if($search == "b"){?>selected="selected"<?}?>>내용</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>날짜</th>
											<th>회원코드</th>
											<th>닉네임</th>
											<th>내용</th>
											<th>적립</th>
											<th>사용</th>
											<th>잔액</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select * from $iw[point_table] where ep_code = '$iw[store]' $search_sql";
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

										$sql = "select * from $iw[point_table] where ep_code = '$iw[store]' $search_sql order by pt_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$pt_deposit = $row["pt_deposit"];
											$pt_withdraw = $row["pt_withdraw"];
											$pt_balance = $row["pt_balance"];
											$pt_content = $row["pt_content"];
											$pt_datetime = date("Y.m.d", strtotime($row["pt_datetime"]));
											$mb_code = $row["mb_code"];

											$row2 = sql_fetch("select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'");
											$mb_nick = $row2["mb_nick"];
									?>
										<tr>
											<td data-title="날짜"><?=$pt_datetime?></td>
											<td data-title="회원코드"><a href="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&search=a&searchs=<?=$mb_code?>" ><?=$mb_code?></a></td>
											<td data-title="닉네임"><?=$mb_nick?></td>
											<td data-title="내용"><?=$pt_content?></td>
											<td data-title="적립"><?=$pt_deposit?></td>
											<td data-title="사용"><?=$pt_withdraw?></td>
											<td data-title="잔액"><?=$pt_balance?></td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='7' align='center'>포인트 내역이 없습니다.</td></tr>";
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