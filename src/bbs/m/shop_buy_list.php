<?php
include_once("_common.php");
if ($iw['level']=="guest") alert(national_language($iw['language'],"a0003","로그인 해주시기 바랍니다."),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&re_url={$iw['re_url']}");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<div class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="breadcrumb-box input-group">
                <ol class="breadcrumb ">
					<li><a href="<?=$iw['m_path']?>/shop_buy_list.php?type=shop&ep=<?=$iw['store']?>&gp=<?=$iw['group']?>"><?=$st_shop_name?> <?=national_language($iw['language'],"a0008","구매내역");?></a></li>
				</ol>
                <span class="input-group-btn">
					<a class="btn btn-theme" href="<?=$iw['m_path']?>/shop_cart_form.php?type=<?=$iw['type']?>&ep=<?=$iw['store']?>&gp=<?=$iw['group']?>" title="<?=national_language($iw['language'],"a0009","장바구니");?>"><i class="fa fa-shopping-cart fa-lg"></i></a>
					<a class="btn btn-theme" href="<?=$iw['m_path']?>/shop_buy_list.php?type=<?=$iw['type']?>&ep=<?=$iw['store']?>&gp=<?=$iw['group']?>" title="<?=national_language($iw['language'],"a0008","구매내역");?>"><i class="fa fa-truck fa-lg"></i></a>
                </span>
            </div>
			<div class="masonry">
				<div class="grid-sizer"></div>
				<?php
                    $sql_count = "select count(*) as cnt from {$iw['shop_order_table']} where ep_code = ? and mb_code = ? and sr_display <> 0";
                    $stmt_count = mysqli_prepare($db_conn, $sql_count);
                    mysqli_stmt_bind_param($stmt_count, "ss", $iw['store'], $iw['member']);
                    mysqli_stmt_execute($stmt_count);
                    $result_count = mysqli_stmt_get_result($stmt_count);
					$row_count = mysqli_fetch_assoc($result_count);
                    $total_line = $row_count['cnt'];
                    mysqli_stmt_close($stmt_count);

					$max_line = 5;
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

					$sql = "select * from {$iw['shop_order_table']} where ep_code = ? and mb_code = ? and sr_display <> 0 order by sr_no desc limit ?, ?";
                    $stmt = mysqli_prepare($db_conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ssii", $iw['store'], $iw['member'], $start_line, $end_line);
                    mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);

					$i=0;
					while($row = mysqli_fetch_assoc($result)){
						$sr_no = $row["sr_no"];
						$sr_code = $row["sr_code"];
						$sr_sum = $row["sr_sum"];
						$sr_datetime = date("Y-m-d", strtotime($row["sr_datetime"]));
						$sr_pay = $row["sr_pay"];

						if($sr_pay == "lguplus"){
							$pay_county = "ko";
						}else if($sr_pay == "paypal" || $sr_pay == "alipay"){
							$pay_county = "en";
						}

                        $sql_rowcnt = "select count(*) as cnt from {$iw['shop_order_sub_table']} where ep_code = ? and mb_code = ? and sr_code = ?";
                        $stmt_rowcnt = mysqli_prepare($db_conn, $sql_rowcnt);
                        mysqli_stmt_bind_param($stmt_rowcnt, "sss", $iw['store'], $iw['member'], $sr_code);
                        mysqli_stmt_execute($stmt_rowcnt);
                        $result_rowcnt = mysqli_stmt_get_result($stmt_rowcnt);
						$rowcnt = mysqli_fetch_assoc($result_rowcnt);
                        mysqli_stmt_close($stmt_rowcnt);
						$rowspan_count = $rowcnt['cnt'];
						
                        $sql_rowcnt = "select count(*) as cnt from {$iw['shop_order_sub_table']} where ep_code = ? and mb_code = ? and sr_code = ? and srs_display <> 1";
                        $stmt_rowcnt = mysqli_prepare($db_conn, $sql_rowcnt);
                        mysqli_stmt_bind_param($stmt_rowcnt, "sss", $iw['store'], $iw['member'], $sr_code);
                        mysqli_stmt_execute($stmt_rowcnt);
                        $result_rowcnt = mysqli_stmt_get_result($stmt_rowcnt);
						$rowcnt = mysqli_fetch_assoc($result_rowcnt);
                        mysqli_stmt_close($stmt_rowcnt);
						$cancel_confirm = $rowcnt['cnt'];

                        $sql_lgd = "select * from {$iw['lgd_table']} where ep_code = ? and state_sort = ? and mb_code = ? and lgd_oid = ? and lgd_display <> 0";
                        $stmt_lgd = mysqli_prepare($db_conn, $sql_lgd);
                        mysqli_stmt_bind_param($stmt_lgd, "ssss", $iw['store'], $iw['type'], $iw['member'], $sr_code);
                        mysqli_stmt_execute($stmt_lgd);
                        $result_lgd = mysqli_stmt_get_result($stmt_lgd);
						$rowcnt = mysqli_fetch_assoc($result_lgd);
                        mysqli_stmt_close($stmt_lgd);
						$lgd_paytype = $rowcnt["lgd_paytype"];

						$sqls = "select * from {$iw['shop_order_sub_table']} where ep_code = ? and mb_code = ? and sr_code = ? order by seller_mb_code asc, srs_bundle asc, srs_delivery_type desc, srs_delivery_price desc, sd_code asc, so_no asc";
                        $stmts = mysqli_prepare($db_conn, $sqls);
                        mysqli_stmt_bind_param($stmts, "sss", $iw['store'], $iw['member'], $sr_code);
                        mysqli_stmt_execute($stmts);
						$results = mysqli_stmt_get_result($stmts);
						
						$k=0;
						while($rows = mysqli_fetch_assoc($results)){
							$sd_code = $rows["sd_code"];
							$srs_amount = $rows["srs_amount"];
							$srs_name = stripslashes($rows["srs_name"]);
							$srs_subject = stripslashes($rows["srs_subject"]);
							$srs_delivery_num = $rows["srs_delivery_num"];
							$srs_delivery = $rows["srs_delivery"];
							if(trim($srs_delivery)!=""){
								$usql = "SELECT * FROM {$iw['delivery_info_table']} WHERE de_code = ?";
                                $ustmt = mysqli_prepare($db_conn, $usql);
                                mysqli_stmt_bind_param($ustmt, "s", $srs_delivery);
                                mysqli_stmt_execute($ustmt);
                                $uresult = mysqli_stmt_get_result($ustmt);
								$urow = mysqli_fetch_assoc($uresult);
                                mysqli_stmt_close($ustmt);
								$de_url = $urow['de_url'];
							}else{
								$de_url = "";
							}
							$srs_display = $rows["srs_display"];

							if($srs_display==1){
								$qa_display = national_language($iw['language'],"a0278","결제완료");
							}else if($srs_display==2){
								$qa_display = national_language($iw['language'],"a0279","준비중");
							}else if($srs_display==3){
								$qa_display = national_language($iw['language'],"a0280","배송중");
							}else if($srs_display==4){
								$qa_display = national_language($iw['language'],"a0281","배송완료");
							}else if($srs_display==8){
								$qa_display = national_language($iw['language'],"a0282","취소");
							}else if($srs_display==9){
								$qa_display = national_language($iw['language'],"a0283","반품");
							}else if($srs_display==7){
								$qa_display = national_language($iw['language'],"a0284","결제취소");
							}else if($srs_display==5){
								$qa_display = "입금대기";
							}
				 if($k==0){?>
					<div class="masonry-item w-full h-full">
						<div class="box br-theme">
							<div class="row" style="position:relative;">
								<div class="col-sm-9">
									<ul class="list-inline">
										<li><?=national_language($iw['language'],"a0211","주문번호");?> : <?=$sr_code?></li>
										<li><?=national_language($iw['language'],"a0216","구매일");?> : <?=$sr_datetime?></li>
										<li><?=national_language($iw['language'],"a0219","결제금액");?> : <?=national_money($pay_county, $sr_sum);?></li>
									</ul>
								</div>
								<div class="col-sm-3">
									<ul class="list-inline text-right">
										<li><a href="<?=$iw['m_path']?>/shop_buy_view.php?type=<?=$iw['type']?>&ep=<?=$iw['store']?>&gp=<?=$iw['group']?>&idx=<?=$sr_code?>" class="btn btn-theme btn-sm"><?=national_language($iw['language'],"a0285","상세내역");?></a></li>
										<?php
										$date1 = date_create_from_format('Y-m-d', $sr_datetime);
										$date2 = date_create_from_format('Y-m-d', date('Y-m-d'));
										$sr_date_diff = (array) date_diff($date1, $date2);
										
										if(!$cancel_confirm && $sr_pay == "lguplus" && $lgd_paytype != "SC0040" && $sr_date_diff['days'] < 15){?>
											<li><a href="javascript:all_cancel('<?=$iw['type']?>','<?=$iw['store']?>','<?=$iw['group']?>','<?=$sr_code?>');"" class="btn btn-theme btn-sm"><?=national_language($iw['language'],"a0284","결제취소");?></a></li>
										<?php
										}
										else if(!$cancel_confirm && $sr_pay=="lguplus" && $lgd_paytype=="SC0040" && $sr_date_diff['days'] < 15){
										//else if($lgd_paytype=="SC0040"){?>
											<li><a href="javascript:virtual_cancel('<?=$i?>');"" class="btn btn-theme btn-sm"><?=national_language($iw['language'],"a0325","입금취소");?></a></li>
										<?php
										}
										?>
									</ul>
								</div>
								<div id="vinput_<?=$i?>" class="virtualcls form-group collapse" style="width:100%;position:absolute;top:35px;right:5px;background-color:#B2EBF4;text-align:center;padding:8px;border:1px solid #272727;">
								<form name="virtualFrm_<?=$i?>" id="virtualFrm_<?=$i?>" method="POST" action="shop_buy_cancel_req_virtual.php?type=<?=$iw['type']?>&ep=<?=$iw['store']?>&gp=<?=$iw['group']?>&item=<?=$sr_code?>">
									<div><label for="">환급받을 은행정보를 입력해 주세요.</label><span style="color:red;">( ※ 환불 요청 후, 3 영업일 이후에 환불이 완료됩니다. )</span></div>
									<div>
										<select class="form-control" name="re_bank" id="re_bank" style="width:25%;margin-bottom:5px;display:inline-block;">
											<option value="">은행 선택</option>
											<?php
											$desql = "select * from {$iw['defined_table']} where de_type = 'bk' and de_display = 1 order by de_code asc";
											$deresult = mysqli_query($db_conn, $desql);
											while($derow = mysqli_fetch_assoc($deresult)){
											?>
											<option value="<?=$derow['de_code']?>"><?=$derow['de_memo']?></option>
											<?php } ?>
										</select>
										<input type="text" class="form-control" name="re_account" id="re_account" value="" style="width:30%;margin-bottom:5px;display:inline-block;" placeholder="환급받을 계좌번호" />
										<input type="text" class="form-control" name="re_name" id="re_name" value="" style="width:25%;margin-bottom:5px;display:inline-block;" placeholder="예금주명" />
										<a class="btn btn-theme" style="width:15%;margin-bottom:5px;display:inline-block;background-color:#FAF4C0;border:1px solid #272727;" href="javascript:check_form('<?=$i?>');">환급요청</a>
									</div>
								</form>
								</div>
							</div> <!-- /.row -->

							<table class="table responsive-table">
								<colgroup>
								   <col>
								</colgroup>
								<thead>
									<tr>
										<th style="width:40%;text-align:center;"><?=national_language($iw['language'],"a0286","상품명");?></th>
										<th style="width:20%;text-align:center;"><?=national_language($iw['language'],"a0287","옵션");?></th>
										<th style="width:10%;text-align:center;"><?=national_language($iw['language'],"a0288","갯수");?></th>
										<th style="width:30%;text-align:center;"><?=national_language($iw['language'],"a0289","상태");?></th>
									</tr>
								</thead>
								<tbody>
							<?php }?>
									<tr>
										<td data-th="<?=national_language($iw['language'],"a0286","상품명");?>"><?=$srs_subject?></td>
										<td data-th="<?=national_language($iw['language'],"a0287","옵션");?>"><?=$srs_name?></td>
										<td data-th="<?=national_language($iw['language'],"a0288","갯수");?>" style="text-align:center;"><?=$srs_amount?>개</td>
										<td data-th="<?=national_language($iw['language'],"a0289","상태");?>" style="text-align:center;">
											<span class="label <?php if{?>label-danger<?php }else if($srs_display==5){?>label-warning<?php }else{?>label-success<?php }?>"><?=$qa_display?></span>
											<?php if($srs_delivery_num != "" && ($srs_display==3 || $srs_display==4)){?>
												<a href="javascript:delivery_check('<?=$srs_delivery_num?>','<?=$de_url?>');" title="<?=national_language($iw['language'],"a0326","배송확인");?>:<?=$srs_delivery_num?>">
													<?=national_language($iw['language'],"a0326","배송확인");?>
												</a>
											<?php }?>
										</td>
									</tr>
							<?php if($rowspan_count == $k+1){?>
								</tbody>
							</table>
						</div> <!-- /.box -->
					</div> <!-- /.masonry-item -->
							<?php }?>
				<?php $k++;
						}
                        mysqli_stmt_close($stmts);
						$i++;
					}
                    mysqli_stmt_close($stmt);
				?>
			</div> <!-- /.masonry -->
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
								echo "<li><a href='$PHP_SELF?type=$iw['type']&ep=$iw['store']&gp=$iw['group']&page=$pre'>&laquo;</a></li>";
							} else {
								echo "<li><a href='#'>&laquo;</a></li>";
							}
							
							for($i=$start_page;$i<=$end_page;$i++) {
								if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
								else          echo "<li><a href='$PHP_SELF?type=$iw['type']&ep=$iw['store']&gp=$iw['group']&page=$i'>$i</a></li>";
							}
						 
							if($end_page<$total_page) {
								$next = $end_page + 1;
								echo "<li><a href='$PHP_SELF?type=$iw['type']&ep=$iw['store']&gp=$iw['group']&page=$next'>&raquo;</a></li>";
							} else {
								echo "<li><a href='#'>&raquo;</a></li>";
							}
						}
					?>
				</ul>
			</div>
		</div> <!-- /.col -->
	</div> <!-- /.row -->
