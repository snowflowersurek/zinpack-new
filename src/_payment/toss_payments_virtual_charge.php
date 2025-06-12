<!DOCTYPE html>
<html lang="ko">
<head>
	<title>가상계좌 정보</title>
	<meta charset="utf-8"/>
	<meta http-equiv="x-ua-compatible" content="ie=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
</head>
<body>

<?php
include_once("_common.php");
include_once("_check_parameter.php");
include_once "../include/mailer.php";

global $db_conn;
if (!$db_conn) {
    // _common.php 에서 $connect_db 변수에 할당된 연결을 사용합니다.
    $db_conn = $connect_db;
}
/*
{
  "createdAt": "2022-01-01T00:00:00.000",
  "secret": "I12VUiC7geH6TTfCD5dAX",
  "status": "DONE",	// DONE(입금완료), CANCELED(입금취소), PARTIAL_CANCELED(부분취소)
  "orderId": "Gw9ImlPdh9MbSCrVCoPMF"
}
*/
$postData = file_get_contents('php://input');
$json = json_decode($postData);

$paydate = $json->createdAt;
$arydate = explode(".", $paydate);
$tdate = $arydate[0];
$tdate = str_replace("-","",$tdate);
$tdate = str_replace("T","",$tdate);
$created_date = str_replace(":","",$tdate);

