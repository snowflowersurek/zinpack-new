<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?

function get_param($name){
	global $_POST, $_GET;
	if (!isset($_POST[$name]) || $_POST[$name] == "") {
		if (!isset($_GET[$name]) || $_GET[$name] == "") {
			return false;
		} else {
			 return $_GET[$name];
		}
	}
	return $_POST[$name];
}

$sr_code = trim(mysql_real_escape_string($_POST[sr_code]));
$ct_status = trim(mysql_real_escape_string($_POST[ct_status]));
$chk_cnt = trim(mysql_real_escape_string($_POST[chk_cnt]));

//$row = sql_fetch(" select * from $iw[shop_order_table] where ep_code = '$iw[store]' and sr_code = '$sr_code' ");
$psql = "SELECT a.*, b.* FROM $iw[shop_order_table] a LEFT JOIN $iw[shop_order_sub_table] b ON a.sr_code=b.sr_code WHERE a.ep_code = '$iw[store]' and a.sr_code = '$sr_code'";
$row = sql_fetch($psql);
if ($row[sr_display] == 7) {
	alert("구매자가 결제취소한 상품입니다.","$iw[admin_path]/shop_post_store_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
}

$esql = "SELECT * FROM $iw[lgd_table] WHERE lgd_oid = '$sr_code'";
$erow = sql_fetch($esql);

$srs_display = $ct_status;

if($srs_display == 3){

	// 여기부터 에스크로
	$service_url = "https://pgweb.tosspayments.com/pg/wmp/mertadmin/jsp/escrow/rcvdlvinfo.jsp";		// for SERVICE
	//$service_url = "http://pgweb.tosspayments.com:7085/pg/wmp/mertadmin/jsp/escrow/rcvdlvinfo.jsp";		// for TEST
	$mid = "wiz2016";
	$mertkey = "50a2213850df98c1a75a48d26f18abb5";
	//$mertkey = "live_ck_YyZqmkKeP8gkX0omXZK8bQRxB9lG";
	//$mertkey = "test_ck_YoEjb0gm23Pg1lQ9wBW8pGwBJn5e";
	$oid = $sr_code;
		$sdcode = $row['sd_code'];
		$psql = "SELECT * FROM $iw[shop_data_table] WHERE sd_code = '$sdcode'";
		$prow = sql_fetch($psql);
	$productid = get_param($prow['sd_code']);
		$orderdate = str_replace("-","",$row['sr_datetime']);
		$orderdate = str_replace(":","",$orderdate);
		$orderdate = str_replace(" ","",$orderdate);
	$orderdate = substr($orderdate, 0, 12);
	$dlvtype = "03";			// Appendix에 명시되지 않은 택배사인 경우에는 "01"
	$rcvdate = "";				// 실수령일자
	$rcvname = "";				// 실수령인명
	$rcvrelation = "";		// 관계
	$dlvworker = "";			// 배송자명
	$dlvworkertel = "";		// 배송자전화번호

	for ($i=0; $i<$chk_cnt; $i++) {
		$srs_no = trim(mysql_real_escape_string($_POST[srs_no][$i]));
		$dlvcompcode = trim(mysql_real_escape_string($_POST[dlv_comp_code][$i]));
		$dlvno = trim(mysql_real_escape_string($_POST[srs_delivery_num][$i]));
		$dlv_datetime = mysql_real_escape_string($_POST[dlv_datetime][$i]);
		$dlvdate = str_replace("-","",$dlv_datetime)."1600";
		$dlv_datetime = $dlv_datetime . " 16:00:00";
		if($dlvcompcode=="OT"){		// Appendix에 명시되지 않은 배송수단 (OT: 우체국 우편)
			//$dlvcompcode = "";
			$dlvno = "";
			$dlvtype = "01";
			$rcvdate = date("YmdHis", strtotime($dlv_datetime . ", +5 days"));  // 수령일자: 발송($dlv_datetime) 5일 후
			$rcvname = $row['sr_name'];
			$rcvrelation = "본인";
			$hashdata = md5($mid.$oid.$dlvtype.$rcvdate.$mertkey);
		}else{
			$dlvno = str_replace("-","",$dlvno);
			$dlvtype = "03";
			$rcvdate = "";  // 수령일자
			$rcvname = "";
			$rcvrelation = "";
			$hashdata = md5($mid.$oid.$dlvdate.$dlvcompcode.$dlvno.$mertkey);
		}

		// 여기부터 에스크로
		if(trim($row['api_status'])!="OK" && $erow['lgd_escrow']=='1'){
			$str_url = $service_url."?mid=$mid&oid=$oid&productid=$productid&orderdate=$orderdate&dlvtype=$dlvtype&rcvdate=$rcvdate&rcvname=$rcvname&rcvrelation=$rcvrelation&dlvdate=$dlvdate&dlvcompcode=$dlvcompcode&dlvno=$dlvno&dlvworker=$dlvworker&dlvworkertel=$dlvworkertel&hashdata=$hashdata"; 
			//echo $str_url; exit;

			$ch = curl_init(); 
			curl_setopt ($ch, CURLOPT_URL, $str_url); 
			curl_setopt ($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');  //서버에서 보낸 쿠키정보를 작성할 파일 경로
			curl_setopt ($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt'); //서버에 전송할 쿠키경로를 설정(상기값과 동일)
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			$fp = curl_exec ($ch);
			if(curl_errno($ch)){
				// 연결실패시 DB 처리 로직 추가
				// echo "error!";
				$sql = "update $iw[shop_order_sub_table] set api_status = 'CONNECTION ERROR' where ep_code = '$iw[store]' and seller_mb_code = '$iw[member]' and srs_no = '$srs_no'";
					sql_query($sql);
			}else{
				if(trim($fp)=="OK"){
					// 정상처리되었을때 DB 처리
					//echo "OK!";
					$sql = "update $iw[shop_order_sub_table] set api_status = 'OK' where ep_code = '$iw[store]' and seller_mb_code = '$iw[member]' and srs_no = '$srs_no'";
					sql_query($sql);
				}else{
					// 비정상처리 되었을때 DB 처리
					// echo "NOT OK!";
					$sql = "update $iw[shop_order_sub_table] set api_status = 'FAILED' where ep_code = '$iw[store]' and seller_mb_code = '$iw[member]' and srs_no = '$srs_no'";
					sql_query($sql);
				}
			}
			curl_close($ch);
		}
		// 여기까지 에스크로

		$sql = "update $iw[shop_order_sub_table] set 
			srs_display = '$srs_display',
			srs_delivery = '$dlvcompcode',
			srs_delivery_num = '$dlvno',
			srs_delivery_dt = '$dlv_datetime'
			where ep_code = '$iw[store]' and seller_mb_code = '$iw[member]' and srs_no = '$srs_no'
			";
		sql_query($sql);
	}
	$osql = "update $iw[shop_order_table] set sr_display = '$srs_display' where sr_code = '$sr_code' ";
	sql_query($osql);
	
}else{
	for ($i=0; $i<$chk_cnt; $i++) {
		$srs_no = trim(mysql_real_escape_string($_POST[srs_no][$i]));
		$ct_chk = trim(mysql_real_escape_string($_POST[ct_chk][$i]));
		if ($ct_chk == 1){
			$sql = "update $iw[shop_order_sub_table] set
				srs_display = '$srs_display'
				where ep_code = '$iw[store]' and seller_mb_code = '$iw[member]' and srs_no = '$srs_no'
				";
			sql_query($sql);
		}
	}
	$osql = "update $iw[shop_order_table] set sr_display = '$srs_display' where sr_code = '$sr_code' ";
	sql_query($osql);
}
goto_url("$iw[admin_path]/shop_post_store_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$sr_code");
?>