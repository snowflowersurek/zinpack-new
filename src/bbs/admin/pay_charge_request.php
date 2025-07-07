<?php
include_once("_common.php");
if ($iw[level]=="guest") alert("로그인 해주시기 바랍니다. ","$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

//var_dump($_POST);exit;
/*
array(7) { 
	["PAYMENT_DEVICE"]=> string(2) "pc"
	["LGD_BUYER"]=> string(12) "박비안네" 
	["LGD_BUYERID"]=> string(24) "mb338901927609cab5915cc6" 
	["LGD_PRODUCTINFO"]=> string(18) "사이트이용료" 
	["LGD_BUYEREMAIL"]=> string(29) "ddoddotears@wizwindigital.com" 
	["LGD_AMOUNT"]=> string(6) "300000"
	["URL_TO_RETURN"]=> string(102) "http://www.info-way.co.kr/bbs/admin/pay_charge_res.php?type=charge&ep=ep1822322763609cab5915c89&gp=all" 
	["REQUEST_URL"]=> string(98) "http://www.info-way.co.kr/bbs/admin/pay_charge.php?type=charge&ep=ep1822322763609cab5915c89&gp=all" 
	["LGD_CUSTOM_FIRSTPAY"]=> string(6) "SC0010" 
}
*/
	$CST_PLATFORM           = $iw["pay_platform"];
	$LGD_OID                = "cg".uniqid(rand());
	$LGD_AMOUNT             = $HTTP_POST_VARS["LGD_AMOUNT"];
	$LGD_BUYER              = $HTTP_POST_VARS["LGD_BUYER"];
	$LGD_PRODUCTINFO        = $HTTP_POST_VARS["LGD_PRODUCTINFO"];
	$LGD_BUYEREMAIL         = $HTTP_POST_VARS["LGD_BUYEREMAIL"];
	$LGD_TIMESTAMP          = date(YmdHms); 
	$LGD_BUYERID            = $HTTP_POST_VARS["LGD_BUYERID"];
	$LGD_BUYERIP            = $_SERVER["REMOTE_ADDR"];
	$LGD_CUSTOM_FIRSTPAY		= $HTTP_POST_VARS["LGD_CUSTOM_FIRSTPAY"];

	$PAYMENT_DOMAIN					= $_SERVER["SERVER_NAME"];
	$PAYMENT_TYPE						= "charge";
	$PAYMENT_DEVICE					= $HTTP_POST_VARS["PAYMENT_DEVICE"];

	$URLTORETURN						= $HTTP_POST_VARS["URL_TO_RETURN"];
	$REQURL									= $HTTP_POST_VARS["REQUEST_URL"];
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>TOSS eCredit서비스</title>
</head>
<body>
	<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="https://<?=$iw[pay_site]?>/_payment/toss_payments_request.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
		<input type="hidden" name="CST_PLATFORM"          id="CST_PLATFORM"					value="<?= $CST_PLATFORM ?>">
		<input type="hidden" name="LGD_OID"               id="LGD_OID"							value="<?= $LGD_OID ?>">
		<input type="hidden" name="LGD_BUYER"             id="LGD_BUYER"						value="<?= $LGD_BUYER ?>">
		<input type="hidden" name="LGD_PRODUCTINFO"       id="LGD_PRODUCTINFO"			value="<?= $LGD_PRODUCTINFO ?>">
		<input type="hidden" name="LGD_AMOUNT"            id="LGD_AMOUNT"						value="<?= $LGD_AMOUNT ?>">
		<input type="hidden" name="LGD_BUYEREMAIL"        id="LGD_BUYEREMAIL"				value="<?= $LGD_BUYEREMAIL ?>">
		<input type="hidden" name="LGD_TIMESTAMP"         id="LGD_TIMESTAMP"				value="<?= $LGD_TIMESTAMP ?>">
		<input type="hidden" name="LGD_BUYERIP"           id="LGD_BUYERIP"					value="<?= $LGD_BUYERIP ?>">
		<input type="hidden" name="LGD_BUYERID"           id="LGD_BUYERID"					value="<?= $LGD_BUYERID ?>">
		<input type="hidden" name="LGD_CUSTOM_FIRSTPAY"   id="LGD_CUSTOM_FIRSTPAY"	value="<?= $LGD_CUSTOM_FIRSTPAY ?>">
		<input type="hidden" name="PAYMENT_DOMAIN"				id="PAYMENT_DOMAIN"				value="<?= $PAYMENT_DOMAIN ?>">
		<input type="hidden" name="PAYMENT_TYPE"					id="PAYMENT_TYPE"					value="<?= $PAYMENT_TYPE ?>">
		<input type="hidden" name="URL_TO_RETURN"					id="URL_TO_RETURN"				value="<?= $URLTORETURN ?>">
		<input type="hidden" name="REQUEST_URL"						id="REQUEST_URL"					value="<?= $REQURL ?>">
	</form>
</body>
<script language = 'javascript'>
	document.getElementById('LGD_PAYINFO').submit();
</script>
</html>



