<?php
include_once("_common.php");
if ($iw['level'] != "admin") alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-users"></i>
			그룹관리
		</li>
		<li class="active">전체 회원관리</li>
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
			전체 회원관리
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
										선택 회원을
										<select onchange="javascript:level_change(this.value);">
											<?
												$level_array = array(); 
												$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
												$result = sql_query($sql);
												while($row = @sql_fetch_array($result)){
													array_push($level_array, $row["gl_name"]);
											?>
												<option value="<?=$row["gl_level"]?>"><?=$row["gl_name"]?></option>
											<?
												}
											?>
										</select>(으)로
										<button class="btn btn-primary" type="button" onclick="form_submit();">
											변경
										</button>
									</div>
									<div class="col-sm-6">
										<div class="dataTable-option-right">
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
													$search_sql = "and mb_mail like '%$searchs%'";
												}else if($search =="c"){
													$search_sql = "and mb_name like '%$searchs%'";
												}else if($search =="d"){
													$search_sql = "and mb_nick like '%$searchs%'";
												}
											?>
											<form name="search_form" id="search_form" action="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
											<label>검색: <select name="search">
												<option value="a" <?if($search == "a"){?>selected="selected"<?}?>>회원코드</option>
												<option value="b" <?if($search == "b"){?>selected="selected"<?}?>>아이디(이메일)</option>
												<option value="c" <?if($search == "c"){?>selected="selected"<?}?>>이름</option>
												<option value="d" <?if($search == "d"){?>selected="selected"<?}?>>닉네임</option>
											</select></label><input type="text" name="searchs" value="<?=$searchs?>">
											</form>
										</div>
									</div>
								</div>
								<form id="frmorderform" name="frmorderform" action="<?=$iw['admin_path']?>/member_data_level.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
									<table class="table table-striped table-bordered table-hover dataTable">
										<thead>
											<tr>
												<th>선택</th>
												<th>등급</th>
												<th>회원코드</th>
												<th>이름</th>
												<th>닉네임</th>
												<th>아이디</th>
												<th>휴대폰</th>
												<th>포인트</th>
												<th>인증</th>
											</tr>
										</thead>
										<tbody>
										<?
											$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' $search_sql";
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

											$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' $search_sql order by mb_no desc limit $start_line, $end_line";
											$result = sql_query($sql);

											$i=0;
											while($row = @sql_fetch_array($result)){
												$mb_code = $row["mb_code"];
												$mb_mail = $row["mb_mail"];
												$mb_name = $row["mb_name"];
												$mb_nick = $row["mb_nick"];
												$mb_tel = $row["mb_tel"];
												$mb_no = $row["mb_no"];
												$mb_level = $row["mb_level"];
												$mb_point = $row["mb_point"];
												$mb_display = $row["mb_display"];
										?>
											<tr>
												<td data-title="선택"><input type="hidden" name="mb_no[]" value="<?=$mb_no?>" /><input type="checkbox" name='ct_chk[<?=$i?>]' value='1'></td>
												<td data-title="등급"><?=$level_array[$mb_level]?></td>
												<td data-title="회원코드"><a href="<?=$iw['admin_path']?>/member_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$mb_code?>"><?=$mb_code?></a></td>
												<td data-title="이름"><?=$mb_name?></td>
												<td data-title="닉네임"><?=$mb_nick?></td>
												<td data-title="아이디"><?=$mb_mail?></td>
												<td data-title="휴대폰"><?=$mb_tel?></td>
												<td data-title="포인트"><?=$mb_point?></td>
												<td data-title="인증">
												<?if($mb_display==0){?>
													<a href="javascript:email_confirm('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$mb_no?>');"><span class="label label-sm label-warning">승인하기</span></a>
												<?}else{?>
													<span class="label label-sm label-success">완료</span>
												<?}?>
												</td>
											</tr>
										<?
											$i++;
											}
											if($i==0) echo "<tr><td colspan='9' align='center'>검색된 회원정보가 없습니다.</td></tr>";
										?>
										</tbody>
									</table>
									<input type="hidden" name="gm_level" value="0" />
									<input type="hidden" name="gm_count" value="<?=$i?>" />
								</form>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['admin_path']?>/member_data_list_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
												<i class="fa fa-download"></i>
												엑셀저장
											</button>
										</div>
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


<script type="text/javascript">
	function level_change(level)
	{
		document.frmorderform.gm_level.value = level;
	}

	function form_submit(status)
	{
		if (confirm("선택 회원의 등급을 변경하시겠습니까?") == true) {
			document.frmorderform.submit();
		}
		return;
	}
	function email_confirm(type,ep,gp,no) {
		if (confirm("이메일인증을 승인하시겠습니까?")) {
			location.href="member_data_list_email.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+no;
		}
	}
</script>
<?
include_once("_tail.php");
?>