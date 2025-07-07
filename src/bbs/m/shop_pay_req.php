<?php
include_once("_common.php");
if ($iw[level]=="guest") alert("로그인 해주시기 바랍니다. ","$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

	$CST_PLATFORM               = $iw["pay_platform"];
    $LGD_OID                    = $HTTP_POST_VARS["LGD_OID"];
    $LGD_AMOUNT                 = $HTTP_POST_VARS["LGD_AMOUNT"] - $HTTP_POST_VARS["rate_point"];
	$LGD_TAXFREEAMOUNT			= $HTTP_POST_VARS["LGD_TAXFREEAMOUNT"];

	if($LGD_TAXFREEAMOUNT > $LGD_AMOUNT){
		$LGD_TAXFREEAMOUNT		= $LGD_AMOUNT;
	}

    $LGD_BUYER                  = $HTTP_POST_VARS["LGD_BUYER"];
    $LGD_PRODUCTINFO            = $HTTP_POST_VARS["LGD_PRODUCTINFO"];
    $LGD_BUYEREMAIL             = $HTTP_POST_VARS["LGD_BUYEREMAIL"];
    $LGD_TIMESTAMP              = date(YmdHms); 
    $LGD_BUYERID                = $HTTP_POST_VARS["LGD_BUYERID"];
    $LGD_BUYERIP                = $_SERVER["REMOTE_ADDR"];
	$LGD_CUSTOM_FIRSTPAY		= $HTTP_POST_VARS["LGD_CUSTOM_FIRSTPAY"];

	$PAYMENT_DOMAIN				= $_SERVER["SERVER_NAME"];
	$PAYMENT_TYPE				= "product";
	$PAYMENT_DEVICE				= $HTTP_POST_VARS["PAYMENT_DEVICE"];

	
	$row = sql_fetch(" select count(*) as cnt from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$LGD_BUYERID' and sr_code = '$LGD_OID'");
	if (!$row[cnt]) {
		alert("주문할 상품이 존재하지 않습니다.","$iw[m_path]/shop_cart_form.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	}
	
	$sr_buy_name = $HTTP_POST_VARS["sr_buy_name"];
	$sr_buy_phone = $HTTP_POST_VARS["sr_buy_phone_1"]."-".$HTTP_POST_VARS["sr_buy_phone_2"]."-".$HTTP_POST_VARS["sr_buy_phone_3"];
	$sr_buy_mail = $HTTP_POST_VARS["sr_buy_mail"];
	$sr_name = $HTTP_POST_VARS["sr_name"];
	$sr_phone = $HTTP_POST_VARS["sr_phone_1"]."-".$HTTP_POST_VARS["sr_phone_2"]."-".$HTTP_POST_VARS["sr_phone_3"];
	$sr_phone_sub = $HTTP_POST_VARS["sr_phone_sub_1"]."-".$HTTP_POST_VARS["sr_phone_sub_2"]."-".$HTTP_POST_VARS["sr_phone_sub_3"];
	$sr_zip_code = $HTTP_POST_VARS["sr_zip_code"];
	$sr_address = $HTTP_POST_VARS["sr_address"];
	$sr_address_sub = $HTTP_POST_VARS["sr_address_sub"];
	$sr_request = $HTTP_POST_VARS["sr_request"];
	$sr_point = $HTTP_POST_VARS["sr_point"];
	$sr_pay = "lguplus";

	$row = sql_fetch(" select mb_tel,mb_address,mb_zip_code from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'");
	if($row[mb_tel]=="010--"){
		$sql = "update $iw[member_table] set
				mb_tel = '$sr_buy_phone'
				where ep_code = '$iw[store]' and mb_code = '$iw[member]'
				";
		sql_query($sql);
	}
	if(!$row[mb_address] || !$row[mb_zip_code]){
		$sql = "update $iw[member_table] set
				mb_zip_code = '$sr_zip_code',
				mb_address = '$sr_address',
				mb_address_sub = '$sr_address_sub'
				where ep_code = '$iw[store]' and mb_code = '$iw[member]'
				";
		sql_query($sql);
	}

	$sql = "update $iw[shop_order_table] set
			sr_buy_name = '$sr_buy_name',
			sr_buy_phone = '$sr_buy_phone',
			sr_buy_mail = '$sr_buy_mail',
			sr_name = '$sr_name',
			sr_phone = '$sr_phone',
			sr_phone_sub = '$sr_phone_sub',
			sr_zip_code = '$sr_zip_code',
			sr_address = '$sr_address',
			sr_address_sub = '$sr_address_sub',
			sr_request = '$sr_request',
			sr_point = '$sr_point',
			sr_ip = '$LGD_BUYERIP',
			sr_pay = '$sr_pay',
			sr_product = '$LGD_PRODUCTINFO'
			where ep_code = '$iw[store]' and mb_code = '$LGD_BUYERID' and sr_code = '$LGD_OID'
			";
	sql_query($sql);

	$URLTORETURN		= $HTTP_POST_VARS["URL_TO_RETURN"];
	$REQURL					= $HTTP_POST_VARS["REQUEST_URL"];
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>LG유플러스 eCredit서비스</title>
</head>
<body>
	<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="https://<?=$iw[pay_site]?>/_payment/toss_payments_request.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
		<input type="hidden" name="CST_PLATFORM"                id="CST_PLATFORM"		value="<?= $CST_PLATFORM ?>">
		<input type="hidden" name="LGD_OID"                     id="LGD_OID"			value="<?= $LGD_OID ?>">
		<input type="hidden" name="LGD_BUYER"                   id="LGD_BUYER"			value="<?= $LGD_BUYER ?>">
		<input type="hidden" name="LGD_PRODUCTINFO"             id="LGD_PRODUCTINFO"	value="<?= $LGD_PRODUCTINFO ?>">
		<input type="hidden" name="LGD_AMOUNT"                  id="LGD_AMOUNT"			value="<?= $LGD_AMOUNT ?>">
		<input type="hidden" name="LGD_BUYEREMAIL"              id="LGD_BUYEREMAIL"		value="<?= $LGD_BUYEREMAIL ?>">
		<input type="hidden" name="LGD_TIMESTAMP"               id="LGD_TIMESTAMP"		value="<?= $LGD_TIMESTAMP ?>">
		<input type="hidden" name="LGD_BUYERIP"                 id="LGD_BUYERIP"		value="<?= $LGD_BUYERIP ?>">
		<input type="hidden" name="LGD_BUYERID"                 id="LGD_BUYERID"		value="<?= $LGD_BUYERID ?>">
		<input type="hidden" name="LGD_CUSTOM_FIRSTPAY"         id="LGD_CUSTOM_FIRSTPAY"value="<?= $LGD_CUSTOM_FIRSTPAY ?>">
		<input type="hidden" name="LGD_TAXFREEAMOUNT"			id="LGD_TAXFREEAMOUNT"	value="<?= $LGD_TAXFREEAMOUNT ?>">
		<input type="hidden" name="PAYMENT_DOMAIN"				id="PAYMENT_DOMAIN"		value="<?= $PAYMENT_DOMAIN ?>">
		<input type="hidden" name="PAYMENT_TYPE"				id="PAYMENT_TYPE"		value="<?= $PAYMENT_TYPE ?>">
		<input type="hidden" name="URL_TO_RETURN"				id="URL_TO_RETURN"		value="<?= $URLTORETURN ?>">
		<input type="hidden" name="REQUEST_URL"				id="REQUEST_URL"		value="<?= $REQURL ?>">
	</form>
</body>
<script language = 'javascript'>
	document.getElementById('LGD_PAYINFO').submit();
</script>
</html>



