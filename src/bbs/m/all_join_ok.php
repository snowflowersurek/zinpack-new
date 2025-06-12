<?php
include_once("_common_guest.php");
include_once "../../include/mailer.php";

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
$mb_mail = trim($_POST['mb_mail']);
$mb_password = trim($_POST['mb_password']);
$mb_name = trim($_POST['mb_name']);
$mb_nick = trim($_POST['mb_nick']);
$mb_address = trim($_POST['mb_address']);
$mb_address_sub = trim($_POST['mb_address_sub']);
$mb_sub_mail = trim($_POST['mb_sub_mail']);
$ep_autocode = trim($_POST['ep_autocode']);

if($iw[language]=="ko"){
	$mb_tel_1 = trim($_POST['mb_tel_1']);
	$mb_tel_2 = trim($_POST['mb_tel_2']);
	$mb_tel_3 = trim($_POST['mb_tel_3']);
	$mb_tel = $mb_tel_1."-".$mb_tel_2."-".$mb_tel_3;
	$mb_zip_code = trim($_POST['mb_zip_code']);
}else if($iw[language]=="en"){
	$mb_tel = trim($_POST['mb_tel']);
	$mb_zip_code = trim($_POST['mb_zip_code']);
	$mb_address_city = trim($_POST['mb_address_city']);
	$mb_address_state = trim($_POST['mb_address_state']);
	$mb_address_country = trim($_POST['mb_address_country']);
}
$result_check = passwordCheck($mb_password);
if ($result_check[0] == false){
    alert(national_language($iw['language'],"a0084","비밀번호를 특수문자 포함해서 8글자 이상 입력하십시오."),"");
}

$mb_code = "mb".uniqid(rand());
$mb_datetime = date("Y-m-d H:i:s");

$sql_enterprise = "select ep_jointype, ep_autocode from {$iw['enterprise_table']} where ep_code = ?";
$stmt_enterprise = mysqli_prepare($db_conn, $sql_enterprise);
mysqli_stmt_bind_param($stmt_enterprise, "s", $iw['store']);
mysqli_stmt_execute($stmt_enterprise);
$result_enterprise = mysqli_stmt_get_result($stmt_enterprise);
$row_enterprise = mysqli_fetch_assoc($result_enterprise);
mysqli_stmt_close($stmt_enterprise);
$ep_jointype = $row_enterprise["ep_jointype"];

if ($ep_jointype == 0) {
	alert(national_language($iw['language'],"a0095","회원가입이 불가능합니다."),"");
}else if ($ep_jointype == 5 && $row_enterprise["ep_autocode"] != $ep_autocode) {
	alert(national_language($iw['language'],"a0096","가입코드를 확인하여 주십시오."),"");
}else if ($ep_jointype == 4) {
	$sql_invite = "select gi_no, gi_datetime_end from {$iw['group_invite_table']} where ep_code = ? and gp_code = 'all' and gi_display = 0 and if(gi_name_check = 1, gi_name = ?, gi_name_check=0) and if(gi_mail_check = 1, gi_mail = ?, gi_mail_check=0) and if(gi_tel_check = 1, gi_tel = ?, gi_tel_check=0)";
    $stmt_invite = mysqli_prepare($db_conn, $sql_invite);
    mysqli_stmt_bind_param($stmt_invite, "ssss", $iw['store'], $mb_name, $mb_mail, $mb_tel);
    mysqli_stmt_execute($stmt_invite);
    $result_invite = mysqli_stmt_get_result($stmt_invite);
	$row_invite = mysqli_fetch_assoc($result_invite);
    mysqli_stmt_close($stmt_invite);

	if ($row_invite) {
		if ($row_invite["gi_datetime_end"] >= $mb_datetime){
			$sql_update_invite = "update {$iw['group_invite_table']} set mb_code = ?, gi_display = 1 where ep_code = ? and gp_code = 'all' and gi_no = ? and gi_display = 0";
            $stmt_update_invite = mysqli_prepare($db_conn, $sql_update_invite);
            mysqli_stmt_bind_param($stmt_update_invite, "ssi", $mb_code, $iw['store'], $row_invite['gi_no']);
            mysqli_stmt_execute($stmt_update_invite);
            mysqli_stmt_close($stmt_update_invite);
		}else{
			alert(national_language($iw['language'],"a0097","가입할수 있는 날짜를 초과하였습니다."),"");
		}
	}else{
		alert(national_language($iw['language'],"a0098","초대자 명단에 일치하는 회원정보가 없습니다."),"");
	}
}

