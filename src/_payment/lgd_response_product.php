<?php
include_once("_common.php");
include_once("_check_parameter.php");
$return_domain = get_cookie("payment_domain");
$return_time = get_cookie("payment_time");
    
$protocol = "http://";

if ($return_domain == "www.aviation.co.kr") {
	$protocol = "https://";
}

    /*
     * [최종결제요청 페이지(STEP2-2)]
     *
     * LG유플러스으로 부터 내려받은 LGD_PAYKEY(인증Key)를 가지고 최종 결제요청.(파라미터 전달시 POST를 사용하세요)
     */

	$configPath = "lgdacom"; //LG유플러스에서 제공한 환경파일("/conf/lgdacom.conf,/conf/mall.conf") 위치 지정. 

    /*
     *************************************************
     * 1.최종결제 요청 - BEGIN
     *  (단, 최종 금액체크를 원하시는 경우 금액체크 부분 주석을 제거 하시면 됩니다.)
     *************************************************
     */
    $CST_PLATFORM               = $HTTP_POST_VARS["CST_PLATFORM"];
    $CST_MID                    = $HTTP_POST_VARS["CST_MID"];
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;
    $LGD_PAYKEY                 = $HTTP_POST_VARS["LGD_PAYKEY"];

    require_once("lgdacom/XPayClient.php");
    $xpay1 = new XPayClient($configPath, $CST_PLATFORM);
	$xpay = &$xpay1;
    $xpay->Init_TX($LGD_MID);    
    
    $xpay->Set("LGD_TXNAME", "PaymentByKey");
    $xpay->Set("LGD_PAYKEY", $LGD_PAYKEY);
    
    //금액을 체크하시기 원하는 경우 아래 주석을 풀어서 이용하십시요.
	//$DB_AMOUNT = "DB나 세션에서 가져온 금액"; //반드시 위변조가 불가능한 곳(DB나 세션)에서 금액을 가져오십시요.
	//$xpay->Set("LGD_AMOUNTCHECKYN", "Y");
	//$xpay->Set("LGD_AMOUNT", $DB_AMOUNT);
	    
    /*
     *************************************************
     * 1.최종결제 요청(수정하지 마세요) - END
     *************************************************
     */

    /*
     * 2. 최종결제 요청 결과처리
     *
     * 최종 결제요청 결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
     */
    if ($xpay->TX()) {
        //1)결제결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        //echo "결제요청이 완료되었습니다.  <br>";
        //echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        //echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
        //$keys = $xpay->Response_Names();
		//foreach($keys as $name) {
        //    echo $name . " = " . $xpay->Response($name, 0) . "<br>";
		//}

		$LGD_RESPCODE = $xpay->Response("LGD_RESPCODE",0);					//응답코드
		$LGD_RESPMSG = $xpay->Response("LGD_RESPMSG",0);					//응답메세지
		$LGD_MID = $xpay->Response("LGD_MID",0);							//LG유플러스 발급 아이디
		$LGD_OID = $xpay->Response("LGD_OID",0);							//이용업체 거래번호(주문번호)
		$LGD_AMOUNT = $xpay->Response("LGD_AMOUNT",0);						//결제금액
		$LGD_TID = $xpay->Response("LGD_TID",0);							//LG유플러스 거래번호
		$LGD_PAYTYPE = $xpay->Response("LGD_PAYTYPE",0);					//결제수단
		$LGD_PAYDATE = $xpay->Response("LGD_PAYDATE",0);					//결제일시
		$LGD_HASHDATA = $xpay->Response("LGD_HASHDATA",0);					//해쉬데이타
		$LGD_TIMESTAMP = $xpay->Response("LGD_TIMESTAMP",0);				//타임스탬프
		$LGD_BUYER = $xpay->Response("LGD_BUYER",0);						//구매자명
		$LGD_PRODUCTINFO = $xpay->Response("LGD_PRODUCTINFO",0);			//구매내역
		$LGD_BUYERID = $xpay->Response("LGD_BUYERID",0);					//구매자아이디
		$LGD_BUYEREMAIL = $xpay->Response("LGD_BUYEREMAIL",0);				//구매자메일
		$LGD_FINANCENAME = $xpay->Response("LGD_FINANCENAME",0);			//결제기관명
		$LGD_FINANCEAUTHNUM = $xpay->Response("LGD_FINANCEAUTHNUM",0);		//결제기관승인번호
		$LGD_CASHRECEIPTNUM = $xpay->Response("LGD_CASHRECEIPTNUM",0);		//현금영수증승인번호
		$LGD_CASHRECEIPTSELFYN = $xpay->Response("LGD_CASHRECEIPTSELFYN",0);//현금영수증자진발급제유무
		$LGD_CASHRECEIPTKIND = $xpay->Response("LGD_CASHRECEIPTKIND",0);	//현금영수증종류
		$LGD_CARDNUM = $xpay->Response("LGD_CARDNUM",0);					//신용카드번호
		$LGD_CARDINSTALLMONTH = $xpay->Response("LGD_CARDINSTALLMONTH",0);	//신용카드할부개월
		$LGD_CARDNOINTYN = $xpay->Response("LGD_CARDNOINTYN",0);			//신용카드무이자여부
		$LGD_CARDGUBUN1 = $xpay->Response("LGD_CARDGUBUN1",0);				//신용카드추가정보1
		$LGD_CARDGUBUN2 = $xpay->Response("LGD_CARDGUBUN2",0);				//신용카드추가정보2
		$LGD_ACCOUNTNUM = $xpay->Response("LGD_ACCOUNTNUM",0);				//가상계좌발급번호
		$LGD_ACCOUNTOWNER = $xpay->Response("LGD_ACCOUNTOWNER",0);			//계좌주명
		$LGD_PAYER = $xpay->Response("LGD_PAYER",0);						//가상계좌입금자명
		$LGD_CASTAMOUNT = $xpay->Response("LGD_CASTAMOUNT",0);				//입금누적금액
		$LGD_CASCAMOUNT = $xpay->Response("LGD_CASCAMOUNT",0);				//현입금금액
		$LGD_CASFLAG = $xpay->Response("LGD_CASFLAG",0);					//거래종류(R:할당,I:입금,C:취소)
		$LGD_CASSEQNO = $xpay->Response("LGD_CASSEQNO",0);					//가상계좌일련번호
		$LGD_SAOWNER = $xpay->Response("LGD_SAOWNER",0);					//가상계좌 입금계좌주명
		$LGD_TELNO = $xpay->Response("LGD_TELNO",0);						//결제휴대폰번호

		$row = sql_fetch("select * from $payment[lgd_request_table] where ep_code = '$payment[store]' and mb_code = '$LGD_BUYERID' and payment_domain = '$return_domain' and lgd_oid = '$LGD_OID' and lgd_display = 0 and lgd_timestamp = '$return_time'");
		
		if($row[lgd_no]){
			$lgd_datetime = date("Y-m-d H:i:s");
			$sql = "insert into $payment[lgd_response_table] set
					payment_domain = '$row[payment_domain]',
					payment_device = '$row[payment_device]',
					payment_type = '$row[payment_type]',
					ep_code = '$payment[store]',
					mb_code = '$LGD_BUYERID',
					state_sort = '$payment[type]',
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
					lgd_datetime = '$lgd_datetime',
					lgd_display = 1
					";
			sql_query($sql);

			$sql = "update $payment[lgd_request_table] set
					lgd_display = 1
					where lgd_no = '$row[lgd_no]' and lgd_display = 0 and lgd_timestamp = '$return_time'
					";
			sql_query($sql);
		}
		
        if( "0000" == $xpay->Response_Code() ) {		
			//최종결제요청 결과 성공 DB처리 실패시 Rollback 처리
			$isDBOK = true; //DB처리 실패시 false로 변경해 주세요.

			if( !$isDBOK ) {
           		$xpay->Rollback("상점 DB처리 실패로 인하여 Rollback 처리 [TID:" . $xpay->Response("LGD_TID",0) . ",MID:" . $xpay->Response("LGD_MID",0) . ",OID:" . $xpay->Response("LGD_OID",0) . "]");            		            		
            		
                echo "TX Rollback Response_code = " . $xpay->Response_Code() . "<br>";
                echo "TX Rollback Response_msg = " . $xpay->Response_Msg() . "<p>";
            		
                if( "0000" == $xpay->Response_Code() ) {
					alert("자동취소가 정상적으로 완료 되었습니다.",$protocol.$return_domain."/bbs/m/shop_cart_form.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]"); 
                }else{
					alert("자동취소가 정상적으로 처리되지 않았습니다.",$protocol.$return_domain."/bbs/m/shop_cart_form.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]");  
                }
          	}else{
				$payment_success = true;
			}
        }else{
          	//최종결제요청 결과 실패 DB처리
         	alert("결제요청이 실패하였습니다.",$protocol.$return_domain."/bbs/m/shop_cart_form.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]");          	            
        }
    }else {
        //2)API 요청실패 화면처리
        echo "결제요청이 실패하였습니다.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
            
        //최종결제요청 결과 실패 DB처리
        alert("결제요청이 실패하였습니다.",$protocol.$return_domain."/bbs/m/shop_cart_form.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]");           	                        
    }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>LG유플러스 eCredit서비스</title>

