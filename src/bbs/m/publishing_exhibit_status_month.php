<?php
include_once("_common.php");
include_once("_head.php");

// $sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
// $row = sql_fetch($sql);
// if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 후 이용 가능합니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");

$ep_row = sql_fetch("select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$book_path = "/publishing/$ep_row[ep_nick]/book";
$exhibit_path = "/publishing/$ep_row[ep_nick]/exhibit";

$menu = $_GET["menu"];
$page = $_GET["page"];

if (!$page) {
	$page=1;
}

$current_year = date("Y");
$current_month = date("n");

if ($_GET['year'] && $_GET['month']) {
	$search_year = $_GET['year'];
	$search_month = $_GET['month'];
} else {
	$search_year = date("Y");
	$search_month = date("n");
}
$prev_year = ($search_year - 1);
$next_year = ($search_year + 1);

$is_rent = $_GET["is_rent"];
$keyword = $_GET["keyword"];
?>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li>
				<?php
					$hm_code = $menu;
					$hm_row = sql_fetch("select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and hm_code = '$hm_code'");
					echo stripslashes($hm_row[hm_name])
				?>
				</li>
			</ol>
		</div>
		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
			<div class="grid-sizer"></div>
		</div> <!-- /.masonry -->
		
		<div class="box br-theme">
			<table class="table table-bordered">
				<colgroup>
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
					<col width="8.33%">
				</colgroup>
				<tr>
					<th class="text-center media-heading" colspan="12">
						<h4>
							<a href="<?php echo "$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$pre&year=$prev_year&month=$search_month";?>"><span class="glyphicon glyphicon-chevron-left small"></span></a>
							&nbsp;
							<strong><?=$search_year?>년</strong>
							&nbsp;
							<a href="<?php echo "$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$pre&year=$next_year&month=$search_month";?>"><span class="glyphicon glyphicon-chevron-right small"></span></a>
						</h4>
					</th>
				</tr>
				<tr>
				<?php 
				for ($i=1; $i<=12; $i++) {
					if ($search_month == $i) {
						echo "<th style='padding:0; background-color:rgba(0, 0, 0, 0.2);'>";
						echo "	<div class='w-full text-center' style='padding:6px 0;'><h4><strong>".sprintf("%02d", $i)."월</strong></h4></div>";
						echo "</th>";
					} else {
						echo "<th style='padding:0;'>";
						echo "	<a href='$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$pre&year=$search_year&month=$i'>";
						echo "		<div class='w-full text-center' style='padding:6px 0;'><h4>".sprintf("%02d", $i)."월</h4></div>";
						echo "	</a>";
						echo "</th>";
					}
				}
				?>
				</tr>
			</table>
			
			<form class="row" style="padding-bottom:20px;" action="<?=$PHP_SELF?>" method="get">
				<input type="hidden" name="ep" value="<?=$iw[store]?>" />
				<input type="hidden" name="gp" value="<?=$iw[group]?>" />
				<input type="hidden" name="menu" value="<?=$menu?>" />
				<input type="hidden" name="page" value="1" />
				<input type="hidden" name="year" value="<?=$search_year?>" />
				<input type="hidden" name="month" value="<?=$search_month?>" />
				<div class="col-xs-5 col-sm-3 col-md-2 col-sm-offset-5 col-md-offset-7">
					<select name="is_rent" class="form-control input-sm">
						<option value="" <?=($is_rent == "" ? "selected" : "")?>>전체 그림전시</option>
						<option value="Y" <?=($is_rent == "Y" ? "selected" : "")?>>신청가능 그림전시</option>
					</select>
				</div>
				<div class="col-xs-7 col-sm-4 col-md-3">
					<div class="input-group">
						<input type="text" class="form-control input-sm" name="keyword" maxlength="20" value="<?=$keyword?>" placeholder="그림전시명" />
						<span class="input-group-btn">
							<button type="submit" class="btn btn-sm btn-default">검색</button>
						</span>
					</div>
				</div>
			</form>
			
			<table class="table responsive-table">
				<colgroup>
				   <col style="width: 80px;">
				</colgroup>
				<thead>
					<tr>
						<th>번호</th>
						<!-- <th>이미지</th> -->
						<th>그림전시명</th>
						<th>액자수(점)</th>
						<th>액자크기</th>
						<th>전시상태</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if ($search_year <= $current_year) {
					$search = "";
					
					if ($keyword) {
						$search .= " AND E.picture_name like '%$keyword%' ";
					}
					
					if ($is_rent == "Y") {
						if ($search_month >= $current_month) {
							$search .= " AND ES.strOrgan IS NULL ";
						} else {
							$search .= " AND ES.strOrgan = '' ";
						}
					}
					
					$sql = "
						SELECT 
							E.picture_idx, E.picture_name, E.how_many, E.size, ES.year, ES.month, ES.strOrgan, B.BookImage 
						FROM 
							iw_publishing_exhibit E 
						LEFT JOIN (SELECT ep_code, picture_idx, year, month, strOrgan from iw_publishing_exhibit_status WHERE year = '$search_year' AND month = '$search_month') ES 
							ON E.ep_code = ES.ep_code AND E.picture_idx = ES.picture_idx 
						LEFT JOIN iw_publishing_books B 
							ON E.ep_code = B.ep_code AND E.book_id = B.BookID 
						WHERE E.ep_code = '$iw[store]' AND E.can_rent = 'Y' $search 
						ORDER BY E.picture_name ASC
					";
					$result = sql_query($sql);
					$total_rows = mysqli_num_rows($result);
					
					$page_rows = 10;
					$start_row = ($page-1) * $page_rows;
					$total_page = ceil($total_rows / $page_rows);
					
					if ($total_rows < $page_rows) {
						$end_row = $total_rows;
					} else if($page==$total_page) {
						$end_row = $total_rows - ($page_rows*($total_page-1));
					} else {
						$end_row = $page_rows;
					}
					
					$sql = "
						SELECT 
							E.picture_idx, E.picture_name, E.how_many, E.size, ES.year, ES.month, ES.strOrgan, B.BookImage 
						FROM 
							iw_publishing_exhibit E 
						LEFT JOIN (SELECT ep_code, picture_idx, year, month, strOrgan from iw_publishing_exhibit_status WHERE year = '$search_year' AND month = '$search_month') ES 
							ON E.ep_code = ES.ep_code AND E.picture_idx = ES.picture_idx 
						LEFT JOIN iw_publishing_books B 
							ON E.ep_code = B.ep_code AND E.book_id = B.BookID 
						WHERE E.ep_code = '$iw[store]' AND E.can_rent = 'Y' $search 
						ORDER BY E.picture_name ASC LIMIT $start_row, $end_row
					";
					$result = sql_query($sql);
					
					$i=0;
					while ($row = @sql_fetch_array($result)) {
						$mb_code = $row["mb_code"];
						$picture_idx = $row["picture_idx"];
						$picture_name = stripslashes($row["picture_name"]);
						$how_many = $row["how_many"];
						$picture_size = $row["size"];
						$picture_photo = $row["BookImage"];
						
						if ($picture_photo != "") {
							$picture_photo = $iw[path].$book_path."/".$picture_photo;
						}
						
						$strOrgan = $row["strOrgan"];
				?>
					<tr>
						<td data-th="번호"><?=($total_rows - $start_row - $i)?></td>
						<!-- <td data-th="이미지">
							<?php
							if ($picture_photo != "") {
								echo "<img class='media-object img-responsive' src='$picture_photo' style='max-width:160px;'>";
							} else {
								echo "&nbsp;";
							}
							?>
						</td> -->
						<td data-th="그림전시명">
							<a href="<?=$iw['m_path']?>/publishing_exhibit_data_view.php?type=exhibit&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$picture_idx?>"><?=$picture_name?></a>
						</td>
						<td data-th="액자수(점)"><?=$how_many?></td>
						<td data-th="액자크기"><?=$picture_size?></td>
						<td data-th="전시상태">
							<?php
							if($strOrgan == ""){
								if ($search_year > $current_year || ($search_year == $current_year && $search_month >= $current_month)) {
									echo "<a href='publishing_exhibit_application.php?ep=$iw[store]&gp=$iw[group]&idx=$picture_idx&name=$picture_name&month=$search_month' class='btn btn-theme'>신청하기</a>";
								} else {
									echo "&nbsp;";
								}
							}else{
								echo $strOrgan;
							}
							?>
						</td>
					</tr>
				<?php
					$i++;
					}
					
					if($i==0) {
						echo "<tr><td colspan='8' align='center'>검색된 그림전시가 없습니다.</td></tr>";
					}
				} else {
						echo "<tr><td colspan='8' align='center'>검색된 그림전시가 없습니다.</td></tr>";
				}
				?>
				</tbody>
			</table>
		</div> <!-- /.box -->
		<div class="clearfix"></div>
		<div class="pagContainer text-center">
			<ul class="pagination">
				<?php
					$max_page_cnt = 10;
					
					if ($total_page!=0) {
						if ($page>$total_page) {
							$page=$total_page;
						}
						
						$start_page = ((ceil($page/$max_page_cnt)-1)*$max_page_cnt)+1;
						$end_page = $start_page+$max_page_cnt-1;
						
						if ($end_page>$total_page) {
							$end_page=$total_page;
						}
					 
						if ($page>$max_page_cnt) {
							$pre = $start_page - 1;
							echo "<li><a href='$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$pre&year=$search_year&month=$search_month&is_rent=$is_rent&keyword=$keyword'>&laquo;</a></li>";
						} else {
							echo "<li><a href='javascript:void(0);'>&laquo;</a></li>";
						}
						
						for ($i=$start_page; $i<=$end_page; $i++) {
							if ($i==$page) {
								echo "<li class='active'><a href='#'>$i</a></li>";
							} else {
								echo "<li><a href='$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$i&year=$search_year&month=$search_month&is_rent=$is_rent&keyword=$keyword'>$i</a></li>";
							}
						}
					 
						if ($end_page<$total_page) {
							$next = $end_page + 1;
							echo "<li><a href='$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$next&year=$search_year&month=$search_month&is_rent=$is_rent&keyword=$keyword'>&raquo;</a></li>";
						} else {
							echo "<li><a href='javascript:void(0);'>&raquo;</a></li>";
						}
					}
				?>
			</ul>
		</div>
	</div> <!-- /.row -->
</div> <!-- /.content -->

<?php
include_once("_tail.php");
?>



