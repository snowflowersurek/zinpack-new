<?php
include_once("_common.php");

$md_code = $_GET["mcb"];
$cg_group = $_GET["cg"];

$row = sql_fetch(" select cg_level_download from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_group' ");
$cg_level_download = $row[cg_level_download];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = $iw[path].'/'.$iw[type].'/'.$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$md_code;

$sql = "select md_attach_name, md_attach,mb_code from $iw[mcb_data_table] where ep_code = '$iw[store]' and md_code = '$md_code'";
$file = sql_fetch($sql);
if (!$file["md_attach"]) alert("파일 정보가 존재하지 않습니다.");

if($cg_level_download != 10 && $iw[mb_level] < $cg_level_download && $file["mb_code"] != $iw[member]){
	alert("해당 카테고리에 다운로드 권한이 없습니다.","");
}

$filepath = "$upload_path/$file[md_attach]";
$filepath = addslashes($filepath);
if (!is_file($filepath) || !file_exists($filepath))
    alert("파일이 존재하지 않습니다.");

if (preg_match("/^utf/i", $iw[charset]))
    $original = urlencode($file[md_attach_name]);
else
    $original = $file[md_attach_name];

if(preg_match("/msie/i", $_SERVER[HTTP_USER_AGENT]) && preg_match("/5\.5/", $_SERVER[HTTP_USER_AGENT])) {
    header("content-type: doesn/matter");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-transfer-encoding: binary");
} else {
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-description: php generated data");
}
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen("$filepath", "rb");

$download_rate = 10;

while(!feof($fp)) {
    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose ($fp);
flush();
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />



