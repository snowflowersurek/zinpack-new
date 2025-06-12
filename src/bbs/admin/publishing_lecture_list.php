<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">작가강연회 신청내역</li>
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
			작가강연회 신청내역
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
								<!--설명-->
							</div>
							<div class="table-set-mobile dataTable-wrapper">
								<div class="row">
									<div class="col-sm-4">
										<div class="dataTable-option">
											<select id="download_year" class="form-control" style="display:inline-block; width:auto; height:34px; padding:4px;">
												<?php
													for ($i=date("Y"); $i>=2015; $i--) {
														if ($i == $searchyear) {
															echo '<option value="'.$i.'" selected>'.$i.'년</option>';
														} else {
															echo '<option value="'.$i.'">'.$i.'년</option>';
														}
													}
												?>
											</select>
											<button class="btn btn-success" type="button" onclick="downloadExcel();">
												<i class="fa fa-download"></i>
												엑셀저장
											</button>
										</div>
									</div>
									<div class="col-sm-8">
										<div class="dataTable-option-right">
											<?
												if($_POST['search']){
													$search = $_POST['search'];
													$searchby1 = $_POST['searchby1'];
													$searchby2 = $_POST['searchby2'];
													$keyword = $_POST['keyword'];
												}else{
													$search = $_GET['search'];
													$searchby1 = $_GET['searchby1'];
													$searchby2 = $_GET['searchby2'];
													$keyword = $_GET['keyword'];
												}
												
												$search_sql = "";
												
												if($searchby1 != ""){
													$search_sql = $search_sql." and strConfirm = '$searchby1'";
												}
												
												if($searchby2 =="username"){
													$search_sql = $search_sql." and userName like '%$keyword%'";
												}else if($searchby2 =="organ"){
													$search_sql = $search_sql." and strOrgan like '%$keyword%'";
												}else if($searchby2 =="author"){
													$search_sql = $search_sql." and (strAuthor1 like '%$keyword%' or strAuthor2 like '%$keyword%' or strAuthor3 like '%$keyword%')";
												}else if($searchby2 =="book"){
													$search_sql = $search_sql." and (strAuthorBook1 like '%$keyword%' or strAuthorBook2 like '%$keyword%' or strAuthorBook3 like '%$keyword%')";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
												<label>검색:
												<select name="searchby1" class="form-control" style="display:inline-block; margin:0; width:auto;">
													<option value="">전체</option>
													<option value="N" <?if($searchby1 == "N"){?>selected="selected"<?}?>>접수대기</option>
													<option value="A" <?if($searchby1 == "A"){?>selected="selected"<?}?>>접수완료</option>
													<option value="D" <?if($searchby1 == "D"){?>selected="selected"<?}?>>도서관연락</option>
													<option value="J" <?if($searchby1 == "J"){?>selected="selected"<?}?>>작가섭외</option>
													<option value="Y" <?if($searchby1 == "Y"){?>selected="selected"<?}?>>강연확정</option>
													<option value="C" <?if($searchby1 == "C"){?>selected="selected"<?}?>>강연취소</option>
												</select>
												<select name="searchby2" class="form-control" style="display:inline-block; margin:0; width:auto;">
													<option value="username" <?if($searchby2 == "username"){?>selected="selected"<?}?>>신청자</option>
													<option value="organ" <?if($searchby2 == "organ"){?>selected="selected"<?}?>>기관명</option>
													<option value="author" <?if($searchby2 == "author"){?>selected="selected"<?}?>>희망작가</option>
													<option value="book" <?if($searchby2 == "book"){?>selected="selected"<?}?>>도서명</option>
												</select>
												</label>
												<input type="text" name="keyword" class="form-control" style="display:inline-block; margin:0;" value="<?=$keyword?>">
												<input type="hidden" name="search" value="Y">
											</form>
										</div>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th>번호</th>
											<th>신청자</th>
											<th>신청기관 분류</th>
											<th>기관명</th>
											<th>희망작가 및 도서명</th>
											<th>희망일정</th>
											<th>신청일</th>
											<th>신청현황</th>
										</tr>
									</thead>
									<tbody>
									<?
										$sql = "select * from $iw[publishing_lecture_table] where ep_code = '$iw[store]' $search_sql";
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

										$sql = "select * from $iw[publishing_lecture_table] where ep_code = '$iw[store]' $search_sql order by intSeq desc limit $start_line, $end_line";
										$result = sql_query($sql);

										$i=0;
										while($row = @sql_fetch_array($result)){
											$intSeq = $row["intSeq"];
											$userName = $row["userName"];
											$strGubun = $row["strGubun"];
											$strGubunTxt = $row["strGubunTxt"];
											$strOrgan = $row["strOrgan"];
											$confirm_Author = $row["confirm_Author"];
											$strAuthor1 = $row["strAuthor1"];
											$strAuthor2 = $row["strAuthor2"];
											$strAuthor3 = $row["strAuthor3"];
											$strAuthorBook1 = $row["strAuthorBook1"];
											$strAuthorBook2 = $row["strAuthorBook2"];
											$strAuthorBook3 = $row["strAuthorBook3"];
											$confirm_date = $row["confirm_date"];
											$strDate1 = $row["strDate1"];
											$strDate2 = $row["strDate2"];
											$strDate3 = $row["strDate3"];
											$strRegDate = substr($row["strRegDate"], 0, 10);
											$strConfirm = $row["strConfirm"];
											
											if ($strGubun == "기타") {
												$strGubun = $strGubun."(".$strGubunTxt.")";
											}
									?>
										<tr>
											<td data-title="번호"><?=($total_line - $start_line - $i)?></td>
											<td data-title="신청자"><a href="<?=$iw['admin_path']?>/publishing_lecture_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&id=<?=$intSeq?>"><?=$userName?></a></td>
											<td data-title="신청기관 분류"><?=$strGubun?></td>
											<td data-title="기관명"><?=$strOrgan?></td>
											<td data-title="희망작가 및 도서명">
												<?php
												if ($strAuthor1 != "") {
													if ($confirm_Author == 1) {
														echo "<span class='text-success'><strong>1지망 : $strAuthor1 - $strAuthorBook1</strong></span> <span class='text-success glyphicon glyphicon-ok'></span>";
													} else {
														echo "1지망 : $strAuthor1 - $strAuthorBook1";
													}
												}
												if ($strAuthor2 != "") {
													if ($confirm_Author == 2) {
														echo "<br><span class='text-success'><strong>2지망 : $strAuthor2 - $strAuthorBook2</strong></span> <span class='text-success glyphicon glyphicon-ok'></span>";
													} else {
														echo "<br>2지망 : $strAuthor2 - $strAuthorBook2";
													}
												}
												if ($strAuthor3 != "") {
													if ($confirm_Author == 3) {
														echo "<br><span class='text-success'><strong>3지망 : $strAuthor3 - $strAuthorBook3</strong></span> <span class='text-success glyphicon glyphicon-ok'></span>";
													} else {
														echo "<br>3지망 : $strAuthor3 - $strAuthorBook3";
													}
												}
												?>
											</td>
											<td data-title="희망일정">
												<?php
												if ($strDate1 != "") {
													$strDateText1 = substr($strDate1, 0, 4)."-".substr($strDate1, 4, 2)."-".substr($strDate1, 6, 2)." ".substr($strDate1, 8, 2).":".substr($strDate1, 10, 2)."~".substr($strDate1, 12, 2).":".substr($strDate1, 14, 2);
													
													if ($confirm_date == 1) {
														echo "<span class='text-success'><strong>1지망 : $strDateText1</strong></span> <span class='text-success glyphicon glyphicon-ok'></span>";
													} else {
														echo "1지망 : $strDateText1";
													}
												}
												if ($strDate2 != "") {
													$strDateText2 = substr($strDate2, 0, 4)."-".substr($strDate2, 4, 2)."-".substr($strDate2, 6, 2)." ".substr($strDate2, 8, 2).":".substr($strDate2, 10, 2)."~".substr($strDate2, 12, 2).":".substr($strDate2, 14, 2);
													
													if ($confirm_date == 2) {
														echo "<br><span class='text-success'><strong>2지망 : $strDateText2</strong></span> <span class='text-success glyphicon glyphicon-ok'></span>";
													} else {
														echo "<br>2지망 : $strDateText2";
													}
												}
												if ($strDate3 != "") {
													$strDateText3 = substr($strDate3, 0, 4)."-".substr($strDate3, 4, 2)."-".substr($strDate3, 6, 2)." ".substr($strDate3, 8, 2).":".substr($strDate3, 10, 2)."~".substr($strDate3, 12, 2).":".substr($strDate3, 14, 2);
													
													if ($confirm_date == 3) {
														echo "<br><span class='text-success'><strong>3지망 : $strDateText3</strong></span> <span class='text-success glyphicon glyphicon-ok'></span>";
													} else {
														echo "<br>3지망 : $strDateText3";
													}
												}
												?>
											</td>
											<td data-title="신청일"><?=$strRegDate?></td>
											<td data-title="신청현황">
												<?if($strConfirm == "N"){?>
													<span class="label label-sm label-default">접수대기</span>
												<?}else if($strConfirm == "A"){?>
													<span class="label label-sm label-primary">접수완료</span>
												<?}else if($strConfirm == "D"){?>
													<span class="label label-sm label-warning">도서관연락</span>
												<?}else if($strConfirm == "J"){?>
													<span class="label label-sm label-info">작가섭외</span>
												<?}else if($strConfirm == "Y"){?>
													<span class="label label-sm label-success">강연확정</span>
												<?}else if($strConfirm == "C"){?>
													<span class="label label-sm label-danger">강연취소</span>
												<?}?>
											</td>
										</tr>
									<?
										$i++;
										}
										if($i==0) echo "<tr><td colspan='8' align='center'>검색된 게시글이 없습니다.</td></tr>";
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
														echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&search=$search&searchby1=$searchby1&searchby2=$searchby2&keyword=$keyword'><i class='fa fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
														else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&search=$search&searchby1=$searchby1&searchby2=$searchby2&keyword=$keyword'>$i</a></li>";
													}
												 	
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&search=$search&searchby1=$searchby1&searchby2=$searchby2&keyword=$keyword'><i class='fa fa-angle-double-right'></i></a></li>";
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
function downloadExcel() {
	var year = $('#download_year').val();
	location.href='<?=$iw['admin_path']?>/publishing_lecture_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&year=' + year;
}
</script>

<?
include_once("_tail.php");
?>