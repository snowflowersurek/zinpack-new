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
		<li class="active">업체정보</li>
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
			업체정보
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
										<div class="dataTable-option-right text-center">
											<?php
												$search = $_REQUEST['search'] ?? '';
												$searchs = $_REQUEST['searchs'] ?? '';
												$search_sql = '';

												if(!empty($searchs)) {
													if($search =="a"){
														$search_sql = "and ep_code like '%$searchs%'";
													}else if($search =="b"){
														$search_sql = "and ep_corporate like '%$searchs%'";
													}else if($search =="c"){
														$search_sql = "and ep_permit_number like '%$searchs%'";
													}else if($search =="d"){
														$search_sql = "and ep_nick like '%$searchs%'";
													}
												}
											?>
											<form name="search_form" id="search_form" action="<?php echo $_SERVER['PHP_SELF']; ?>?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>" method="post" class="text-end" style="margin-bottom:0;">
											<label style="font-size:1.2rem; font-weight:500;">검색: <select name="search" class="form-select form-select-lg" style="font-size:1.1rem; display:inline-block; width:12rem; min-width:180px; height:120%; min-height:3.2rem; vertical-align:middle;">
												<option value="a" <?php if($search == "a"){ echo 'selected="selected"'; }?>>사이트코드</option>
												<option value="b" <?php if($search == "b"){ echo 'selected="selected"'; }?>>업체명</option>
												<option value="c" <?php if($search == "c"){ echo 'selected="selected"'; }?>>사업자번호</option>
												<option value="d" <?php if($search == "d"){ echo 'selected="selected"'; }?>>포워딩ID</option>
											</select></label><input type="text" name="searchs" value="<?php echo htmlspecialchars($searchs, ENT_QUOTES); ?>" class="form-control form-control-lg" style="font-size:1.1rem; width:auto; display:inline-block; vertical-align:middle; margin-left:8px; height:120%; min-height:2.5rem;">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th class="text-center">사이트코드</th>
											<th class="text-center">업체명</th>
											<th class="text-center">이메일</th>
											<th class="text-center">포워딩ID</th>
											<th class="text-center">회원수</th>
											<th class="text-center">사용현황</th>
											<th class="text-center">생성일</th>
											<th class="text-center">만료일</th>
											<th class="text-center">마스터</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from {$iw['enterprise_table']} where ep_code<>'{$iw['store']}' AND ep_code NOT LIKE '%.del' $search_sql";
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

										$sql = "select * from {$iw['enterprise_table']} where ep_code<>'{$iw['store']}' AND ep_code NOT LIKE '%.del' $search_sql order by ep_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$ep_no = $row["ep_no"];
											$ep_code = $row["ep_code"];
											$mb_code = $row["mb_code"];
											$ep_nick = $row["ep_nick"];
											$ep_corporate = $row["ep_corporate"];
											$ep_permit_number = $row["ep_permit_number"];
											$ep_state_mcb = $row["ep_state_mcb"];
											$ep_state_publishing = $row["ep_state_publishing"];
											$ep_state_doc = $row["ep_state_doc"];
											$ep_state_shop = $row["ep_state_shop"];
											$ep_state_book = $row["ep_state_book"];
											$ep_datetime = $row["ep_datetime"];
											$ep_expiry = $row["ep_expiry_date"];
											$avail_type = $row["avail_type"];

											$sql_sub = " select * from {$iw['member_table']} where mb_code = '$mb_code'";
											$row_sub = sql_fetch($sql_sub);
											$mb_name = $row_sub["mb_name"] ?? '';
											$mb_mail = $row_sub["mb_mail"] ?? '';

											$row1 = sql_fetch(" select count(*) as cnt from {$iw['member_table']} where ep_code = '".str_replace(".del", "", $ep_code)."'");
											$ep_total = number_format($row1["cnt"] ?? 0);
									?>
										<tr>
											<td class="text-center" data-title="사이트코드"><a href="<?php echo $iw['super_path']; ?>/enterprise_view.php?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>&idx=<?php echo $ep_no; ?>"><?php echo $ep_code; ?></a></td>
											<td class="text-center" data-title="업체명"><a href="<?php echo $iw['m_path']; ?>/main.php?type=main&ep=<?php echo $ep_code; ?>&gp=all" target="_blank"><?php echo $ep_corporate; ?> <i class="fa fa-external-link"></i></a></td>
											<td class="text-center" data-title="이메일"><?php echo $mb_mail; ?></td>
											<td class="text-center" data-title="포워딩ID"><?php echo $ep_nick; ?></td>
											<td class="text-end" data-title="회원수"><?php echo $ep_total; ?></td>
											<td class="text-center" data-title="사용현황">
												<div class="action-icon">
													<?php if($ep_state_mcb == 1){?><i class="fa fa-clipboard"></i><?php }?>
													<?php if($ep_state_publishing == 1){?><i class="fa fa-book"></i><?php }?>
													<?php if($ep_state_shop == 1){?><i class="fa fa-shopping-cart"></i><?php }?>
													<?php if($ep_state_doc == 1){?><i class="fa fa-inbox"></i><?php }?>
													<?php if($ep_state_book == 1){?><i class="fa fa-newspaper-o"></i><?php }?>
												</div>
											</td>
											<td class="text-center" data-title="생성일"><?php echo substr($ep_datetime, 0, 10); ?></td>
											<td class="text-center" data-title="만료일"><?php echo $ep_expiry; ?></td>
											<td class="text-center" data-title="마스터">
												<?php
												if (substr_compare($ep_code, ".del", -strlen(".del")) === 0 || $avail_type==4 || $avail_type==0) {
												?>
												<a href="javascript:ep_all_delete('<?php echo $iw['type']; ?>', '<?php echo $iw['store']; ?>', '<?php echo $iw['group']; ?>','<?php echo $ep_code; ?>');"><span class="label label-sm label-danger">삭제</span></a>
												<?php 
												} else {
												?>
												<a href="javascript:master_key('<?php echo $iw['type']; ?>', '<?php echo $iw['store']; ?>', '<?php echo $iw['group']; ?>','<?php echo $mb_code; ?>','<?php echo $ep_code; ?>','all');"><span class="label label-sm label-success">로그인</span></a>
												<a href="javascript:master_point('<?php echo $iw['type']; ?>', '<?php echo $iw['store']; ?>', '<?php echo $iw['group']; ?>','<?php echo $mb_code; ?>','<?php echo $ep_code; ?>','all');"><span class="label label-sm label-primary">포인트</span></a>
												<?php
												}
												?>
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>등록된 사이트가 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?php echo $iw['super_path']; ?>/enterprise_write.php?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>'">
												<i class="fa fa-check"></i>
												사이트 생성
											</button>
											<button class="btn btn-success" type="button" onclick="location.href='<?php echo $iw['super_path']; ?>/enterprise_excel.php?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>'">
												<i class="fa fa-download"></i>
												엑셀저장
											</button>
										</div>
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

													// 이전 버튼
													if($page>$max_page) {
														$pre = $start_page - 1;
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm' href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&page=$pre&search=$search&searchs=$searchs'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm disabled' href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}

													// 페이지 번호
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='page-item'><a class='btn btn-secondary btn-sm active' href='#'>$i</a></li>";
														else          echo "<li class='page-item'><a class='btn btn-outline-secondary btn-sm' href='{$_SERVER['PHP_SELF']}?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&page=$i&search=$search&searchs=$searchs'>$i</a></li>";
													}

													// 다음 버튼
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
	function ep_all_delete(type, ep, gp, ep_code){
		if(confirm("삭제하시면 모든 데이터와 자료가 지워집니다.\n정말 삭제하시겠습니까?")){
			location.href="del_enterprise.php?type="+type+"&ep="+ep+"&gp="+gp+"&epcode="+ep_code;
		}
	}
</script>

<?php
include_once("_tail.php");
?>