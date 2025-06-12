<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

if (!$_GET['idx']) alert("잘못된 접근입니다!","");
$ad_code = $_GET['idx'];

$sql = "select * from $iw[about_data_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and ad_code = '$ad_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["ad_no"]) alert("잘못된 접근입니다!","");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/about/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}

$upload_path .= '/'.$ad_code;
$abs_dir = $iw[path].$upload_path;

@mkdir($abs_dir, 0707);
@chmod($abs_dir, 0707);

function rmdirAll($dir) {
   $dirs = dir($dir);
   while(false !== ($entry = $dirs->read())) {
      if(($entry != '.') && ($entry != '..')) {
         if(is_dir($dir.'/'.$entry)) {
            rmdirAll($dir.'/'.$entry);
         } else {
            @unlink($dir.'/'.$entry);
         }
       }
    }
    $dirs->close();
    @rmdir($dir);
}
rmdirAll($abs_dir);

$sql = "delete from $iw[about_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and ad_code = '$ad_code' and mb_code = '$iw[member]'";
sql_query($sql);

$sql = "delete from $iw[comment_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cm_code = '$ad_code'";
sql_query($sql);

alert("페이지가 삭제되었습니다.","$iw[admin_path]/about_data_list.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");
?>