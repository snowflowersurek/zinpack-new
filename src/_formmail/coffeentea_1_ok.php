<?php
include_once("_common.php");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
// 1. 모든 입력값에 대해 XSS 방지 및 trim 처리
$mail_01 = htmlspecialchars(trim($_POST['mail_01']), ENT_QUOTES, 'UTF-8');
$mail_02 = htmlspecialchars(trim($_POST['mail_02']), ENT_QUOTES, 'UTF-8');
$mail_03 = htmlspecialchars(trim($_POST['mail_03']), ENT_QUOTES, 'UTF-8');
$mail_04 = htmlspecialchars(trim($_POST['mail_04']), ENT_QUOTES, 'UTF-8');
$mail_05 = htmlspecialchars(trim($_POST['mail_05']), ENT_QUOTES, 'UTF-8');
$mail_06 = htmlspecialchars(trim($_POST['mail_06']), ENT_QUOTES, 'UTF-8');
$mail_07 = htmlspecialchars(trim($_POST['mail_07']), ENT_QUOTES, 'UTF-8');
$mail_08 = htmlspecialchars(trim($_POST['mail_08']), ENT_QUOTES, 'UTF-8');
$mail_09 = htmlspecialchars(trim($_POST['mail_09']), ENT_QUOTES, 'UTF-8');
$mail_10 = htmlspecialchars(trim($_POST['mail_10']), ENT_QUOTES, 'UTF-8');
$mail_11 = htmlspecialchars(trim($_POST['mail_11']), ENT_QUOTES, 'UTF-8');
$mail_12 = htmlspecialchars(trim($_POST['mail_12']), ENT_QUOTES, 'UTF-8');
$mail_13 = htmlspecialchars(trim($_POST['mail_13']), ENT_QUOTES, 'UTF-8');

// 2. 이메일 유효성 검사
$from_email = trim($_POST['mail_11']);
if (!filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
    alert("유효하지 않은 이메일 주소입니다.", "");
    exit;
}

$to = "coffeentea@icoffeentea.com";
$fname  = "=?{$iw['charset']}?B?" . base64_encode($mail_02) . "?=";
$subject = "=?{$iw['charset']}?B?" . base64_encode('2015 골든커피어워드(GCA) [원두콘테스트 부문]') . "?=";

// 3. 메일 본문 생성 (변수는 이미 이스케이프됨)
$mail_body = "
	<table border='1' cellspacing='0' style='border-collapse: collapse;'>
		<tr> <td>참가부문(품목)</td> <td>{$mail_01}</td> </tr>
		<tr> <td>업체명</td> <td>{$mail_02}</td> </tr>
		<tr> <td>로스터기종</td> <td>{$mail_03}</td> </tr>
		<tr> <td>우편번호</td> <td>{$mail_04}</td> </tr>
		<tr> <td>주소</td> <td>{$mail_05}</td> </tr>
		<tr> <td>대표자명</td> <td>{$mail_06}</td> </tr>
		<tr> <td>사업자번호</td> <td>{$mail_07}</td> </tr>
		<tr> <td>전화번호(사무실)</td> <td>{$mail_08}</td> </tr>
		<tr> <td>전화번호(핸드폰)</td> <td>{$mail_09}</td> </tr>
		<tr> <td>팩스번호</td> <td>{$mail_10}</td> </tr>
		<tr> <td>이메일</td> <td>{$mail_11}</td> </tr>
		<tr> <td>홈페이지</td> <td>{$mail_12}</td> </tr>
		<tr> <td>기타요청</td> <td>" . nl2br($mail_13) . "</td> </tr>
	</table>
";

// 4. 안전한 메일 헤더 생성
$header  = "Return-Path: <{$from_email}>\n";
$header .= "From: {$fname} <{$from_email}>\n";
$header .= "Reply-To: <{$from_email}>\n";
$header .= "MIME-Version: 1.0\n";
$header .= "Content-Type: TEXT/HTML; charset={$iw['charset']}\n";
$header .= "Content-Transfer-Encoding: base64\n\n";
$header .= chunk_split(base64_encode($mail_body));

$email = mail($to, $subject, "", $header);

if (!$email){
	$ma="메일 전송 실패! 다시 시도해주시기 바랍니다.";
} else {
	$ma="메일 전송 성공! 접수가 성공적으로 이루어졌습니다.";
}

alert($ma,"");
?>



