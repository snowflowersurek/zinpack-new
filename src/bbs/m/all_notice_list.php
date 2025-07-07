<?php
include_once("_common_guest.php");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>

<div class="content">
	<div class="breadcrumb-box input-group">
		<ol class="breadcrumb ">
			<li><a href="<?=$iw[m_path]?>/all_notice_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0012","공지사항");?></a></li>
		</ol>
	</div>
	<div class="box br-theme">
		<table class="table responsive-table">
			<colgroup>
			   <col style="width: 80px;">
			</colgroup>
			<thead>
				<tr>
					<th><?=national_language($iw[language],"a0125","번호");?></th>
					<th><?=national_language($iw[language],"a0126","제목");?></th>
					<th><?=national_language($iw[language],"a0127","날짜");?></th>
				</tr>
			</thead>
			<tbody>
				<?php
                    // 중요 공지 조회
					$sql_important = "select * from {$iw['notice_table']} where ep_code = ? and gp_code = ? and nt_display = 1 and nt_type = 1 order by nt_no desc";
                    $stmt_important = mysqli_prepare($db_conn, $sql_important);
                    mysqli_stmt_bind_param($stmt_important, "ss", $iw['store'], $iw['group']);
                    mysqli_stmt_execute($stmt_important);
					$result_important = mysqli_stmt_get_result($stmt_important);

					while($row = mysqli_fetch_assoc($result_important)){
						$nt_no = $row["nt_no"];
						$nt_type = $row["nt_type"];
						$nt_subject = stripslashes($row["nt_subject"]);
						$nt_datetime = date("y.m.d", strtotime($row["nt_datetime"]));
				?>
					<tr>
						<td data-th="<?=national_language($iw[language],"a0125","번호");?>"><i class="fa fa-star"></i></td>
						<td data-th="<?=national_language($iw[language],"a0126","제목");?>"><a href="<?=$iw['m_path']?>/all_notice_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$nt_no?>"><?=$nt_subject?></a></td>
						<td data-th="<?=national_language($iw[language],"a0127","날짜");?>"><?=$nt_datetime?></td>
					</tr>
				<?php
					}
                    mysqli_stmt_close($stmt_important);

                    // 일반 공지 개수 조회
					$sql_count = "select count(*) as cnt from {$iw['notice_table']} where ep_code = ? and gp_code = ? and nt_display = 1";
                    $stmt_count = mysqli_prepare($db_conn, $sql_count);
                    mysqli_stmt_bind_param($stmt_count, "ss", $iw['store'], $iw['group']);
                    mysqli_stmt_execute($stmt_count);
                    $result_count = mysqli_stmt_get_result($stmt_count);
                    $row_count = mysqli_fetch_assoc($result_count);
					$total_line = $row_count['cnt'];
                    mysqli_stmt_close($stmt_count);

					$max_line = 10;
					$max_page = 5;
						
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

                    // 일반 공지 목록 조회
					$sql = "select * from {$iw['notice_table']} where ep_code = ? and gp_code = ? and nt_display = 1 order by nt_no desc limit ?, ?";
                    $stmt = mysqli_prepare($db_conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ssii", $iw['store'], $iw['group'], $start_line, $max_line);
                    mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);

					$i=0;
					while($row = mysqli_fetch_assoc($result)){
						$nt_no = $row["nt_no"];
						$nt_type = $row["nt_type"];
						$nt_subject = $row["nt_subject"];
						$nt_datetime = date("y.m.d", strtotime($row["nt_datetime"]));
				?>
					<tr>
						<td data-th="<?=national_language($iw[language],"a0125","번호");?>"><?=$nt_no?></td>
						<td data-th="<?=national_language($iw[language],"a0126","제목");?>"><a href="<?=$iw['m_path']?>/all_notice_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$nt_no?>"><?=$nt_subject?></a></td>
						<td data-th="<?=national_language($iw[language],"a0127","날짜");?>"><?=$nt_datetime?></td>
					</tr>
				<?php
					$i++;
					}
                    mysqli_stmt_close($stmt);
					if($i==0) echo "<tr><td colspan='3' class='text-center'>".national_language($iw[language],"a0128","공지사항이 없습니다.")."</td></tr>";
				?>
			</tbody>
		</table>

	</div> <!-- /.box -->
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
						echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre'>&laquo;</a></li>";
					} else {
						echo "<li><a href='#'>&laquo;</a></li>";
					}
					
					for($i=$start_page;$i<=$end_page;$i++) {
						if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
						else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i'>$i</a></li>";
					}
				 
					if($end_page<$total_page) {
						$next = $end_page + 1;
						echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next'>&raquo;</a></li>";
					} else {
						echo "<li><a href='#'>&raquo;</a></li>";
					}
				}
			?>
		</ul>
	</div>
</div>

<?php
include_once("_tail.php");
?>



