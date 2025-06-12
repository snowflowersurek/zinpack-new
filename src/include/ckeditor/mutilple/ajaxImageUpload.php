 <?php
error_reporting(0);
session_start();

$up_url = base64_decode($_COOKIE[md5("iw_upload")]); // 기본 업로드 URL
$uploaddir = "../../..".$up_url."/"; // 기본 업로드 폴더
@mkdir($uploaddir, 0707);
@chmod($uploaddir, 0707);

$session_id='infoway'; //$session id
define ("MAX_SIZE","9999000");
function getExtension($str)
{
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
}
include_once("../../../include/lib/lib_image_resize.php");

$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") 
{
    foreach ($_FILES['photos']['name'] as $name => $value)
    {
		
        $filename = stripslashes($_FILES['photos']['name'][$name]);
        $size=filesize($_FILES['photos']['tmp_name'][$name]);
        //get the extension of the file in a lower case format
          $ext = getExtension($filename);
          $ext = strtolower($ext);
     	
         if(in_array($ext,$valid_formats))
         {
	       if ($size < (MAX_SIZE*1024))
	       {
		   $image_name=uniqid(rand()).".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES['photos']['name'][$name]);
		   
		   $exifData = "";
		   $img_size = "";
		   $newname=$uploaddir.$image_name;
           $exifData = exif_read_data($_FILES["photos"]["tmp_name"][$name]); 
           if (move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newname)) 
           {
				$img_size = GetImageSize($newname);

				if($exifData['Orientation'] == 6) { 
				// 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨 
					$degree = 270;
					$img_width = $img_size[1];
				} 
				else if($exifData['Orientation'] == 8) { 
					// 반시계방향으로 90도 돌려줘야 정상 
					$degree = 90;
					$img_width = $img_size[1];
				} 
				else if($exifData['Orientation'] == 3) { 
					$degree = 180;
					$img_width = $img_size[0];
				}else{
					$img_width = $img_size[0];
				}
				if($img_width > 1100 || $degree){
					$image = new SimpleImage();
					$image->load($newname);
					if($degree){
						$image->rotate($degree);
					}
					if($img_width > 1100){
						$image->resizeToWidth(1100);
					}
					$image->save($newname);
				}

				echo "<img alt='' src='".$uploaddir.$image_name."'>";
	       $time=time();
	       }
	       else
	       {
	        echo '<span>You have exceeded the size limit! so moving unsuccessful! </span>';
            }

	       }
		   else
		   {
			echo '<span>You have exceeded the size limit!</span>';
          
	       }
       
          }
          else
         { 
	     	echo '<span>Unknown extension!</span>';
           
	     }
           
     }
}

?>