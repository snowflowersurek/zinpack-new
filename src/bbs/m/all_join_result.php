<?php
include_once("_common_guest.php");
include_once("_head.php");
?>

<div class="breadcrumb-box input-group">
	<ol class="breadcrumb ">
		<li><a href="#"><?=national_language($iw[language],"a0104","회원가입 확인");?></a></li>
	</ol>
</div>
<div class="content">
	<div class="alert alert-info text-center">
		<h4><strong><?=$_GET[mail]?></strong>으로 인증 이메일이 발송되었습니다.</h4>
		<p>전송된 이메일을 확인하여 가입 절차를 완료해주세요.</p>
		<p>받은 이메일에 인증 메일이 없다면 스팸 또는 정크 메일함을 확인해주세요.</p>
		<p class="text-danger">48시간 내 미인증 시 가입정보는 자동삭제됩니다.</p>
	</div>
</div>
<?php
include_once("_tail.php");
?>



