<?php
include_once("_common.php");

set_cookie("iw_member","",time()-36000);
goto_url("$iw[super_path]/login.php?type=dashboard&ep=$iw[store]&gp=$iw[group]");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />



