<!DOCTYPE html>
<html lang="ko">
<head>
    <title>결제 성공</title>
	<meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
</head>
<body>

<?php
$paymentKey = $_GET['paymentKey'];
$orderId = $_GET['orderId'];
$amount = $_GET['amount'];
$method = $_GET['md'];

//var_dump($_GET);
//var_dump($_POST); exit;
$payment_config = require_once(__DIR__ . '/../config/payment.php');
$secretKey = $payment_config['toss_secret_key'];
//$secretKey = $payment_config['toss_test_secret_key'];

$url = 'https://api.tosspayments.com/v1/payments/' . $paymentKey;

$data = array('orderId' => $orderId, 'amount' => $amount);

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
$isSuccess = $httpCode == 200;
$responseJson = json_decode($response);
//var_dump($responseJson); exit;
$paytype = $method;

if($paytype == '카드'){
	$paytype_code = 'SC0010';
	$finance_com = $responseJson->card->company;
	$approved_no = $responseJson->card->approveNo;
	$card_no = $responseJson->card->number;
	$nointyn = ($responseJson->card->isInterestFree)?'1':'0';
	$lgd_resp = "결제성공";
}else if($paytype == '휴대폰'){
	$paytype_code = 'SC0060';
	$finance_com = $responseJson->mobilePhone->carrier;
	$approved_no = $responseJson->mobilePhone->settlementStatus;
	$card_no = $responseJson->mobilePhone->number;
	$nointyn = '0';
	$lgd_resp = "결제성공";
}else if($paytype == '계좌이체'){
	$paytype_code = 'SC0030';
	$finance_com = $responseJson->transfer->bank;
	$approved_no = $responseJson->transfer->settlementStatus;
	$card_no = '';
	$nointyn = '0';
	$lgd_resp = "입금완료";
}else if($paytype == '가상계좌'){
	$paytype_code = 'SC0040';
	$finance_com = $responseJson->virtualAccount->bank;
	$approved_no = $responseJson->virtualAccount->refundStatus;
	$card_no = $responseJson->virtualAccount->accountNumber;
	$nointyn = '0';
	$lgd_resp = "입금대기";
}else{
	$paytype_code = 'OTHER';
	$finance_com = '';
	$approved_no = '';
	$card_no = '';
	$nointyn = '0';
}
/*
$paydate = $responseJson->approvedAt;
$arydate = explode("+", $paydate);
$tdate = $arydate[0];
$tdate = str_replace("-","",$tdate);
$tdate = str_replace("T","",$tdate);
$approved_date = str_replace(":","",$tdate);
*/
$approved_date = date("YmdHis");

include_once("_common.php");
include_once("_check_parameter.php");

global $db_conn;
if (!$db_conn) {
    // _common.php 에서 $connect_db 변수에 할당된 연결을 사용합니다.
    $db_conn = $connect_db;
}

$return_domain = get_cookie("payment_domain");
$return_time = get_cookie("payment_time");
$buyerid = get_cookie("buyer_id");
$response_domain = get_cookie("response_domain");

