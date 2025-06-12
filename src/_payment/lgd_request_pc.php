<?php
include_once("_common.php");
include_once("_check_parameter.php");
    /*
     * [결제 인증요청 페이지(STEP2-1)]
     *
     * 샘플페이지에서는 기본 파라미터만 예시되어 있으며, 별도로 필요하신 파라미터는 연동메뉴얼을 참고하시어 추가 하시기 바랍니다.     
     */

    /*
     * 1. 기본결제 인증요청 정보 변경
     * 
     * 기본정보를 변경하여 주시기 바랍니다.(파라미터 전달시 POST를 사용하세요)
     */
	$PAYMENT_DOMAIN				= $HTTP_POST_VARS["PAYMENT_DOMAIN"];
	$PAYMENT_TYPE				= $HTTP_POST_VARS["PAYMENT_TYPE"];

	if($PAYMENT_TYPE == "point"){
		$return_domain= "http://$PAYMENT_DOMAIN/bbs/m/all_point_charge.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]";
	}else{
		$return_domain= "http://$PAYMENT_DOMAIN/bbs/m/shop_cart_form.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]";
	}
	$row = sql_fetch(" select count(*) as cnt from $payment[site_user_table] where ps_domain = '$PAYMENT_DOMAIN' and ps_display = 1");
	if (!$row[cnt]) alert("결제시스템이 활성화 되어있지 않습니다.",$return_domain);
	set_cookie("payment_domain", $PAYMENT_DOMAIN, time()+36000);

    $CST_PLATFORM               = $HTTP_POST_VARS["CST_PLATFORM"];      //LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = "wiz2016";           //상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                        //테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)
    $LGD_OID                    = $HTTP_POST_VARS["LGD_OID"];           //주문번호(상점정의 유니크한 주문번호를 입력하세요)
    $LGD_AMOUNT                 = $HTTP_POST_VARS["LGD_AMOUNT"];        //결제금액("," 를 제외한 결제금액을 입력하세요)
	$LGD_TAXFREEAMOUNT			= $HTTP_POST_VARS["LGD_TAXFREEAMOUNT"];
    $LGD_BUYER                  = $HTTP_POST_VARS["LGD_BUYER"];         //구매자명
    $LGD_PRODUCTINFO            = $HTTP_POST_VARS["LGD_PRODUCTINFO"];   //상품명
    $LGD_BUYEREMAIL             = $HTTP_POST_VARS["LGD_BUYEREMAIL"];    //구매자 이메일
    $LGD_TIMESTAMP              = $HTTP_POST_VARS["LGD_TIMESTAMP"];     //타임스탬프
	set_cookie("payment_time", $LGD_TIMESTAMP, time()+36000);

    $LGD_CUSTOM_SKIN            = "red";                               //상점정의 결제창 스킨 (red, blue, cyan, green, yellow)
    $LGD_MERTKEY				= "50a2213850df98c1a75a48d26f18abb5";									//상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
	$configPath 				= "lgdacom"; 						//LG유플러스에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정. 	    
    $LGD_BUYERID                = $HTTP_POST_VARS["LGD_BUYERID"];       //구매자 아이디
    $LGD_BUYERIP                = $HTTP_POST_VARS["LGD_BUYERIP"];       //구매자IP

	$LGD_CUSTOM_FIRSTPAY		= $HTTP_POST_VARS["LGD_CUSTOM_FIRSTPAY"]; //결제수단
	$LGD_CUSTOM_USABLEPAY		= $HTTP_POST_VARS["LGD_CUSTOM_FIRSTPAY"];	//결제수단 제한

	$LGD_CUSTOM_MERTNAME		= "(주)위즈윈디지털";			//상점명
	$LGD_CUSTOM_MERTPHONE		= "028525567";				//상점전화번호
	$LGD_CUSTOM_BUSINESSNUM		= "1198193821";				//사업자번호
	$LGD_CUSTOM_CEONAME			= "박비안네";					//대표자명
	
	
	$lgd_datetime = date("Y-m-d H:i:s");
	$sql = "insert into $payment[lgd_request_table] set
			payment_domain = '$PAYMENT_DOMAIN',
			payment_device = 'pc',
			payment_type = '$PAYMENT_TYPE',
			ep_code = '$payment[store]',
			mb_code = '$LGD_BUYERID',
			state_sort = '$payment[type]',
			lgd_mid = '$LGD_MID',
			lgd_oid = '$LGD_OID',
			lgd_amount = '$LGD_AMOUNT',
			lgd_buyer = '$LGD_BUYER',
			lgd_productinfo = '$LGD_PRODUCTINFO',
			lgd_buyeremail = '$LGD_BUYEREMAIL',
			lgd_timestamp = '$LGD_TIMESTAMP',
			lgd_buyerip = '$LGD_BUYERIP',
			lgd_custom_firstpay = '$LGD_CUSTOM_FIRSTPAY',
			lgd_datetime = '$lgd_datetime'
			";
	sql_query($sql);

    /*
     * 가상계좌(무통장) 결제 연동을 하시는 경우 아래 LGD_CASNOTEURL 을 설정하여 주시기 바랍니다. 
     */    
    $LGD_CASNOTEURL				= "http://www.info-way.co.kr/_payment/lgd_cas_noteurl.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]";    

    /*
     *************************************************
     * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
     * 
     * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
     *************************************************
     *
     * 해쉬 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
     * LGD_MID          : 상점아이디
     * LGD_OID          : 주문번호
     * LGD_AMOUNT       : 금액
     * LGD_TIMESTAMP    : 타임스탬프
     * LGD_MERTKEY      : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
     *
     * MD5 해쉬데이터 암호화 검증을 위해
     * LG유플러스에서 발급한 상점키(MertKey)를 환경설정 파일(lgdacom/conf/mall.conf)에 반드시 입력하여 주시기 바랍니다.
     */
    require_once("lgdacom/XPayClient.php");
    $xpay1 = new XPayClient($configPath, $CST_PLATFORM);
	$xpay = &$xpay1;
   	$xpay->Init_TX($LGD_MID);
    $LGD_HASHDATA = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_TIMESTAMP.$xpay->config[$LGD_MID]);
    $LGD_CUSTOM_PROCESSTYPE = "TWOTR";
    /*
     *************************************************
     * 2. MD5 해쉬암호화 (수정하지 마세요) - END
     *************************************************
     */
	
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>LG유플러스 eCredit서비스</title>

