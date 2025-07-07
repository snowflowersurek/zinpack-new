<?php
include_once("../../include/lib/lib_image_resize.php");

$up_url = base64_decode($_COOKIE[md5("iw_upload")]); // 기본 업로드 URL
$up_dir = "../..".$up_url; // 기본 업로드 폴더
@mkdir($up_dir, 0707, true);
@chmod($up_dir, 0707);

// 업로드 DIALOG 에서 전송된 값
$funcNum = $_GET['CKEditorFuncNum'] ;
$CKEditor = $_GET['CKEditor'] ;
$langCode = $_GET['langCode'] ;
 
if(isset($_FILES['upload']['tmp_name']))
{
    $file_name = $_FILES['upload']['name'];
    $ext = strtolower(substr($file_name, (strrpos($file_name, '.') + 1)));
 
    if ('jpg' != $ext && 'jpeg' != $ext && 'gif' != $ext && 'png' != $ext)
    {
        echo '이미지만 가능';
        return false;
    }

	$file_name = uniqid(rand()).".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["upload"]["name"]);
    $save_dir = sprintf('%s/%s', $up_dir, $file_name);
    $save_url = sprintf('%s/%s', $up_url, $file_name);
	$exifData = exif_read_data($_FILES["upload"]["tmp_name"]); 
    if (move_uploaded_file($_FILES["upload"]["tmp_name"],$save_dir)){
    	$degree = 0;
		$img_size = GetImageSize($save_dir);
		$img_width = $img_size[0];

		if ($exifData['Orientation']) {
			if ($exifData['Orientation'] == 6) { 
			// 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨 
				$degree = 270;
				$img_width = $img_size[1];
			} else if ($exifData['Orientation'] == 8) { 
				// 반시계방향으로 90도 돌려줘야 정상 
				$degree = 90;
				$img_width = $img_size[1];
			} else if ($exifData['Orientation'] == 3) { 
				$degree = 180;
				$img_width = $img_size[0];
			}
		}

		if($img_width > 1100 || $degree){
			$image = new SimpleImage();
			$image->load($save_dir);
			if($degree){
				$image->rotate($degree);
			}
			if($img_width > 1100){
				$image->resizeToWidth(1100);
			}
			$image->save($save_dir);
		}

        echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$save_url', '업로드완료');</script>";
	}
}
?>



