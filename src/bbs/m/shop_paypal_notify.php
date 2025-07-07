<?php
include_once("_common_guest.php");
/**
 *  PHP-PayPal-IPN Example
 *
 *  This shows a basic example of how to use the IpnListener() PHP class to 
 *  implement a PayPal Instant Payment Notification (IPN) listener script.
 *
 *  For a more in depth tutorial, see my blog post:
 *  http://www.micahcarrick.com/paypal-ipn-with-php.html
 *
 *  This code is available at github:
 *  https://github.com/Quixotix/PHP-PayPal-IPN
 *
 *  @package    PHP-PayPal-IPN
 *  @author     Micah Carrick
 *  @copyright  (c) 2011 - Micah Carrick
 *  @license    http://opensource.org/licenses/gpl-3.0.html
 */
 
 
/*
Since this script is executed on the back end between the PayPal server and this
script, you will want to log errors to a file or email. Do not try to use echo
or print--it will not work! 

Here I am turning on PHP error logging to a file called "ipn_errors.log". Make
sure your web server has permissions to write to that file. In a production 
environment it is better to have that log file outside of the web root.
*/
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/paypal_ipn_errors.log');


// instantiate the IpnListener class
include('paypal_ipnlistener.php');
$listener = new IpnListener();


/*
When you are testing your IPN script you should be using a PayPal "Sandbox"
account: https://developer.paypal.com
When you are ready to go live change use_sandbox to false.
*/
$listener->use_sandbox = false;

/*
By default the IpnListener object is going  going to post the data back to PayPal
using cURL over a secure SSL connection. This is the recommended way to post
the data back, however, some people may have connections problems using this
method. 

To post over standard HTTP connection, use:
$listener->use_ssl = false;

To post using the fsockopen() function rather than cURL, use:
$listener->use_curl = false;
*/

/*
The processIpn() method will encode the POST variables sent by PayPal and then
POST them back to the PayPal server. An exception will be thrown if there is 
a fatal error (cannot connect, your server is not configured properly, etc.).
Use a try/catch block to catch these fatal errors and log to the ipn_errors.log
file we setup at the top of this file.

The processIpn() method will send the raw data on 'php://input' to PayPal. You
can optionally pass the data to processIpn() yourself:
$verified = $listener->processIpn($my_post_data);
*/
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(0);
}

/*
The processIpn() method returned true if the IPN was "VERIFIED" and false if it
was "INVALID".
*/
error_log("SHOP(".$_POST['payment_status'].")".$_POST['business']."(".$_POST['custom'].")".$_POST['txn_id']."(".$_POST['mc_gross'].")");
if ($verified) {
	
	$PAYPAL_business = trim(sql_real_escape_string($_POST['business']));						//페이팔 발급 아이디
	$PAYPAL_mc_gross = trim(sql_real_escape_string($_POST['mc_gross']))*1000;					//결제금액
	$PAYPAL_invoice = trim(sql_real_escape_string($_POST['invoice']));							//주문번호
	$PAYPAL_txn_id	= trim(sql_real_escape_string($_POST['txn_id']));							//페이팔 거래번호
	$PAYPAL_item_name = trim(sql_real_escape_string($_POST['item_name']));						//상품정보
	$PAYPAL_payment_status = trim(sql_real_escape_string($_POST['payment_status']));			//결제결과
	$PAYPAL_custom = trim(sql_real_escape_string($_POST['custom']));							//구매자아이디

	$sr_datetime = date("Y-m-d H:i:s");
	$sql = "insert into $iw[paypal_table] set
			ep_code = '$iw[store]',
			mb_code = '$PAYPAL_custom',
			state_sort = '$iw[type]',
			pp_invoice = '$PAYPAL_invoice',
			pp_txn_id = '$PAYPAL_txn_id',
			pp_payment_status = '$PAYPAL_payment_status',
			pp_business = '$PAYPAL_business',
			pp_mc_gross = '$PAYPAL_mc_gross',
			pp_item_name = '$PAYPAL_item_name',
			pp_datetime = '$sr_datetime',
			pp_display = 0
			";
	sql_query($sql);

	if ($PAYPAL_payment_status=='Completed') {
		$row = sql_fetch(" select count(*) as cnt from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$PAYPAL_custom' and sr_code = '$PAYPAL_invoice' and sr_display = 0");
		if ($row[cnt]) {
			//최종결제요청 결과 성공 DB처리
			
			$row = sql_fetch(" select sr_point from $iw[shop_order_table] where ep_code = '$iw[store]' and mb_code = '$PAYPAL_custom' and sr_code = '$PAYPAL_invoice'");
			$sr_point = $row["sr_point"];
			if($sr_point > 0){
				$row = sql_fetch(" select mb_point from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$PAYPAL_custom'");
				$mb_point = $row["mb_point"];
				//포인트 차감 내역
				$sql = "insert into $iw[point_table] set
						ep_code = '$iw[store]',
						mb_code = '$PAYPAL_custom',
						state_sort = '$iw[type]',
						pt_withdraw = '$sr_point',
						pt_balance = $mb_point-$sr_point,
						pt_content = '$pp_item_name',
						pt_datetime = '$sr_datetime'
						";
				sql_query($sql);
				
				//회원정보 포인트 차감
				$sql = "update $iw[member_table] set
						mb_point = mb_point-$sr_point
						where ep_code = '$iw[store]' and mb_code = '$PAYPAL_custom'
						";
				sql_query($sql);
			}
		
			$sql = "update $iw[shop_order_table] set
					sr_datetime = '$sr_datetime',
					sr_display = 1
					where ep_code = '$iw[store]' and mb_code = '$PAYPAL_custom' and sr_code = '$PAYPAL_invoice'
					";
			sql_query($sql);

			$sql = "update $iw[shop_order_sub_table] set
					srs_display = 1
					where ep_code = '$iw[store]' and mb_code = '$PAYPAL_custom' and sr_code = '$PAYPAL_invoice'
					";
			sql_query($sql);

			$sql = "update $iw[paypal_table] set
					pp_display = 1
					where ep_code = '$iw[store]' and state_sort = '$iw[type]' and mb_code = '$PAYPAL_custom' and pp_invoice = '$PAYPAL_invoice' and pp_txn_id = '$PAYPAL_txn_id'
					";
			sql_query($sql);
			
			$sql = "delete from $iw[shop_cart_table] where ep_code = '$iw[store]' and mb_code = '$PAYPAL_custom'";
			sql_query($sql);

			$sql = "select * from $iw[shop_order_sub_table] where ep_code = '$iw[store]' and mb_code = '$PAYPAL_custom' and sr_code = '$PAYPAL_invoice'";
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
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">



