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
$item_no = (int)$_GET['item'];
if (!$item_no) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$sql = "delete from {$iw['shop_cart_table']} where ep_code = ? and mb_code = ? and sc_no = ?";
$stmt = mysqli_prepare($db_conn, $sql);
mysqli_stmt_bind_param($stmt, "ssi", $iw['store'], $iw['member'], $item_no);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

goto_url("{$iw['m_path']}/shop_cart_form.php?type={$iw['type']}&ep={$iw['store']}&gp={$iw['group']}");
?>



