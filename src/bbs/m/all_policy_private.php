<?php
include_once("_common_guest.php");
include_once("_head.php");

$sql = "select ep_policy_private from $iw[enterprise_table] where ep_code = '$iw[store]'";
$row = sql_fetch($sql);
if (!$row["ep_policy_private"]) alert("잘못된 접근입니다!","");
?>
<div class="breadcrumb-box input-group">
	<ol class="breadcrumb ">
		<li><a href="http://<?=$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]?>"><?=national_language($iw[language],"a0028","개인정보 처리방침");?></a></li>
	</ol>
</div>
<div class="content">
	<div class="box br-default">
		<?=stripslashes($row["ep_policy_private"])?>
	</div>
</div>
<?php
include_once("_tail.php");

