<?php
$version = $_GET["v"];
if($version==1){
	$file_name = "./admin/file/epubjs/reader.html";
}else if($version==2){
	$file_name = "./admin/file/epub/index.html";
}else if($version==3){
	$file_name = "./admin/file/flipbook/index.html";
}else if($version==4){
	$file_name = "./admin/file/magazine/index.html";
}

header('Location: '.$file_name);
?>



