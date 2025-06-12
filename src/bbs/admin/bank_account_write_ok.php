<?php
include_once("_common.php");
if ($iw[type] != "bank" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$ac_no = trim(mysql_real_escape_string($_POST[ac_no]));
$ac_holder = trim(mysql_real_escape_string($_POST[ac_holder]));
$ac_bank = trim(mysql_real_escape_string($_POST[ac_bank]));
$ac_number = trim(mysql_real_escape_string($_POST[ac_number]));

$ac_datetime = date("Y-m-d H:i:s");

$sql = "update $iw[account_table] set
		ac_holder = '$ac_holder',
		ac_bank = '$ac_bank',
		ac_number = '$ac_number',
		ac_datetime = '$ac_datetime',
		ac_display = 0
		where ep_code = '$iw[store]' and mb_code = '$iw[member]' and ac_no = '$ac_no'
		";

sql_query($sql);

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);

$mb_mail = $row["mb_mail"];

$to = "$mb_mail";
$fmail = "infoway@info-way.co.kr";
$fname  = "=?$iw[charset]?B?" . base64_encode('위즈윈디지털') . "?=";
$subject = "=?$iw[charset]?B?" . base64_encode('[위즈윈디지털]계좌정보변경 이메일인증') . "?=";

$mail_body = "
<table width=650 border=0 cellspacing=0>
	<tr>
		<td>
		  예금주 : $ac_holder<br/>
		  은행명 : $ac_bank<br/>
		  계좌번호 : $ac_number<br/>
		  아래의 '인증하기'를 클릭하시면, 계좌정보 인증이 완료됩니다.<br/>
		  <a href='http://www.info-way.co.kr/bbs/m/all_account_confirm.php?ep=$iw[store]&gp=$iw[group]&mb=$iw[member]&date=$ac_datetime'>인증하기</a>
		</td>
	</tr>
</table>";
$mail_body = nl2br($mail_body);

$header  = "Return-Path: <$fmail>\n";
$header .= "From: $fname <$fmail>\n";
$header .= "Reply-To: <$fmail>\n";
$header .= "MIME-Version: 1.0\n";
$header .= "X-Mailer: SIR Mailer 0.92 (localhost) : $_SERVER[SERVER_ADDR] : $_SERVER[REMOTE_ADDR] : localhost : $_SERVER[PHP_SELF] : $_SERVER[HTTP_REFERER] \n";
$header .= "Content-Type: TEXT/HTML; charset=$iw[charset]\n";
$header .= "Content-Transfer-Encoding: BASE64\n\n";
$header .= chunk_split(base64_encode($mail_body)) . "\n";

$email = mail($to, $subject, "", $header);

if (!$email)
	//발송 실패시, 메세지(수정바람)
	$ma="";
else
	//발송 성공시, 메세지(수정바람)
	$ma="";

alert("$mb_mail 인증메일이 발송되었습니다.","$iw[admin_path]/bank_account_write.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>