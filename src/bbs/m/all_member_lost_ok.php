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
<?php
$mb_mail = trim($_POST['mb_mail']);
$mb_name = trim($_POST['mb_name']);

$sql_check = "select count(*) as cnt from {$iw['member_table']} where mb_mail = ? and ep_code = ? and mb_name = ?";
$stmt_check = mysqli_prepare($db_conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "sss", $mb_mail, $iw['store'], $mb_name);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
$rowmail = mysqli_fetch_assoc($result_check);
mysqli_stmt_close($stmt_check);

if (!$rowmail['cnt']) {
	alert(national_language($iw['language'],"a0119","이메일주소와 이름을 확인하여 주십시오."),"");
}else{
	$sql_setting = "select st_title from {$iw['setting_table']} where ep_code = ? and gp_code = ?";
    $stmt_setting = mysqli_prepare($db_conn, $sql_setting);
    mysqli_stmt_bind_param($stmt_setting, "ss", $iw['store'], $iw['group']);
    mysqli_stmt_execute($stmt_setting);
    $result_setting = mysqli_stmt_get_result($stmt_setting);
	$row_setting = mysqli_fetch_assoc($result_setting);
    mysqli_stmt_close($stmt_setting);
	$st_title = $row_setting["st_title"];

	$mb_password_tmp = "";
	$microtimes = explode (" ", microtime());
	$microsecond = explode (".", $microtimes[0]);
	$mb_password_tmp .= $microsecond[1];
	$mb_password_tmp .= date("y");
	$mb_password_tmp .= date("s");
	$mb_password_tmp .= date("m");
	$mb_password_tmp .= date("H");

    $hashed_password = password_hash($mb_password_tmp, PASSWORD_BCRYPT);
	$sql_update = "update {$iw['member_table']} set mb_password = ? where mb_mail = ? and ep_code = ? and mb_name = ?";
    $stmt_update = mysqli_prepare($db_conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ssss", $hashed_password, $mb_mail, $iw['store'], $mb_name);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

	$to = $mb_mail;
	$from = "no-reply@wizwindigital.com";
	$fromName  = $st_title;
	$subject = $st_title.' 임시 비밀번호 발급';
	$content = '
  		<div style="padding:20px;font-family:Arial,\'Apple SD Gothic Neo\',\'맑은 고딕\',\'Malgun Gothic\',\'돋움\',dotum;">
			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;text-align:left;">
				<tbody>
					<tr>
						<td style="font-size:24px;line-height:34px;color:#333;"><strong>'.$st_title.'</strong><br>임시 비밀번호 발급</td>
					</tr>
					<tr>
						<td height="32"></td>
					</tr>
					<tr>
						<td style="border-top:1px solid #333;">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tbody>
									<tr>
										<td width="100" style="padding:16px 0 16px 8px;font-size:13px;line-height:20px;color:#999;vertical-align:top;border-bottom:1px solid #eee;">임시 비밀번호</td>
										<td style="padding:16px 0;font-size:14px;line-height:20px;color:#333;vertical-align:top;border-bottom:1px solid #eee;">'.$mb_password_tmp.'</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding:16px 4px;font-size:13px;line-height:20px;color:#df3926;">
							<p>※ 발급된 임시 비밀번호로 로그인 후 회원정보 페이지에서 비밀번호를 변경하시기 바랍니다.</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	';

	mailer($fromName, $from, $to, $subject, $content, true);

	alert(national_language($iw['language'],"a0124","이메일로 임시비밀번호를 전송하였습니다."),"{$iw['m_path']}/all_login.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
}
?>



