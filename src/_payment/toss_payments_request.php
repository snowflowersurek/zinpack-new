<!DOCTYPE html>
<html lang="ko">
<head>
    <title>구매하기</title>
    <meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
</head>

<?php
include_once("_common.php");
include_once("_check_parameter.php");
session_start();

global $db_conn;
if (!$db_conn) {
    // _common.php 에서 $connect_db 변수에 할당된 연결을 사용합니다.
    $db_conn = $connect_db;
}

	$PAYMENT_DOMAIN			= $HTTP_POST_VARS["PAYMENT_DOMAIN"];
	$PAYMENT_TYPE				= $HTTP_POST_VARS["PAYMENT_TYPE"];
	$response_domain		= $HTTP_POST_VARS["URL_TO_RETURN"];
	$return_domain			= $HTTP_POST_VARS["REQUEST_URL"];

    $row = null;
    $sql = "select count(*) as cnt from {$payment['site_user_table']} where ps_domain = ? and ps_display = 1";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $PAYMENT_DOMAIN);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

	if (!$row['cnt']) alert("결제시스템이 활성화 되어있지 않습니다.",$return_domain);
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

    $LGD_BUYERID                = $HTTP_POST_VARS["LGD_BUYERID"];       //구매자 아이디
    $LGD_BUYERIP                = $HTTP_POST_VARS["LGD_BUYERIP"];       //구매자IP
	$LGD_CUSTOM_FIRSTPAY		= $HTTP_POST_VARS["LGD_CUSTOM_FIRSTPAY"]; //결제수단

	$_SESSION[$LGD_OID] = $response_domain;
	//$_SESSION['back_domain'] = $return_domain;

	//set_cookie("response_domain", $response_domain, time()+36000);
	set_cookie("back_domain", $return_domain, time()+36000);
	set_cookie("payment_time", $LGD_TIMESTAMP, time()+36000);
	set_cookie("buyer_id", $LGD_BUYERID, time()+36000);

	$lgd_datetime = date("Y-m-d H:i:s");
	$sql = "insert into {$payment['lgd_request_table']} set
			payment_domain = ?,
			payment_device = 'pc',
			payment_type = ?,
			ep_code = ?,
			mb_code = ?,
			state_sort = ?,
			lgd_mid = ?,
			lgd_oid = ?,
			lgd_amount = ?,
			lgd_buyer = ?,
			lgd_productinfo = ?,
			lgd_buyeremail = ?,
			lgd_timestamp = ?,
			lgd_buyerip = ?,
			lgd_custom_firstpay = ?,
			lgd_datetime = ?,
			res_url = ?
			";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssssssssss", $PAYMENT_DOMAIN, $PAYMENT_TYPE, $payment['store'], $LGD_BUYERID, $payment['type'], $LGD_MID, $LGD_OID, $LGD_AMOUNT, $LGD_BUYER, $LGD_PRODUCTINFO, $LGD_BUYEREMAIL, $LGD_TIMESTAMP, $LGD_BUYERIP, $LGD_CUSTOM_FIRSTPAY, $lgd_datetime, $response_domain);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
	//echo "request sql : ".$sql."<br><br>";

	$method = '';
	if($HTTP_POST_VARS['LGD_CUSTOM_FIRSTPAY']=='SC0010'){	// 신용카드
		$method = '카드';
	}else if($HTTP_POST_VARS['LGD_CUSTOM_FIRSTPAY']=='SC0060'){	// 휴대폰
		$method = '휴대폰';
	}else if($HTTP_POST_VARS['LGD_CUSTOM_FIRSTPAY']=='SC0030'){	// 계좌이체
		$method = '계좌이체';
	}else if($HTTP_POST_VARS['LGD_CUSTOM_FIRSTPAY']=='SC0040'){	// 가상계좌
		$method = '가상계좌';
	}
?>
<body onload="toss_pay_request()">

<script src="https://js.tosspayments.com/v1/payment"></script>
<script>
    var tossPayments = TossPayments("live_ck_YyZqmkKeP8gkX0omXZK8bQRxB9lG");
    //var tossPayments = TossPayments("test_ck_YoEjb0gm23Pg1lQ9wBW8pGwBJn5e");50a2213850df98c1a75a48d26f18abb5(구 모듈용)

		var paytype = "<?php echo $PAYMENT_TYPE ?>";
    var orderId = "<?php echo $HTTP_POST_VARS['LGD_OID'] ?>";
		var method = "<?php echo $method ?>";
		var amount = "<?php echo $HTTP_POST_VARS['LGD_AMOUNT'] ?>";
		var freeAmount = "<?php echo $HTTP_POST_VARS['LGD_TAXFREEAMOUNT'] ?>";
		var orderName = "<?php echo $LGD_PRODUCTINFO ?>";
		var customerName = "<?php echo $HTTP_POST_VARS['LGD_BUYER'] ?>";

    function toss_pay_request() {
        if (method === '빌링') {	// 자동이체와 비슷하게 고정된 지출비를 결제할 때 사용.
            var randomCustomerKey = 'customer' + new Date().getTime();

            tossPayments.requestBillingAuth('카드', {
                customerKey: randomCustomerKey,
                successUrl: location.href + '/billing_confirm.php',
                failUrl: location.href + '/fail.php',
            });
        } else {
            var paymentData = {
                amount: amount,
								taxFreeAmount: freeAmount,
                orderId: orderId,
                orderName: orderName,
                customerName: customerName,
                successUrl: window.location.origin + "/_payment/toss_payments_success.php?type=<?=$payment['type']?>&ep=<?=$payment['store']?>&gp=<?=$payment['group']?>&md=<?=$method?>",
                failUrl: window.location.origin + "/_payment/toss_payments_fail.php",
            };

            if (method === '가상계좌') {	// 가상계좌 웹훅 URL 주소
							if(paytype=="charge"){
                paymentData.virtualAccountCallbackUrl = "https://www.info-way.co.kr/_payment/toss_payments_virtual_charge.php?type=<?=$payment['type']?>&ep=<?=$payment['store']?>&gp=<?=$payment['group']?>";
							}else{
                paymentData.virtualAccountCallbackUrl = "https://www.info-way.co.kr/_payment/toss_payments_virtual.php?type=<?=$payment['type']?>&ep=<?=$payment['store']?>&gp=<?=$payment['group']?>";
							}
            }

            tossPayments.requestPayment(method, paymentData)
						.catch(function (error) {
							if(error.code === "USER_CANCEL") {
								//alert("결제를 취소하였습니다.");
								location.replace("<?=$return_domain?>");
							}
						});
        }
    }
</script>
</body>
</html>
