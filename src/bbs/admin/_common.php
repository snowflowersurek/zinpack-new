<?php
$iw_path = "../.."; // common.php 의 상대 경로

// 관리자 페이지에서는 ep, gp, type 파라미터가 없어도 기본값 설정
if (empty($_GET['ep'])) {
    $_GET['ep'] = "ep1822322763609cab5915c89"; // 기본 기업코드
}
if (empty($_GET['gp'])) {
    $_GET['gp'] = "all"; // 기본 그룹코드 (관리자는 all)
}
if (empty($_GET['type'])) {
    $_GET['type'] = "dashboard"; // 기본 타입 (대시보드)
}

include_once("$iw_path/include/common.php");
include_once("$iw[include_path]/member_check.php");



