<?php
include_once("_common.php");

$contest_code = $_GET["contest_code"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = $iw[path].'/publishing/'.$row[ep_nick].'/contest/'.$contest_code;

$sql = "select origin_filename, attach_filename from iw_publishing_contest where ep_code = '$iw[store]' and contest_code = '$contest_code'";
$file = sql_fetch($sql);
if (!$file["attach_filename"]) alert("파일 정보가 존재하지 않습니다.");

$filepath = "$upload_path/$file[attach_filename]";
$filepath = addslashes($filepath);

if (!is_file($filepath) || !file_exists($filepath))
    alert("파일이 존재하지 않습니다.");

if (preg_match("/^utf/i", $iw[charset]))
    $original = urlencode($file[origin_filename]);
else
    $original = $file[origin_filename];

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