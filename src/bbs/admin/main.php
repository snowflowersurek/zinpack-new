<?php
include_once("_common.php");
include_once("_head.php");
include_once("rank_update.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-dashboard"></i>
			?�쉬보드
		</li>
		<li class="active">?�쉬보드</li>
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
			?�쉬보드
			<small>
				<i class="fa fa-angle-double-right"></i>
				?�쉬보드
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<?php
				$show_pay_button = false;
				$chkdate = date("Y-m-d");
				$eprow = sql_fetch("select * from $iw[enterprise_table] where ep_code = '$iw[store]'");
				$ep_edate = $eprow['ep_expiry_date'];
				$ep_member = $eprow['mb_code'];
				if($ep_edate != "0000-00-00" && $ep_member==$iw['member']){	// ?�이?��?리자�??�인 가??
					$diff = strtotime($ep_edate) - strtotime($chkdate);
					$diff_days = $diff / (60*60*24);
					if($diff_days < 50){
						$show_pay_button = true;
					}
				}
				$show_receipt_button = "";
				$chrow = sql_fetch("select * from $iw[charge_table] where ep_code = '$iw[store]' and ch_result = '1' order by pt_datetime desc");
				$pt_edate = $eprow['pt_datetime'];
				$paytype = $eprow['ch_paytype'];
				$orderid = $eprow['ogd_oid'];
				if($pt_edate && $paytype=="SC0040" && $ep_member==$iw['member']){	// ?�이?��?리자�??�인 가??
					$diff = strtotime($chkdate) - strtotime($pt_edate);
					$diff_days = $diff / (60*60*24);
					if($diff_days < 50){
						if($paytype=="SC0010") $show_receipt_button = "1";
						else if($paytype=="SC0040") $show_receipt_button = "2";
					}
				}

				//?�여?�인??
				$row = sql_fetch("select mb_point from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'");
				$mb_point = $row["mb_point"];
				
				//게시??게시�?
				$row = sql_fetch("select count(md_no) as cnt from $iw[mcb_data_table] where ep_code = '$iw[store]' and md_display = 1 and  mb_code = '$iw[member]'");
				$mcb_count = $row["cnt"];
				
				//컨텐�?게시�?
				$row = sql_fetch("select count(dd_no) as cnt from $iw[doc_data_table] where ep_code = '$iw[store]' and dd_display = 1 and  mb_code = '$iw[member]'");
				$doc_count = $row["cnt"];
				
				//?�북 게시�?
				$row = sql_fetch("select count(bd_no) as cnt from $iw[book_data_table] where ep_code = '$iw[store]' and bd_display = 1 and  mb_code = '$iw[member]'");
				$book_count = $row["cnt"];
				
				//게시???��?
				$row = sql_fetch("select count(c.cm_no) as cnt from $iw[comment_table] as c join $iw[mcb_data_table] as m on c.cm_code = m.md_code where m.ep_code = '$iw[store]' and m.mb_code = '$iw[member]' and m.md_display = 1 and c.cm_display = 1");
				$mcb_comment = $row["cnt"];
				
				//컨텐�??��?
				$row = sql_fetch("select count(c.cm_no) as cnt from $iw[comment_table] as c join $iw[doc_data_table] as d on c.cm_code = d.dd_code where d.ep_code = '$iw[store]' and d.mb_code = '$iw[member]' and d.dd_display = 1 and c.cm_display = 1");
				$doc_comment = $row["cnt"];
				
				//?�북 ?��?
				$row = sql_fetch("select count(c.cm_no) as cnt from $iw[comment_table] as c join $iw[book_data_table] as b on c.cm_code = b.bd_code where b.ep_code = '$iw[store]' and b.mb_code = '$iw[member]' and b.bd_display = 1 and c.cm_display = 1");
				$book_comment = $row["cnt"];
				
				
				$sql = "select * from $iw[account_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
				$row = sql_fetch($sql);
				$ac_calculate = $row["ac_calculate"];
				$now_start = date("Y-m-d H:i:s", strtotime(date("Ymd", strtotime($ac_calculate))));
				$now_end = date("Y-m-d H:i:s", strtotime(date("Ymd").' - 1 days + 23 hours + 59 minutes + 59 seconds'));
				
				//게시??매출
				$row = sql_fetch("select sum(ms_price) as price_total from $iw[mcb_support_table] where ep_code = '$iw[store]' and seller_mb_code='$iw[member]' and (ms_datetime >= '$now_start' and ms_datetime <= '$now_end')");
				$mcb_sales = $row["price_total"];

				//컨텐�?매출
				$row = sql_fetch("select sum(db_price) as price_total from $iw[doc_buy_table] where ep_code = '$iw[store]' and seller_mb_code='$iw[member]' and (db_datetime >= '$now_start' and db_datetime <= '$now_end')");
				$doc_sales = $row["price_total"];
				$row = sql_fetch("select sum(ds_price) as price_total from $iw[doc_support_table] where ep_code = '$iw[store]' and seller_mb_code='$iw[member]' and (ds_datetime >= '$now_start' and ds_datetime <= '$now_end')");
				$doc_sales = $doc_sales + $row["price_total"];
				
				//?�북 매출
				$row = sql_fetch("select sum(bb_price) as price_total from $iw[book_buy_table] where ep_code = '$iw[store]' and seller_mb_code='$iw[member]' and (bb_datetime >= '$now_start' and bb_datetime <= '$now_end')");
				$book_sales = $row["price_total"];
				$row = sql_fetch("select sum(bs_price) as price_total from $iw[book_support_table] where ep_code = '$iw[store]' and seller_mb_code='$iw[member]' and (bs_datetime >= '$now_start' and bs_datetime <= '$now_end')");
				$book_sales = $book_sales + $row["price_total"];
				
				//컨텐�??�운로드
				$row = sql_fetch("select count(db_no) as cnt from $iw[doc_buy_table] where ep_code = '$iw[store]' and seller_mb_code='$iw[member]' and (db_datetime >= '$now_start' and db_datetime <= '$now_end')");
				$doc_download = $row["cnt"];
				
				//?�북 ?�운로드
				$row = sql_fetch("select count(bb_no) as cnt from $iw[book_buy_table] where ep_code = '$iw[store]' and seller_mb_code='$iw[member]' and (bb_datetime >= '$now_start' and bb_datetime <= '$now_end')");
				$book_download = $row["cnt"];
			?>
				<div class="row">
					<div class="col-sm-12">
						<h4 class="lighter"><i class="fa fa-star"></i>Total Stats</h4>
						<section class="content-box infobox-container">
						
							<div class="infobox infobox-blue">
								<div class="infobox-icon">
									<i class="fa fa-certificate"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($mb_point)?></span>
									<div class="infobox-content">보유Point</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="infobox infobox-blue">
								<div class="infobox-icon">
									<i class="fa fa-pencil-square-o"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($mcb_count+$doc_count+$book_count)?></span>
									<div class="infobox-content">총게시물수</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="infobox infobox-blue">
								<div class="infobox-icon">
									<i class="fa fa-comments"></i>
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
									<i class="fa fa-money"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">총매출</div>
									<div class="infobox-content"><?=number_format($mcb_sales+$doc_sales+$book_sales)?> Point</div>
								</div>
							</div>
							
							<div class="infobox infobox-small infobox-dark infobox-grey">
								<div class="infobox-icon">
									<i class="fa fa-download"></i>
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
						<h4 class="lighter"><i class="fa fa-star"></i>게시판 Stats</h4>
						<section class="content-box infobox-container">
							<div class="infobox infobox-green">
								<div class="infobox-icon">
									<i class="fa fa-pencil-square-o"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($mcb_count)?></span>
									<div class="infobox-content">게시판 게시물수</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="infobox infobox-green">
								<div class="infobox-icon">
									<i class="fa fa-comments"></i>
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
									<i class="fa fa-money"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">매출</div>
									<div class="infobox-content"><?=number_format($mcb_sales)?> Point</div>
								</div>
							</div>
							
						</section>
					</div><!-- / .col -->
					
					<div class="col-sm-4">
						<h4 class="lighter"><i class="fa fa-star"></i>컨텐츠 Stats</h4>
						<section class="content-box infobox-container">
						
							<div class="infobox infobox-yellow">
								<div class="infobox-icon">
									<i class="fa fa-pencil-square-o"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($doc_count)?></span>
									<div class="infobox-content">컨텐츠 게시물수</div>
								</div>
								<!--<div class="stat stat-up">8%</div>-->
							</div>
							
							<div class="infobox infobox-yellow">
								<div class="infobox-icon">
									<i class="fa fa-comments"></i>
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
									<i class="fa fa-money"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">매출</div>
									<div class="infobox-content"><?=number_format($doc_sales)?> Point</div>
								</div>
							</div>
							
							<div class="infobox infobox-small infobox-dark infobox-grey">
								<div class="infobox-icon">
									<i class="fa fa-download"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">다운로드</div>
									<div class="infobox-content"><?=number_format($doc_download)?></div>
								</div>
							</div>
							
						</section>
					</div><!-- / .col -->
					
					<div class="col-sm-4">
						<h4 class="lighter"><i class="fa fa-star"></i>이북 Stats</h4>
						<section class="content-box infobox-container">
						
							<div class="infobox infobox-red">
								<div class="infobox-icon">
									<i class="fa fa-pencil-square-o"></i>
								</div>
								<div class="infobox-data">
									<span class="infobox-data-number"><?=number_format($book_count)?></span>
									<div class="infobox-content">이북 게시물수</div>
								</div>
								<!--<div class="stat stat-down">8%</div>-->
							</div>
							
							<div class="infobox infobox-red">
								<div class="infobox-icon">
									<i class="fa fa-comments"></i>
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
									<i class="fa fa-money"></i>
								</div>
								<div class="infobox-data">
									<div class="infobox-content">매출</div>
									<div class="infobox-content"><?=number_format($book_sales)?> Point</div>
								</div>
							</div>
							
							<div class="infobox infobox-small infobox-dark infobox-grey">
								<div class="infobox-icon">
									<i class="fa fa-download"></i>
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
						<h4 class="lighter"><i class="fa fa-star"></i>어제 판매순위 (<?=date("Y년 m월 d일", strtotime($day_start))?>)</h4>
						<section class="content-box">
							<div class="dataTable-wrapper">
								<table class="table table-bordered table-striped table-hover dataTable">
									<thead>
										<tr>
											<th>
												<i class="fa fa-caret-right"></i>
												순위
											</th>

											<th>
												<i class="fa fa-caret-right"></i>
												유저명
											</th>

											<th class="hidden-480">
												<i class="fa fa-caret-right"></i>
												판매금액
											</th>
										</tr>
									</thead>

									<tbody>
									<?php
										$sql = "select * from $iw[rank_day_table] where ep_code = '$iw[store]' order by rd_price desc limit 0, 10";
										$result = sql_query($sql);

										$i=1;
										while($row = @sql_fetch_array($result)){
											$rd_price = number_format($row["rd_price"]);
											$mb_code = $row["mb_code"];
											$rd_total = $row["rd_total"];
											
											$row2 = sql_fetch("select * from $iw[member_table] where mb_code = '$mb_code' and ep_code = '$iw[store]'");
											$mb_nick = $row2["mb_nick"];
									?>
										<tr>
											<td><?=$i?></td>
											<td><?=$mb_nick?></td>
											<td><?=$rd_price?> Point</td>
										</tr>
									<?php
										$i++;
										}
									 for($i=$i;$i<=10;$i++){?>
										<tr>
											<td><?=$i?></td>
											<td>-</td>
											<td>0 Point</td>
										</tr>
									<?php }
										$row = sql_fetch("select * from $iw[rank_day_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'");
										if($row["rd_no"]){
											$rd_price = $row["rd_price"];
											$row = sql_fetch("select count(*) as cnt from $iw[rank_day_table] where rd_price > $rd_price and ep_code = '$iw[store]'");
											$my_rank = $row["cnt"]+1;
										}else{
											$rd_price = 0;
											$my_rank = "-";
										}
										$row = sql_fetch("select * from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'");
										$mb_nick = $row["mb_nick"];
									?>
										<tr>
											<td><?=$my_rank?></td>
											<td><?=$mb_nick?></td>
											<td><?=number_format($rd_price)?> Point</td>
										</tr>
									</tbody>
								</table>
							</div><!-- / .dataTable-wrapper -->
						</section>
					</div><!-- / .col -->
					<div class="col-sm-4">
						<?php
							$day_start = date("Y-m-d H:i:s", strtotime(date("Ymd")-date("day").'+ 1 day'));
							$day_end = date("Y-m-d H:i:s", strtotime(date("Ymd").'+ 23 hours + 59 minutes + 59 seconds'));
						?>
						<h4 class="lighter"><i class="fa fa-star"></i>이번달 판매순위 (<?=date("Y년 m월", strtotime($day_start))?>)</h4>
						<section class="content-box">
							<div class="dataTable-wrapper">
								<table class="table table-bordered table-striped table-hover dataTable">
									<thead>
										<tr>
											<th>
												<i class="fa fa-caret-right"></i>
												순위
											</th>

											<th>
												<i class="fa fa-caret-right"></i>
												유저명
											</th>

											<th class="hidden-480">
												<i class="fa fa-caret-right"></i>
												판매금액
											</th>
										</tr>
									</thead>

									<tbody>
									<?php
										$sql = "select * from $iw[rank_month_table] where ep_code = '$iw[store]' order by rm_price desc limit 0, 10";
										$result = sql_query($sql);

										$i=1;
										while($row = @sql_fetch_array($result)){
											$rm_price = number_format($row["rm_price"]);
											$mb_code = $row["mb_code"];
											$rm_total = $row["rm_total"];
											
											$row2 = sql_fetch("select * from $iw[member_table] where mb_code = '$mb_code' and ep_code = '$iw[store]'");
											$mb_nick = $row2["mb_nick"];
									?>
										<tr>
											<td><?=$i?></td>
											<td><?=$mb_nick?></td>
											<td><?=$rm_price?> Point</td>
										</tr>
									<?php
										$i++;
										}
									 for($i=$i;$i<=10;$i++){?>
										<tr>
											<td><?=$i?></td>
											<td>-</td>
											<td>0 Point</td>
										</tr>
									<?php }
										$row = sql_fetch("select * from $iw[rank_month_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'");
										if($row["rm_no"]){
											$rm_price = $row["rm_price"];
											$row = sql_fetch("select count(*) as cnt from $iw[rank_month_table] where rm_price > $rm_price and ep_code = '$iw[store]'");
											$my_rank = $row["cnt"]+1;
										}else{
											$rm_price = 0;
											$my_rank = "-";
										}
										$row = sql_fetch("select * from $iw[member_table] where mb_code = '$iw[member]' and ep_code = '$iw[store]'");
										$mb_nick = $row["mb_nick"];
									?>
										<tr>
											<td><?=$my_rank?></td>
											<td><?=$mb_nick?></td>
											<td><?=number_format($rm_price)?> Point</td>
										</tr>
									</tbody>
								</table>
							</div><!-- / .dataTable-wrapper -->
						</section>
					</div><!-- / .col -->
					<div class="col-sm-4">
						<h4 class="lighter"><i class="fa fa-star"></i>서버 디스크 사용량</h4>
						<section class="content-box">
							<div class="dataTable-wrapper" id="dir_size">
								<button class="btn btn-success ladda-button" data-style="expand-left" onclick="getDirectorySize(this);">조회</button>
							</div><!-- / .dataTable-wrapper -->
							<div><!-- pay charge -->
							</div>
						</section>
					</div><!-- / .col -->
					<?php
					if($show_pay_button){
					?>
					<div class="col-sm-4">
						<h4 class="lighter"><i class="fa fa-star"></i>서버 사용 만료기간</h4>
						<section class="content-box">
							<div class="dataTable-wrapper">
								<table class="table table-bordered table-striped table-hover dataTable">
									<thead>
										<tr>
											<th>
												<i class="fa fa-caret-right"></i>
												만료기간
											</th>
											<th>
												<i class="fa fa-caret-right"></i>
												결제
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="text-align:center;">
												<?=$iw['expiry_date']?> 
											</td>
											<td style="text-align:center;">
												<a href="pay_charge.php?type=charge&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">결제하기</a> 
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</section>
					</div>
					<?php } ?><?php if($show_receipt_button){ 
								$result = get_receipt($orderid, $show_receipt_button);
					?>
						<div class="col-sm-4">
							<h4 class="lighter"><i class="fa fa-star"></i><a href="javascript:openwin('<?=$result[url]?>')"><?=$result['txt']?></a></h4>
						</div>
					<?php } ?>
				</div><!-- / .row -->
			</div><!-- / .col -->
		</div><!-- / .row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->
<script>
$(function() {
	Ladda.bind('button.ladda-button');
});

function getDirectorySize(element) {
	$(element).text("최대 1분 이상 소요될 수 있습니다.");
	var loading = Ladda.create(element);
	loading.start();

	setTimeout(function() {
		$.ajax({
			type: "GET", 
			url: "<?=$iw['admin_path']?>/ajax/_directory_size.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>", 
			dataType: "json",
			success: function(resData){
				loading.stop();
				$("#dir_size").html('<table class="table table-bordered table-striped table-hover dataTable"><tr><td>'+ resData.dir_size +'</td></tr></table>');
			},
			error: function(response){
				loading.stop();
				$(element).text("조회");
				alert('error\n\n' + response.responseText);
				return false;
			}
		});
	}, 500);
}

function openwin(url){
	var win = window.open(url, "PopupWin", "width=500,height=800");
}
</script>
<?php
include_once("_tail.php");
?>




