<?php
include_once("_common.php");
if ($iw[type] != "group" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?php
$gi_no = $_GET["idx"];

$sql = "delete from $iw[group_invite_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gi_no = '$gi_no'";
sql_query($sql);

alert("초대정보가 삭제되었습니다.","$iw[admin_path]/member_invite_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>



