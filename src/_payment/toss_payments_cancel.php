<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>토스 eCredit서비스</title>
</head>
<?php
include_once("_common.php");
include_once("_check_parameter.php");
$payment_config = require_once(__DIR__ . '/../config/payment.php');
//var_dump($_POST); exit;
    /*
     * [결제취소 요청 페이지]
     *
     * LG유플러스으로 부터 내려받은 거래번호(LGD_TID)를 가지고 취소 요청을 합니다.(파라미터 전달시 POST를 사용하세요)
     * (승인시 LG유플러스으로 부터 내려받은 PAYKEY와 혼동하지 마세요.)
     */

	$PAYMENT_DOMAIN				= $HTTP_POST_VARS["PAYMENT_DOMAIN"];
    
	$protocol = "http://";
	
	if ($PAYMENT_DOMAIN == "www.aviation.co.kr") {
		$protocol = "https://";
	}

	$row = sql_fetch(" select count(*) as cnt from $payment[site_user_table] where ps_domain = '$PAYMENT_DOMAIN' and ps_display = 1");
	if (!$row[cnt]) alert("결제시스템이 활성화 되어있지 않습니다.",$protocol.$PAYMENT_DOMAIN."/bbs/m/shop_buy_list.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]");
	set_cookie("payment_domain", $PAYMENT_DOMAIN, time()+36000);

	$LGD_OID					= $HTTP_POST_VARS["LGD_OID"];
	$LGD_PRODUCTINFO			= $HTTP_POST_VARS["LGD_PRODUCTINFO"];
	$LGD_AMOUNT					= $HTTP_POST_VARS["LGD_AMOUNT"];
	$LGD_TIMESTAMP				= $HTTP_POST_VARS["LGD_TIMESTAMP"];
	$LGD_BUYERIP				= $HTTP_POST_VARS["LGD_BUYERIP"];
	$LGD_BUYERID				= $HTTP_POST_VARS["LGD_BUYERID"];

    $CST_PLATFORM               = $HTTP_POST_VARS["CST_PLATFORM"];       //LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
    $LGD_MID                    = $HTTP_POST_VARS["LGD_MID"];            //상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
    $LGD_TID                	= $HTTP_POST_VARS["LGD_TID"];			 //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)
    $LGD_KEY                	= $HTTP_POST_VARS["LGD_KEY"];			 //TOSS 에서 내려받은 paymentKey (LGD_KEY)
    
 	$configPath 				= "lgdacom"; 						 //LG유플러스에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정.   
    
	$row = sql_fetch("select COUNT(*) AS cnt from $payment[cancel_request_table] where toss_paymentkey = '$LGD_KEY'");
	if($row['cnt'] == 0){
		$lgd_datetime = date("Y-m-d H:i:s");
		$sql = "insert into $payment[cancel_request_table] set
				payment_domain = '$PAYMENT_DOMAIN',
				ep_code = '$payment[store]',
				mb_code = '$LGD_BUYERID',
				state_sort = '$payment[type]',
				lgd_tid = '$LGD_TID',
				lgd_mid = '$LGD_MID',
				lgd_oid = '$LGD_OID',
				toss_paymentkey = '$LGD_KEY',
				lgd_timestamp = '$LGD_TIMESTAMP',
				lgd_buyerip = '$LGD_BUYERIP',
				lgd_datetime = '$lgd_datetime'
				";
		sql_query($sql);
	}

$secretKey = $payment_config['toss_secret_key'];
//$secretKey = $payment_config['toss_test_secret_key'];

$url = 'https://api.tosspayments.com/v1/payments/' . $LGD_KEY . '/cancel';

$data = array('cancelReason' => "고객 단순변심", 'cancelAmount' => $LGD_AMOUNT);

$credential = base64_encode($secretKey . ':');

$curlHandle = curl_init($url);

curl_setopt_array($curlHandle, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic ' . $credential,
        'Content-Type: application/json'
    ),
    CURLOPT_POSTFIELDS => json_encode($data)
));

$response = curl_exec($curlHandle);

$httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);

$responseJson = json_decode($response);
// var_dump($responseJson); exit; 
// object(stdClass)#1 (2) { ["code"]=> string(24) "ALREADY_CANCELED_PAYMENT" ["message"]=> string(34) "이미 취소된 결제 입니다." }

if($httpCode == 200){
	$payment_success = true;
	$Response_Code = $httpCode;
	$Response_Msg = "결제 취소요청 성공";

	$row = sql_fetch("select * from $payment[cancel_request_table] where ep_code = '$payment[store]' and mb_code = '$LGD_BUYERID' and payment_domain = '$PAYMENT_DOMAIN' and lgd_tid = '$LGD_TID' and lgd_oid = '$LGD_OID' and lgd_display = 0 and lgd_timestamp = '$LGD_TIMESTAMP'");

	if($row[cancel_no]) {
		$lgd_datetime = date("Y-m-d H:i:s");
		$sql = "insert into $payment[cancel_response_table] set
				payment_domain = '$PAYMENT_DOMAIN',
				ep_code = '$payment[store]',
				mb_code = '$LGD_BUYERID',
				state_sort = '$payment[type]',
				lgd_tid = '$LGD_TID',
				lgd_mid = '$LGD_MID',
				lgd_oid = '$LGD_OID',
				toss_paymentkey = '$LGD_KEY',
				lgd_respcode = '$Response_Code',
				lgd_respmsg = '$Response_Msg',
				lgd_datetime = '$lgd_datetime',
				lgd_display = 1
				";
		//echo "sql : " . $sql;
		sql_query($sql);

		$sql = "update $payment[cancel_request_table] set
				lgd_display = 1
				where cancel_no = '$row[cancel_no]' and lgd_display = 0 and lgd_timestamp = '$LGD_TIMESTAMP' and lgd_tid = '$LGD_TID'
				";
		sql_query($sql);
	}

}else{
	$payment_success = false;
	$Response_Code = $responseJson->code;
	$Response_Msg = $responseJson->message;

	alert("$Response_Msg", $protocol.$PAYMENT_DOMAIN."/bbs/m/shop_buy_list.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]");
}

?>
<body>
<?php if($payment_success){?>
<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="<?=$protocol.$PAYMENT_DOMAIN?>/bbs/m/shop_buy_cancel_res.php?type=<?=$payment[type]?>&ep=<?=$payment[store]?>&gp=<?=$payment[group]?>">
<input type="hidden" name="LGD_RESPCODE"                id="LGD_RESPCODE"		value="<?= $Response_Code ?>">
<input type="hidden" name="LGD_RESPMSG"                 id="LGD_RESPMSG"		value="<?= $Response_Msg ?>">
<input type="hidden" name="LGD_MID"                     id="LGD_MID"			value="<?= $LGD_MID ?>">
<input type="hidden" name="LGD_OID"                     id="LGD_OID"			value="<?= $LGD_OID ?>">
<input type="hidden" name="LGD_TID"						id="LGD_TID"			value="<?= $LGD_TID ?>">
</form>
<script language = 'javascript'>
	document.getElementById('LGD_PAYINFO').submit();
</script>
<?php }?>
</body>
</html>



