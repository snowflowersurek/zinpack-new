<?php
include_once("_common.php");
include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-home"></i>
			결제시스템
		</li>
		<li class="active">취소응답</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			취소응답
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
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
											<?php
												if($_POST['search']){
													$search = $_POST['search'];
													$searchs = $_POST['searchs'];
												}else{
													$search = $_GET['search'];
													$searchs = $_GET['searchs'];
												}
												if($search =="a"){
													$search_sql = "where payment_domain like '%$searchs%'";
												}else if($search =="b"){
													$search_sql = "where lgd_oid like '%$searchs%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?php if{?>selected="selected"<?php }?>>도메인</option>
												<option value="b" <?php if{?>selected="selected"<?php }?>>주문코드</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>응답일자</th>
											<th>응답코드</th>
											<th>도메인</th>
											<th>LGU+거래번호</th>
											<th>주문코드</th>
											<th>회원명</th>
											<th>주문상품</th>
											<th>가격</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from $payment[cancel_response_table] $search_sql";
										$result = sql_query($sql);
										$total_line = mysqli_num_rows($result);

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

										$sql = "select * from $payment[cancel_response_table] $search_sql order by cancel_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$lgd_no = $row["lgd_no"];
											$payment_domain = $row["payment_domain"];
											$lgd_oid = $row["lgd_oid"];
											$lgd_tid = $row["lgd_tid"];
											$lgd_datetime = $row["lgd_datetime"];
											$lgd_respcode = $row["lgd_respcode"];
											
											$sql_sub = "select * from $payment[lgd_response_table] where lgd_oid = '$lgd_oid' and lgd_tid = '$lgd_tid'";
											$row_sub = sql_fetch($sql_sub);
											$lgd_buyer = $row_sub["lgd_buyer"];
											$lgd_productinfo = $row_sub["lgd_productinfo"];
											$lgd_amount = number_format($row_sub["lgd_amount"]);
									?>
										<tr>
											<td data-title="응답일자"><?=$lgd_datetime?></td>
											<td data-title="응답코드"><?=$lgd_respcode?></td>
											<td data-title="도메인"><?=$payment_domain?></td>
											<td data-title="LGU+거래번호"><?=$lgd_tid?></td>
											<td data-title="주문코드"><?=$lgd_oid?></td>
											<td data-title="회원명"><?=$lgd_buyer?></td>
											<td data-title="주문상품"><?=$lgd_productinfo?></td>
											<td data-title="가격"><?=$lgd_amount?></td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>취소응답 내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
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
														echo "<li class='prev'><a href='$PHP_SELF?page=$pre&search=$search&searchs=$searchs'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?page=$i&search=$search&searchs=$searchs'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?page=$next&search=$search&searchs=$searchs'><i class='fa fa-angle-double-right'></i></a></li>";
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



