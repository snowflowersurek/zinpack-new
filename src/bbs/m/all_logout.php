<?php
include_once("_common_guest.php");

set_cookie("iw_member","",time()-3600*24*365);
goto_url("$iw[m_path]/main.php?type=main&ep=$iw[store]&gp=$iw[group]");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />



