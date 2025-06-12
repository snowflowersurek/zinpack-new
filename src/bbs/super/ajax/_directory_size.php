<?php
$iw_path = "../../.."; // common.php 의 상대 경로
include_once("$iw_path/include/common.php");
include_once("$iw[include_path]/member_super.php");
if($iw['default_domain'] != $_SERVER['HTTP_HOST']) exit;

function getDirectorySize($path) {
	$bytes = 0;
	$path = realpath ($path);

	if ($path !== false && $path != '' && file_exists ($path)) {
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
			$bytes += $object->getSize();
		}
	}

	return $bytes;
}

$ep_nick = $_GET['ep_nick'];

if ($ep_nick == "") {
	echo "null";
	exit;
}

$total_size = getDirectorySize($iw['path']."/about/".$ep_nick);
$total_size += getDirectorySize($iw['path']."/book/".$ep_nick);
$total_size += getDirectorySize($iw['path']."/doc/".$ep_nick);
$total_size += getDirectorySize($iw['path']."/main/".$ep_nick);
$total_size += getDirectorySize($iw['path']."/mcb/".$ep_nick);
$total_size += getDirectorySize($iw['path']."/publishing/".$ep_nick);
$total_size += getDirectorySize($iw['path']."/shop/".$ep_nick);

$kb = $total_size / 1024;
$mb = $kb / 1024;
$gb = $mb / 1024;

$dir_size = $total_size > (1024*1024*1024) ? number_format($gb, 2)."GB" : ($total_size > (1024*1024) ? number_format($mb)."MB" : number_format($kb)."KB");

echo json_encode(array('dir_size' => $dir_size));
?>