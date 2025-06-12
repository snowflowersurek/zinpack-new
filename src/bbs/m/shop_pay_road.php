<?php
include_once("_common.php");
if ($iw[level]=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	
$LGD_CUSTOM_FIRSTPAY = $HTTP_POST_VARS["LGD_CUSTOM_FIRSTPAY"];
if($LGD_CUSTOM_FIRSTPAY=="PAYPAL"){
	$paypal_business			= "aeroitems@info-way.co.kr";
	$paypal_return				= "$iw[url]/bbs/m/shop_paypal_return.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]";
	$paypal_cancel_return		= "$iw[url]/bbs/m/shop_paypal_cancel.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]";
	$paypal_notify_url			= "$iw[url]/bbs/m/shop_paypal_notify.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]";

    $paypal_invoice             = $HTTP_POST_VARS["LGD_OID"];           //주문번호(상점정의 유니크한 주문번호를 입력하세요)
	$paypal_amount              = $HTTP_POST_VARS["LGD_AMOUNT"]/1000-$HTTP_POST_VARS["rate_point"];        //결제금액("," 를 제외한 결제금액을 입력하세요)
	$paypal_item_name			= $HTTP_POST_VARS["LGD_PRODUCTINFO"];   //상품명
	$paypal_custom				= $HTTP_POST_VARS["LGD_BUYERID"];       //구매자 아이디
	$paypal_ip	                = $_SERVER["REMOTE_ADDR"];

	$row = sql_fetch(" select count(*) as cnt from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$paypal_custom' and sr_code = '$paypal_invoice' and ep_code = '$iw[store]'");
	if (!$row[cnt]) {
		alert("주문할 상품이 존재하지 않습니다.","$iw[m_path]/shop_cart_form.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	}

	$sr_name = $HTTP_POST_VARS["sr_name"];
	$sr_phone = $HTTP_POST_VARS["sr_phone"];
	$sr_phone_sub = $HTTP_POST_VARS["sr_phone_sub"];
	$sr_zip_code = $HTTP_POST_VARS["sr_zip_code"];
	$sr_address = $HTTP_POST_VARS["sr_address"];
	$sr_address_sub = $HTTP_POST_VARS["sr_address_sub"];
	$sr_address_city = $HTTP_POST_VARS["sr_address_city"];
	$sr_address_state = $HTTP_POST_VARS["sr_address_state"];
	$sr_address_country = $HTTP_POST_VARS["sr_address_country"];
	$sr_request = $HTTP_POST_VARS["sr_request"];
	$sr_point = $HTTP_POST_VARS["sr_point"];
	$sr_pay = "paypal";

	$sql = "update $iw[shop_order_table] set
			sr_name = '$sr_name',
			sr_phone = '$sr_phone',
			sr_phone_sub = '$sr_phone_sub',
			sr_zip_code = '$sr_zip_code',
			sr_address = '$sr_address',
			sr_address_sub = '$sr_address_sub',
			sr_address_city = '$sr_address_city',
			sr_address_state = '$sr_address_state',
			sr_address_country = '$sr_address_country',
			sr_request = '$sr_request',
			sr_point = '$sr_point',
			sr_ip = '$paypal_ip',
			sr_pay = '$sr_pay',
			sr_product = '$paypal_item_name'
			where ep_code = '$iw[store]' and mb_code = '$paypal_custom' and sr_code = '$paypal_invoice'
			";
	sql_query($sql);
?>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>PAYPAL 결제</title>
	</head>
	<body>
		<form id="paypal_load" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="charset" value="UTF-8">
			<input type="hidden" name="upload" value="1">											<!-- 결제요청 -->
			<input type="hidden" name="return" value="<?= $paypal_return ?>">						<!-- 결제완료페이지 -->
			<input type="hidden" name="cancel_return" value="<?= $paypal_cancel_return ?>">			<!-- 결제취소페이지 -->
			<input type="hidden" name="notify_url" value="<?= $paypal_notify_url ?>">				<!-- IPN페이지 -->
			<input type="hidden" name="business" value="<?= $paypal_business ?>">					<!-- 구매자ID -->
			<input type="hidden" name="invoice" value="<?= $paypal_invoice ?>">						<!-- 주문번호 -->
			<input type="hidden" name="amount" value="<?= $paypal_amount ?>">						<!-- 결제금액 -->
			<input type="hidden" name="item_name" value="<?= $paypal_item_name ?>">					<!-- 상품정보 -->
			<input type="hidden" name="custom" value="<?= $paypal_custom ?>">						<!-- 구매자ID -->
		</form>

		<script language = 'javascript'>
			document.getElementById('paypal_load').submit();
		</script>
	</body>
	</html>
<?
}else if($LGD_CUSTOM_FIRSTPAY=="ALIPAY"){
	$alipay_out_trade_no		= $HTTP_POST_VARS["LGD_OID"];           //주문번호(상점정의 유니크한 주문번호를 입력하세요)
	$alipay_total_fee			= $HTTP_POST_VARS["LGD_AMOUNT"]/1000-$HTTP_POST_VARS["rate_point"];        //결제금액("," 를 제외한 결제금액을 입력하세요)
	$alipay_subject				= $HTTP_POST_VARS["LGD_PRODUCTINFO"];   //상품명
	$alipay_user_id				= $HTTP_POST_VARS["LGD_BUYERID"];       //구매자 아이디
	$alipay_ip	                = $_SERVER["REMOTE_ADDR"];

	require_once("alipay_service.php");
	require_once("alipay_config.php");
	$notify_url = "$iw[url]/bbs/m/shop_alipay_notify.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&mb=$alipay_user_id";
	$return_url = "$iw[url]/bbs/m/shop_alipay_return.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&mb=$alipay_user_id";
	
	$row = sql_fetch(" select count(*) as cnt from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$alipay_user_id' and sr_code = '$alipay_out_trade_no' and ep_code = '$iw[store]'");
	if (!$row[cnt]) {
		alert("주문할 상품이 존재하지 않습니다.","$iw[m_path]/shop_cart_form.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	}

	$sr_name = $HTTP_POST_VARS["sr_name"];
	$sr_phone = $HTTP_POST_VARS["sr_phone"];
	$sr_phone_sub = $HTTP_POST_VARS["sr_phone_sub"];
	$sr_zip_code = $HTTP_POST_VARS["sr_zip_code"];
	$sr_address = $HTTP_POST_VARS["sr_address"];
	$sr_address_sub = $HTTP_POST_VARS["sr_address_sub"];
	$sr_address_city = $HTTP_POST_VARS["sr_address_city"];
	$sr_address_state = $HTTP_POST_VARS["sr_address_state"];
	$sr_address_country = $HTTP_POST_VARS["sr_address_country"];
	$sr_request = $HTTP_POST_VARS["sr_request"];
	$sr_point = $HTTP_POST_VARS["sr_point"];
	$sr_pay = "alipay";

	$sql = "update $iw[shop_order_table] set
			sr_name = '$sr_name',
			sr_phone = '$sr_phone',
			sr_phone_sub = '$sr_phone_sub',
			sr_zip_code = '$sr_zip_code',
			sr_address = '$sr_address',
			sr_address_sub = '$sr_address_sub',
			sr_address_city = '$sr_address_city',
			sr_address_state = '$sr_address_state',
			sr_address_country = '$sr_address_country',
			sr_request = '$sr_request',
			sr_point = '$sr_point',
			sr_ip = '$alipay_ip',
			sr_pay = '$sr_pay',
			sr_product = '$alipay_subject'
			where ep_code = '$iw[store]' and mb_code = '$alipay_user_id' and sr_code = '$alipay_out_trade_no'
			";
	sql_query($sql);

	$parameter = array(
	"service" => "create_forex_trade", //this is the service name
	"partner" =>$partner,                                               
	"return_url" =>$return_url,  
	"notify_url" =>$notify_url,  
	"_input_charset" => $_input_charset,                                
	"subject" => $alipay_subject, //subject is the name of the product, you'd better change it
	"body" => "product",  //body is the description of the product , you'd beeter change it
	"out_trade_no" => $alipay_out_trade_no,   
	"total_fee" => $alipay_total_fee, //the price of products
	"currency"=>"USD", // change it as the currency which you used on your website
	);
	$alipay = new alipay_service($parameter,$security_code,$sign_type);
	//print_r($parameter);
	$link = $alipay->create_url();
	goto_url($link);
}
?>