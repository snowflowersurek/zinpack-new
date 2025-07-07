<?php
include_once("_common.php");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
	$db_conn = $connect_db;
}

$search = $_REQUEST['search'] ?? '';
$searchs = $_REQUEST['searchs'] ?? '';
$start_date = $_REQUEST['start_date'] ?? date("Ymd", strtotime(date("Ymd").' -2 months'));
if (!$start_date) {
	$start_date = date("Ymd", strtotime(date("Ymd").' -2 months'));
}
$end_date = $_REQUEST['end_date'] ?? date("Ymd");
if (!$end_date) {
	$end_date = date("Ymd");
}

$now_start = date("Y-m-d H:i:s", strtotime($start_date));
$now_end = date("Y-m-d H:i:s", strtotime($end_date.' + 23 hours + 59 minutes + 59 seconds'));

$search_sql = " WHERE (a.pt_datetime >= ? AND a.pt_datetime <= ?) ";
$params = [$now_start, $now_end];
$types = "ss";

if($searchs) {
	$keyword_param = "%{$searchs}%";
	if($search =="a"){
		$search_sql .= " AND a.ep_code LIKE ? ";
		$params[] = $keyword_param; $types .= "s";
	}else if($search =="b"){
		$search_sql .= " AND a.ogd_oid LIKE ? ";
		$params[] = $keyword_param; $types .= "s";
	}
}
?>
<div class="breadcrumbs" id="breadcrumbs">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><i class="fas fa-credit-card"></i> 결제내역</li>
			<li class="breadcrumb-item active" aria-current="page">관리비 결제내역</li>
		</ol>
	</nav>
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			관리비 결제내역
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
											<input type="text" name="start_date" class="form-control" maxlength="8" value="<?=$start_date?>">
											<span class="input-group-text">~</span>
											<input type="text" name="end_date" class="form-control" maxlength="8" value="<?=$end_date?>">
											<button class="btn btn-primary" type="submit">조회</button>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="input-group float-end">
											<select name="search" class="form-select">
												<option value="a" <?php if($search=="a"){?>selected="selected"<?php }?>>업체코드</option>
												<option value="b" <?php if($search=="b"){?>selected="selected"<?php }?>>거래번호</option>
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
											<th>입금액</th>
											<th>거래번호</th>
											<th>승인결과</th>
											<th>승인일시</th>
											<th>결제방식</th>
											<th>결제자</th>
											<th>만료일자</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$sql_count = "SELECT count(*) as cnt FROM {$iw['charge_table']} a {$search_sql}";
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

										$sql = "SELECT a.*, b.lgd_respmsg, b.lgd_buyer, b.lgd_castamount, c.ep_corporate, c.ep_expiry_date
												FROM {$iw['charge_table']} a
												LEFT JOIN {$iw['lgd_table']} b ON a.ogd_oid = b.lgd_oid
												LEFT JOIN {$iw['enterprise_table']} c ON a.ep_code = c.ep_code
												{$search_sql} ORDER BY a.ch_no desc LIMIT ?, ?";
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
											$ep_code = $row["ep_code"];
											$ogd_oid = $row["ogd_oid"];
											$ch_amount = number_format($row["ch_amount"]);
											$lgd_paydate = date( "Y-m-d H:i:s", strtotime($row["pt_datetime"]) );
											$ch_paytype = $row["ch_paytype"];

											$row1 = sql_fetch("select * from $iw[lgd_table] where lgd_oid = '$ogd_oid'");
											if($row1['lgd_oid']==""){
												$lgd_respmsg = "미승인";
												$lgd_buyer = "";
												$lgd_castamount = "0";
											}else{
												$lgd_respmsg = $row1["lgd_respmsg"];
												$lgd_buyer = $row1["lgd_buyer"];
												$lgd_castamount = $row1["lgd_castamount"];
											}

											$row2 = sql_fetch("select ep_corporate, ep_expiry_date from $iw[enterprise_table] where ep_code = '$ep_code'");
											$ep_corporate = $row2["ep_corporate"];
											$ep_expiry = $row2["ep_expiry_date"];
									?>
										<tr>
											<td data-title="업체명"><?=$ep_corporate?></td>
											<td data-title="승인금액"><?=$ch_amount?>원</td>
											<td data-title="입금액"><?php if($lgd_castamount){?><?=number_format($lgd_castamount)?>원<?php }?></td>
											<td data-title="거래번호"><?=$ogd_oid?></td>
											<td data-title="승인결과"><?=$lgd_respmsg?></td>
											<td data-title="승인일시"><?=$lgd_paydate?></td>
											<td data-title="결제방식">
												<?php if($ch_paytype=="SC0010"){?>
													신용카드
												<?php }else if($ch_paytype=="SC0030"){?>
													계좌이체
												<?php }else if($ch_paytype=="SC0060"){?>
													휴대폰
												<?php }else if($ch_paytype=="SC0040"){?>
													가상계좌
												<?php }?>
											</td>
											<td data-title="결제자"><?=$lgd_buyer?></td>
											<td data-title="만료일자"><?=$ep_expiry?></td>
										</tr>
									<?php
										$i++;
										}
										mysqli_stmt_close($stmt);
										if($i==0) echo "<tr><td colspan='9' class='text-center'>결제승인내역이 없습니다.</td></tr>";
									?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-sm-6">
										<!-- <div class="dataTable-info">
											<button class="btn btn-success" type="button" onclick="location.href='<?=$iw['super_path']?>/pay_list_excel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&start_date=<?=$start_date?>&end_date=<?=$end_date?>'">
												<i class="fas fa-check"></i>
												엑셀 출력
											</button>
										</div>
									</div> -->
									<div class="col-sm-12">
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



