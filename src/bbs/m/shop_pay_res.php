<?php
header('Content-Type: text/html; charset=utf-8');
include_once("_common.php");
include_once "../../include/mailer.php";
//if ($iw[level]=="guest") alert("로그인 해주시기 바랍니다. ","$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

    $LGD_RESPCODE = $_POST[LGD_RESPCODE];					//응답코드
	$LGD_RESPMSG = $_POST[LGD_RESPMSG];					//응답메세지
	$LGD_MID = $_POST[LGD_MID];							//LG유플러스 발급 아이디
	$LGD_OID = $_POST[LGD_OID];							//이용업체 거래번호(주문번호)
	$LGD_AMOUNT = $_POST[LGD_AMOUNT];						//결제금액
	$LGD_TID = $_POST[LGD_TID];							//LG유플러스 거래번호
	$LGD_PAYTYPE = $_POST[LGD_PAYTYPE];					//결제수단
	$LGD_PAYDATE = $_POST[LGD_PAYDATE];					//결제일시
	$LGD_HASHDATA = $_POST[LGD_HASHDATA];					//해쉬데이타
	$LGD_TIMESTAMP = $_POST[LGD_TIMESTAMP];				//타임스탬프
	$LGD_BUYER = $_POST[LGD_BUYER];						//구매자명
	$LGD_PRODUCTINFO = $_POST[LGD_PRODUCTINFO];			//구매내역
	$LGD_BUYERID = $_POST[LGD_BUYERID];					//구매자아이디
	$LGD_BUYEREMAIL = $_POST[LGD_BUYEREMAIL];				//구매자메일
	$LGD_FINANCENAME = $_POST[LGD_FINANCENAME];			//결제기관명
	$LGD_FINANCEAUTHNUM = $_POST[LGD_FINANCEAUTHNUM];		//결제기관승인번호
	$LGD_CASHRECEIPTNUM = $_POST[LGD_CASHRECEIPTNUM];		//현금영수증승인번호
	$LGD_CASHRECEIPTSELFYN = $_POST[LGD_CASHRECEIPTSELFYN];//현금영수증자진발급제유무
	$LGD_CASHRECEIPTKIND = $_POST[LGD_CASHRECEIPTKIND];	//현금영수증종류
	$LGD_CARDNUM = $_POST[LGD_CARDNUM];					//신용카드번호
	$LGD_CARDINSTALLMONTH = $_POST[LGD_CARDINSTALLMONTH];	//신용카드할부개월
	$LGD_CARDNOINTYN = $_POST[LGD_CARDNOINTYN];			//신용카드무이자여부
	$LGD_CARDGUBUN1 = $_POST[LGD_CARDGUBUN1];				//신용카드추가정보1
	$LGD_CARDGUBUN2 = $_POST[LGD_CARDGUBUN2];				//신용카드추가정보2
	$LGD_ACCOUNTNUM = $_POST[LGD_ACCOUNTNUM];				//가상계좌발급번호
	$LGD_ACCOUNTOWNER = $_POST[LGD_ACCOUNTOWNER];			//계좌주명
	$LGD_PAYER = $_POST[LGD_PAYER];						//가상계좌입금자명
	$LGD_CASTAMOUNT = $_POST[LGD_CASTAMOUNT];				//입금누적금액
	$LGD_CASCAMOUNT = $_POST[LGD_CASCAMOUNT];				//현입금금액
	$LGD_CASFLAG = $_POST[LGD_CASFLAG];					//거래종류(R:할당,I:입금,C:취소)
	$LGD_CASSEQNO = $_POST[LGD_CASSEQNO];					//가상계좌일련번호
	$LGD_SAOWNER = $_POST[LGD_SAOWNER];					//가상계좌 입금계좌주명
	$LGD_TELNO = $_POST[LGD_TELNO];						//결제휴대폰번호
	$LGD_ESCROW = $_POST[LGD_ESCROW];						//결제휴대폰번호

	$sql_check = "select count(*) as cnt from {$iw['lgd_table']} where ep_code = ? and mb_code = ? and lgd_oid = ? and lgd_tid = ?";
	$stmt_check = mysqli_prepare($db_conn, $sql_check);
	mysqli_stmt_bind_param($stmt_check, "ssss", $iw['store'], $LGD_BUYERID, $LGD_OID, $LGD_TID);
	mysqli_stmt_execute($stmt_check);
	$result_check = mysqli_stmt_get_result($stmt_check);
	$row = mysqli_fetch_assoc($result_check);
	mysqli_stmt_close($stmt_check);

	if( ("0000" == $LGD_RESPCODE || "200" == $LGD_RESPCODE) && !$row['cnt']) {
		$lgd_display = 1;
		if($LGD_PAYTYPE == "SC0040"){
			$lgd_display = 5;
		}
		$sr_datetime = date("Y-m-d H:i:s");
		$sql = "insert into {$iw['lgd_table']} set
				ep_code = ?, mb_code = ?, state_sort = ?, lgd_oid = ?, lgd_tid = ?,
				lgd_respcode = ?, lgd_respmsg = ?, lgd_mid = ?, lgd_amount = ?, lgd_paytype = ?,
				lgd_paydate = ?, lgd_hashdata = ?, lgd_timestamp = ?, lgd_buyer = ?, lgd_productinfo = ?,
				lgd_buyeremail = ?, lgd_financename = ?, lgd_financeauthnum = ?, lgd_cashreceiptnum = ?, lgd_cashreceiptselfyn = ?,
				lgd_cashreceiptkind = ?, lgd_cardnum = ?, lgd_cardinstallmonth = ?, lgd_cardnointyn = ?, lgd_cardgubun1 = ?,
				lgd_cardgubun2 = ?, lgd_accountnum = ?, lgd_accountowner = ?, lgd_payer = ?, lgd_castamount = ?,
				lgd_cascamount = ?, lgd_casflag = ?, lgd_casseqno = ?, lgd_saowner = ?, lgd_telno = ?,
				lgd_datetime = ?, lgd_escrow = ?";
		
		$stmt = mysqli_prepare($db_conn, $sql);
		mysqli_stmt_bind_param($stmt, 'sssssssssssssssssssssssssssssssssssss',
			$iw['store'], $LGD_BUYERID, $iw['type'], $LGD_OID, $LGD_TID,
			$LGD_RESPCODE, $LGD_RESPMSG, $LGD_MID, $LGD_AMOUNT, $LGD_PAYTYPE,
			$LGD_PAYDATE, $LGD_HASHDATA, $LGD_TIMESTAMP, $LGD_BUYER, $LGD_PRODUCTINFO,
			$LGD_BUYEREMAIL, $LGD_FINANCENAME, $LGD_FINANCEAUTHNUM, $LGD_CASHRECEIPTNUM, $LGD_CASHRECEIPTSELFYN,
			$LGD_CASHRECEIPTKIND, $LGD_CARDNUM, $LGD_CARDINSTALLMONTH, $LGD_CARDNOINTYN, $LGD_CARDGUBUN1,
			$LGD_CARDGUBUN2, $LGD_ACCOUNTNUM, $LGD_ACCOUNTOWNER, $LGD_PAYER, $LGD_CASTAMOUNT,
			$LGD_CASCAMOUNT, $LGD_CASFLAG, $LGD_CASSEQNO, $LGD_SAOWNER, $LGD_TELNO,
			$sr_datetime, $LGD_ESCROW);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		// 포인트 조회
		$sql_pt = "select sr_point from {$iw['shop_order_table']} where ep_code = ? and mb_code = ? and sr_code = ?";
		$stmt_pt = mysqli_prepare($db_conn, $sql_pt);
		mysqli_stmt_bind_param($stmt_pt, "sss", $iw['store'], $LGD_BUYERID, $LGD_OID);
		mysqli_stmt_execute($stmt_pt);
		$result_pt = mysqli_stmt_get_result($stmt_pt);
		$row_pt = mysqli_fetch_assoc($result_pt);
		mysqli_stmt_close($stmt_pt);
		$sr_point = (int)$row_pt["sr_point"];

		if($sr_point > 0){
			// 회원 현재 포인트 조회
			$sql_mb_pt = "select mb_point from {$iw['member_table']} where ep_code = ? and mb_code = ?";
			$stmt_mb_pt = mysqli_prepare($db_conn, $sql_mb_pt);
			mysqli_stmt_bind_param($stmt_mb_pt, "ss", $iw['store'], $LGD_BUYERID);
			mysqli_stmt_execute($stmt_mb_pt);
			$result_mb_pt = mysqli_stmt_get_result($stmt_mb_pt);
			$row_mb_pt = mysqli_fetch_assoc($result_mb_pt);
			mysqli_stmt_close($stmt_mb_pt);
			$mb_point = (int)$row_mb_pt["mb_point"];
			
			// 포인트 차감 내역 기록
			$pt_content = "[쇼핑]".$LGD_PRODUCTINFO;
			$sql_pt_log = "insert into {$iw['point_table']} set
					ep_code = ?, mb_code = ?, state_sort = ?, pt_withdraw = ?, 
					pt_balance = ?, pt_content = ?, pt_datetime = ?";
			$stmt_pt_log = mysqli_prepare($db_conn, $sql_pt_log);
			$new_balance = $mb_point - $sr_point;
			mysqli_stmt_bind_param($stmt_pt_log, "sssisss", $iw['store'], $LGD_BUYERID, $iw['type'], $sr_point, $new_balance, $pt_content, $sr_datetime);
			mysqli_stmt_execute($stmt_pt_log);
			mysqli_stmt_close($stmt_pt_log);
			
			// 회원 포인트 차감
			$sql_pt_update = "update {$iw['member_table']} set mb_point = mb_point - ? where ep_code = ? and mb_code = ?";
			$stmt_pt_update = mysqli_prepare($db_conn, $sql_pt_update);
			mysqli_stmt_bind_param($stmt_pt_update, "iss", $sr_point, $iw['store'], $LGD_BUYERID);
			mysqli_stmt_execute($stmt_pt_update);
			mysqli_stmt_close($stmt_pt_update);
		}
	
		// 주문 테이블 상태 업데이트
		$sql_order = "update {$iw['shop_order_table']} set sr_datetime = ?, sr_display = ? where ep_code = ? and mb_code = ? and sr_code = ?";
		$stmt_order = mysqli_prepare($db_conn, $sql_order);
		mysqli_stmt_bind_param($stmt_order, "sisss", $sr_datetime, $lgd_display, $iw['store'], $LGD_BUYERID, $LGD_OID);
		mysqli_stmt_execute($stmt_order);
		mysqli_stmt_close($stmt_order);

		// 주문 서브 테이블 상태 업데이트
		$sql_order_sub = "update {$iw['shop_order_sub_table']} set srs_display = ? where ep_code = ? and mb_code = ? and sr_code = ?";
		$stmt_order_sub = mysqli_prepare($db_conn, $sql_order_sub);
		mysqli_stmt_bind_param($stmt_order_sub, "isss", $lgd_display, $iw['store'], $LGD_BUYERID, $LGD_OID);
		mysqli_stmt_execute($stmt_order_sub);
		mysqli_stmt_close($stmt_order_sub);

		// LGD 테이블 상태 업데이트
		$sql_lgd_update = "update {$iw['lgd_table']} set lgd_display = ? where ep_code = ? and state_sort = ? and mb_code = ? and lgd_oid = ? and lgd_tid = ?";
		$stmt_lgd_update = mysqli_prepare($db_conn, $sql_lgd_update);
		mysqli_stmt_bind_param($stmt_lgd_update, "isssss", $lgd_display, $iw['store'], $iw['type'], $LGD_BUYERID, $LGD_OID, $LGD_TID);
		mysqli_stmt_execute($stmt_lgd_update);
		mysqli_stmt_close($stmt_lgd_update);
		
		// 장바구니 비우기
		$sql_cart = "delete from {$iw['shop_cart_table']} where ep_code = ? and mb_code = ?";
		$stmt_cart = mysqli_prepare($db_conn, $sql_cart);
		mysqli_stmt_bind_param($stmt_cart, "ss", $iw['store'], $LGD_BUYERID);
		mysqli_stmt_execute($stmt_cart);
		mysqli_stmt_close($stmt_cart);

		// 재고 및 판매량 업데이트
		$sql_items = "select sd_code, so_no, srs_amount from {$iw['shop_order_sub_table']} where ep_code = ? and mb_code = ? and sr_code = ?";
		$stmt_items = mysqli_prepare($db_conn, $sql_items);
		mysqli_stmt_bind_param($stmt_items, "sss", $iw['store'], $LGD_BUYERID, $LGD_OID);
		mysqli_stmt_execute($stmt_items);
		$result_items = mysqli_stmt_get_result($stmt_items);

		$sql_update_option = "update {$iw['shop_option_table']} set so_amount = so_amount - ? where ep_code = ? and so_no = ? and sd_code = ?";
		$stmt_update_option = mysqli_prepare($db_conn, $sql_update_option);

		$sql_update_data = "update {$iw['shop_data_table']} set sd_sell = sd_sell + ? where ep_code = ? and sd_code = ?";
		$stmt_update_data = mysqli_prepare($db_conn, $sql_update_data);

		while($row_item = mysqli_fetch_assoc($result_items)){
			$srs_amount = (int)$row_item['srs_amount'];
			$so_no = (int)$row_item['so_no'];
			$sd_code = $row_item['sd_code'];
			
			mysqli_stmt_bind_param($stmt_update_option, "isss", $srs_amount, $iw['store'], $so_no, $sd_code);
			mysqli_stmt_execute($stmt_update_option);

			mysqli_stmt_bind_param($stmt_update_data, "iss", $srs_amount, $iw['store'], $sd_code);
			mysqli_stmt_execute($stmt_update_data);
		}
		mysqli_stmt_close($stmt_items);
		mysqli_stmt_close($stmt_update_option);
		mysqli_stmt_close($stmt_update_data);
		
		if($LGD_PAYTYPE == "SC0040"){
			alert("[은행]$LGD_FINANCENAME [가상계좌]$LGD_ACCOUNTNUM [입금자명]$LGD_PAYER 입금을 하시면, 결제가 완료됩니다.","$iw[m_path]/shop_buy_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$LGD_OID");
		}else{
			$paytype = "";
				
			if ($LGD_PAYTYPE == "SC0010") {
				$paytype = "신용카드";
			} else if ($LGD_PAYTYPE == "SC0030") {
				$paytype = "계좌이체";
			}
			
			// 관리자 정보 조회
			$sql_admin_mail = "SELECT a.st_title, b.mb_mail FROM {$iw['setting_table']} a JOIN {$iw['enterprise_table']} c ON a.ep_code = c.ep_code JOIN {$iw['member_table']} b ON c.mb_code = b.mb_code WHERE a.ep_code = ? AND a.gp_code = 'all'";
			$stmt_admin_mail = mysqli_prepare($db_conn, $sql_admin_mail);
			mysqli_stmt_bind_param($stmt_admin_mail, "s", $iw['store']);
			mysqli_stmt_execute($stmt_admin_mail);
			$result_admin_mail = mysqli_stmt_get_result($stmt_admin_mail);
			$row_admin = mysqli_fetch_assoc($result_admin_mail);
			mysqli_stmt_close($stmt_admin_mail);
			$st_title = $row_admin["st_title"];
			$to_admin = $row_admin["mb_mail"];
			
			// 메일 내용 생성
			$row2 = sql_fetch("select st_title from $iw[setting_table] where ep_code = '$iw[store]' and gp_code = 'all'");
			$st_title = $row2["st_title"];
			$row2 = sql_fetch("select mb_code from $iw[enterprise_table] where ep_code = '$iw[store]'");
			$st_mb_code = $row2["mb_code"];
			$row2 = sql_fetch("select mb_mail, mb_sub_mail from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$st_mb_code'");
			
			$to = $row2["mb_mail"];
			$from = "no-reply@wizwindigital.com";
			$fromName = $st_title;
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
			
			// 관리자에게 메일
			mailer($fromName, $from, $to_admin, $subject, $content, true);

			// 판매자 이메일 목록 조회 (JOIN으로 N+1 문제 해결)
			$sql_seller = "SELECT DISTINCT c.mb_mail FROM {$iw['shop_order_sub_table']} a JOIN {$iw['member_table']} c ON a.seller_mb_code = c.mb_code AND a.ep_code = c.ep_code WHERE a.ep_code = ? AND a.mb_code = ? AND a.sr_code = ?";
			$stmt_seller = mysqli_prepare($db_conn, $sql_seller);
			mysqli_stmt_bind_param($stmt_seller, "sss", $iw['store'], $LGD_BUYERID, $LGD_OID);
			mysqli_stmt_execute($stmt_seller);
			$result_seller = mysqli_stmt_get_result($stmt_seller);
			
			$to_sellers = [];
			while($row_seller = mysqli_fetch_assoc($result_seller)){
				$to_sellers[] = $row_seller["mb_mail"];
			}
			mysqli_stmt_close($stmt_seller);

			if (!empty($to_sellers)) {
				mailer($fromName, $from, implode(",", $to_sellers), $subject, $content, true);
			}

			alert("결제가 정상적으로 처리되었습니다.","$iw[m_path]/shop_buy_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$LGD_OID");
		}
	}else{
		//최종결제요청 결과 실패 DB처리
		alert("결제정보 등록을 실패하였습니다.","$iw[m_path]/shop_cart_form.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");          	            
	}
?>
