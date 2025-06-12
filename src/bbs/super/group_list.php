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
		<li class="active">그룹정보</li>
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
			그룹정보
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
														$search_sql = "and ep_code like '%$searchs%'";
													}else if($search =="b"){
														$search_sql = "and gp_code like '%$searchs%'";
													}else if($search =="c"){
														$search_sql = "and gp_subject like '%$searchs%'";
													}else if($search =="d"){
														$search_sql = "and gp_nick like '%$searchs%'";
													}else if($search =="e"){
														$search_sql = "and mb_code like '%$searchs%'";
													}
												}
											?>
											<form name="search_form" id="search_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>" method="post" class="text-end" style="margin-bottom:0;">
											<label style="font-size:1.2rem; font-weight:500;">검색: <select name="search" class="form-select form-select-lg" style="font-size:1.1rem; display:inline-block; width:12rem; min-width:180px; height:120%; min-height:3.2rem; vertical-align:middle;">
												<option value="a" <?php if($search == "a"){ echo 'selected="selected"'; }?>>업체코드</option>
												<option value="b" <?php if($search == "b"){ echo 'selected="selected"'; }?>>그룹코드</option>
												<option value="c" <?php if($search == "c"){ echo 'selected="selected"'; }?>>그룹이름</option>
												<option value="d" <?php if($search == "d"){ echo 'selected="selected"'; }?>>그룹주소</option>
												<option value="e" <?php if($search == "e"){ echo 'selected="selected"'; }?>>회원코드</option>
											</select></label><input type="text" name="searchs" value="<?php echo htmlspecialchars($searchs, ENT_QUOTES); ?>" class="form-control form-control-lg" style="font-size:1.1rem; width:auto; display:inline-block; vertical-align:middle; margin-left:8px; height:120%; min-height:2.5rem;">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th class="text-center">업체명</th>
											<th class="text-center">그룹코드</th>
											<th class="text-center">그룹이름</th>
											<th class="text-center">회원수</th>
											<th class="text-center">개설일자</th>
											<th class="text-center">가입방식</th>
											<th class="text-center">마스터</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from {$iw['group_table']} where gp_display = 1 $search_sql";
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

										$sql = "select * from {$iw['group_table']} where gp_display = 1 $search_sql order by gp_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$ep_code = $row["ep_code"];
											$gp_no = $row["gp_no"];
											$gp_code = $row["gp_code"];
											$mb_code = $row["mb_code"];
											$gp_nick = $row["gp_nick"];
											$gp_subject = $row["gp_subject"];
											$gp_type = $row["gp_type"];
											$gp_datetime = date("Y.m.d", strtotime($row["gp_datetime"]));
											
											$row1 = sql_fetch(" select count(*) as cnt from {$iw['group_member_table']} where ep_code = '$ep_code' and gp_code = '$gp_code'");
											$gp_total = number_format($row1["cnt"] ?? 0);

											$row2 = sql_fetch("select ep_corporate from {$iw['enterprise_table']} where ep_code = '$ep_code'");
											$ep_corporate = $row2["ep_corporate"] ?? '';
									?>
										<tr>
											<td class="text-center" data-title="업체명"><?php echo $ep_corporate; ?></td>
											<td class="text-center" data-title="그룹코드"><?php echo $gp_code; ?></td>
											<td class="text-center" data-title="그룹이름"><?php echo $gp_subject; ?></td>
											<td class="text-end" data-title="회원수"><?php echo $gp_total; ?></td>
											<td class="text-center" data-title="개설일자"><?php echo $gp_datetime; ?></td>
											<td class="text-center" data-title="가입방식">
												<?php if($gp_type == 0){?>
													가입불가
												<?php }else if($gp_type == 1){?>
													관리자승인
												<?php }else if($gp_type == 2){?>
													자동가입
												<?php }else if($gp_type == 4){?>
													가입초대
												<?php }else if($gp_type == 5){?>
													가입코드입력
												<?php }?>
											</td>
											<td class="text-center" data-title="마스터">
												<a href="javascript:master_key('<?php echo $iw['type']; ?>', '<?php echo $iw['store']; ?>', '<?php echo $iw['group']; ?>','<?php echo $mb_code; ?>','<?php echo $ep_code; ?>','<?php echo $gp_code; ?>');"><span class="label label-sm label-success">로그인</span></a>
												<a href="javascript:master_point('<?php echo $iw['type']; ?>', '<?php echo $iw['store']; ?>', '<?php echo $iw['group']; ?>','<?php echo $mb_code; ?>','<?php echo $ep_code; ?>','<?php echo $gp_code; ?>');"><span class="label label-sm label-primary">포인트</span></a>
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='7' align='center'>등록된 그룹이 없습니다.</td></tr>";
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