if ($json->status) {
    // handle deposit result
	/*
		lgd_display 의 값에 따라 상태가 바뀌는 거 같은데, 도무지 설명해 놓은데가 없으니...
	*/
	$resultMSG = "결제결과 상점 DB처리(LGD_CASNOTEURL) 결과값을 입력해 주시기 바랍니다.";

	$status = $json->status;
	$orderid = $json->orderId;
	switch($status) {
		case "DONE": $resmsg = "입금완료"; $LGD_CASFLAG = "I";
			break;
		case "CANCELED": $resmsg = "입금취소"; $LGD_CASFLAG = "C";
			break;
		case "PARTIAL_CANCELED": $resmsg = "부분취소"; $LGD_CASFLAG = "R";
			break;
		default: $resmsg = "입금대기"; $LGD_CASFLAG = "R";
			break;
	}

	$row = null;
    $sql = "SELECT * FROM {$payment['lgd_response_table']} WHERE lgd_oid = ?";
    $stmt = mysqli_prepare($db_conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $orderid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

	$payment_domain = $row["payment_domain"];
	$payment_device = $row["payment_device"];
	$payment_type = $row["payment_type"];
	$LGD_PRODUCTINFO = $row["lgd_productinfo"];
	$LGD_BUYER = $row["lgd_buyer"];
	$LGD_AMOUNT = $row["lgd_amount"];
	$LGD_FINANCENAME = $row["lgd_accountowner"];
	$lgd_datetime = $row["lgd_datetime"];
	$ep_code = $row["ep_code"];
	$mb_code = $row["mb_code"];

	if($status == "DONE") {

		mysqli_select_db($db_conn, "infoway");

        $row = null;
        $sql = "select * from iw_lgd where ep_code = ? and mb_code = ? and lgd_oid = ?";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $ep_code, $mb_code, $orderid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

		$chk_castamount = $row["lgd_cascamount"];
		$chk_cascamount = $row["lgd_cascamount"].";".$LGD_CASCAMOUNT;	// 가상계좌 입금이 부분 입금으로 진행될 수도 있는건가 ???
		$chk_casflag = $row["lgd_casflag"].";".$LGD_CASFLAG;	// 무통장입금 플래그(무통장입금) - 'R':계좌할당, 'I':입금, 'C':입금취소
		$chk_display = $row["lgd_display"];

		mysqli_select_db($db_conn, "payment");
		$sql = "update {$payment['lgd_response_table']} set 
				lgd_castamount = ?,
				lgd_respmsg = ?,
				lgd_paydate = ?,
				lgd_timestamp = ?,
				lgd_casflag = ?
				where ep_code = ? and mb_code = ? and lgd_oid = ?
				";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssss", $chk_castamount, $resmsg, $created_date, $created_date, $chk_casflag, $ep_code, $mb_code, $orderid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);


		mysqli_select_db($db_conn, "infoway");
		$sql = "update iw_lgd set
				lgd_castamount = ?,
				lgd_display = 1,
				lgd_respmsg = ?,
				lgd_paydate = ?,
				lgd_timestamp = ?,
				lgd_casflag = ? 
				where ep_code = ? and mb_code = ? and lgd_oid = ? 
				";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $chk_castamount, $resmsg, $created_date, $created_date, $chk_casflag, $ep_code, $mb_code, $orderid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

		$sql = "update iw_charge set
				ch_result = '1' 
				where ogd_oid = ? 
				";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $orderid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

		//관리자에게 메일
        $row2 = null;
        $sql = "select st_title from iw_setting where ep_code = ? and gp_code = 'all'";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $ep_code);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row2 = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
		$st_title = $row2["st_title"];

        $row2 = null;
        $sql = "select * from iw_enterprise where ep_code = ?";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $ep_code);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row2 = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
		$ep_corp = $row2["ep_corporate"];
		$st_mb_code = $row2["mb_code"];
		$pre_expiry_date = $row2["ep_expiry_date"];

		$pre_expiry_date_ary = explode("-",$pre_expiry_date);
		$pre_expiry_year = $pre_expiry_date_ary[0];
		$new_expiry_date = (intval($pre_expiry_year) + 1)."-".$pre_expiry_date_ary[1]."-".$pre_expiry_date_ary[2];
		$sql = "update iw_enterprise set
							ep_expiry_date = ?,
							avail_type = '1'
							where ep_code = ?
						";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $new_expiry_date, $ep_code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $row2 = null;
        $sql = "select mb_mail, mb_sub_mail from iw_member where ep_code = ? and mb_code = ?";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $ep_code, $st_mb_code);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row2 = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
		//$to = $row2["mb_mail"];
		$to = "zinfo@wizwindigital.com";
		//$from = "no-reply@".preg_replace('/^www\./', '', $payment_domain);
		$from = "no-reply@wizwindigital.com";
		$fromName = "진팩";
		$subject = $ep_corp.' 사이트관리비 입금이 완료되었습니다.';
		$content = '
			<div style="padding:20px;font-family:Arial,\'Apple SD Gothic Neo\',\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum;">
				<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;text-align:left;">
					<tbody>
						<tr>
							<td style="font-size:24px;line-height:34px;color:#333;"><strong>'.$ep_corp.'</strong>사이트관리비 입금이 완료되었습니다.</td>
						</tr>
						<tr>
							<td height="32"></td>
						</tr>
						<tr>
							<td style="border-top:1px solid #333;">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">입금일시</td>
											<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$lgd_datetime.'</td>
										</tr>
										<tr>
											<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문상품</td>
											<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$LGD_PRODUCTINFO.'</td>
										</tr>
										<tr>
											<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">연장 유효기간</td>
											<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$new_expiry_date.'</td>
										</tr>
										<tr>
											<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">주문회원</td>
											<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$LGD_BUYER.'</td>
										</tr>
										<tr>
											<td width="150" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">결제금액</td>
											<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">
												<span style="font-size:18px;font-weight:bold;color:#df3926;">'.number_format($LGD_AMOUNT).'원</span>
												<span style="margin:0 4px;font-size:13px;"> ('.$LGD_FINANCENAME.' 가상계좌)</span>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		';
		$resultMSG = "COMPLETED";
		mailer($fromName, $from, $to, $subject, $content, true);

	} else if($status == "CANCELED") {

		mysqli_select_db($db_conn, "infoway");
        $row = null;
        $sql = "select * from iw_lgd where ep_code = ? and mb_code = ? and lgd_oid = ?";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $ep_code, $mb_code, $orderid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

		$chk_castamount = $row["lgd_castamount"];
		$chk_cascamount = $row["lgd_cascamount"].";-".$LGD_CASCAMOUNT;
		$chk_casflag = $row["lgd_casflag"].";".$LGD_CASFLAG;
		$chk_display = $row["lgd_display"];

		mysqli_select_db($db_conn, "payment");
		$sql = "update {$payment['lgd_response_table']} set
				lgd_respmsg = ?,
				lgd_paydate = ?,
				lgd_timestamp = ?,
				lgd_casflag = ?
				where ep_code = ? and mb_code = ? and lgd_oid = ?
				";
        $stmt = mysqli_prepare($db_conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $resmsg, $created_date, $created_date, $chk_casflag, $ep_code, $mb_code, $orderid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);


		if($chk_display == 1){
			mysqli_select_db($db_conn, "infoway");
			$sql = "update iw_lgd set
					lgd_display = 5,
					lgd_respmsg = ?,
					lgd_paydate = ?,
					lgd_timestamp = ?,
					lgd_casflag = ?
					where ep_code = ? and mb_code = ? and lgd_oid = ?
					";
            $stmt = mysqli_prepare($db_conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssss", $resmsg, $created_date, $created_date, $chk_casflag, $ep_code, $mb_code, $orderid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);


			$cdatetime = date("Y-m-d H:i:s");
			$sql = "update iw_charge set
								cancel_datetime = ?,
								ch_result = '5'
								where ogd_oid = ?
							";
            $stmt = mysqli_prepare($db_conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $cdatetime, $orderid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $row2 = null;
            $sql = "select * from iw_enterprise where ep_code = ?";
            $stmt = mysqli_prepare($db_conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $ep_code);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row2 = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

			$st_mb_code = $row2["mb_code"];
			$pre_expiry_date = $row2["ep_expiry_date"];

			$pre_expiry_date_ary = explode("-",$pre_expiry_date);
			$pre_expiry_year = $pre_expiry_date_ary[0];
			$new_expiry_date = (intval($pre_expiry_year) - 1)."-".$pre_expiry_date_ary[1]."-".$pre_expiry_date_ary[2];
			$sql = "update iw_enterprise set
								ep_expiry_date = ?,
								avail_type = '4'
								where ep_code = ?
							";
            $stmt = mysqli_prepare($db_conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $new_expiry_date, $ep_code);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
		}
		//if( 무통장 입금취소 성공 상점처리결과 성공 ) 
		$resultMSG = "CANCELED";
	}
}
echo $resultMSG;
?>

</body>
</html>

