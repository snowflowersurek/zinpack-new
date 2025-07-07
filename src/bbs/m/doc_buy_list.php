<?php
include_once("_common.php");
if (($iw[gp_level] == "gp_guest" && $iw[group] != "all") || ($iw[level] == "guest" && $iw[group] == "all")) alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");
?>
<div class="content">
	<div class="breadcrumb-box input-group">
		<ol class="breadcrumb">
			<li><a href="<?=$iw[m_path]?>/doc_buy_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=$st_doc_name?> <?=national_language($iw[language],"a0005","구매자료");?></a></li>
		</ol>
		<span class="input-group-btn">
			<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
			<a class="btn btn-theme" href="<?=$iw[m_path]?>/doc_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-cloud-download fa-lg"></i></a>
		</span>
	</div>
	<div class="box br-theme">
		<div class="clearfix"></div>
		
		<table class="table responsive-table">
			<colgroup>
			   <col>
			</colgroup>
			<thead>
				<tr>
					<th><?=national_language($iw[language],"a0150","자료명");?></th>
					<th class="text-right"><?=national_language($iw[language],"a0144","유효기간");?></th>
					<th class="text-right"><?=national_language($iw[language],"a0145","다운로드");?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$sql = "select * from $iw[doc_buy_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
					$result = sql_query($sql);
					$total_line = mysqli_num_rows($result);

					$max_line = 10;
					$max_page = 5;
						
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

					$sql = "select * from $iw[doc_buy_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' order by db_no desc limit $start_line, $end_line";
					$result = sql_query($sql);

					$i=0;
					while($row = @sql_fetch_array($result)){
						$db_subject = stripslashes($row["db_subject"]);
						$dd_code = $row["dd_code"];
						$gp_code = $row["gp_code"];

						$download_btn ="<a href='$iw[m_path]/$iw[type]_data_download.php?type=$iw[type]&ep=$iw[store]&gp=$gp_code&item=$dd_code' class='btn btn-theme btn-sm'>다운로드</a>";

						if($row["db_datetime"]==$row["db_end_datetime"]){
							$db_date = national_language($iw[language],"a0147","제한 없음");
						}else{
							$db_date = date("y.m.d", strtotime($row["db_end_datetime"]))." ".date("(H:i)", strtotime($row["db_end_datetime"]));
							if($row["db_end_datetime"] < date("Y-m-d H:i:s")){
								$download_btn ="-";
							}
						}				 
				?>
					<tr>
						<td data-th="<?=national_language($iw[language],"a0150","자료명");?>"><a href="<?=$iw['m_path']?>/<?=$iw[type]?>_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$gp_code?>&item=<?=$dd_code?>"><?=$db_subject?></a></td>
						<td data-th="<?=national_language($iw[language],"a0144","유효기간");?>" class="text-right"><?=$db_date?></td>
						<td data-th="<?=national_language($iw[language],"a0145","다운로드");?>" class="text-right"><?=$download_btn?></td>
					</tr>
				<?php
					$i++;
					}
					if($i==0) echo "<tr><td colspan='3' align='center'>".national_language($iw[language],"a0146","구매내역이 없습니다.")."</td></tr>";
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