if ($ep_jointype == 1 || $ep_jointype == 2 || $ep_jointype == 4 || $ep_jointype == 5) {
    // 이메일 중복 확인
	$sql_mail_check = "select count(*) as cnt from {$iw['member_table']} where mb_mail = ? and ep_code = ?";
    $stmt_mail_check = mysqli_prepare($db_conn, $sql_mail_check);
    mysqli_stmt_bind_param($stmt_mail_check, "ss", $mb_mail, $iw['store']);
    mysqli_stmt_execute($stmt_mail_check);
    $result_mail_check = mysqli_stmt_get_result($stmt_mail_check);
    $rowmail = mysqli_fetch_assoc($result_mail_check);
    mysqli_stmt_close($stmt_mail_check);

    // 닉네임 중복 확인
	$sql_nick_check = "select count(*) as cnt from {$iw['member_table']} where mb_nick = ? and ep_code = ?";
    $stmt_nick_check = mysqli_prepare($db_conn, $sql_nick_check);
    mysqli_stmt_bind_param($stmt_nick_check, "ss", $mb_nick, $iw['store']);
    mysqli_stmt_execute($stmt_nick_check);
    $result_nick_check = mysqli_stmt_get_result($stmt_nick_check);
    $rownick = mysqli_fetch_assoc($result_nick_check);
    mysqli_stmt_close($stmt_nick_check);

	if ($rowmail['cnt']) {
		alert(national_language($iw['language'],"a0099","이메일주소가 이미 존재합니다."),"");
	}else if ($rownick['cnt']) {
		alert(national_language($iw['language'],"a0100","닉네임이 이미 존재합니다."),"");
	}else{
		if ($mb_zip_code == '90002' || strlen($mb_tel) > 13) {
			exit;
		}

        // 사이트 제목 조회
		$sql_setting = "select st_title from {$iw['setting_table']} where ep_code = ? and gp_code = ?";
        $stmt_setting = mysqli_prepare($db_conn, $sql_setting);
        mysqli_stmt_bind_param($stmt_setting, "ss", $iw['store'], $iw['group']);
        mysqli_stmt_execute($stmt_setting);
        $result_setting = mysqli_stmt_get_result($stmt_setting);
		$row_setting = mysqli_fetch_assoc($result_setting);
        mysqli_stmt_close($stmt_setting);
		$st_title = $row_setting["st_title"];

        $hashed_password = password_hash($mb_password, PASSWORD_BCRYPT);
		$sql_insert = "insert into {$iw['member_table']} set
				mb_code = ?, ep_code = ?, mb_mail = ?, mb_password = ?, mb_name = ?, mb_nick = ?,
				mb_tel = ?, mb_sub_mail = ?, mb_zip_code = ?, mb_address = ?, mb_address_sub = ?,
				mb_address_city = ?, mb_address_state = ?, mb_address_country = ?, mb_ip = ?,
				mb_datetime = ?, mb_display = 0";
        $stmt_insert = mysqli_prepare($db_conn, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "sssssssssssssssss", 
            $mb_code, $iw['store'], $mb_mail, $hashed_password, $mb_name, $mb_nick, $mb_tel, 
            $mb_sub_mail, $mb_zip_code, $mb_address, $mb_address_sub, $mb_address_city, 
            $mb_address_state, $mb_address_country, $_SERVER['REMOTE_ADDR'], $mb_datetime);
        mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);

		$to = $mb_mail;
		$from = "no-reply@wizwindigital.com";
		$fromName  = $st_title;
		$subject = $st_title.' 회원가입 인증메일';
		$content = '
  			<div style="padding:20px;font-family:Arial,\'Apple SD Gothic Neo\',\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum;">
				<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;text-align:left;">
					<tbody>
						<tr>
							<td style="font-size:24px;line-height:34px;color:#333;"><strong>'.$st_title.'</strong><br>회원가입 인증메일</td>
						</tr>
						<tr>
							<td height="32"></td>
						</tr>
						<tr>
							<td style="border-top:1px solid #333;">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td style="padding:24px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;">
												<p>안녕하세요. '.$st_title.'입니다.</p>
												<p>아래 <span style="color:#df3926;">이메일 인증하기</span>를 누르면 회원가입 절차가 완료됩니다.</p>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td height="48">
								<a href="'.$iw['url'].'/bbs/m/all_email_confirm.php?ep='.$iw['store'].'&gp='.$iw['group'].'&mb='.$mb_code.'" style="display:inline-block;padding:12px 48px;font-size:16px;line-height:24px;font-weight:bold;color:#fff;text-align:center;text-decoration:none;background-color:#df3926;border-radius:5px;" rel="noopener noreferrer" target="_blank">이메일 인증하기</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		';

		mailer($fromName, $from, $to, $subject, $content, true);

		goto_url("{$iw['m_path']}/all_join_result.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}&mail={$mb_mail}");
	}
}
?>