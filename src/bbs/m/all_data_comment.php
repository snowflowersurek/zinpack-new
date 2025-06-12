<?php
include_once("_common.php");
if ($iw[level] == "guest") alert(national_language($iw[language],"a0032","로그인 후 이용해주세요"),"");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$cm_code = trim(mysql_real_escape_string($_POST[cm_code]));
$cm_content = mysql_real_escape_string($_POST[cm_content]);
$cm_recomment = trim(mysql_real_escape_string($_POST[cm_recomment]));
$cm_secret = trim(mysql_real_escape_string($_POST[cm_secret]));

if (!$cm_secret) {
	$cm_secret = 0;
}

if($iw[type]=="shop"){
	$row = sql_fetch(" select mb_code,sd_subject from $iw[shop_data_table] where ep_code = '$iw[store]' and sd_display = 1 and sd_code = '$cm_code' ");
	$mail_title = $row[sd_subject];
}else if($iw[type]=="doc"){
	$row = sql_fetch(" select mb_code,dd_subject from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and dd_display = 1 and dd_code = '$cm_code' ");
	$mail_title = $row[dd_subject];
}else if($iw[type]=="mcb"){
	$row = sql_fetch(" select mb_code,md_subject from $iw[mcb_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and md_display = 1 and md_code = '$cm_code' ");
	$mail_title = $row[md_subject];
}else if($iw[type]=="publishing"){
	$row = sql_fetch(" select mb_code,BookName from $iw[publishing_books_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and book_display = 1 and BookID = '$cm_code' ");
	$mail_title = $row[BookName];
}else if($iw[type]=="book"){
	$row = sql_fetch(" select mb_code,bd_subject from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and bd_display = 1 and bd_code = '$cm_code' ");
	$mail_title = $row[bd_subject];
}else if($iw[type]=="about"){
	$row = sql_fetch(" select mb_code,ad_subject from $iw[about_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and ad_display = 1 and ad_code = '$cm_code' ");
	$mail_title = $row[ad_subject];
}

if (!$row[mb_code]) {
	alert(national_language($iw[language],"a0033","글이 존재하지 않습니다."),"");
}else{
	$cm_datetime = date("Y-m-d H:i:s");

	$sql = "insert into $iw[comment_table] set
			cm_code = '$cm_code',
			ep_code = '$iw[store]',
			gp_code = '$iw[group]',
			mb_code = '$iw[member]',
			state_sort = '$iw[type]',
			cm_recomment = '$cm_recomment',
			cm_content = '$cm_content',
			cm_ip = '$_SERVER[REMOTE_ADDR]',
			cm_datetime = '$cm_datetime',
			cm_secret = '$cm_secret',
			cm_display = 1
			";

	sql_query($sql);
	
	$mb_code = $row[mb_code];
	$row2 = sql_fetch("select st_title from $iw[setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$st_title = $row2["st_title"];
	$row2 = sql_fetch("select mb_code from $iw[enterprise_table] where ep_code = '$iw[store]'");
	$st_mb_code = $row2["mb_code"];
	$row2 = sql_fetch("select mb_mail, mb_sub_mail from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$st_mb_code'");
	$st_mb_mail = $row2["mb_sub_mail"];
	if($st_mb_mail == "") $st_mb_mail = $row2["mb_mail"];
	$row2 = sql_fetch("select mb_mail from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'");
	$to_mail = $row2["mb_mail"];

	$to = "$to_mail";
	$fmail = "$st_mb_mail";
	$fname  = "=?$iw[charset]?B?" . base64_encode($st_title) . "?=";
	$subject = "=?$iw[charset]?B?" . base64_encode('['.$st_title.'] 댓글 알림') . "?=";

	$mail_body = "
		<table width=650 border=0 cellspacing=0>
			<tr>
				<td width=80>
				  <b>게시물</b>
				</td>
				<td>
				  $mail_title (<a href='$iw[url]/bbs/m/$iw[type]_data_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&item=$cm_code'>해당 게시물로 바로 가기</a>)
				</td>
			</tr>
			<tr>
				<td>
				  <b>댓글 내용</b>
				</td>
				<td>
				  $cm_content
				</td>
			</tr>
		</table>
	";

	$mail_body = nl2br($mail_body);

	$header  = "Return-Path: <$fmail>\n";
	$header .= "From: $fname <$fmail>\n";
	$header .= "Reply-To: <$fmail>\n";
	$header .= "MIME-Version: 1.0\n";
	$header .= "X-Mailer: SIR Mailer 0.92 (localhost) : $_SERVER[SERVER_ADDR] : $_SERVER[REMOTE_ADDR] : localhost : $_SERVER[PHP_SELF] : $_SERVER[HTTP_REFERER] \n";
	$header .= "Content-Type: TEXT/HTML; charset=$iw[charset]\n";
	$header .= "Content-Transfer-Encoding: BASE64\n\n";
	$header .= chunk_split(base64_encode($mail_body)) . "\n";

// 	$email = mail($to, $subject, "", $header);

	alert(national_language($iw[language],"a0034","댓글이 등록되었습니다."),"$iw[m_path]/$iw[type]_data_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&item=$cm_code");
}
?>