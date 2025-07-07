<?php
include_once("_common.php");
include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">회원정보</li>
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
			회원정보
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
												$search = $_REQUEST['search'] ?? '';
												$searchs = $_REQUEST['searchs'] ?? '';
												$search_sql = '';

												if(!empty($searchs)) {
													if($search =="a"){
														$search_sql = "where mb_name like '%$searchs%'";
													}else if($search =="b"){
														$search_sql = "where mb_nick like '%$searchs%'";
													}else if($search =="c"){
														$search_sql = "where mb_mail like '%$searchs%'";
													}else if($search =="d"){
														$search_sql = "where mb_code like '%$searchs%'";
													}else if($search =="e"){
														$search_sql = "where ep_code like '%$searchs%'";
													}
												}
											?>
											<form name="search_form" id="search_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>" method="post" class="text-end" style="margin-bottom:0;">
											<label style="font-size:1.2rem; font-weight:500;">검색: <select name="search" class="form-select form-select-lg" style="font-size:1.1rem; display:inline-block; width:12rem; min-width:180px; height:120%; min-height:3.2rem; vertical-align:middle;">
												<option value="a" <?php if($search == "a"){ echo 'selected="selected"'; }?>>이름</option>
												<option value="b" <?php if($search == "b"){ echo 'selected="selected"'; }?>>닉네임</option>
												<option value="c" <?php if($search == "c"){ echo 'selected="selected"'; }?>>아이디</option>
												<option value="d" <?php if($search == "d"){ echo 'selected="selected"'; }?>>회원코드</option>
												<option value="e" <?php if($search == "e"){ echo 'selected="selected"'; }?>>업체코드</option>
											</select></label><input type="text" name="searchs" value="<?php echo htmlspecialchars($searchs, ENT_QUOTES); ?>" class="form-control form-control-lg" style="font-size:1.1rem; width:auto; display:inline-block; vertical-align:middle; margin-left:8px; height:120%; min-height:2.5rem;">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th class="text-center">업체명</th>
											<th class="text-center">등급</th>
											<th class="text-center">회원코드</th>
											<th class="text-center">이름</th>
											<th class="text-center">닉네임</th>
											<th class="text-center">아이디</th>
											<th class="text-center">휴대폰</th>
											<th class="text-center">가입일시</th>
											<th class="text-center">마지막로그인</th>
											<th class="text-end">포인트</th>
											<th class="text-center">관리</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from {$iw['member_table']} $search_sql";
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

										$sql = "select * from {$iw['member_table']} $search_sql order by mb_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$ep_code = $row["ep_code"];
											$mb_code = $row["mb_code"];
											$mb_mail = $row["mb_mail"];
											$mb_name = $row["mb_name"];
											$mb_nick = $row["mb_nick"];
											$mb_tel = $row["mb_tel"];
											$mb_point = $row["mb_point"];
											$mb_display = $row["mb_display"];
											$mb_level = $row["mb_level"];
											$mb_datetime = $row["mb_datetime"];
											$mb_login_datetime = $row["mb_login_datetime"];

											$row2 = sql_fetch("select ep_corporate from {$iw['enterprise_table']} where ep_code = '$ep_code'");
											$ep_corporate = $row2["ep_corporate"] ?? '';
									?>
										<tr>
											<td class="text-center" data-title="업체명"><?php echo $ep_corporate; ?></td>
											<td class="text-center" data-title="등급">
											<?php
											if ($row['mb_display'] == 9){
												echo "최고관리자";
											}else if ($row['mb_display'] == 7){
												echo "업체관리자";
											}else if ($row['mb_display'] == 4){
												echo "판매자";
											}else{
												echo "회원";
											}
											?>
											</td>
											<td class="text-center" data-title="회원코드"><a href="<?php echo $iw['super_path']; ?>/member_data_view.php?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&idx=<?php echo $mb_code; ?>"><?php echo $mb_code; ?></a></td>
											<td class="text-center" data-title="이름"><?php echo $mb_name; ?></td>
											<td class="text-center" data-title="닉네임"><?php echo $mb_nick; ?></td>
											<td class="text-center" data-title="아이디"><?php echo $mb_mail; ?></td>
											<td class="text-center" data-title="휴대폰"><?php echo $mb_tel; ?></td>
											<td class="text-center" data-title="가입일시"><?php echo $mb_datetime; ?></td>
											<td class="text-center" data-title="마지막로그인"><?php echo $mb_login_datetime; ?></td>
											<td class="text-end" data-title="포인트"><?php echo $mb_point; ?></td>
											<td class="text-center" data-title="관리">
												<a href="javascript:master_key('<?php echo $iw['type']; ?>', '<?php echo $iw['store']; ?>', '<?php echo $iw['group']; ?>','<?php echo $mb_code; ?>','<?php echo $ep_code; ?>','all');"><span class="label label-sm label-success">로그인</span></a>
												<a href="javascript:master_point('<?php echo $iw['type']; ?>', '<?php echo $iw['store']; ?>', '<?php echo $iw['group']; ?>','<?php echo $mb_code; ?>','<?php echo $ep_code; ?>','all');"><span class="label label-sm label-primary">포인트</span></a>
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='11' align='center'>검색된 회원정보가 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
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

<script type="text/javascript">
	function master_key(type, ep, gp, mb_code, ep_code, gp_code) {
		location.href="master_key_login.php?type="+type+"&ep="+ep+"&gp="+gp+"&mep="+ep_code+"&mgp="+gp_code+"&mmb="+mb_code;
	}
	function master_point(type, ep, gp, mb_code, ep_code, gp_code) {
		location.href="master_point.php?type="+type+"&ep="+ep+"&gp="+gp+"&mep="+ep_code+"&mgp="+gp_code+"&mmb="+mb_code;
	}
</script>

<?php
include_once("_tail.php");
?>



