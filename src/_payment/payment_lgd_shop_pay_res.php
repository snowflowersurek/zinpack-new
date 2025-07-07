<?php
include_once("_common.php");
include_once "../include/mailer.php";

$LGD_OID = $_GET['lgd_oid'];
$LGD_TID = $_GET['lgd_tid'];

if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

if (!$LGD_OID || !$LGD_TID) {
	alert("결제 정보가 올바르지 않습니다.");
}

$lgd_row = sql_fetch("select * from infoway.iw_lgd where lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'");

if ($lgd_row) {
	alert("등록된 결제정보입니다.");
} else {
	$payment_row = sql_fetch("select * from $payment[lgd_response_table] where lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'");
	
	if ($payment_row) {
		$lgd_display = 1;
		
		if ($payment_row['lgd_paytype'] == "SC0040") {
			$lgd_display = 5;
		}
		
		$sql = "insert into infoway.iw_lgd set
				ep_code = '$payment_row[ep_code]',
				mb_code = '$payment_row[mb_code]',
				state_sort = '$payment_row[state_sort]',
				lgd_oid = '$payment_row[lgd_oid]',
				lgd_tid = '$payment_row[lgd_tid]',
				lgd_respcode = '$payment_row[lgd_respcode]',
				lgd_respmsg = '$payment_row[lgd_respmsg]',
				lgd_mid = '$payment_row[lgd_mid]',
				lgd_amount = '$payment_row[lgd_amount]',
				lgd_paytype = '$payment_row[lgd_paytype]',
				lgd_paydate = '$payment_row[lgd_paydate]',
				lgd_hashdata = '$payment_row[lgd_hashdata]',
				lgd_timestamp = '$payment_row[lgd_timestamp]',
				lgd_buyer = '$payment_row[lgd_buyer]',
				lgd_productinfo = '$payment_row[lgd_productinfo]',
				lgd_buyeremail = '$payment_row[lgd_buyeremail]',
				lgd_financename = '$payment_row[lgd_financename]',
				lgd_financeauthnum = '$payment_row[lgd_financeauthnum]',
				lgd_cashreceiptnum = '$payment_row[lgd_cashreceiptnum]',
				lgd_cashreceiptselfyn = '$payment_row[lgd_cashreceiptselfyn]',
				lgd_cashreceiptkind = '$payment_row[lgd_cashreceiptkind]',
				lgd_cardnum = '$payment_row[lgd_cardnum]',
				lgd_cardinstallmonth = '$payment_row[lgd_cardinstallmonth]',
				lgd_cardnointyn = '$payment_row[lgd_cardnointyn]',
				lgd_cardgubun1 = '$payment_row[lgd_cardgubun1]',
				lgd_cardgubun2 = '$payment_row[lgd_cardgubun2]',
				lgd_accountnum = '$payment_row[lgd_accountnum]',
				lgd_accountowner = '$payment_row[lgd_accountowner]',
				lgd_payer = '$payment_row[lgd_payer]',
				lgd_castamount = '$payment_row[lgd_castamount]',
				lgd_cascamount = '$payment_row[lgd_cascamount]',
				lgd_casflag = '$payment_row[lgd_casflag]',
				lgd_casseqno = '$payment_row[lgd_casseqno]',
				lgd_saowner = '$payment_row[lgd_saowner]',
				lgd_telno = '$payment_row[lgd_telno]',
				lgd_datetime = '$payment_row[lgd_datetime]',
				lgd_display = $lgd_display
				";
		sql_query($sql);
	
		$row = sql_fetch("select sr_point from infoway.iw_shop_order where ep_code = '$payment_row[ep_code]' and mb_code = '$payment_row[mb_code]' and sr_code = '$LGD_OID' ");
		$sr_point = $row["sr_point"];
		
		if ($sr_point > 0) {
			$row = sql_fetch(" select mb_point from infoway.iw_member where ep_code = '$payment_row[ep_code]' and mb_code = '$payment_row[mb_code]'");
			$mb_point = $row["mb_point"];
			//포인트 차감 내역
			$pt_content = "[쇼핑]".$payment_row[lgd_productinfo];
			$sql = "insert into infoway.iw_point set
					ep_code = '$payment_row[ep_code]',
					mb_code = '$payment_row[mb_code]',
					state_sort = '$payment_row[state_sort]',
					pt_withdraw = '$sr_point',
					pt_balance = $mb_point-$sr_point,
					pt_content = '$pt_content',
					pt_datetime = '$payment_row[lgd_datetime]'
					";
			sql_query($sql);
			
			//회원정보 포인트 차감
			$sql = "update infoway.iw_member set
					mb_point = mb_point-$sr_point
					where ep_code = '$payment_row[ep_code]' and mb_code = '$payment_row[mb_code]'
					";
			sql_query($sql);
		}
	
		$sql = "update infoway.iw_shop_order set
				sr_datetime = '$payment_row[lgd_datetime]',
				sr_display = $lgd_display
				where ep_code = '$payment_row[ep_code]' and mb_code = '$payment_row[mb_code]' and sr_code = '$LGD_OID'
				";
		sql_query($sql);
	
		$sql = "update infoway.iw_shop_order_sub set
				srs_display = $lgd_display
				where ep_code = '$payment_row[ep_code]' and mb_code = '$payment_row[mb_code]' and sr_code = '$LGD_OID'
				";
		sql_query($sql);
	
		$sql = "update infoway.iw_lgd set
				lgd_display = $lgd_display
				where ep_code = '$payment_row[ep_code]' and state_sort = '$payment_row[state_sort]' and mb_code = '$payment_row[mb_code]' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'
				";
		sql_query($sql);
		
		$sql = "delete from infoway.iw_shop_cart where ep_code = '$payment_row[ep_code]' and mb_code = '$payment_row[mb_code]'";
		sql_query($sql);
	
		$sql = "select * from infoway.iw_shop_order_sub where ep_code = '$payment_row[ep_code]' and mb_code = '$payment_row[mb_code]' and sr_code = '$LGD_OID'";
		$result = sql_query($sql);
	
		while ($row = @sql_fetch_array($result)) {
			$sd_code = $row["sd_code"];
			$so_no = $row["so_no"];
			$srs_amount = $row["srs_amount"];
	
			$sql = "update infoway.iw_shop_option set
					so_amount = so_amount-$srs_amount
					where ep_code = '$payment_row[ep_code]' and so_no = '$so_no' and sd_code = '$sd_code'
					";
			sql_query($sql);
	
			$sql = "update infoway.iw_shop_data set
					sd_sell = sd_sell+$srs_amount
					where ep_code = '$payment_row[ep_code]' and sd_code = '$sd_code'
					";
			sql_query($sql);
		}
		
		if ($payment_row['lgd_paytype'] == "SC0040") {
			alert("가상계좌 결제정보가 등록되었습니다.");
		} else {
			$paytype = "";
			
			if ($payment_row['lgd_paytype'] == "SC0010") {
				$paytype = "신용카드";
			} else if ($payment_row['lgd_paytype'] == "SC0030") {
				$paytype = "계좌이체";
			}
			
			//관리자에게 메일
			$row2 = sql_fetch("select st_title from infoway.iw_setting where ep_code = '$payment_row[ep_code]' and gp_code = 'all'");
			$st_title = $row2["st_title"];
			$row2 = sql_fetch("select mb_code from infoway.iw_enterprise where ep_code = '$payment_row[ep_code]'");
			$st_mb_code = $row2["mb_code"];
			$row2 = sql_fetch("select mb_mail, mb_sub_mail from infoway.iw_member where ep_code = '$payment_row[ep_code]' and mb_code = '$st_mb_code'");
			
			$to = $row2["mb_mail"];
			$from = "no-reply@wizwindigital.com";
			$fromName  = $st_title;
			$subject = $st_title.' 주문이 접수되었습니다.';
			$content = '
				<div style="padding:20px;font-family:Arial,\'Apple SD Gothic Neo\',\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum;">
					<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;text-align:left;">
						<tbody>
							<tr>
								<td style="font-size:24px;line-height:34px;color:#333;"><strong>'.$st_title.'</strong><br>주문이 접수되었습니다.</td>
							</tr>
							<tr>
								<td height="32"></td>
							</tr>
							<tr>
								<td style="border-top:1px solid #333;">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문일시</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$payment_row[lgd_datetime].'</td>
											</tr>
											<tr>
												<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문상품</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$payment_row[lgd_productinfo].'</td>
											</tr>
											<tr>
												<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문회원</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$payment_row[lgd_buyer].'</td>
											</tr>
											<tr>
												<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">결제금액</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">
													<span style="font-size:18px;font-weight:bold;color:#df3926;">'.number_format($payment_row[lgd_amount]).'원</span>
													<span style="margin:0 4px;font-size:13px;">'.$payment_row[lgd_financename].' '.$paytype.'</span>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			';

			mailer($fromName, $from, $to, $subject, $content, true);

			//판매자에게 메일
			$sql = "select DISTINCT(seller_mb_code) from infoway.iw_shop_order_sub where ep_code = '$payment_row[ep_code]' and mb_code = '$payment_row[mb_code]' and sr_code = '$LGD_OID'";
			$result = sql_query($sql);
			
			$to = "";
			while($row = @sql_fetch_array($result)){
				$seller_mb_code = $row["seller_mb_code"];
				$row2 = sql_fetch("select mb_mail from infoway.iw_member where ep_code = '$payment_row[ep_code]' and mb_code = '$seller_mb_code'");
				$to .= $row2["mb_mail"].",";
			}

			mailer($fromName, $from, $to, $subject, $content, true);

			alert("결제정보가 정상적으로 등록되었습니다.");
		}
	} else {
		alert("결제정보 등록을 실패하였습니다.");
	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">



