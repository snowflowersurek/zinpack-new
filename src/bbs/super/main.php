<?php
include_once("_common.php");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

$chkcookie = get_cookie("expiry_chk_date");
//echo "chk date: " . $chkcookie;
$chkexpiry = ($chkcookie=="" || $chkcookie!=date("Y-m-d"))?"1":"0";
//echo $chkexpiry;
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-dashboard"></i>
			데쉬보드
		</li>
		<li class="active">데쉬보드</li>
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
			데쉬보드
			<small>
				<i class="fas fa-angle-double-right"></i>
				데쉬보드
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12">
			<!-- PAGE CONTENT BEGINS -->
			<?php
				//잔여포인트
                $sql = "select sum(mb_point) as mb_point from {$iw['member_table']}";
                $result = mysqli_query($db_conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$mb_point = (float)($row["mb_point"] ?? 0);
				
				//게시판 게시물
                $sql = "select count(md_no) as cnt from {$iw['mcb_data_table']} where md_display = 1";
                $result = mysqli_query($db_conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$mcb_count = (int)($row["cnt"] ?? 0);
				
				//컨텐츠 게시물
                $sql = "select count(dd_no) as cnt from {$iw['doc_data_table']} where dd_display = 1";
                $result = mysqli_query($db_conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$doc_count = (int)($row["cnt"] ?? 0);
				
				//이북 게시물
                $sql = "select count(bd_no) as cnt from {$iw['book_data_table']} where bd_display = 1";
                $result = mysqli_query($db_conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$book_count = (int)($row["cnt"] ?? 0);
				
				//게시판 댓글
                $sql = "select count(c.cm_no) as cnt from {$iw['comment_table']} as c join {$iw['mcb_data_table']} as m on c.cm_code = m.md_code where m.md_display = 1 and c.cm_display = 1";
                $result = mysqli_query($db_conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$mcb_comment = (int)($row["cnt"] ?? 0);
				
				//컨텐츠 댓글
                $sql = "select count(c.cm_no) as cnt from {$iw['comment_table']} as c join {$iw['doc_data_table']} as d on c.cm_code = d.dd_code where d.dd_display = 1 and c.cm_display = 1";
                $result = mysqli_query($db_conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$doc_comment = (int)($row["cnt"] ?? 0);
				
				//이북 댓글
                $sql = "select count(c.cm_no) as cnt from {$iw['comment_table']} as c join {$iw['book_data_table']} as b on c.cm_code = b.bd_code where b.bd_display = 1 and c.cm_display = 1";
                $result = mysqli_query($db_conn, $sql);
				$row = mysqli_fetch_assoc($result);
				$book_comment = (int)($row["cnt"] ?? 0);
				
				
				$now_start = "2013-11-30 00:00:00";
				$now_end = date("Y-m-d H:i:s", strtotime(date("Ymd").' - 1 days + 23 hours + 59 minutes + 59 seconds'));
				
				
				//게시판 매출
                $sql = "select sum(ms_price) as price_total from {$iw['mcb_support_table']} where (ms_datetime >= ? and ms_datetime <= ?)";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $now_start, $now_end);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
				$mcb_sales = (float)($row["price_total"] ?? 0);

				//컨텐츠 매출
                $sql = "select sum(db_price) as price_total from {$iw['doc_buy_table']} where (db_datetime >= ? and db_datetime <= ?)";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $now_start, $now_end);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
				$doc_sales = (float)($row["price_total"] ?? 0);

                $sql = "select sum(ds_price) as price_total from {$iw['doc_support_table']} where (ds_datetime >= ? and ds_datetime <= ?)";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $now_start, $now_end);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
				$doc_sales += (float)($row["price_total"] ?? 0);
				
				//이북 매출
                $sql = "select sum(bb_price) as price_total from {$iw['book_buy_table']} where (bb_datetime >= ? and bb_datetime <= ?)";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $now_start, $now_end);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
				$book_sales = (float)($row["price_total"] ?? 0);

                $sql = "select sum(bs_price) as price_total from {$iw['book_support_table']} where (bs_datetime >= ? and bs_datetime <= ?)";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $now_start, $now_end);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
				$book_sales += (float)($row["price_total"] ?? 0);
				
				//컨텐츠 다운로드
                $sql = "select count(db_no) as cnt from {$iw['doc_buy_table']} where (db_datetime >= ? and db_datetime <= ?)";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $now_start, $now_end);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
				$doc_download = (int)($row["cnt"] ?? 0);
				
				//이북 다운로드
                $sql = "select count(bb_no) as cnt from {$iw['book_buy_table']} where (bb_datetime >= ? and bb_datetime <= ?)";
                $stmt = mysqli_prepare($db_conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $now_start, $now_end);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
				$row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
				$book_download = (int)($row["cnt"] ?? 0);
			?>
				<div class="row">
					<div class="col-12">
						<h4 class="lighter"><i class="fas fa-star"></i>Total Stats</h4>
						<section class="content-box infobox-container">
						
							<div class="infobox infobox-blue">
								<div class="infobox-icon">
									<i class="fas fa-certificate"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($mb_point)?></span>
									<div class="infobox-content">잔여point</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="infobox infobox-blue">
								<div class="infobox-icon">
									<i class="far fa-pen-to-square"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($mcb_count+$doc_count+$book_count)?></span>
									<div class="infobox-content">총게시물수</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="infobox infobox-blue">
								<div class="infobox-icon">
									<i class="fas fa-comments"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($mcb_comment+$doc_comment+$book_comment)?></span>
									<div class="infobox-content">총댓글수</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="space-6"></div>
							
							<div class="infobox infobox-small infobox-dark infobox-blue">
								<div class="infobox-icon">
									<i class="fas fa-money-bill-wave"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">총매출</div>
									<div class="infobox-content"><?=number_format($mcb_sales+$doc_sales+$book_sales)?> point</div>
								</div>
							</div>
							
							<div class="infobox infobox-small infobox-dark infobox-grey">
								<div class="infobox-icon">
									<i class="fas fa-download"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">총다운로드</div>
									<div class="infobox-content"><?=number_format($doc_download+$book_download)?></div>
								</div>
							</div>
							
						</section>
					</div><!-- / .col -->
				</div><!-- / .row -->	
				
				<div class="hr hr-24"></div>
				
				<div class="row">
					<div class="col-sm-4">
						<h4 class="lighter"><i class="fas fa-star"></i>게시판 Stats</h4>
						<section class="content-box infobox-container">
							<div class="infobox infobox-green">
								<div class="infobox-icon">
									<i class="far fa-pen-to-square"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($mcb_count)?></span>
									<div class="infobox-content">게시판 게시물수</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="infobox infobox-green">
								<div class="infobox-icon">
									<i class="fas fa-comments"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($mcb_comment)?></span>
									<div class="infobox-content">게시판 댓글수</div>
								</div>
								<!--<div class="stat stat-down">8%</div>-->
							</div>

							<div class="space-6"></div>
							
							<div class="infobox infobox-small infobox-dark infobox-blue">
								<div class="infobox-icon">
									<i class="fas fa-money-bill-wave"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">매출</div>
									<div class="infobox-content"><?=number_format($mcb_sales)?> point</div>
								</div>
							</div>
							
						</section>
					</div><!-- / .col -->
					
					<div class="col-sm-4">
						<h4 class="lighter"><i class="fas fa-star"></i>컨텐츠 Stats</h4>
						<section class="content-box infobox-container">
						
							<div class="infobox infobox-yellow">
								<div class="infobox-icon">
									<i class="far fa-pen-to-square"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($doc_count)?></span>
									<div class="infobox-content">컨텐츠 게시물수</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="infobox infobox-yellow">
								<div class="infobox-icon">
									<i class="fas fa-comments"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($doc_comment)?></span>
									<div class="infobox-content">컨텐츠 댓글수</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="space-6"></div>
							
							<div class="infobox infobox-small infobox-dark infobox-blue">
								<div class="infobox-icon">
									<i class="fas fa-money-bill-wave"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">매출</div>
									<div class="infobox-content"><?=number_format($doc_sales)?> point</div>
								</div>
							</div>
							
							<div class="infobox infobox-small infobox-dark infobox-grey">
								<div class="infobox-icon">
									<i class="fas fa-download"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">다운로드</div>
									<div class="infobox-content"><?=number_format($doc_download)?></div>
								</div>
							</div>
							
						</section>
					</div><!-- / .col -->
					
					<div class="col-sm-4">
						<h4 class="lighter"><i class="fas fa-star"></i>e북 Stats</h4>
						<section class="content-box infobox-container">
						
							<div class="infobox infobox-red">
								<div class="infobox-icon">
									<i class="far fa-pen-to-square"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($book_count)?></span>
									<div class="infobox-content">이북 게시물수</div>
								</div>
								<!--<div class="stat stat-down">8%</div>-->
							</div>
							
							<div class="infobox infobox-red">
								<div class="infobox-icon">
									<i class="fas fa-comments"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($book_comment)?></span>
									<div class="infobox-content">이북 댓글수</div>
								</div>
								<!--<div class="stat stat-down">8%</div>-->
							</div>
							
							<div class="space-6"></div>
							
							<div class="infobox infobox-small infobox-dark infobox-blue">
								<div class="infobox-icon">
									<i class="fas fa-money-bill-wave"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">매출</div>
									<div class="infobox-content"><?=number_format($book_sales)?> point</div>
								</div>
							</div>
							
							<div class="infobox infobox-small infobox-dark infobox-grey">
								<div class="infobox-icon">
									<i class="fas fa-download"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">다운로드</div>
									<div class="infobox-content"><?=number_format($book_download)?></div>
								</div>
							</div>
							
						</section>
					</div><!-- / .col -->
				</div><!-- / .row -->
				
				<div class="hr hr-24"></div>
				
				<div class="row">
					<div class="col-sm-4">
						<?php
							$day_start = date("Y-m-d H:i:s", strtotime(date("Ymd").' - 1 days'));
							$day_end = date("Y-m-d H:i:s", strtotime(date("Ymd").' - 1 days + 23 hours + 59 minutes + 59 seconds'));
						?>
						<h4 class="lighter"><i class="fas fa-star"></i>오늘의 랭킹 (<?=date("Y년 m월 d일",strtotime($day_start))?>)</h4>
						<section class="content-box">
							<div class="dataTable-wrapper">
								<table class="table table-bordered table-striped table-hover dataTable">
									<thead>
										<tr>
											<th>
												<i class="fas fa-caret-right"></i>
												순위
											</th>

											<th>
												<i class="fas fa-caret-right"></i>
												닉네임
											</th>

											<th class="d-none d-sm-table-cell">
												<i class="fas fa-caret-right"></i>
												판매금액
											</th>
										</tr>
									</thead>

									<tbody>
									<?php
										$sql = "select r.*, m.mb_nick from {$iw['rank_day_table']} r left join {$iw['member_table']} m on r.mb_code = m.mb_code order by r.rd_price desc limit 10";
										$result = mysqli_query($db_conn, $sql);

										$i=1;
										while($row = mysqli_fetch_assoc($result)){
											$rd_price_raw = $row["rd_price"] ?? 0;
											$rd_price = is_numeric($rd_price_raw) ? number_format($rd_price_raw) : 0;
											$mb_nick = $row["mb_nick"] ?? '-';
									?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $mb_nick; ?></td>
											<td><?php echo $rd_price; ?> point</td>
										</tr>
									<?php
										$i++;
										}
									 for($i=$i;$i<=10;$i++){?>
										<tr>
											<td><?php echo $i; ?></td>
											<td>-</td>
											<td>0 point</td>
										</tr>
									<?php }?>
									</tbody>
								</table>
							</div><!-- / .dataTable-wrapper -->
						</section>
					</div><!-- / .col -->
					<div class="col-sm-4">
						<?php
							$day_start = date("Y-m-01 00:00:00");
							$day_end = date("Y-m-t 23:59:59");
						?>
						<h4 class="lighter"><i class="fas fa-star"></i>이달의 랭킹 (<?php echo date("Y년 m월",strtotime($day_start)); ?>)</h4>
						<section class="content-box">
							<div class="dataTable-wrapper">
								<table class="table table-bordered table-striped table-hover dataTable">
									<thead>
										<tr>
											<th>
												<i class="fas fa-caret-right"></i>
												순위
											</th>

											<th>
												<i class="fas fa-caret-right"></i>
												닉네임
											</th>

											<th class="d-none d-sm-table-cell">
												<i class="fas fa-caret-right"></i>
												판매금액
											</th>
										</tr>
									</thead>

									<tbody>
									<?php
										$sql = "select r.*, m.mb_nick from {$iw['rank_month_table']} r left join {$iw['member_table']} m on r.mb_code = m.mb_code order by r.rm_price desc limit 10";
										$result = mysqli_query($db_conn, $sql);

										$i=1;
										while($row = mysqli_fetch_assoc($result)){
											$rm_price_raw = $row["rm_price"] ?? 0;
											$rm_price = is_numeric($rm_price_raw) ? number_format($rm_price_raw) : 0;
											$mb_nick = $row["mb_nick"] ?? '-';
									?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $mb_nick; ?></td>
											<td><?php echo $rm_price; ?> point</td>
										</tr>
									<?php
										$i++;
										}
									 for($i=$i;$i<=10;$i++){?>
										<tr>
											<td><?php echo $i; ?></td>
											<td>-</td>
											<td>0 point</td>
										</tr>
									<?php }?>
									</tbody>
								</table>
							</div><!-- / .dataTable-wrapper -->
						</section>
					</div><!-- / .col -->
					<div class="col-sm-4">
						<h4 class="lighter"><i class="fas fa-star"></i>서버 사용현황</h4>
						<section class="content-box">
							<div class="dataTable-wrapper">
								<table class="table table-bordered table-striped table-hover dataTable">
									<thead>
										<tr>
											<th>
												<i class="fas fa-caret-right"></i>
												분류
											</th>

											<th>
												<i class="fas fa-caret-right"></i>
												크기
											</th>
										</tr>
										<tr>
											<td colspan="2" style="text-align:center;">
												<a href="show_top_cmd.php?type=dashboard&ep=infowayglobal&gp=all&status=1">SERVER PROCESSING STATUS</a> 
											</td>
										</tr>
									</thead>
									
									<tbody>
										<?php /*
											$linux_url =$iw['server_path'];
											$total_size = 0;
											
											$dir = $linux_url."/about";    // 체크하고 싶은 디렉토리 설정
											$du = exec('du -s '.$dir);
											$total_size += $du;
											$du = number_format($du/1024); 
											echo "<tr><td>소개</td><td>".$du." M</td></tr>";

											$dir = $linux_url."/mcb";    // 체크하고 싶은 디렉토리 설정
											$du = exec('du -s '.$dir);
											$total_size += $du;
											$du = number_format($du/1024); 
											echo "<tr><td>게시판</td><td>".$du." M</td></tr>";
											
											$dir = $linux_url."/shop";    // 체크하고 싶은 디렉토리 설정
											$du = exec('du -s '.$dir);
											$total_size += $du;
											$du = number_format($du/1024); 
											echo "<tr><td>쇼핑몰</td><td>".$du." M</td></tr>";

											$dir = $linux_url."/doc";    // 체크하고 싶은 디렉토리 설정
											$du = exec('du -s '.$dir);
											$total_size += $du;
											$du = number_format($du/1024); 
											echo "<tr><td>컨텐츠몰</td><td>".$du." M</td></tr>";

											$dir = $linux_url."/book";    // 체크하고 싶은 디렉토리 설정
											$du = exec('du -s '.$dir);
											$total_size += $du;
											$du = number_format($du/1024); 
											echo "<tr><td>이북몰</td><td>".$du." M</td></tr>";

											$dir = $linux_url."/main";    // 체크하고 싶은 디렉토리 설정
											$du = exec('du -s '.$dir);
											$total_size += $du;
											$du = number_format($du/1024); 
											echo "<tr><td>기타</td><td>".$du." M</td></tr>";

											$du = number_format($total_size/1024); 
											echo "<tr><td>전체 사용량</td><td>".$du." M</td></tr>";*/
										?>
									</tbody>
								</table>
							</div><!-- / .dataTable-wrapper -->
						</section>
					</div><!-- / .col -->
				</div><!-- / .row -->
			</div><!-- / .col -->
		</div><!-- / .row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->
<script>
var chkyn = '<?php echo $chkexpiry; ?>';
if(chkyn==1){
	console.log("start checking~");
	$.ajax({
		type: "GET", 
		url: "<?php echo $iw['super_path']; ?>/ajax/chk_expiry.php?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>", 
		dataType: "text",
		success: function(resData){
			console.log("completed!" + resData);
		},
		error: function(response){
			console.log('error\n\n' + response.responseText);
			return false;
		}
	});
	$.ajax({
		type: "GET", 
		url: "<?php echo $iw['super_path']; ?>/ajax/toss_settlement.php?type=<?php echo $iw['type']; ?>&ep=<?php echo $iw['store']; ?>&gp=<?php echo $iw['group']; ?>", 
		dataType: "text",
		success: function(resData){
			console.log("completed!" + resData);
		},
		error: function(response){
			console.log('error\n\n' + response.responseText);
			return false;
		}
	});
}else{
	console.log("checked already!");
}
</script>
<?php
include_once("_tail.php");
?>



