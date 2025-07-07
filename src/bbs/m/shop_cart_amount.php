<?php
include_once("_common.php");
if ($iw['level']=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$sd_code = trim($_GET['item']);
$sc_no = (int)$_GET['opt'];
$sc_amount = (int)$_GET['amount'];

if (!$sd_code || !$sc_no || !$sc_amount) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

// 다른 옵션들의 합계 조회
$sql_sum = "select sum(sc_amount) as sumount from {$iw['shop_cart_table']} where ep_code = ? and mb_code = ? and sd_code = ? and sc_no <> ?";
$stmt_sum = mysqli_prepare($db_conn, $sql_sum);
mysqli_stmt_bind_param($stmt_sum, "sssi", $iw['store'], $iw['member'], $sd_code, $sc_no);
mysqli_stmt_execute($stmt_sum);
$result_sum = mysqli_stmt_get_result($stmt_sum);
$row_sum = mysqli_fetch_assoc($result_sum);
mysqli_stmt_close($stmt_sum);
$cart_total = (int)$row_sum['sumount'];

// 상품 정보 조회
$sql_data = "select sd_subject, sd_max from {$iw['shop_data_table']} where ep_code = ? and sd_code = ?";
$stmt_data = mysqli_prepare($db_conn, $sql_data);
mysqli_stmt_bind_param($stmt_data, "ss", $iw['store'], $sd_code);
mysqli_stmt_execute($stmt_data);
$result_data = mysqli_stmt_get_result($stmt_data);
$row_data = mysqli_fetch_assoc($result_data);
mysqli_stmt_close($stmt_data);
$sd_subject = $row_data["sd_subject"];
$sd_max = (int)$row_data["sd_max"];

if ($sd_max > 0 && $sd_max < $cart_total + $sc_amount){
	$sc_amount = $sd_max - $cart_total;
	if ($sc_amount < 0) $sc_amount = 0;
	echo "<script>alert('".national_language($iw[language],'a0221','1인당 최대 구매 가능 수량 :')."{$sd_subject}({$sd_max})');</script>";
}

// 장바구니 수량 업데이트
$sql_update = "update {$iw['shop_cart_table']} set sc_amount = ? where ep_code = ? and mb_code = ? and sc_no = ?";
$stmt_update = mysqli_prepare($db_conn, $sql_update);
mysqli_stmt_bind_param($stmt_update, "issi", $sc_amount, $iw['store'], $iw['member'], $sc_no);
mysqli_stmt_execute($stmt_update);
mysqli_stmt_close($stmt_update);

goto_url("{$iw['m_path']}/shop_cart_form.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
?>



