<?php
include_once("_common.php");
if ($iw['level']=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

if (!isset($_GET["idx"])) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");
$sr_code = trim($_GET["idx"]);

$sql = "select a.*, b.ct_name from {$iw['shop_order_table']} as a left join {$iw['country_table']} as b on a.sr_address_country = b.ct_code where a.ep_code = ? and a.mb_code = ? and a.sr_code = ? and a.sr_display <> 0";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $iw['store'], $iw['member'], $sr_code);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row["sr_no"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$sr_no = $row["sr_no"];
$sr_name = $row["sr_name"];
$sr_phone = $row["sr_phone"];
$sr_phone_sub = $row["sr_phone_sub"];
$sr_zip_code = $row["sr_zip_code"];
$sr_address = $row["sr_address"];
$sr_address_sub = $row["sr_address_sub"];
$sr_address_city = $row["sr_address_city"];
$sr_address_state = $row["sr_address_state"];
$sr_address_country = $row["ct_name"];
$sr_request = $row["sr_request"];
$sr_sum = $row["sr_sum"];
$sr_point = number_format($row["sr_point"]);
$sr_datetime = date("Y-m-d", strtotime($row["sr_datetime"]));
$sr_pay = $row["sr_pay"];

$address = "[".$sr_zip_code."] ".$sr_address." ".$sr_address_sub;
if($sr_pay == "lguplus"){
	$pay_county = "ko";
	$sql = "select * from {$iw['lgd_table']} where ep_code = ? and state_sort = ? and mb_code = ? and lgd_oid = ? and lgd_display <> 0";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $iw['store'], $iw['type'], $iw['member'], $sr_code);
    mysqli_stmt_execute($stmt);
    $result_pay = mysqli_stmt_get_result($stmt);
	$row_pay = mysqli_fetch_assoc($result_pay);
    mysqli_stmt_close($stmt);
	$lgd_mid = $row_pay["lgd_mid"];
	$lgd_tid = $row_pay["lgd_tid"];
	$lgd_display = $row_pay["lgd_display"];
	$lgd_amount = $row_pay["lgd_amount"];
	$lgd_paytype = $row_pay["lgd_paytype"];
	$lgd_financename = $row_pay["lgd_financename"];
	$lgd_cardinstallmonth = $row_pay["lgd_cardinstallmonth"];
	$lgd_cardnointyn = $row_pay["lgd_cardnointyn"];
	$lgd_accountowner = $row_pay["lgd_accountowner"];
	$lgd_payer = $row_pay["lgd_payer"];
	$lgd_accountnum = $row_pay["lgd_accountnum"];
	$ptype = ($lgd_paytype=="SC0040")?"2":"1";
}else if($sr_pay == "paypal"){
	$pay_county = "en";
	$address = "[".$sr_zip_code."] ".$sr_address_sub.", ".$sr_address.", ".$sr_address_city.", ".$sr_address_state.", ".$sr_address_country;
	$sql = "select * from {$iw['paypal_table']} where ep_code = ? and state_sort = ? and mb_code = ? and pp_invoice = ? and pp_display <> 0";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $iw['store'], $iw['type'], $iw['member'], $sr_code);
    mysqli_stmt_execute($stmt);
    $result_pay = mysqli_stmt_get_result($stmt);
	$row_pay = mysqli_fetch_assoc($result_pay);
    mysqli_stmt_close($stmt);
	$lgd_amount = $row_pay["pp_mc_gross"];
}else if($sr_pay == "alipay"){
	$pay_county = "en";
	$address = "[".$sr_zip_code."] ".$sr_address_sub.", ".$sr_address.", ".$sr_address_city.", ".$sr_address_state.", ".$sr_address_country;
	$sql = "select * from {$iw['alipay_table']} where ep_code = ? and state_sort = ? and mb_code = ? and ap_out_trade_no = ? and ap_display <> 0";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $iw['store'], $iw['type'], $iw['member'], $sr_code);
    mysqli_stmt_execute($stmt);
    $result_pay = mysqli_stmt_get_result($stmt);
	$row_pay = mysqli_fetch_assoc($result_pay);
    mysqli_stmt_close($stmt);
	$lgd_amount = $row_pay["ap_total_fee"];
}

?>