<script language = 'javascript'>
<!--
/*
 * 상점결제 인증요청후 PAYKEY를 받아서 최종결제 요청.
 */
function doPay_ActiveX(){
    ret = xpay_check(document.getElementById('LGD_PAYINFO'), '<?= $CST_PLATFORM ?>');

    if (ret=="00"){     //ActiveX 로딩 성공
        var LGD_RESPCODE        = dpop.getData('LGD_RESPCODE');       //결과코드
        var LGD_RESPMSG         = dpop.getData('LGD_RESPMSG');        //결과메세지

        if( "0000" == LGD_RESPCODE ) { //인증성공
            var LGD_PAYKEY      = dpop.getData('LGD_PAYKEY');         //LG유플러스 인증KEY
            var msg = "인증결과 : " + LGD_RESPMSG + "\n";
            msg += "LGD_PAYKEY : " + LGD_PAYKEY +"\n\n";
            document.getElementById('LGD_PAYKEY').value = LGD_PAYKEY;
            //alert(msg);
            document.getElementById('LGD_PAYINFO').submit();
        } else { //인증실패
            alert("인증이 실패하였습니다. " + LGD_RESPMSG);
            location.href="<?=$return_domain?>";
        }
    } else {
        alert("LG U+ 전자결제를 위한 ActiveX Control이 설치되지 않았습니다.");
        location.href="<?=$return_domain?>";
    }
}

//-->
</script>

