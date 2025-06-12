<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

$contest_code = $_GET["contest_code"];

$sql = "select * from iw_publishing_contest where ep_code = '$iw[store]' and gp_code='$iw[group]' and contest_code = '$contest_code'";
$row = sql_fetch($sql);
if (!$row["contest_code"]) alert("잘못된 접근입니다.","");

$subject = stripslashes($row["subject"]);
$start_date = substr($row["start_date"], 0, 10);
$end_date = substr($row["end_date"], 0, 10);

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">공모전 응모내역</li>
	</ul><!-- .breadcrumb -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			공모전 응모내역
			<small class="dark">
				<i class="fa fa-angle-double-right"></i>
				<?=$subject?>
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
								<!--설명-->
							</div>
							<div class="table-set-mobile dataTable-wrapper">
								<div class="row">
									<div class="col-sm-4">
										<div class="dataTable-option">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/publishing_contestant_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&contest_code=<?=$contest_code?>'">
												<i class="fa fa-download"></i>
												엑셀저장
											</button>
										</div>
									</div>
									<div class="col-sm-8">
										<div class="dataTable-option-right">
											<?
												if($_POST['searchby']){
													$searchby = $_POST['searchby'];
													$keyword = $_POST['keyword'];
												}else{
													$searchby = $_GET['searchby'];
													$keyword = $_GET['keyword'];
												}
												if($searchby =="name"){
													$search_sql = "and user_name like '%$keyword%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="searchby">
												<option value="name" <?if($searchby == "name"){?>selected="selected"<?}?>>응모자명</option>
											</select></label><input type="text" name="keyword" value="<?=$keyword?>">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>접수번호</th>
											<th>작품제목</th>
											<th>응모자명</th>
											<th>연락처</th>
											<th>이메일</th>
											<th>주소</th>
											<th>응모일시</th>
											<th>첨부파일</th>
											<th>관리</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select * from iw_publishing_contestant where ep_code = '$iw[store]' and gp_code = '$iw[group]' and contest_code = '$contest_code' $search_sql";
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
										
										$sql = "select * from iw_publishing_contestant where ep_code = '$iw[store]' and gp_code = '$iw[group]' and contest_code = '$contest_code' $search_sql order by idx desc limit $start_line, $end_line";
										$result = sql_query($sql);
										
										$i=0;
										while($row = @sql_fetch_array($result)){
											$idx = $row["idx"];
											$user_name = $row["user_name"];
											$user_phone = $row["user_phone"];
											$user_email = $row["user_email"];
											$zipcode = $row["zipcode"];
											$addr1 = $row["addr1"];
											$addr2 = $row["addr2"];
											$work_title = $row["work_title"];
											$origin_filename = $row["origin_filename"];
											$attach_filename = $row["attach_filename"];
											$reg_date = substr($row["reg_date"], 0, 16);
									?>
										<tr>
											<td data-title="접수번호"><?=$idx?></td>
											<td data-title="작품제목"><?=$work_title?></td>
											<td data-title="응모자명"><?=$user_name?></td>
											<td data-title="연락처"><?=$user_phone?></td>
											<td data-title="이메일"><?=$user_email?></td>
											<td data-title="주소"><?="[".$zipcode."] ".$addr1." ".$addr2?></td>
											<td data-title="응모일시"><?=$reg_date?></td>
											<td data-title="첨부파일">
												<a href="javascript:file_download('<?=$iw['admin_path']?>/publishing_contestant_download.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&contest_code=<?=$contest_code?>&idx=<?=$idx?>');">다운로드</a>
											</td>
											<td data-title="관리">
												<a href="javascript:delete_contestant('<?=$iw['admin_path']?>/publishing_contestant_delete.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&contest_code=<?=$contest_code?>&idx=<?=$idx?>');">삭제하기</a>
											</td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='9' align='center'>응모내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-4">
										<div class="dataTable-info">
											
										</div>
									</div>
									<div class="col-sm-8">
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
														echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&searchby=$searchby&keyword=$keyword'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&searchby=$searchby&keyword=$keyword'>$i</a></li>";
													}
												 	
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&searchby=$searchby&keyword=$keyword'><i class='fa fa-angle-double-right'></i></a></li>";
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
	function file_download(link) {
		document.location.href=link;
	}

	function delete_contestant(link) {
		if (confirm('응모 작품을 내역에서 삭제하시겠습니까?\n삭제 후에는 복구되지 않습니다.')) {
			document.location.href=link;
		}
	}
</script>

<?
include_once("_tail.php");
?>