<?php
include_once("_common.php");

$prevkey = $_POST['key'];
$postkey = "em".date("Ymd");
$chk_mon = trim($_REQUEST['mon']);
$epcode = trim($_REQUEST['epcorp']);
$taxsum = trim($_REQUEST['taxsum']);
$freesum = trim($_REQUEST['freesum']);
$ebook = trim($_REQUEST['ebook']);

$totsum = 0;
if($chk_mon=="" || $epcode=="" || $taxsum=="" || $freesum==""){
	echo '<script>alert("잘못된 접근입니다.");close();</script>';
}
$mon_ary = explode("-",$chk_mon);
$yr = $mon_ary[0];
$mo = $mon_ary[1];

if($taxsum > 0){
	$tax_charge = $taxsum * 0.1;
	$tax_pay_amt = $taxsum - $tax_charge;
	$totsum += $tax_pay_amt;
	$tax_charge = number_format($tax_charge);
	$tax_supply_amt = ceil($taxsum / 1.1);
	$tax_add_amt = $taxsum - $tax_supply_amt;
	$tax_supply_amt = number_format($tax_supply_amt);
	$tax_add_amt = number_format($tax_add_amt);
	$taxsum = number_format($taxsum);
}else{
	$tax_charge = "";
	$tax_pay_amt = "";
	$tax_supply_amt = "";
	$tax_add_amt = "";
	$taxsum = "";
}

if($ebook > 0){
	$totsum += $ebook;
	$tax_pay_amt += $ebook; 
	$ebook_pay_amt = number_format($ebook);
	$ebook_supply_amt = ceil($ebook / 1.1);
	$ebook_add_amt = $ebook - $ebook_supply_amt;
	$ebook_supply_amt = number_format($ebook_supply_amt);
	$ebook_add_amt = number_format($ebook_add_amt);
	//$taxsum = number_format($taxsum);
}else{
	$ebook_pay_amt = "";
	$ebook_supply_amt = "";
	$ebook_add_amt = "";
}
$tax_pay_amt = ($tax_pay_amt=="")?"":number_format($tax_pay_amt);

if($freesum > 0){
	$free_charge = $freesum * 0.1;
	$free_pay_amt = $freesum - $free_charge;
	$totsum += $free_pay_amt;
	$free_pay_amt = number_format($free_pay_amt);
	$free_charge = number_format($free_charge);
	$freesum = number_format($freesum);
	$totsum = number_format($totsum);
}else{
	$free_charge = "";
	$free_pay_amt = "";
	$freesum = "";
	$totsum = "";
}

//$sql = "SELECT a.ep_corporate, b.mb_mail FROM $iw[enterprise_table] a LEFT JOIN $iw[member_table] b ON a.mb_code=b.mb_code WHERE a.ep_code = '$epcode'";
$sql = "SELECT * FROM $iw[enterprise_table] WHERE ep_code = '$epcode'";
$row = sql_fetch($sql);
$corp = $row['ep_corporate'];
$to = $row['admin_email'];
//$to = "ohmaga@naver.com";
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>업체별 정산서</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
	<style type="text/css" media="print">
    @page 
    {
        size: auto;
        margin: 40px 25px 0;
    }
	</style>
