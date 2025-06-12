<?php
include_once("_common.php");
include_once("_head.php");

$menu = $_GET["menu"];

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 후 이용 가능합니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
?>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li>
				<?
					$hm_code = $_GET["menu"];
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
			<table class="table responsive-table">
				<colgroup>
				   <col style="width: 80px;">
				</colgroup>
				<thead>
					<tr>
						<th>번호</th>
						<th>신청자</th>
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
				$sql = "select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
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
				
				$sql = "select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' order by idx desc limit $start_line, $end_line";
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
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="신청자"><a href="<?=$iw['m_path']?>/publishing_exhibit_status_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$menu?>&item=<?=$idx?>"><?=$userName?></a></td>
						<td data-th="전시기관명"><?=$strOrgan?></td>
						<td data-th="그림전시명"><?=$picture_name?></td>
						<td data-th="전시일정"><?=$exhibit_month?></td>
						<td data-th="연락처"><?=$userTel?></td>
						<td data-th="신청일"><?=$md_date?></td>
						<td data-th="신청현황">
							<?if($stat == "1"){?>
								<span class="label label-sm label-default">대기 중</span>
							<?}else if($stat == "2"){?>
								<span class="label label-sm label-success">전시확정</span>
							<?}else if($stat == "3"){?>
								<span class="label label-sm label-warning">보류</span>
							<?}else if($stat == "4"){?>
								<span class="label label-sm label-danger">전시연기</span>
							<?}?>
							<a href="<?=$iw['m_path']?>/publishing_exhibit_status_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$menu?>&item=<?=$idx?>"><span class="btn btn-sm btn-theme">상세보기</span></a>
						</td>
					</tr>
				<?		
					$i++;
					}
					if($i==0) echo "<tr><td colspan='8' align='center'>검색된 게시글이 없습니다.</td></tr>";
				?>
				</tbody>
			</table>
		</div> <!-- /.box -->
		<div class="clearfix"></div>
		<div class="pagContainer text-center">
			<ul class="pagination">
				<?
					if($total_page!=0){
						if($page>$total_page) { $page=$total_page; }
						$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
						$end_page = $start_page+$max_page-1;
					 
						if($end_page>$total_page) {$end_page=$total_page;}
					 
						if($page>$max_page) {
							$pre = $start_page - 1;
							echo "<li><a href='$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$pre'>&laquo;</a></li>";
						} else {
							echo "<li><a href='#'>&laquo;</a></li>";
						}
						
						for($i=$start_page;$i<=$end_page;$i++) {
							if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
							else          echo "<li><a href='$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$i'>$i</a></li>";
						}
					 
						if($end_page<$total_page) {
							$next = $end_page + 1;
							echo "<li><a href='$PHP_SELF?ep=$iw[store]&gp=$iw[group]&menu=$menu&page=$next'>&raquo;</a></li>";
						} else {
							echo "<li><a href='#'>&raquo;</a></li>";
						}
					}
				?>
			</ul>
		</div>
	</div> <!-- /.row -->
</div> <!-- /.content -->

<?
include_once("_tail.php");
?>