<?php
include_once("_common.php");
include_once("_head.php");

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 후 이용 가능합니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");

$menu = $_GET["menu"];
?>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li>
				<?php
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
						<th>희망작가</th>
						<th>신청일</th>
						<th>신청현황</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql = "select * from $iw[publishing_lecture_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
				$result = sql_query($sql);
				$total_line = mysqli_num_rows($result);
				
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

				$sql = "select * from $iw[publishing_lecture_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' order by intSeq desc limit $start_line, $end_line";
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
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="희망작가">
							<a href="<?=$iw['m_path']?>/publishing_lecture_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$menu?>&item=<?=$intSeq?>">
							<?php
							if ($strAuthor1 != "") {
								echo "1지망 : $strAuthor1 - $strAuthorBook1";
							}
							if ($strAuthor2 != "") {
								echo "<br>2지망 : $strAuthor2 - $strAuthorBook2";
							}
							if ($strAuthor3 != "") {
								echo "<br>3지망 : $strAuthor3 - $strAuthorBook3";
							}
							?>
							</a>
						</td>
						<td data-th="신청일"><?=$strRegDate?></td>
						<td data-th="신청현황">
							<?php if($strConfirm == "N"){?>
								<span class="label label-sm label-default">접수대기</span>
							<?php }else if($strConfirm == "A"){?>
								<span class="label label-sm label-primary">접수완료</span>
							<?php }else if($strConfirm == "D"){?>
								<span class="label label-sm label-warning">도서관연락</span>
							<?php }else if($strConfirm == "J"){?>
								<span class="label label-sm label-info">작가섭외</span>
							<?php }else if($strConfirm == "Y"){?>
								<span class="label label-sm label-success">강연확정</span>
							<?php }else if($strConfirm == "C"){?>
								<span class="label label-sm label-danger">강연취소</span>
							<?php }?>
						</td>
					</tr>
				<?php
					$i++;
					}
					if($i==0) echo "<tr><td colspan='4' align='center'>검색된 게시글이 없습니다.</td></tr>";
				?>
				</tbody>
			</table>
		</div> <!-- /.box -->
		<div class="clearfix"></div>
		<div class="pagContainer text-center">
			<ul class="pagination">
				<?php
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

<?php
include_once("_tail.php");
?>



