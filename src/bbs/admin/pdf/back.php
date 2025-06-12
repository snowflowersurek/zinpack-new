<?php
ini_set('max_execution_time', 3600);
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<meta http-equiv="content-type" content="text/html; charset=<?=$iw['charset']?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<?
	$upload_path = $_POST[upload_path];
	$bd_code = trim(mysql_real_escape_string($_POST[bd_code]));
	$pdf_quality = trim(mysql_real_escape_string($_POST[pdf_quality]));
	$pdf_size = trim(mysql_real_escape_string($_POST[pdf_size]));

	$abs_dir = $iw[path].$upload_path;
	
	if($_FILES["bd_file"]["name"] && $_FILES["bd_file"]["size"]>209715200){
		alert("PDF 최대용량은 200MB 이하입니다.","");
	}else if($_FILES["bd_file"]["name"] && $_FILES["bd_file"]["size"]>0){

		$bd_file = $bd_code.eregi_replace("(.+)(\.[pdf])","\\2",$_FILES["bd_file"]["name"]);
		$result = move_uploaded_file($_FILES["bd_file"]["tmp_name"], "{$abs_dir}/{$bd_file}");
		
		if($result){
			function delete_all($dir) {
				$d = @dir($dir);
				while ($entry = $d->read()) {
					if ($entry == "." || $entry == "..") continue;
					if (is_dir($entry)) delete_all($entry);
					else unlink($dir."/".$entry);
				}
			}

			@mkdir($abs_dir.'/page', 0707);
			@chmod($abs_dir.'/page', 0707);
			@mkdir($abs_dir.'/thum', 0707);
			@chmod($abs_dir.'/thum', 0707);
			delete_all($abs_dir.'/page');
			delete_all($abs_dir.'/thum');
			
			$imagick = new Imagick($abs_dir.'/'.$bd_file); 
			$count_imagick = $imagick->getNumberImages();

			$sql = "update $iw[book_data_table] set
					bd_file = '$count_imagick'
					where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
					";
			sql_query($sql);

			$pdf_width = $imagick->getImageWidth();
			$pdf_height = $imagick->getImageHeight();
			$tumb_height = $pdf_height*(150/$pdf_width);

			/*if($pdf_size == 0 || ($pdf_width <= $pdf_size && $pdf_height <= $pdf_size)){
				$img_width = $pdf_width;
				$img_height = $pdf_height;
			}else */if($pdf_width >= $pdf_height){
				$img_width = $pdf_size;
				$img_height = $pdf_height*($pdf_size/$pdf_width);
			}else{
				$img_width = $pdf_width*($pdf_size/$pdf_height);
				$img_height = $pdf_size;
			}
			
			$imagick->clear(); 
			$imagick->destroy();
			
			for($x = 0; $x < $count_imagick; $x++){
				$imagick = new Imagick();
				$imagick->setResolution(600, 600);
				$imagick->readImage($abs_dir.'/'.$bd_file.'['.$x.']');
				$imagick->setCompression(Imagick::COMPRESSION_JPEG); 
				$imagick->setCompressionQuality($pdf_quality); 
				$imagick->setImageFormat('jpeg');
				$imagick->resizeImage($img_width,$img_height,Imagick::FILTER_LANCZOS,1);
				$imagick->writeImage($abs_dir.'/page/'.$x.'.jpg');
				$imagick->resizeImage(150,$tumb_height,Imagick::FILTER_LANCZOS,1);
				$imagick->writeImage($abs_dir.'/thum/'.$x.'.jpg');
				$imagick->clear(); 
				$imagick->destroy();
			}

			@unlink($abs_dir.'/'.$bd_file);
		}else{
			alert("PDF 첨부에러.","");
		}
	}

	

	alert("PDF 파일이 등록되었습니다.","$iw[admin_path]/pdf/pdf_data_edit.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code");
?>