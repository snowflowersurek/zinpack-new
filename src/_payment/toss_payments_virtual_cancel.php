<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>LG유플러스 eCredit서비스</title>
</head>
<?php
include_once("_common.php");
include_once("_check_parameter.php");

global $db_conn;
if (!$db_conn) {
    // _common.php 에서 $connect_db 변수에 할당된 연결을 사용합니다.
    $db_conn = $connect_db;
}
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

    $row = null;
    $sql = "select count(*) as cnt from {$payment['site_user_table']} where ps_domain = ? and ps_display = 1";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $PAYMENT_DOMAIN);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

	if (!$row['cnt']) alert("결제시스템이 활성화 되어있지 않습니다.",$protocol.$PAYMENT_DOMAIN."/bbs/m/shop_buy_list.php?type={$payment['type']}&ep={$payment['store']}&gp={$payment['group']}");
	set_cookie("payment_domain", $PAYMENT_DOMAIN, time()+36000);

	$LGD_OID					= $HTTP_POST_VARS["LGD_OID"];
	$LGD_PRODUCTINFO			= $HTTP_POST_VARS["LGD_PRODUCTINFO"];
	$LGD_AMOUNT					= $HTTP_POST_VARS["LGD_AMOUNT"];
	$LGD_TIMESTAMP				= $HTTP_POST_VARS["LGD_TIMESTAMP"];
	$LGD_BUYERIP				= $HTTP_POST_VARS["LGD_BUYERIP"];
	$LGD_BUYERID				= $HTTP_POST_VARS["LGD_BUYERID"];

    $CST_PLATFORM               = $HTTP_POST_VARS["CST_PLATFORM"];
    $LGD_MID                    = $HTTP_POST_VARS["LGD_MID"];
    $LGD_TID                	= $HTTP_POST_VARS["LGD_TID"];
    $LGD_KEY                	= $HTTP_POST_VARS["LGD_KEY"];

    $RE_BANK                	= $HTTP_POST_VARS["RE_BANK"];
    $RE_ACCOUNT                	= $HTTP_POST_VARS["RE_ACCOUNT"];
    $RE_NAME                	= $HTTP_POST_VARS["RE_NAME"];

    $row = null;
    $sql = "select COUNT(*) AS cnt from {$payment['cancel_request_table']} where toss_paymentkey = ?";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $LGD_KEY);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

	if($row['cnt'] == 0){
		$lgd_datetime = date("Y-m-d H:i:s");
		$sql = "insert into {$payment['cancel_request_table']} set
					payment_domain = ?,
					ep_code = ?,
					mb_code = ?,
					state_sort = ?,
					lgd_tid = ?,
					lgd_mid = ?,
					lgd_oid = ?,
					toss_paymentkey = ?,
					lgd_timestamp = ?,
					lgd_buyerip = ?,
					lgd_datetime = ?,
					re_bank		= ?,
					re_account	= ?,
					re_name		= ?
				";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssssss", $PAYMENT_DOMAIN, $payment['store'], $LGD_BUYERID, $payment['type'], $LGD_TID, $LGD_MID, $LGD_OID, $LGD_KEY, $LGD_TIMESTAMP, $LGD_BUYERIP, $lgd_datetime, $RE_BANK, $RE_ACCOUNT, $RE_NAME);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
	}

$payment_config = require_once(__DIR__ . '/../config/payment.php');

$secretKey = $payment_config['toss_secret_key'];
//$secretKey = $payment_config['toss_test_secret_key'];

$url = 'https://api.tosspayments.com/v1/payments/' . $LGD_KEY . '/cancel';

$data = array('cancelReason' => "고객 단순변심", 'cancelAmount' => $LGD_AMOUNT, 'refundReceiveAccount' => array('bank'=>$RE_BANK, 'accountNumber'=>$RE_ACCOUNT, 'holderName'=>$RE_NAME));

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
//var_dump($responseJson);
echo "<br><br>";

if($httpCode == 200){
	$payment_success = true;
	$Response_Code = $httpCode;
	$Response_Msg = '가상계좌 환불요청 성공';

    $row = null;
    $sql = "select * from {$payment['cancel_request_table']} where ep_code = ? and mb_code = ? and payment_domain = ? and lgd_tid = ? and lgd_oid = ? and lgd_display = 0 and lgd_timestamp = ?";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $payment['store'], $LGD_BUYERID, $PAYMENT_DOMAIN, $LGD_TID, $LGD_OID, $LGD_TIMESTAMP);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

	if($row['cancel_no']) {
		$lgd_datetime = date("Y-m-d H:i:s");
		$sql = "insert into {$payment['cancel_response_table']} set
				payment_domain = ?,
				ep_code = ?,
				mb_code = ?,
				state_sort = ?,
				lgd_tid = ?,
				lgd_mid = ?,
				lgd_oid = ?,
				toss_paymentkey = ?,
				lgd_respcode = ?,
				lgd_respmsg = ?,
				lgd_datetime = ?,
				lgd_display = 1
				";
		//echo "sql : " . $sql;
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssss", $PAYMENT_DOMAIN, $payment['store'], $LGD_BUYERID, $payment['type'], $LGD_TID, $LGD_MID, $LGD_OID, $LGD_KEY, $Response_Code, $Response_Msg, $lgd_datetime);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

		$sql = "update {$payment['cancel_request_table']} set
				lgd_display = 1
				where cancel_no = ? and lgd_display = 0 and lgd_timestamp = ? and lgd_tid = ?
				";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $row['cancel_no'], $LGD_TIMESTAMP, $LGD_TID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
	}

}else{
	$payment_success = false;
	$Response_Code = $responseJson->code;
	$Response_Msg = $responseJson->message;

	alert("결제 취소요청이 실패하였습니다.\n[오류코드] $Response_Code\n[오류메세지] $Response_Msg",$protocol.$PAYMENT_DOMAIN."/bbs/m/shop_buy_list.php?type=$payment[type]&ep=$payment[store]&gp=$payment[group]");
}

?>
<body>
<?php if($payment_success){?>
<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="<?=$protocol.$PAYMENT_DOMAIN?>/bbs/m/shop_buy_virtual_cancel_res.php?type=<?=$payment[type]?>&ep=<?=$payment[store]?>&gp=<?=$payment[group]?>">
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



