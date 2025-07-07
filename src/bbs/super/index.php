<?php
// Super 관리자 디렉토리 기본 페이지
// login.php로 리다이렉트 (필수 파라미터 포함)
$ep = $_GET['ep'] ?? 'ep1822322763609cab5915c89';
$gp = $_GET['gp'] ?? 'all';
$type = $_GET['type'] ?? 'dashboard';

header("Location: login.php?type=$type&ep=$ep&gp=$gp");
exit;
?> 