<?php
include_once("_common.php");
if ($iw[level]=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

alert(national_language($iw[language],"a0263","결제요청이 실패하였습니다."),"$iw[m_path]/shop_cart_form.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">