</head>
<body onload="doPay_ActiveX();">
<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="lgd_response_<?=$PAYMENT_TYPE?>.php?type=<?=$payment[type]?>&ep=<?=$payment[store]?>&gp=<?=$payment[group]?>">
<input type="hidden" name="CST_PLATFORM"                id="CST_PLATFORM"		value="<?= $CST_PLATFORM ?>">                   <!-- 테스트, 서비스 구분 -->
<input type="hidden" name="CST_MID"                     id="CST_MID"			value="<?= $CST_MID ?>">                        <!-- 상점아이디 -->
<input type="hidden" name="LGD_MID"                     id="LGD_MID"			value="<?= $LGD_MID ?>">                        <!-- 상점아이디 -->
<input type="hidden" name="LGD_OID"                     id="LGD_OID"			value="<?= $LGD_OID ?>">                        <!-- 주문번호 -->
<input type="hidden" name="LGD_BUYER"                   id="LGD_BUYER"			value="<?= $LGD_BUYER ?>">           			<!-- 구매자 -->
<input type="hidden" name="LGD_PRODUCTINFO"             id="LGD_PRODUCTINFO"	value="<?= $LGD_PRODUCTINFO ?>">     			<!-- 상품정보 -->
<input type="hidden" name="LGD_AMOUNT"                  id="LGD_AMOUNT"			value="<?= $LGD_AMOUNT ?>">                     <!-- 결제금액 -->
<input type="hidden" name="LGD_TAXFREEAMOUNT"           id="LGD_TAXFREEAMOUNT"	value="<?= $LGD_TAXFREEAMOUNT ?>">                     <!-- 면세 -->
<input type="hidden" name="LGD_BUYEREMAIL"              id="LGD_BUYEREMAIL"		value="<?= $LGD_BUYEREMAIL ?>">                 <!-- 구매자 이메일 -->
<input type="hidden" name="LGD_CUSTOM_SKIN"             id="LGD_CUSTOM_SKIN"   	value="<?= $LGD_CUSTOM_SKIN ?>">                <!-- 결제창 SKIN -->
<input type="hidden" name="LGD_CUSTOM_PROCESSTYPE"      id="LGD_CUSTOM_PROCESSTYPE"		value="<?= $LGD_CUSTOM_PROCESSTYPE ?>">         <!-- 트랜잭션 처리방식 -->
<input type="hidden" name="LGD_TIMESTAMP"               id="LGD_TIMESTAMP"		value="<?= $LGD_TIMESTAMP ?>">                  <!-- 타임스탬프 -->
<input type="hidden" name="LGD_HASHDATA"                id="LGD_HASHDATA"		value="<?= $LGD_HASHDATA ?>">                   <!-- MD5 해쉬암호값 -->
<input type="hidden" name="LGD_PAYKEY"                  id="LGD_PAYKEY">														<!-- LG유플러스 PAYKEY(인증후 자동셋팅)-->
<input type="hidden" name="LGD_VERSION"         		id="LGD_VERSION"		value="PHP_XPay_2.5">							<!-- 버전정보 (삭제하지 마세요) -->
<input type="hidden" name="LGD_BUYERIP"                 id="LGD_BUYERIP"		value="<?= $LGD_BUYERIP ?>">           			<!-- 구매자IP -->
<input type="hidden" name="LGD_BUYERID"                 id="LGD_BUYERID"		value="<?= $LGD_BUYERID ?>">           			<!-- 구매자ID -->

<input type="hidden" name="LGD_CUSTOM_FIRSTPAY"         id="LGD_CUSTOM_FIRSTPAY"		value="<?= $LGD_CUSTOM_FIRSTPAY ?>">           			
<input type="hidden" name="LGD_CUSTOM_USABLEPAY"        id="LGD_CUSTOM_USABLEPAY"		value="<?= $LGD_CUSTOM_USABLEPAY ?>">           			
<input type="hidden" name="LGD_CUSTOM_MERTNAME"			id="LGD_CUSTOM_MERTNAME"		value="<?= $LGD_CUSTOM_MERTNAME ?>">  
<input type="hidden" name="LGD_CUSTOM_MERTPHONE"        id="LGD_CUSTOM_MERTPHONE"		value="<?= $LGD_CUSTOM_MERTPHONE ?>">  
<input type="hidden" name="LGD_CUSTOM_BUSINESSNUM"      id="LGD_CUSTOM_BUSINESSNUM"		value="<?= $LGD_CUSTOM_BUSINESSNUM ?>">  
<input type="hidden" name="LGD_CUSTOM_CEONAME"          id="LGD_CUSTOM_CEONAME"			value="<?= $LGD_CUSTOM_CEONAME ?>">  

<!-- 가상계좌(무통장) 결제연동을 하시는 경우  할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 LG 유플러스에 전송해야 합니다 . -->
<input type="hidden" name="LGD_CASNOTEURL"          	id="LGD_CASNOTEURL"		value="<?= $LGD_CASNOTEURL ?>">			<!-- 가상계좌 NOTEURL -->  
<input type="hidden" name="LGD_CASHRECEIPTYN"                 id="LGD_CASHRECEIPTYN"		value="Y">

</form>
</body>
<!--  xpay.js는 반드시 body 밑에 두시기 바랍니다. -->
<!--  UTF-8 인코딩 사용 시는 xpay.js 대신 xpay_utf-8.js 을  호출하시기 바랍니다.-->
<script language="javascript" src="<?= $_SERVER['SERVER_PORT']!=443?"http":"https" ?>://xpay.uplus.co.kr<?=($CST_PLATFORM == "test")?($_SERVER['SERVER_PORT']!=443?":7080":":7443"):""?>/xpay/js/xpay_utf-8.js" type="text/javascript"></script>
</html>