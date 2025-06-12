<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] == "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">가입한 그룹</li>
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
			가입한 그룹
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
											<!--<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?if($search == "a"){?>selected="selected"<?}?>>그룹코드</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>-->
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>그룹코드</th>
											<th>그룹이름</th>
											<th>가입일</th>
											<th>상태</th>
											<th>링크</th>
										</tr>
									</thead>
									<tbody>
									<?
										$row = sql_fetch(" select * from $iw[enterprise_table] where ep_code = '$iw[store]' ");
										$ep_nick = $row["ep_nick"];
										$ep_state_mcb = $row["ep_state_mcb"];
										$ep_state_publishing = $row["ep_state_publishing"];
										$ep_state_doc = $row["ep_state_doc"];
										$ep_state_shop = $row["ep_state_shop"];
										$ep_state_book = $row["ep_state_book"];

										$sql = "select * from $iw[group_member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
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

										$sql = "select * from $iw[group_member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' order by gm_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$gp_code = $row["gp_code"];
											$gm_display = $row["gm_display"];
											$gm_datetime = date("Y.m.d", strtotime($row["gm_datetime"]));

											$sqlg = "select * from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$gp_code'";
											$rowg = sql_fetch($sqlg);
											$gp_subject = $rowg["gp_subject"];
											$gp_nick = $rowg["gp_nick"];
											$mb_code = $rowg["mb_code"];
									?>
										<tr>
											<td data-title="그룹코드"><?=$gp_code?></td>
											<td data-title="그룹이름"><?=$gp_subject?></td>
											<td data-title="가입일"><?=$gm_datetime?></td>
											<td data-title="상태">
												<?if($gm_display == 9){?>
													<span class="label label-sm label-success">관리자</span>
												<?}else if($gm_display == 1){?>
													<span class="label label-sm label-success">회원</span>
												<?}else if($gm_display == 0){?>
													<span class="label label-sm label-warning">가입대기</span>
												<?}?>
											</td>
											<td data-title="링크">
												<a href="<?=$iw[m_path]?>/main.php?type=main&ep=<?=$iw[store]?>&gp=<?=$gp_code?>" target="_blank">바로가기</a>
											</td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='5' align='center'>가입한 그룹이 없습니다.</td></tr>";
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