<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include_once("_common.php");
include_once "../../include/mailer.php";

    $TOSS_RESPMSG = $_POST[TOSS_RESPMSG];					//응답코드
	$ORDERID = $_POST[TOSS_ORDERID];					//응답메세지

	$sql = "update $iw[lgd_table] set
				lgd_respmsg = $TOSS_RESPMSG
			where lgd_oid = '$ORDERID'
			";
	sql_query($sql);

	//관리자에게 메일
	$row2 = sql_fetch("select st_title from $iw[setting_table] where ep_code = '$iw[store]' and gp_code = 'all'");
	$st_title = $row2["st_title"];
	$row2 = sql_fetch("select mb_code from $iw[enterprise_table] where ep_code = '$iw[store]'");
	$st_mb_code = $row2["mb_code"];
	$row2 = sql_fetch("select mb_mail, mb_sub_mail from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$st_mb_code'");
	
	$to = $row2["mb_mail"];
	$from = "no-reply@wizwindigital.com";
	$fromName = $st_title;
	$subject = $st_title.' 가상계좌 입금이 완료되었습니다.';
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
										<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$sr_datetime.'</td>
									</tr>
									<tr>
										<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문상품</td>
										<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$LGD_PRODUCTINFO.'</td>
									</tr>
									<tr>
										<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문회원</td>
										<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$LGD_BUYER.'</td>
									</tr>
									<tr>
										<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">결제금액</td>
										<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">
											<span style="font-size:18px;font-weight:bold;color:#df3926;">'.number_format($LGD_AMOUNT).'원</span>
											<span style="margin:0 4px;font-size:13px;">'.$LGD_FINANCENAME.' '.$paytype.'</span>
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
/*
	//판매자에게 메일
	$sql = "select DISTINCT(seller_mb_code) from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$LGD_BUYERID' and sr_code = '$ORDERID'";
	$result = sql_query($sql);
	
	$to = "";
	while($row = @sql_fetch_array($result)){
		$seller_mb_code = $row["seller_mb_code"];
		$row2 = sql_fetch("select mb_mail from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$seller_mb_code'");
		$to .= $row2["mb_mail"].",";
	}

	mailer($fromName, $from, $to, $subject, $content, true);
*/
?>




