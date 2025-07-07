<?php
include_once("_common.php");
if ($iw[level] == "guest") alert(national_language($iw[language],"a0032","로그인 후 이용해주세요"),"");

if (!$_GET['item'] || !$_GET['co']) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");
$cm_code = $_GET[item];
$cm_no = $_GET[co];

$sql = "select cm_no from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and mb_code = '$iw[member]' and cm_code = '$cm_code' and cm_no = '$cm_no'";
$row = sql_fetch($sql);
if (!$row["cm_no"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

$sql = "delete from $iw[comment_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and mb_code = '$iw[member]' and cm_code = '$cm_code' and cm_no = '$cm_no'";
sql_query($sql);

alert(national_language($iw[language],"a0036","댓글이 삭제되었습니다"),"$iw[m_path]/$iw[type]_data_view.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&item=$cm_code");
?>



