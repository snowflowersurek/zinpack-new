<?php
include_once("_common.php");

set_cookie("payment_member","",time()-36000);
goto_url("index.php");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />