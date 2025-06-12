<?php
include_once("_common.php");
if ($iw[level]=="guest") alert("로그인 해주시기 바랍니다. ","$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
    
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
		$pt_datetime = date("Y-m-d H:i:s");
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
				lgd_datetime = '$pt_datetime',
				lgd_display = $lgd_display
				";
		sql_query($sql);
		
		$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$LGD_BUYERID'");
		$mb_point = $row["mb_point"]+$LGD_PRODUCTINFO;
		
		$pt_content = "포인트 충전 ".number_format($LGD_AMOUNT)." 원";

		$sql = "insert into $iw[point_table] set
				ep_code = '$iw[store]',
				mb_code = '$LGD_BUYERID',
				state_sort = '$iw[type]',
				pt_deposit = '$LGD_PRODUCTINFO',
				pt_balance = '$mb_point',
				pt_content = '$pt_content',
				pt_datetime = '$pt_datetime',
				lgd_oid = '$LGD_OID',
				pt_display = $lgd_display
				";
		sql_query($sql);


		if($LGD_PAYTYPE == "SC0040"){

			alert("[은행]$LGD_FINANCENAME [가상계좌]$LGD_ACCOUNTNUM [입금자명]$LGD_PAYER 입금을 하시면, 포인트가 충전됩니다.","$iw[m_path]/all_point_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
		}else{
		
			//회원정보 포인트 추가
			$sql = "update $iw[member_table] set
					mb_point = '$mb_point'
					where ep_code = '$iw[store]' and mb_code = '$LGD_BUYERID'
					";
			sql_query($sql);
		
			alert("포인트가 충전되었습니다.","$iw[m_path]/all_point_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
		}
	}else{
		//최종결제요청 결과 실패 DB처리
		alert("결제요청이 실패하였습니다.","$iw[m_path]/all_point_charge.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");          	            
	}

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">