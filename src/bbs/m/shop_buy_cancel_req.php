<?php
include_once("_common.php");
if ($iw[level]=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

	$sr_code = $_GET["item"];

	$row = sql_fetch(" select count(*) as cnt from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and sr_code = '$sr_code' and srs_display <> 1");
	if($row[cnt]){
		alert(national_language($iw[language],"a0209","결제를 취소할 수 없습니다."),"$iw[m_path]/shop_buy_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
	}

	$sql = "select * from $iw[lgd_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and lgd_oid = '$sr_code' and lgd_display = 1";
	$row = sql_fetch($sql);
	$lgd_mid = $row["lgd_mid"];
	$lgd_tid = $row["lgd_tid"];
	$lgd_paymentkey = $row["lgd_hashdata"];
	$LGD_PRODUCTINFO = "[".national_language($iw[language],"a0284","결제취소")."]".$row["lgd_productinfo"];

    $CST_PLATFORM               = $iw["pay_platform"];
    $LGD_MID                    = $lgd_mid;
    $LGD_TID                	= $lgd_tid;
	$LGD_OID                	= $sr_code;
	$LGD_KEY                	= $lgd_paymentkey;

	$LGD_BUYERID                = $iw[member];
	$LGD_TIMESTAMP              = date(YmdHms);
	$LGD_BUYERIP                = $_SERVER["REMOTE_ADDR"];

	$PAYMENT_DOMAIN				= $_SERVER["SERVER_NAME"]; 
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>LG유플러스 eCredit서비스</title>
</head>
<body>
	<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="http://<?=$iw[pay_site]?>/_payment/toss_payments_cancel.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">
		<input type="hidden" name="CST_PLATFORM"                id="CST_PLATFORM"		value="<?= $CST_PLATFORM ?>">
		<input type="hidden" name="LGD_MID"						id="LGD_MID"			value="<?= $LGD_MID ?>">
		<input type="hidden" name="LGD_TID"						id="LGD_TID"			value="<?= $LGD_TID ?>">
		<input type="hidden" name="LGD_OID"						id="LGD_OID"			value="<?= $LGD_OID ?>">
		<input type="hidden" name="LGD_KEY"						id="LGD_KEY"			value="<?= $LGD_KEY ?>">
		<input type="hidden" name="LGD_PRODUCTINFO"             id="LGD_PRODUCTINFO"	value="<?= $LGD_PRODUCTINFO ?>">
		<input type="hidden" name="LGD_TIMESTAMP"               id="LGD_TIMESTAMP"		value="<?= $LGD_TIMESTAMP ?>">
		<input type="hidden" name="LGD_BUYERIP"                 id="LGD_BUYERIP"		value="<?= $LGD_BUYERIP ?>">
		<input type="hidden" name="LGD_BUYERID"                 id="LGD_BUYERID"		value="<?= $LGD_BUYERID ?>">
		<input type="hidden" name="PAYMENT_DOMAIN"				id="PAYMENT_DOMAIN"		value="<?= $PAYMENT_DOMAIN ?>">
	</form>
</body>
<script language = 'javascript'>
	document.getElementById('LGD_PAYINFO').submit();
</script>
</html>



