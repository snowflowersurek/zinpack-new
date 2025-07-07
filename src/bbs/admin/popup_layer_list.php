<?php
include_once("_common.php");
if ($iw[level] != "admin") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-object-group"></i>
			디자인 설정
		</li>
		<li class="active">팝업창</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			팝업창
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
										
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>코드</th>
											<th>제목</th>
											<th>게시일</th>
											<th>사이즈</th>
											<th>상태</th>
											<th>등록일</th>
											<th>관리</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql = "select * from $iw[popup_layer_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'";
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

										$sql .= " order by pl_no desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$pl_no = $row["pl_no"];
											$pl_subject = stripslashes($row["pl_subject"]);
											$pl_stime = date("Y-m-d", strtotime($row["pl_stime"]));
											$pl_etime = date("Y-m-d", strtotime($row["pl_etime"]));
											$pl_width = $row["pl_width"];
											$pl_height = $row["pl_height"];
											$pl_state = $row["pl_state"];
											$pl_date = date("Y-m-d", strtotime($row["pl_date"]));
											if($pl_state == 1){
												$pl_display = "사용";
											}else if($pl_state == 0){
												$pl_display = "미사용";
											}
									?>
										<tr>
											<td data-title="코드"><?=$pl_no?></td>
											<td data-title="제목"><?=$pl_subject?></td>
											<td data-title="게시일"><?=$pl_stime." ~ ".$pl_etime?></td>
											<td data-title="사이즈"><?="W:".$pl_width." / H:".$pl_height?></td>
											<td data-title="상태"><?=$pl_display?></td>
											<td data-title="등록일"><?=$pl_date?></td>
											<td data-title="관리">
												<a href="<?=$iw['admin_path']?>/popup_layer_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$pl_no?>"><span class="label label-sm label-default">수정</span></a>
												<a href="<?=$iw['admin_path']?>/popup_layer_delete.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$pl_no?>"><span class="label label-sm label-danger">삭제</span></a>
											</td>
										</tr>
									<?php
										$i++;
										}
										if($i==0) echo "<tr><td colspan='7' align='center'>팝업창이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/popup_layer_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
												<i class="fa fa-check"></i>
												팝업창 등록
											</button>
										</div>
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
														echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next'><i class='fa fa-angle-double-right'></i></a></li>";
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



