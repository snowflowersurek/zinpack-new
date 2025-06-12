<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$picture_idx = $_GET["id"];

$sql = "select * from $iw[publishing_exhibit_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx'";
$row = sql_fetch($sql);
if (!$row["picture_idx"]) alert("잘못된 접근입니다!","");

$picture_name = stripslashes($row["picture_name"]);
$special = stripslashes($row["special"]);
$how_many = stripslashes($row["how_many"]);
$size = $row["size"];
$book_id = $row["book_id"];
$contents = stripslashes($row["contents"]);
$can_rent = $row["can_rent"];
$reg_date = $row["reg_date"];

if(preg_match('/(iPod|iPhone|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone)/i', $_SERVER['HTTP_USER_AGENT'])){
	$mobile_check = "ok";
}
?>

<script type="text/javascript" src="/include/ckeditor/ckeditor.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">그림전시관리</li>
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
			그림전시관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				상세정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="exhibit_form" name="exhibit_form" action="<?=$iw['admin_path']?>/publishing_exhibit_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">

					<input type="hidden" name="picture_idx" value="<?=$picture_idx?>" />
					<input type="hidden" name="reg_date" value="<?=$reg_date?>" />

					<div class="form-group">
						<label class="col-sm-1 control-label">등록일시</label>
						<div class="col-sm-8">
							<input type="text" name="reg_date2" value="<?=substr($reg_date, 0, 16)?>" maxlength="16"> 예) <?=date("Y-m-d H:i")?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">그림전시명</label>
						<div class="col-sm-8">
							<input type="text" class="col-sm-12" name="picture_name" value="<?=$picture_name?>" maxlength="30">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">대여가능여부</label>
						<div class="col-sm-8">
							<select name="can_rent">
								<option value="Y" <?php if($can_rent == "Y") echo "selected"; ?>>가능</option>
								<option value="M" <?php if($can_rent == "M") echo "selected"; ?>>점검</option>
								<option value="N" <?php if($can_rent == "N") echo "selected"; ?>>불가능</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">특별기획전</label>
						<div class="col-sm-8">
							<input type="text" name="special" value="<?=$special?>" maxlength="30"> 특별기획전 그림이 아니면 공란
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">액자수(점)</label>
						<div class="col-sm-8">
							<input type="text" name="how_many" value="<?=$how_many?>" maxlength="30">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">액자사이즈</label>
						<div class="col-sm-8">
							<input type="text" name="size" value="<?=$size?>" maxlength="30"> 65x35 형태, 단위는 cm
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">도서정보</label>
						<div class="col-sm-8">
							<input type="text" name="book_id" value="<?=$book_id?>" onclick="searchBook();" readonly> 
							<button type="button" onclick="searchBook();">검색</button> 해당 그림의 도서코드
						</div>
					</div>

					<div class="form-group" id="web_editor">
						<label class="col-sm-1 control-label">그림전시 소개</label>
						<div class="col-sm-8">
							<textarea class="ckeditor" id="contents" name="contents"><?=$contents?></textarea>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/publishing_exhibit_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
								<i class="fa fa-undo"></i>
								취소
							</button>
						</div>
					</div>
				</form>
			<!-- PAGE CONTENT ENDS -->

			<div class="table-set-mobile dataTable-wrapper">
				<table class="table table-striped table-bordered table-hover dataTable">
					<thead>
						<tr>
							<th>번호</th>
							<th>신청인</th>
							<th>전시기관명</th>
							<th>그림전시명</th>
							<th>전시일정</th>
							<th>연락처</th>
							<th>신청일</th>
							<th>신청현황</th>
						</tr>
					</thead>
					<tbody>
					<?
						$sql = "select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx'";
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
						
						$sql = "select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' order by idx desc limit $start_line, $end_line";
						$result = sql_query($sql);
						
						$i=0;
						while($row = @sql_fetch_array($result)){
							$idx = $row["idx"];
							$stat = $row["stat"];
							$picture_name = $row["picture_name"];
							$year = $row["year"];
							$month = $row["month"];
							$userName = $row["userName"];
							$strOrgan = $row["strOrgan"];
							$userTel = $row["userTel"];
							$userPhone = $row["userPhone"];
							$homepage = $row["homepage"];
							$md_date = substr($row["md_date"], 0, 10);
							
							if ($year != "" && $month != "") {
								$exhibit_month = sprintf("%04d", $year)."년 ".sprintf("%02d", $month)."월";
							} else {
								$exhibit_month = "";
							}
					?>
						<tr>
							<td data-title="번호"><?=($total_line - $start_line - $i)?></td>
							<td data-title="신청인"><a href="<?=$iw['admin_path']?>/publishing_exhibit_status_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&id=<?=$idx?>"><?=$userName?></a></td>
							<td data-title="전시기관명"><?=$strOrgan?></td>
							<td data-title="그림전시명"><?=$picture_name?></td>
							<td data-title="전시일정"><?=$exhibit_month?></td>
							<td data-title="연락처"><?=$userTel?></td>
							<td data-title="신청일"><?=$md_date?></td>
							<td data-title="신청현황">
								<?if($stat == "1"){?>
									<span class="label label-sm label-warning" style="background-color:#999;">대기 중</span>
								<?}else if($stat == "2"){?>
									<span class="label label-sm label-success">전시확정</span>
								<?}else if($stat == "3"){?>
									<span class="label label-sm label-warning">보류</span>
								<?}else if($stat == "4"){?>
									<span class="label label-sm label-warning">전시연기</span>
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
										echo "<li class='prev'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&id=$picture_idx&page=$pre'><i class='fa fa-angle-double-left'></i></a></li>";
									} else {
										echo "<li class='prev disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
									}
									
									for($i=$start_page;$i<=$end_page;$i++) {
										if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
										else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&id=$picture_idx&page=$i'>$i</a></li>";
									}
								 	
									if($end_page<$total_page) {
										$next = $end_page + 1;
										echo "<li class='next'><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&id=$picture_idx&page=$next'><i class='fa fa-angle-double-right'></i></a></li>";
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
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<script type="text/javascript">
function searchBook(type) {
	window.open('publishing_books_search.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>', 'SearchBook', 'width=800,height=600');
}

function selectBook(code, name){
	document.exhibit_form.book_id.value = code;
}

function check_form() {
	if (exhibit_form.picture_name.value == ""){
		alert("그림전시명 입력하세요.");
		exhibit_form.picture_name.focus();
		return;
	}
	exhibit_form.submit();
}
</script>

<?
include_once("_tail.php");
?>