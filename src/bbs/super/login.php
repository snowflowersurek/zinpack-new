<?php
include_once("_common.php");

if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

// 로그인된 사용자는 메인으로 이동
if (isset($iw['level']) && $iw['level'] == "super") {
    header("Location: {$iw['super_path']}/main.php?type=dashboard&ep={$iw['store']}&gp={$iw['group']}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<title>ZINPACK 관리페이지</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="summary here">
    <meta name="author" content="">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href="/favicon.ico" rel="icon">
	<link rel="stylesheet" type="text/css" href="<?=$iw['design_path']?>/css/zinpack.login.css" />
</head>
<body>

<form id="mb_form" name="mb_form" action="<?=$iw['super_path']?>/login_ok.php?ep=<?=$iw['store']?>&gp=<?=$iw['group']?>" method="post">
	<div class="pageLogin">
		<div class="loginWrap">
			<h1><img src="<?=$iw['design_path']?>/img/admin_login_zinpack.gif" alt="ZINPACK" /></h1>
			<p>Administrator</p>
			<ul class="login_input">
				<li>
					<label>
						<span class="lbl"><img src="<?=$iw['design_path']?>/img/admin_login_id.gif" alt="ID" /></span>
						<input type="text" name="mb_mail">
					</label>
				</li>
				<li>
					<label>
						<span class="lbl"><img src="<?=$iw['design_path']?>/img/admin_login_pw.gif" alt="PW" /></span>
						<input type="password" name="mb_password" onkeydown="javascript: if (event.keyCode == 13) {check_form();}" />
					</label>
				</li>
				<li>
					<input type="button" class="btn_adminLogin" onclick="javascript:check_form();" value="LOGIN...New" />
				</li>
			</ul>
		</div>
		<div class="footWrap">
			<p>COPYRIGHT &copy; WIZWINDIGITAL. ALL RIGHT RESERVED.</p>
		</div>
	</div>
</form>

<script type="text/javascript">
	function check_form() {
		if (mb_form.mb_mail.value == ""){
			alert("아이디를 입력하여 주십시오.");
			mb_form.mb_mail.focus();
			return;
		}
		if (mb_form.mb_password.value == ""){
			alert("비밀번호를 입력하여 주십시오.");
			mb_form.mb_password.focus();
			return;
		}
		mb_form.submit();
	}
</script>
<?php
include_once("{$iw['super_path']}/_tail_sub.php");
exit; // 다른 파일의 영향을 받지 않도록 여기서 실행을 강제 종료
?>



