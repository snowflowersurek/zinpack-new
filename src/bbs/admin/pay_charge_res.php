<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include_once("_common.php");
include_once "../../include/mailer.php";
//if ($iw[level]=="guest") alert("로그인 해주시기 바랍니다. ","$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

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

	$row = sql_fetch("select count(*) as cnt from $iw[lgd_table] where ep_code = '$iw[store]' and mb_code = '$LGD_BUYERID' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID' and lgd_timestamp = '$LGD_TIMESTAMP'");

	if( ("0000" == $LGD_RESPCODE || "200" == $LGD_RESPCODE) && !$row[cnt]) {
		$lgd_display = 1;
		if($LGD_PAYTYPE == "SC0040"){
			$lgd_display = 5;
		}
		$sr_datetime = date("Y-m-d H:i:s");
		$sql = "insert into $iw[lgd_table] set
				ep_code = '$iw[store]',
				mb_code = '$LGD_BUYERID',
				state_sort = '$iw[type]',
				lgd_oid = '$LGD_OID',
				lgd_tid = '$LGD_TID',
				lgd_respcode = '$LGD_RESPCODE',
				lgd_respmsg = '$LGD_RESPMSG',
				lgd_mid = '$LGD_MID',
				lgd_amount = '$LGD_AMOUNT',
				lgd_paytype = '$LGD_PAYTYPE',
				lgd_paydate = '$LGD_PAYDATE',
				lgd_hashdata = '$LGD_HASHDATA',
				lgd_timestamp = '$LGD_TIMESTAMP',
				lgd_buyer = '$LGD_BUYER',
				lgd_productinfo = '$LGD_PRODUCTINFO',
				lgd_buyeremail = '$LGD_BUYEREMAIL',
				lgd_financename = '$LGD_FINANCENAME',
				lgd_financeauthnum = '$LGD_FINANCEAUTHNUM',
				lgd_cashreceiptnum = '$LGD_CASHRECEIPTNUM',
				lgd_cashreceiptselfyn = '$LGD_CASHRECEIPTSELFYN',
				lgd_cashreceiptkind = '$LGD_CASHRECEIPTKIND',
				lgd_cardnum = '$LGD_CARDNUM',
				lgd_cardinstallmonth = '$LGD_CARDINSTALLMONTH',
				lgd_cardnointyn = '$LGD_CARDNOINTYN',
				lgd_cardgubun1 = '$LGD_CARDGUBUN1',
				lgd_cardgubun2 = '$LGD_CARDGUBUN2',
				lgd_accountnum = '$LGD_ACCOUNTNUM',
				lgd_accountowner = '$LGD_ACCOUNTOWNER',
				lgd_payer = '$LGD_PAYER',
				lgd_castamount = '$LGD_CASTAMOUNT',
				lgd_cascamount = '$LGD_CASCAMOUNT',
				lgd_casflag = '$LGD_CASFLAG',
				lgd_casseqno = '$LGD_CASSEQNO',
				lgd_saowner = '$LGD_SAOWNER',
				lgd_telno = '$LGD_TELNO',
				lgd_datetime = '$sr_datetime'
				";
		sql_query($sql);

		$sql = "update $iw[lgd_table] set
				lgd_display = $lgd_display
				where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$LGD_BUYERID' and lgd_oid = '$LGD_OID' and lgd_tid = '$LGD_TID'
				";
		sql_query($sql);

		// $iw[charge_table] 에 데이터 저장
		$sql = "insert into $iw[charge_table] set
				ep_code			= '$iw[store]',
				mb_code			= '$LGD_BUYERID',
				ch_amount		= '$LGD_AMOUNT',
				ogd_oid			= '$LGD_OID',
				pt_datetime = '$sr_datetime',
				ch_paytype	= '$LGD_PAYTYPE',
				ch_result		= '1'
				";
		sql_query($sql);
		$last_chuid = mysqli_insert_id($iw['connect']);

		if($LGD_PAYTYPE == "SC0040"){
			$sql = "update $iw[charge_table] set 
							ch_result = '0' 
							where ch_no = '$last_chuid'
						";
			sql_query($sql);

			alert("[은행]$LGD_FINANCENAME [가상계좌]$LGD_ACCOUNTNUM [입금자명]$LGD_PAYER 입금을 하시면, 결제가 완료됩니다.","$iw[admin_path]/main.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$LGD_OID");

			// 이 부분에 이메일발송 코딩을 해줘야 함. (결제한 관리자에게..가상계좌 입금정보)
			//mailer($fromName, $from, $to, $subject, $content, true);

		}else{
			$row2 = sql_fetch("select * from $iw[enterprise_table] where ep_code = '$iw[store]'");
			$ep_corp = $row2["ep_corporate"];
			$pre_expiry_date = $row2["ep_expiry_date"];
			$pre_expiry_date_ary = explode("-",$pre_expiry_date);
			$pre_expiry_year = $pre_expiry_date_ary[0];
			$new_expiry_date = (intval($pre_expiry_year) + 1)."-".$pre_expiry_date_ary[1]."-".$pre_expiry_date_ary[2];

			$sql = "update $iw[enterprise_table] set
							ep_expiry_date = '$new_expiry_date',
							avail_type = '1'
							where ep_code = '$iw[store]'
						";
			sql_query($sql);

			$to = "zinfo@wizwindigital.com";
			$from = "no-reply@wizwindigital.com";
			$fromName = "진팩";
			$subject = $ep_corp.' 사이트관리비 입금이 완료되었습니다.';
			$content = '
				<div style="padding:20px;font-family:Arial,\'Apple SD Gothic Neo\',\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum;">
					<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;text-align:left;">
						<tbody>
							<tr>
								<td style="font-size:24px;line-height:34px;color:#333;"><strong>'.$ep_corp.'</strong>사이트관리비 입금이 완료되었습니다.</td>
							</tr>
							<tr>
								<td height="32"></td>
							</tr>
							<tr>
								<td style="border-top:1px solid #333;">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">입금일시</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$sr_datetime.'</td>
											</tr>
											<tr>
												<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문상품</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$LGD_PRODUCTINFO.'</td>
											</tr>
											<tr>
												<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">연장 유효기간</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$new_expiry_date.'</td>
											</tr>
											<tr>
												<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문회원</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$LGD_BUYER.'</td>
											</tr>
											<tr>
												<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">결제금액</td>
												<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">
													<span style="font-size:18px;font-weight:bold;color:#df3926;">'.number_format($LGD_AMOUNT).'원</span>
													<span style="margin:0 4px;font-size:13px;"> (카드결제)</span>
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
			alert("결제가 정상적으로 처리되었습니다.","$iw[admin_path]/main.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$LGD_OID");
		}
	}else{
		//최종결제요청 결과 실패 DB처리
		alert("결제정보 등록을 실패하였습니다.","$iw[admin_path]/main.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");          	            
	}
?>




