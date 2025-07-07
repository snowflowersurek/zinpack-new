<?php
include_once("_common.php");
if ($iw[type] != "shop" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-shopping-cart"></i>
			쇼핑몰
		</li>
		<li class="active">판매자등록 승인</li>
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
			판매자등록 승인
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
													$search_sql = "and mb_code like '%$searchs%'";
												}else if($search =="b"){
													$search_sql = "and ss_name like '%$searchs%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?php if{?>selected="selected"<?php }?>>회원코드</option>
												<option value="b" <?php if{?>selected="selected"<?php }?>>판매자(상호명)</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>회원코드</th>
											<th>판매자(상호명)</th>
											<th>전화번호</th>
											<th>신청일</th>
											<th>승인</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from $iw[shop_seller_table] where ep_code = '$iw[store]' and ss_display = 0 $search_sql";
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

										$sql = "select * from $iw[shop_seller_table] where ep_code = '$iw[store]' and ss_display = 0 $search_sql order by ss_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$ss_no = $row["ss_no"];
											$mb_code = $row["mb_code"];
											$ss_name = $row["ss_name"];
											$ss_tel = $row["ss_tel"];
											$ss_datetime = date("Y.m.d", strtotime($row["ss_datetime"]));
									?>
										<tr>
											<td data-title="회원코드"><a href="<?=$iw['admin_path']?>/shop_seller_approval_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$ss_no?>"><?=$mb_code?></a></td>
											<td data-title="판매자(상호명)"><?=$ss_name?></td>
											<td data-title="전화번호"><?=$ss_tel?></td>
											<td data-title="신청일"><?=$ss_datetime?></td>
											<td data-title="승인"><a href="javascript:approval_edit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$ss_no?>','1');"><span class="label label-sm label-success">승인하기</span></a></td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='5' align='center'>승인할 판매자가 없습니다.</td></tr>";
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
											<?php
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

<script type="text/javascript">
	function approval_edit(type,ep,gp,no,dis) {
		if (confirm("판매자등록을 승인하시겠습니까?")) {
			location.href="shop_seller_approval_ok.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+no+"&dis="+dis;
		}
	}
</script>
<?php
include_once("_tail.php");
?>



