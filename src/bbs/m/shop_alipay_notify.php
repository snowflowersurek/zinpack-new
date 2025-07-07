<?php
include_once("_common.php");
include_once("$iw_path/include/alipay_notify.class.php");

$alipay_config = require_once("$iw_path/include/alipay.config.php");

$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

$ALIPAY_total_fee = trim(sql_real_escape_string($_POST['total_fee']))*1000;			//결제금액
$ALIPAY_out_trade_no = trim(sql_real_escape_string($_POST['out_trade_no']));			//주문번호
$ALIPAY_trade_no	= trim(sql_real_escape_string($_POST['trade_no']));				//페이팔 거래번호
$ALIPAY_trade_status = trim(sql_real_escape_string($_POST['trade_status']));			//결제결과
$ALIPAY_currency = trim(sql_real_escape_string($_POST['currency']));					//통화(달러)
$ALIPAY_member_id = trim(sql_real_escape_string($_GET['mb']));							//구매자아이디

$row = sql_fetch(" select sr_product from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id' and sr_code = '$ALIPAY_out_trade_no'");
$ALIPAY_item_name = $row["sr_product"];

$sr_datetime = date("Y-m-d H:i:s");
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
		ap_datetime = '$sr_datetime',
		ap_display = 0
		";
sql_query($sql);


if ($ALIPAY_trade_status=='TRADE_FINISHED' || $ALIPAY_trade_status=='TRADE_SUCCESS') {
	$row = sql_fetch(" select count(*) as cnt from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id' and sr_code = '$ALIPAY_out_trade_no' and sr_display = 0");
	if ($row[cnt]) {
		//최종결제요청 결과 성공 DB처리
		
		$row = sql_fetch(" select sr_point from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id' and sr_code = '$ALIPAY_out_trade_no' ");
		$sr_point = $row["sr_point"];
		if($sr_point > 0){
			$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id'");
			$mb_point = $row["mb_point"];
			//포인트 차감 내역
			$sql = "insert into $iw[point_table] set
					ep_code = '$iw[store]',
					mb_code = '$ALIPAY_member_id',
					state_sort = '$iw[type]',
					pt_withdraw = '$sr_point',
					pt_balance = $mb_point-$sr_point,
					pt_content = '$ALIPAY_item_name',
					pt_datetime = '$sr_datetime'
					";
			sql_query($sql);
			
			//회원정보 포인트 차감
			$sql = "update $iw[member_table] set
					mb_point = mb_point-$sr_point
					where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id'
					";
			sql_query($sql);
		}
	
		$sql = "update $iw[shop_order_table] set
				sr_datetime = '$sr_datetime',
				sr_display = 1
				where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id' and sr_code = '$ALIPAY_out_trade_no'
				";
		sql_query($sql);

		$sql = "update $iw[shop_order_sub_table] set
				srs_display = 1
				where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id' and sr_code = '$ALIPAY_out_trade_no'
				";
		sql_query($sql);

		$sql = "update $iw[alipay_table] set
				ap_display = 1
				where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$ALIPAY_member_id' and ap_out_trade_no = '$ALIPAY_out_trade_no' and ap_trade_no = '$ALIPAY_trade_no'
				";
		sql_query($sql);
		
		$sql = "delete from $iw[shop_cart_table] where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id'";
		sql_query($sql);

		$sql = "select * from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$ALIPAY_member_id' and sr_code = '$ALIPAY_out_trade_no'";
		$result = sql_query($sql);

		while($row = @sql_fetch_array($result)){
			$sd_code = $row["sd_code"];
			$so_no = $row["so_no"];
			$srs_amount = $row["srs_amount"];

			$sql = "update $iw[shop_option_table] set
					so_amount = so_amount-$srs_amount
					where ep_code = '$iw[store]' and so_no = '$so_no' and sd_code = '$sd_code'
					";
			sql_query($sql);

			$sql = "update $iw[shop_data_table] set
					sd_sell = sd_sell+$srs_amount
					where ep_code = '$iw[store]' and sd_code = '$sd_code'
					";
			sql_query($sql);
		}
	}
}

echo "success";
log_result("verify_success");

function  log_result($word) {
	$fp = fopen("alipay_log.txt","a");	
	flock($fp, LOCK_EX) ;
	fwrite($fp,$word."：execution date ：".strftime("%Y%m%d%H%I%S",time())."\t\n");
	flock($fp, LOCK_UN); 
	fclose($fp);
}
	
?>