</div> <!-- /.content -->
<input type="hidden" id="copyme" />
<script type="text/javascript">
	var clapsNum = "";
	function win_open(url, name, option)
    {
        var popup = window.open(url, name, option);
        popup.focus();
    }

    // 우편번호 창
    function delivery_check(num, url)
    {
        //url = "http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?ems_gubun=E&sid1="+num+"&POST_CODE=&mgbn=trace&traceselect=1&postNum="+num+"&x=27&y=1";
				//navigator.clipboard.writeText(num);
				$("#copyme").val(num);
				let txt = $("#copyme").val();
				navigator.clipboard.writeText(txt);

        win_open(url, "배송조회", "left=50,top=50,width=1250,height=800,scrollbars=1");
    }

	function all_cancel(type,ep,gp,sr_code)
	{
		if (confirm("<?=national_language($iw['language'],'a0291','결제취소를 진행 하시겠습니까? 확인을 누르시면 결제취소가 진행됩니다.');?>") == true) {
			location.href = "shop_buy_cancel_req.php?type="+type+"&ep="+ep+"&gp="+gp+"&item="+sr_code;
		}
	}
	function virtual_cancel(idnum) {
		$(".virtualcls").addClass("collapse");
		if(clapsNum != idnum){
			$("#vinput_"+idnum).toggleClass("collapse");
			clapsNum = idnum;
		}else{
			clapsNum = "";
		}
	}
	function check_form(frmnum) {
		var frm = document.getElementById("virtualFrm_"+frmnum);
		if (frm.re_bank.value == "") {
			alert("환급받을 은행을 선택해 주세요!");
			frm.re_bank.focus();
			return;
		}
		if (frm.re_account.value == "") {
			alert("은행 계좌를 입력해 주세요!");
			frm.re_account.focus();
			return;
		}
		if (frm.re_name.value == "") {
			alert("예금주명을 입력해 주세요!");
			frm.re_name.focus();
			return;
		}
		if(confirm("가상계좌입금 환불 요청 하시겠습니까?")==true){
			frm.submit();
		}
	}
</script>

<?php
include_once("_tail.php");
?>