</head>
<body>
<?if($payment_success){?>
<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="<?=$protocol.$return_domain?>/bbs/m/shop_pay_res.php?type=<?=$payment[type]?>&ep=<?=$payment[store]?>&gp=<?=$payment[group]?>">
<input type="hidden" name="LGD_RESPCODE"                id="LGD_RESPCODE"		value="<?= $LGD_RESPCODE ?>">
<input type="hidden" name="LGD_RESPMSG"                     id="LGD_RESPMSG"			value="<?= $LGD_RESPMSG ?>">
<input type="hidden" name="LGD_MID"                     id="LGD_MID"			value="<?= $LGD_MID ?>">
<input type="hidden" name="LGD_OID"                     id="LGD_OID"			value="<?= $LGD_OID ?>">
<input type="hidden" name="LGD_AMOUNT"                  id="LGD_AMOUNT"			value="<?= $LGD_AMOUNT ?>">
<input type="hidden" name="LGD_TID"              id="LGD_TID"		value="<?= $LGD_TID ?>">
<input type="hidden" name="LGD_PAYTYPE"             id="LGD_PAYTYPE"   	value="<?= $LGD_PAYTYPE ?>">
<input type="hidden" name="LGD_PAYDATE"      id="LGD_PAYDATE"		value="<?= $LGD_PAYDATE ?>">
<input type="hidden" name="LGD_TIMESTAMP"               id="LGD_TIMESTAMP"		value="<?= $LGD_TIMESTAMP ?>">
<input type="hidden" name="LGD_HASHDATA"                id="LGD_HASHDATA"		value="<?= $LGD_HASHDATA ?>">
<input type="hidden" name="LGD_BUYEREMAIL"                 id="LGD_BUYEREMAIL"		value="<?= $LGD_BUYEREMAIL ?>">
<input type="hidden" name="LGD_FINANCENAME"                 id="LGD_FINANCENAME"		value="<?= $LGD_FINANCENAME ?>">
<input type="hidden" name="LGD_FINANCEAUTHNUM"         id="LGD_FINANCEAUTHNUM"		value="<?= $LGD_FINANCEAUTHNUM ?>">           			
<input type="hidden" name="LGD_CASHRECEIPTNUM"        id="LGD_CASHRECEIPTNUM"		value="<?= $LGD_CASHRECEIPTNUM ?>">           			
<input type="hidden" name="LGD_CASHRECEIPTSELFYN"			id="LGD_CASHRECEIPTSELFYN"		value="<?= $LGD_CASHRECEIPTSELFYN ?>">  
<input type="hidden" name="LGD_CASHRECEIPTKIND"        id="LGD_CASHRECEIPTKIND"		value="<?= $LGD_CASHRECEIPTKIND ?>">  
<input type="hidden" name="LGD_CARDNUM"      id="LGD_CARDNUM"		value="<?= $LGD_CARDNUM ?>">  
<input type="hidden" name="LGD_CARDINSTALLMONTH"          id="LGD_CARDINSTALLMONTH"			value="<?= $LGD_CARDINSTALLMONTH ?>">  
<input type="hidden" name="LGD_BUYER"                   id="LGD_BUYER"			value="<?= $LGD_BUYER ?>">
<input type="hidden" name="LGD_PRODUCTINFO"             id="LGD_PRODUCTINFO"	value="<?= $LGD_PRODUCTINFO ?>">
<input type="hidden" name="LGD_CARDNOINTYN"             id="LGD_CARDNOINTYN"	value="<?= $LGD_CARDNOINTYN ?>">
<input type="hidden" name="LGD_CARDGUBUN1"             id="LGD_CARDGUBUN1"	value="<?= $LGD_CARDGUBUN1 ?>">
<input type="hidden" name="LGD_CARDGUBUN2"             id="LGD_CARDGUBUN2"	value="<?= $LGD_CARDGUBUN2 ?>">
<input type="hidden" name="LGD_ACCOUNTNUM"             id="LGD_ACCOUNTNUM"	value="<?= $LGD_ACCOUNTNUM ?>">
<input type="hidden" name="LGD_ACCOUNTOWNER"             id="LGD_ACCOUNTOWNER"	value="<?= $LGD_ACCOUNTOWNER ?>">
<input type="hidden" name="LGD_PAYER"             id="LGD_PAYER"	value="<?= $LGD_PAYER ?>">
<input type="hidden" name="LGD_CASTAMOUNT"             id="LGD_CASTAMOUNT"	value="<?= $LGD_CASTAMOUNT ?>">
<input type="hidden" name="LGD_CASCAMOUNT"             id="LGD_CASCAMOUNT"	value="<?= $LGD_CASCAMOUNT ?>">
<input type="hidden" name="LGD_CASFLAG"             id="LGD_CASFLAG"	value="<?= $LGD_CASFLAG ?>">
<input type="hidden" name="LGD_CASSEQNO"             id="LGD_CASSEQNO"	value="<?= $LGD_CASSEQNO ?>">
<input type="hidden" name="LGD_SAOWNER"             id="LGD_SAOWNER"	value="<?= $LGD_SAOWNER ?>">
<input type="hidden" name="LGD_TELNO"             id="LGD_TELNO"	value="<?= $LGD_TELNO ?>">
<input type="hidden" name="LGD_BUYERID"             id="LGD_BUYERID"	value="<?= $LGD_BUYERID ?>">
</form>
<script language = 'javascript'>
	document.getElementById('LGD_PAYINFO').submit();
</script>
<?}?>
</body>
</html>