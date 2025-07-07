<?php
include_once("_common.php");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
	$db_conn = $connect_db;
}

$search = $_REQUEST['search'] ?? '';
$searchs = $_REQUEST['searchs'] ?? '';
$start_date = $_REQUEST['start_date'] ?? "20131201";
if (!$start_date) {
	$start_date = "20131201";
}
$end_date = $_REQUEST['end_date'] ?? date("Ymd");
if (!$end_date) {
	$end_date = date("Ymd");
}

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

$search_sql = " WHERE (a.pp_datetime >= ? AND a.pp_datetime <= ?) ";
$params = [$now_start, $now_end];
$types = "ss";

if($searchs) {
	$keyword_param = "%{$searchs}%";
	if($search =="a"){
		$search_sql .= " AND a.ep_code LIKE ? ";
		$params[] = $keyword_param; $types .= "s";
	}else if($search =="b"){
		$search_sql .= " AND a.pp_txn_id LIKE ? ";
		$params[] = $keyword_param; $types .= "s";
	}else if($search =="c"){
		$search_sql .= " AND a.pp_payer_email LIKE ? ";
		$params[] = $keyword_param; $types .= "s";
	}
}
?>
<div class="breadcrumbs" id="breadcrumbs">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><i class="fas fa-credit-card"></i> 결제내역</li>
			<li class="breadcrumb-item active" aria-current="page">PAYPAL결제내역</li>
		</ol>
	</nav>
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			PAYPAL결제내역
			<small>
				<i class="fas fa-angle-double-right"></i>
				목록
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12">
			<!-- PAGE CONTENT BEGINS -->
				<div class="row">
					<div class="col-12">
						<h4 class="lighter"><!--제목--></h4>
						<section class="content-box">
							<div class="table-header">
								<!--게시판설명-->
							</div>
							<div class="table-set-mobile dataTable-wrapper">
								<form name="fsearch" id="fsearch" action="<?=$PHP_SELF?>" method="get">
								<input type="hidden" name="type" value="<?=$iw[type]?>">
								<input type="hidden" name="ep" value="<?=$iw[store]?>">
								<input type="hidden" name="gp" value="<?=$iw[group]?>">
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group">
											<label class="input-group-text">상태</label>
											<select class="form-select" onchange="location.href=this.value">
												<option value="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>&search=d&searchs=">전체</option>
												<option value="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>&search=d&searchs=shop" <?php if{?>selected="selected"<?php }?>>쇼핑몰</option>
												<option value="<?=$PHP_SELF?>?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>&search=d&searchs=point" <?php if{?>selected="selected"<?php }?>>포인트</option>
											</select>
											<input type="text" name="start_date" class="form-control" maxlength="8" value="<?=$start_date?>">
											<span class="input-group-text">~</span>
											<input type="text" name="end_date" class="form-control" maxlength="8" value="<?=$end_date?>">
											<button class="btn btn-primary" type="submit">조회</button>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="input-group float-end">
											<select name="search" class="form-select">
												<option value="a" <?php if{?>selected="selected"<?php }?>>업체코드</option>
												<option value="b" <?php if{?>selected="selected"<?php }?>>거래번호</option>
												<option value="c" <?php if{?>selected="selected"<?php }?>>결제자</option>
											</select>
											<input type="text" name="searchs" class="form-control" value="<?=$searchs?>">
											<button class="btn btn-primary" type="submit">검색</button>
										</div>
									</div>
								</div>
								</form>
								<table class="table table-striped table-bordered table-hover dataTable mt-3">
									<thead>
										<tr>
											<th>업체명</th>
											<th>승인금액</th>
											<th>거래번호</th>
											<th>내용</th>
											<th>승인결과</th>
											<th>승인일시</th>
											<th>결제자</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql_count = "SELECT count(*) as cnt FROM {$iw['paypal_table']} a {$search_sql}";
										$stmt_count = mysqli_prepare($db_conn, $sql_count);
										mysqli_stmt_bind_param($stmt_count, $types, ...$params);
										mysqli_stmt_execute($stmt_count);
										$result_count = mysqli_stmt_get_result($stmt_count);
										$row_count = mysqli_fetch_assoc($result_count);
										$total_line = $row_count['cnt'];
										mysqli_stmt_close($stmt_count);

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

										$sql = "SELECT a.*, b.ep_corporate 
												FROM {$iw['paypal_table']} a
												LEFT JOIN {$iw['enterprise_table']} b ON a.ep_code = b.ep_code
												{$search_sql} 
												ORDER BY a.pp_no desc 
												LIMIT ?, ?";
										$stmt = mysqli_prepare($db_conn, $sql);
										$page_params = $params;
										$page_params[] = $start_line;
										$page_params[] = $max_line;
										$page_types = $types . "ii";
										mysqli_stmt_bind_param($stmt, $page_types, ...$page_params);
										mysqli_stmt_execute($stmt);
										$result = mysqli_stmt_get_result($stmt);

										$i=0;
										while($row = mysqli_fetch_assoc($result)){
											$ep_corporate = $row["ep_corporate"];
									?>
										<tr>
											<td data-title="업체명"><?=$ep_corporate?></td>
											<td data-title="승인금액"><?=national_money("en", $row["pp_mc_gross"]);?></td>
											<td data-title="거래번호"><?=$row["pp_txn_id"]?></td>
											<td data-title="내용"><?=$row["pp_item_name"]?></td>
											<td data-title="승인결과"><?=$row["pp_payment_status"]?></td>
											<td data-title="승인일시"><?=$row["pp_datetime"]?></td>
											<td data-title="결제자"><?=$row["mb_code"]?></td>
										</tr>
									<?php
										$i++;
										}
										mysqli_stmt_close($stmt);
										if($i==0) echo "<tr><td colspan='8' class='text-center'>결제승인내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['super_path']?>/pay_paypal_list_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>'">
												<i class="fas fa-check"></i>
												엑셀 출력
											</button>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="d-flex justify-content-center">
											<ul class="pagination">
											<?php
												if($total_page!=0){
													if($page>$total_page) { $page=$total_page; }
													$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
													$end_page = $start_page+$max_page-1;
												 
													if($end_page>$total_page) {$end_page=$total_page;}
												 
													if($page>$max_page) {
														$pre = $start_page - 1;
														echo "<li class='page-item'><a class='page-link' href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$pre&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'><i class='fas fa-angle-double-left'></i></a></li>";
													} else {
														echo "<li class='page-item disabled'><a class='page-link' href='#'><i class='fas fa-angle-double-left'></i></a></li>";
													}
													
													for($i=$start_page;$i<=$end_page;$i++) {
														if($i==$page) echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
														else          echo "<li class='page-item'><a class='page-link' href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$i&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'>$i</a></li>";
													}
												 
													if($end_page<$total_page) {
														$next = $end_page + 1;
														echo "<li class='page-item'><a class='page-link' href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&page=$next&search=$search&searchs=$searchs&start_date=$start_date&end_date=$end_date'><i class='fas fa-angle-double-right'></i></a></li>";
													} else {
														echo "<li class='page-item disabled'><a class='page-link' href='#'><i class='fas fa-angle-double-right'></i></a></li>";
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
document.addEventListener("DOMContentLoaded", function() {
    const fsearch = document.getElementById('fsearch');
    if (fsearch) {
        fsearch.addEventListener('submit', function(e) {
            const startDate = document.querySelector('input[name="start_date"]');
            const endDate = document.querySelector('input[name="end_date"]');
            const num ="0123456789";

            if (startDate.value.length < 8) {
                alert("날짜를 YYYYMMDD 형식으로 입력하여 주십시오.");
                startDate.focus();
                e.preventDefault();
                return;
            }
            for (let i = 0; i < startDate.value.length; i++) {
                if (num.indexOf(startDate.value.charAt(i)) === -1) {
                    alert('숫자로만 입력가능한 항목입니다.');
                    startDate.focus();
                    e.preventDefault();
                    return;
                }
            }
            if (endDate.value.length < 8) {
                alert("날짜를 YYYYMMDD 형식으로 입력하여 주십시오.");
                endDate.focus();
                e.preventDefault();
                return;
            }
            for (let i = 0; i < endDate.value.length; i++) {
                if (num.indexOf(endDate.value.charAt(i)) === -1) {
                    alert('숫자로만 입력가능한 항목입니다.');
                    endDate.focus();
                    e.preventDefault();
                    return;
                }
            }
        });
    }
});
</script>

<?php
include_once("_tail.php");
?>