<div class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="breadcrumb-box input-group">
                <ol class="breadcrumb ">
					<li><a href="#"><?=$st_shop_name?> <?=national_language($iw[language],"a0008","구매내역");?></a></li>
				</ol>
                <span class="input-group-btn">
					<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_cart_form.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0009","장바구니");?>"><i class="fa fa-shopping-cart fa-lg"></i></a>
					<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0008","구매내역");?>"><i class="fa fa-truck fa-lg"></i></a>
                </span>
            </div>
			<div class="masonry">
				<div class="grid-sizer"></div>
				<?
					$total_price = 0;
					$total_delivery = 0;
					$sd_code_check = "";
					$mb_code_check = "";

					$sql = "SELECT a.*, b.de_url, c.rowspan 
                            FROM {$iw['shop_order_sub_table']} a 
                            LEFT JOIN {$iw['delivery_info_table']} b ON a.srs_delivery = b.de_code
                            LEFT JOIN (SELECT sd_code, COUNT(*) as rowspan 
                                       FROM {$iw['shop_order_sub_table']} 
                                       WHERE ep_code = ? AND mb_code = ? AND sr_code = ? 
                                       GROUP BY sd_code) c ON a.sd_code = c.sd_code
                            WHERE a.ep_code = ? AND a.mb_code = ? AND a.sr_code = ? 
                            ORDER BY a.seller_mb_code, a.srs_bundle, a.sd_code, a.so_no";
                    $stmt = mysqli_prepare($db_conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ssssss", $iw['store'], $iw['member'], $sr_code, $iw['store'], $iw['member'], $sr_code);
                    mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);

					while($row = mysqli_fetch_assoc($result)){
						$sd_code = $row["sd_code"];
						$seller_mb_code = $row["seller_mb_code"];
						$so_no = $row["so_no"];
						$srs_amount = $row["srs_amount"];
						$srs_delivery_type = $row["srs_delivery_type"];
						$srs_price = $row["srs_price"];
						$srs_delivery_price = $row["srs_delivery_price"];
						$srs_bundle = $row["srs_bundle"];
						$srs_name = stripslashes($row["srs_name"]);
						$srs_subject = stripslashes($row["srs_subject"]);
						$srs_delivery_num = $row["srs_delivery_num"];
						$srs_delivery = $row["srs_delivery"];
						$de_url = $row['de_url'];
						$srs_display = $row["srs_display"];
                        $rowspan = $row['rowspan'];

						if(trim($srs_delivery)!=""){
							$usql = "SELECT * FROM $iw[delivery_info_table] WHERE de_code = '$srs_delivery'";
							$urow = sql_fetch($usql);
							$de_url = $urow['de_url'];
						}else{
							$de_url = "";
						}

						if($srs_display==1){
							$qa_display = national_language($iw[language],"a0278","결제완료");
						}else if($srs_display==2){
							$qa_display = national_language($iw[language],"a0279","준비중");
						}else if($srs_display==3){
							$qa_display = national_language($iw[language],"a0280","배송중");
						}else if($srs_display==4){
							$qa_display = national_language($iw[language],"a0281","배송완료");
						}else if($srs_display==8){
							$qa_display = national_language($iw[language],"a0282","취소");
						}else if($srs_display==9){
							$qa_display = national_language($iw[language],"a0283","반품");
						}else if($srs_display==7){
							$qa_display = national_language($iw[language],"a0284","결제취소");
						}else if($srs_display==5){
							$qa_display = "입금대기";
						}

						if($seller_mb_code != $mb_code_check){
							$mb_code_check = $seller_mb_code;
						}

						if($sd_code != $sd_code_check){
							$i = 0;
							$sd_code_check = $sd_code;
						}

						$total_price += $srs_price*$srs_amount;
				?>
				<?if($i == 0){?>
					<div class="masonry-item w-full h-full">
						<div class="box br-theme">
							<h4><a href="<?=$iw['m_path']?>/shop_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$sd_code?>"><?=$srs_subject?> 
									<small>
										[<?=national_language($iw[language],"a0293","배송비");?>]
										<?
											$total_delivery += $srs_delivery_price;
											echo $sy_name." ".national_money($pay_county, $srs_delivery_price);
										?>
										 (<?=substr($seller_mb_code, 3, 3).substr($seller_mb_code,-3,3);?>-<?=$srs_bundle?>)
									</small>
								</a>
							</h4>
							<table class="table responsive-table">
								<colgroup>
								   <col style="width:50%;">
								</colgroup>
								<thead>
									<tr>
										<th><?=national_language($iw[language],"a0286","상품명");?></th>
										<th><?=national_language($iw[language],"a0288","갯수");?></th>
										<th><?=national_language($iw[language],"a0151","가격");?></th>
										<th><?=national_language($iw[language],"a0289","상태");?></th>
									</tr>
								</thead>
								<tbody>
							<?}?>
									<tr>
										<td data-th="<?=national_language($iw[language],"a0286","상품명");?>"><?=$srs_name?></td>
										<td data-th="<?=national_language($iw[language],"a0288","갯수");?>"><?=$srs_amount?>개</td>
										<td data-th="가격"><?=national_money($pay_county, $srs_price*$srs_amount);?></td>
										<td data-th="<?=national_language($iw[language],"a0289","상태");?>">
											<span class="label <?if($srs_display==7){?>label-danger<?}else if($srs_display==5){?>label-warning<?}else{?>label-success<?}?>"><?=$qa_display?></span>
											<?if($srs_delivery_num != "" && ($srs_display==3 || $srs_display==4)){?>
												<a href="javascript:delivery_check('<?=$srs_delivery_num?>','<?=$de_url?>');" title="<?=national_language($iw[language],"a0290","등기번호");?>:<?=$srs_delivery_num?>">
													<?=national_language($iw[language],"a0290","등기번호");?>
												</a>
											<?}?>
										</td>
									</tr>

							<?if($rowspan == $i+1){?>
								</tbody>
							</table>
						</div> <!-- /.box -->
					</div> <!-- /.masonry-item -->
					<?}?>
					<?
							$i++;
						}
                        mysqli_stmt_close($stmt);
					?>
				<div class="masonry-item w-full h-full">
					<div class="box br-theme">
						<div class="row">
							<div class="col-sm-6">
								<ul class="list-unstyled">
									<li><?=national_language($iw[language],"a0211","주문번호");?> : <?=$sr_code?></li>
									<li><?=national_language($iw[language],"a0216","구매일");?> : <?=$sr_datetime?></li>
									<li><?=national_language($iw[language],"a0218","주문금액");?> : <?=national_money($pay_county, $sr_sum);?> (<?=national_language($iw[language],"a0292","포인트");?> : <?=$sr_point?>Point)</li>
									<li>
										<?=national_language($iw[language],"a0219","결제금액");?> : <?=national_money($pay_county, $lgd_amount);?> 
										<?if($lgd_paytype=="SC0010"){?>
											/ 신용카드 ( <?=$lgd_financename?> / <?if($lgd_cardnointyn == 0){?>일시불<?}else{?><?=$lgd_cardinstallmonth?>개월<?}?> )
										<?}else if($lgd_paytype=="SC0030"){?>
											/ 계좌이체 ( <?=$lgd_financename?> / 계좌주 : <?=$lgd_accountowner?> )
										<?}else if($lgd_paytype=="SC0060"){?>
											/ 휴대폰 ( <?=$lgd_financename?> )
										<?}else if($lgd_paytype=="SC0040"){?>
											/ 가상계좌 ( <?=$lgd_financename?> <?=$lgd_accountnum?> / 입금자명 : <?=$lgd_payer?> )
										<?}else if($sr_pay == "paypal"){?>
											/ PAYPAL
										<?}else if($sr_pay == "alipay"){?>
											/ ALIPAY
										<?}?>
									</li>
								</ul>
							</div>
							<div class="col-sm-6">
								<ul class="list-unstyled">
									<li><?=national_language($iw[language],"a0070","이름");?> : <?=$sr_name?></li>
									<li><?=national_language($iw[language],"a0217","배송주소");?> : <?=$address?></li>
									<li><?=national_language($iw[language],"a0076","휴대폰 번호");?> : <?=$sr_phone?> / <?=$sr_phone_sub?></li>
									<?if($sr_pay == "lguplus" && $lgd_display=='1'){
												$receipt = get_receipt($sr_code, $ptype);
												if($receipt[url]){
									?>
									<li>
										<a href="javascript:win_open('<?=$receipt[url]?>', '<?=$receipt[txt]?>', 'width=500,height=800')">[ 영수증 출력 ]</a>
									</li>
									<? } } ?>
								</ul>
							</div>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			</div> <!-- /.masonry -->
			<div class="btn-list">
				<a href="<?=$iw['m_path']?>/shop_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" class="btn btn-theme"><?=national_language($iw[language],"a0260","목록");?></a>
			</div>
		</div> <!-- /.col -->
	</div> <!-- /.row -->
</div> <!-- /.content -->
<input type="hidden" id="copyme" />
<script type="text/javascript">
	function win_open(url, name, option) {
			var popup = window.open(url, name, option);
			popup.focus();
	}

	function delivery_check(num, url)  {
			$("#copyme").val(num);
			let txt = $("#copyme").val();
			navigator.clipboard.writeText(txt);

			win_open(url, "배송조회", "left=50,top=50,width=1200,height=800,scrollbars=1");
	}
</script>

<?
include_once("_tail.php");
?>