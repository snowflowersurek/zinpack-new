<?
include_once("_common.php");
if ($iw[type] != "doc" || $iw[level] == "guest") alert("잘못된 접근입니다!","");

$dd_code = $_GET["idx"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = $iw[path].'/'.$iw[type].'/'.$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$dd_code;

if ($iw[group] == "all" && $iw[level] == "admin"){
	$sql = "select dd_file_name, dd_file from $iw[doc_data_table] where ep_code = '$iw[store]' and dd_code = '$dd_code'";
}else if ($iw[group] != "all" && $iw[gp_level] == "gp_admin"){
	$sql = "select dd_file_name, dd_file from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and dd_code = '$dd_code'";
}else{
	$sql = "select dd_file_name, dd_file from $iw[doc_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and dd_code = '$dd_code' and mb_code = '$iw[member]'";
}
$file = sql_fetch($sql);
if (!$file["dd_file"]) alert("파일 정보가 존재하지 않습니다.");

$filepath = "$upload_path/$file[dd_file]";
$filepath = addslashes($filepath);
if (!is_file($filepath) || !file_exists($filepath))
    alert("파일이 존재하지 않습니다.");

if (preg_match("/^utf/i", $iw[charset]))
    $original = urlencode($file[dd_file_name]);
else
    $original = $file[dd_file_name];

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