if($isSuccess) {
	$LGD_RESPCODE = $httpCode;											//응답코드
	$LGD_RESPMSG = $lgd_resp;											//응답메세지
	$LGD_MID = $responseJson->mId;										//LG유플러스 발급 아이디
	$LGD_OID = $responseJson->orderId;									//이용업체 거래번호(주문번호)
	$LGD_AMOUNT = $responseJson->totalAmount;							//결제금액
	$LGD_TID = $responseJson->transactionKey;							//LG유플러스 거래번호
	$LGD_PAYTYPE = $paytype_code;										//결제수단
	$LGD_PAYDATE = $approved_date;										//결제일시		20220525024636
	$LGD_HASHDATA = $responseJson->paymentKey;							//해쉬데이타
	$LGD_TIMESTAMP = $approved_date;									//타임스탬프
	$LGD_PRODUCTINFO = $responseJson->orderName;						//구매내역
	$LGD_FINANCENAME = $finance_com;									//결제기관명
	$LGD_FINANCEAUTHNUM = $approved_no;									//결제기관승인번호
	$LGD_CASHRECEIPTNUM = $responseJson->receiptUrl;					//현금영수증승인번호
	$LGD_CARDNUM = $card_no;											//신용카드번호
	$LGD_CARDINSTALLMONTH = $responseJson->card->installmentPlanMonths;	//신용카드할부개월
	$LGD_CARDNOINTYN = $nointyn;										//신용카드무이자여부
	$LGD_CARDGUBUN1 = $responseJson->card->cardType;					//신용카드추가정보1
	$LGD_CARDGUBUN2 = $responseJson->card->ownerType;					//신용카드추가정보2
	$LGD_ACCOUNTNUM = $responseJson->virtualAccount->accountNumber;		//가상계좌발급번호
	$LGD_ACCOUNTOWNER = $responseJson->virtualAccount->bank;			//계좌주명
	$LGD_PAYER = $responseJson->virtualAccount->customerName;			//가상계좌입금자명
	$LGD_CASTAMOUNT = $responseJson->totalAmount;						//입금누적금액
	$LGD_CASCAMOUNT = $responseJson->totalAmount;						//현입금금액

	$REFUNDSTATE = $responseJson->virtualAccount->refundStatus;			//거래종류(R:할당,I:입금,C:취소), 토스 => NONE(할당), DONE(입금완료), CANCELED(입금취소), PARTIAL_CANCELED(부분취소)
	if($REFUNDSTATE=="DONE"){
		$LGD_CASFLAG = "I";
	}else if($REFUNDSTATE=="CANCELED"){
		$LGD_CASFLAG = "C";
	}else{
		$LGD_CASFLAG = "R";
	}
	$LGD_CASSEQNO = $responseJson->virtualAccount->accountNumber;		//가상계좌일련번호
	$LGD_SAOWNER = $responseJson->virtualAccount->bank;					//가상계좌 입금계좌주명
	$LGD_TELNO = $responseJson->mobilePhone->customerMobilePhone;		//결제휴대폰번호
	$LGD_BUYERID = $buyerid;											//구매자 아이디

    $row = null;
    $sql = "select * from {$payment['lgd_request_table']} where ep_code = ? and mb_code = ? and payment_domain = ? and lgd_oid = ? and lgd_display = 0 and lgd_timestamp = ?";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $payment['store'], $LGD_BUYERID, $return_domain, $LGD_OID, $return_time);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
	//echo "s_sql : ".$s_sql."<br><br>";
	
	if($row['lgd_no']){
		$lgd_datetime = date("Y-m-d H:i:s");
		$sql = "insert into {$payment['lgd_response_table']} set
				payment_domain = ?,
				payment_device = ?,
				payment_type = ?,
				ep_code = ?,
				mb_code = ?,
				state_sort = ?,
				lgd_oid = ?,
				lgd_tid = ?,
				lgd_respcode = ?,
				lgd_respmsg = ?,
				lgd_mid = ?,
				lgd_amount = ?,
				lgd_paytype = ?,
				lgd_paydate = ?,
				lgd_hashdata = ?,
				lgd_timestamp = ?,
				lgd_buyer = ?,
				lgd_productinfo = ?,
				lgd_buyeremail = ?,
				lgd_financename = ?,
				lgd_financeauthnum = ?,
				lgd_cashreceiptnum = ?,
				lgd_cashreceiptselfyn = '',
				lgd_cashreceiptkind = '',
				lgd_cardnum = ?,
				lgd_cardinstallmonth = ?,
				lgd_cardnointyn = ?,
				lgd_cardgubun1 = ?,
				lgd_cardgubun2 = ?,
				lgd_accountnum = ?,
				lgd_accountowner = ?,
				lgd_payer = ?,
				lgd_castamount = ?,
				lgd_cascamount = ?,
				lgd_casflag = ?,
				lgd_casseqno = ?,
				lgd_saowner = ?,
				lgd_telno = ?,
				lgd_datetime = ?,
				lgd_display = 1
				";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssssssssssssssss", 
            $row['payment_domain'], $row['payment_device'], $row['payment_type'], $payment['store'], $row['mb_code'], 
            $payment['type'], $LGD_OID, $LGD_TID, $LGD_RESPCODE, $LGD_RESPMSG, $LGD_MID, $LGD_AMOUNT, $LGD_PAYTYPE, 
            $LGD_PAYDATE, $LGD_HASHDATA, $LGD_TIMESTAMP, $row['lgd_buyer'], $LGD_PRODUCTINFO, $row['lgd_buyeremail'], 
            $LGD_FINANCENAME, $LGD_FINANCEAUTHNUM, $LGD_CASHRECEIPTNUM, $LGD_CARDNUM, $LGD_CARDINSTALLMONTH, 
            $LGD_CARDNOINTYN, $LGD_CARDGUBUN1, $LGD_CARDGUBUN2, $LGD_ACCOUNTNUM, $LGD_ACCOUNTOWNER, $LGD_PAYER, 
            $LGD_CASTAMOUNT, $LGD_CASCAMOUNT, $LGD_CASFLAG, $LGD_CASSEQNO, $LGD_SAOWNER, $LGD_TELNO, $lgd_datetime
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

		//echo "success insert sql : ".$sql."<br><br>";

		$sql = "update {$payment['lgd_request_table']} set
				lgd_display = 1
				where lgd_no = ? and lgd_display = 0 and lgd_timestamp = ?
				";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $row['lgd_no'], $return_time);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
		//echo "success update sql : ".$sql."<br><br>";
	}
}
?>

<section>
    <?php if ($isSuccess) { ?>
<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="<?=$response_domain?>">
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
<input type="hidden" name="LGD_BUYEREMAIL"                 id="LGD_BUYEREMAIL"		value="<?= $row[lgd_buyeremail] ?>">
<input type="hidden" name="LGD_FINANCENAME"                 id="LGD_FINANCENAME"		value="<?= $LGD_FINANCENAME ?>">
<input type="hidden" name="LGD_FINANCEAUTHNUM"         id="LGD_FINANCEAUTHNUM"		value="<?= $LGD_FINANCEAUTHNUM ?>">           			
<input type="hidden" name="LGD_CASHRECEIPTNUM"        id="LGD_CASHRECEIPTNUM"		value="<?= $LGD_CASHRECEIPTNUM ?>">           			
<input type="hidden" name="LGD_CASHRECEIPTSELFYN"			id="LGD_CASHRECEIPTSELFYN"		value="">  
<input type="hidden" name="LGD_CASHRECEIPTKIND"        id="LGD_CASHRECEIPTKIND"		value="">  
<input type="hidden" name="LGD_CARDNUM"      id="LGD_CARDNUM"		value="<?= $LGD_CARDNUM ?>">  
<input type="hidden" name="LGD_CARDINSTALLMONTH"          id="LGD_CARDINSTALLMONTH"			value="<?= $LGD_CARDINSTALLMONTH ?>">  
<input type="hidden" name="LGD_BUYER"                   id="LGD_BUYER"			value="<?= $row['lgd_buyer'] ?>">
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
<div>결제 완료</div>
<script language = 'javascript'>
	document.getElementById('LGD_PAYINFO').submit();
</script>
    <?php } ?>
</section>
</body>
</html>