<body>
<?php
$content = '
	<div id="selArea">
		<table border="1" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="text-align:center;width:700px;">
			<tbody>
				<tr>
					<td colspan="6" style="padding:5px 10px;background-color:#FFBB00;">
						<div style="font-size:1.4rem;font-weight:bold;">'.$yr.'년 '.$mo.'월 정산 내역</div>
						<div style="font-size:1.4rem;font-weight:bold;">[ '.$corp.' ]</div>
					</td>
				</tr>
				<tr>
					<td rowspan="4" style="width:16%;background-color:#D5D5D5;">면세 품목</td>
					<td style="width:16%;background-color:#D5D5D5;padding:5px 10px;padding:5px 10px;">면세</td>
					<td style="width:14%;background-color:#D5D5D5;padding:5px 10px;">수수료율</td>
					<td style="width:18%;background-color:#D5D5D5;padding:5px 10px;">유효결제금액</td>
					<td style="width:18%;background-color:#D5D5D5;padding:5px 10px;">공급가액</td>
					<td style="width:18%;background-color:#D5D5D5;padding:5px 10px;">부가세</td>
				</tr>
				<tr>
					<td style="background-color:#D5D5D5;padding:5px 10px;">합계</td>
					<td></td>
					<td style="padding:5px 10px;text-align:right;">'.$freesum.'</td>
					<td style="padding:5px 10px;text-align:right;"></td>
					<td style="padding:5px 10px;text-align:right;"></td>
				</tr>
				<tr>
					<td style="background-color:#D5D5D5;padding:5px 10px;">수수료</td>
					<td>10%</td>
					<td style="padding:5px 10px;text-align:right;">-'.$free_charge.'</td>
					<td style="padding:5px 10px;text-align:right;"></td>
					<td style="padding:5px 10px;text-align:right;"></td>
				</tr>
				<tr>
					<td style="background-color:#D5D5D5;padding:5px 10px;">지급액</td>
					<td style="background-color:#FFF612;"></td>
					<td style="padding:5px 10px;text-align:right;background-color:#FFF612;"><strong>'.$free_pay_amt.'</strong></td>
					<td style="padding:5px 10px;text-align:right;background-color:#FFF612;"></td>
					<td style="padding:5px 10px;text-align:right;background-color:#FFF612;"></td>
				</tr>

				<tr>
					<td rowspan="5" style="width:16%;background-color:#D5D5D5;">과세 품목</td>
					<td style="width:16%;background-color:#D5D5D5;padding:5px 10px;">포함</td>
					<td style="width:14%;background-color:#D5D5D5;padding:5px 10px;">수수료율</td>
					<td style="width:18%;background-color:#D5D5D5;padding:5px 10px;">유효결제금액</td>
					<td style="width:18%;background-color:#D5D5D5;padding:5px 10px;">공급가액</td>
					<td style="width:18%;background-color:#D5D5D5;padding:5px 10px;">부가세</td>
				</tr>
				<tr>
					<td style="background-color:#D5D5D5;padding:5px 10px;">합계</td>
					<td></td>
					<td style="padding:5px 10px;text-align:right;">'.$taxsum.'</td>
					<td style="padding:5px 10px;text-align:right;">'.$tax_supply_amt.'</td>
					<td style="padding:5px 10px;text-align:right;">'.$tax_add_amt.'</td>
				</tr>
				<tr>
					<td style="background-color:#D5D5D5;padding:5px 10px;">수수료</td>
					<td>10%</td>
					<td style="padding:5px 10px;text-align:right;">-'.$tax_charge.'</td>
					<td style="padding:5px 10px;text-align:right;"></td>
					<td style="padding:5px 10px;text-align:right;"></td>
				</tr>
				<tr>
					<td style="background-color:#D5D5D5;padding:5px 10px;">이북판매</td>
					<td></td>
					<td style="padding:5px 10px;text-align:right;">'.$ebook_pay_amt.'</td>
					<td style="padding:5px 10px;text-align:right;">'.$ebook_supply_amt.'</td>
					<td style="padding:5px 10px;text-align:right;">'.$ebook_add_amt.'</td>
				</tr>
				<tr>
					<td style="background-color:#D5D5D5;padding:5px 10px;">지급액</td>
					<td style="background-color:#FFF612;"></td>
					<td style="padding:5px 10px;text-align:right;background-color:#FFF612;"><strong>'.$tax_pay_amt.'</strong></td>
					<td style="padding:5px 10px;text-align:right;background-color:#FFF612;"></td>
					<td style="padding:5px 10px;text-align:right;background-color:#FFF612;"></td>
				</tr>

				<tr>
					<td colspan="2" style="padding:10px 10px;background-color:#FFF612;"><strong>합계</strong></td>
					<td colspan="2" style="padding:10px 10px;text-align:right;background-color:#FFF612;"><strong>'.$totsum.'</strong></td>
					<td colspan="2" style="padding:10px 10px;text-align:right;background-color:#FFF612;"></td>
				</tr>
			</tbody>
		</table>
	</div>
	';
	echo $content;
	
	if($prevkey == $postkey){
		$postkey = "";
		$headers = 'From: admin@info-way.co.kr'       . "\r\n" .
					'MIME-Version: 1.0' . "\r\n" .
					"Content-type:text/html;charset=UTF-8" . "\r\n"; 
		$subject = "[".$corp."] 정산 내역서를 발송합니다.";

		mail($to, $subject, $content, $headers);
		echo "<script>close();</script>";
	}
?>
	<div id="email_nt" style="display:none;"><span style="color:red;padding:10px;">잠시만 기려주세요... 메일 발송이 완료되면 자동으로 닫힙니다.</span></div>
	<div style="text-align:center;padding:20px;">
		<form name="sendmail" method="POST">
			<input type="hidden" name="key" value="<?=$postkey?>">
			<input type="hidden" name="to" value="<?=$to?>">
			<input type="hidden" name="subject" value="<?=$subject?>">

			<a onclick="win_close()" style="text-decoration:none;background-color:#5cb85c;padding: 0.5em 0.8em;color:#fff;text-align:center;vertical-align:baseline;border-radius:0.3em;cursor:pointer;">닫기</a>
			<a onclick="submit_mail()" style="text-decoration:none;background-color:#FF5E00;padding: 0.5em 0.8em;color:#fff;text-align:center;vertical-align:baseline;border-radius:0.3em;cursor:pointer;">이메일 보내기</a>
			<a onclick="fnPrint()" style="text-decoration:none;background-color:#0054FF;padding: 0.5em 0.8em;color:#fff;text-align:center;vertical-align:baseline;border-radius:0.3em;cursor:pointer;">프린트하기</a>
		</form>
	</div>
	<script src="https://code.jquery.com/jquery-latest.min.js"></script>
	<script>
	function win_close(){
		this.close();
	}
	function submit_mail(){
		if(confirm("업체에게 정산서를 보내시겠습니까?")){
			$("#email_nt").css("display","block");
			document.sendmail.submit();
		}
	}
	var fnPrint = function() {
		document.body.innerHTML = selArea.innerHTML;
		window.print();
	};
	</script>
</body>
</html>



