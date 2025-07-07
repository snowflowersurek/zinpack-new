<?php
include_once("_common_guest.php");
require_once("alipay_notify.php");
require_once("alipay_config.php");
$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->notify_verify();
if($verify_result) {
	
	$ALIPAY_total_fee = trim(sql_real_escape_string($_POST['total_fee']))*1000;			//결제금액
	$ALIPAY_out_trade_no = trim(sql_real_escape_string($_POST['out_trade_no']));			//주문번호
	$ALIPAY_trade_no	= trim(sql_real_escape_string($_POST['trade_no']));				//페이팔 거래번호
	$ALIPAY_trade_status = trim(sql_real_escape_string($_POST['trade_status']));			//결제결과
	$ALIPAY_currency = trim(sql_real_escape_string($_POST['currency']));					//통화(달러)
	$ALIPAY_member_id = trim(sql_real_escape_string($_GET['mb']));					//구매자아이디

	if($ALIPAY_total_fee = 9990){
		$ALIPAY_item_name = 650;
	}else if($ALIPAY_total_fee = 19990){
		$ALIPAY_item_name = 1310;
	}else if($ALIPAY_total_fee = 29990){
		$ALIPAY_item_name = 2030;
	}else if($ALIPAY_total_fee = 39990){
		$ALIPAY_item_name = 2690;
	}else if($ALIPAY_total_fee = 49990){
		$ALIPAY_item_name = 3360;
	}else if($ALIPAY_total_fee = 99990){
		$ALIPAY_item_name = 6800;
	}else if($ALIPAY_total_fee = 199990){
		$ALIPAY_item_name = 13500;
	}
	
	$pt_datetime = date("Y-m-d H:i:s");
	$sql = "insert into $iw[alipay_table] set
			ep_code = '$iw[store]',
			mb_code = '$ALIPAY_member_id',
			state_sort = '$iw[type]',
			ap_out_trade_no = '$ALIPAY_out_trade_no',
			ap_currency = '$ALIPAY_currency',
			ap_total_fee = '$ALIPAY_total_fee',
			ap_trade_no = '$ALIPAY_trade_no',
			ap_trade_status = '$ALIPAY_trade_status',
			ap_item_name = '$ALIPAY_item_name',
			ap_datetime = '$pt_datetime',
			ap_display = 0
			";
	sql_query($sql);

	if ($ALIPAY_trade_status=='TRADE_FINISHED' || $ALIPAY_trade_status=='TRADE_SUCCESS') {
		$row = sql_fetch(" select count(*) as cnt from $iw[alipay_table] where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$ALIPAY_member_id' and ap_out_trade_no = '$ALIPAY_out_trade_no' and ap_display = 1");
		if (!$row[cnt]) {
			$sql = "update $iw[alipay_table] set
					ap_display = 1
					where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$ALIPAY_member_id' and ap_out_trade_no = '$ALIPAY_out_trade_no' and ap_trade_no = '$ALIPAY_trade_no'
					";
			sql_query($sql);

			//회원정보 포인트 추가
			$sql = "update $iw[member_table] set
					mb_point = mb_point+$ALIPAY_item_name
					where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id'
					";
			sql_query($sql);
			
			$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id'");
			$mb_point = $row["mb_point"];
			$pt_content = "Point charged ".national_money("en", $PAYPAL_mc_gross);

			$sql = "insert into $iw[point_table] set
					ep_code = '$iw[store]',
					mb_code = '$ALIPAY_member_id',
					state_sort = '$iw[type]',
					pt_deposit = '$ALIPAY_item_name',
					pt_balance = '$mb_point',
					pt_content = '$pt_content',
					pt_datetime = '$pt_datetime'
					";
			sql_query($sql);
		}
	}

	echo "success";
	log_result("verify_success");
}
else  {
	echo "fail";
	log_result ("verify_failed");
}
function  log_result($word) {
	$fp = fopen("alipay_log.txt","a");	
	flock($fp, LOCK_EX) ;
	fwrite($fp,$word."：execution date ：".strftime("%Y%m%d%H%I%S",time())."\t\n");
	flock($fp, LOCK_UN); 
	fclose($fp);
}
	
?>



