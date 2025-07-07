<?php
ini_set('max_execution_time', 36000);
include_once("_common.php");
include_once($iw['admin_path']."/_head.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-home"></i>
			이북몰
		</li>
		<li class="active">이북 관리</li>
	</ul>
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			이북 관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				PDF 샘플
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<i class='fa fa-spinner fa-spin'></i> PDF 최적화 진행중..
			<?php
				$p = new ProgressBar();
				echo '<div style="width: 300px;">';
				$p->render();
				echo "</div>";
			?>
			</div>
		</div>
	</div>
</div>
<?php
include_once($iw['admin_path']."/_tail.php");

	class ProgressBar {
			var $percentDone = 0;
			var $pbid;
			var $pbarid;
			var $tbarid;
			var $textid;
			var $decimals = 1;
	 
			function __construct($percentDone = 0) {
					$this->pbid = 'pb';
					$this->pbarid = 'progress-bar';
					$this->tbarid = 'transparent-bar';
					$this->textid = 'pb_text';
					$this->percentDone = $percentDone;
			}
	 
			function render() {
					//print ($GLOBALS['CONTENT']);
					//$GLOBALS['CONTENT'] = '';
					print($this->getContent());
					$this->flush();
					//$this->setProgressBarProgress(0);
			}
	 
			function getContent() {
					$this->percentDone = floatval($this->percentDone);
					$percentDone = number_format($this->percentDone, $this->decimals, '.', '') .'%';
					$content .= '<div id="'.$this->pbid.'" class="pb_container">
							<div id="'.$this->textid.'" class="'.$this->textid.'">'.$percentDone.'</div>
							<div class="pb_bar">
									<div id="'.$this->pbarid.'" class="pb_before"
									style="width: '.$percentDone.';"></div>
									<div id="'.$this->tbarid.'" class="pb_after"></div>
							</div>
							<br style="height: 1px; font-size: 1px;"/>
					</div>
					<style>
							.pb_container {
									position: relative;
							}
							.pb_bar {
									width: 100%;
									height: 1.3em;
									border: 1px solid silver;
									-moz-border-radius-topleft: 5px;
									-moz-border-radius-topright: 5px;
									-moz-border-radius-bottomleft: 5px;
									-moz-border-radius-bottomright: 5px;
									-webkit-border-top-left-radius: 5px;
									-webkit-border-top-right-radius: 5px;
									-webkit-border-bottom-left-radius: 5px;
									-webkit-border-bottom-right-radius: 5px;
							}
							.pb_before {
									float: left;
									height: 1.3em;
									background-color: #43b6df;
									-moz-border-radius-topleft: 5px;
									-moz-border-radius-bottomleft: 5px;
									-webkit-border-top-left-radius: 5px;
									-webkit-border-bottom-left-radius: 5px;
							}
							.pb_after {
									float: left;
									background-color: #FEFEFE;
									-moz-border-radius-topright: 5px;
									-moz-border-radius-bottomright: 5px;
									-webkit-border-top-right-radius: 5px;
									-webkit-border-bottom-right-radius: 5px;
							}
							.pb_text {
									padding-top: 0.1em;
									position: absolute;
									left: 48%;
							}
					</style>'."\r\n";
					return $content;
			}
	 
			function setProgressBarProgress($percentDone, $text = '') {
					$this->percentDone = $percentDone;
					$text = $text ? $text : number_format($this->percentDone, $this->decimals, '.', '').'%';
					print('
					<script type="text/javascript">
					if (document.getElementById("'.$this->pbarid.'")) {
							document.getElementById("'.$this->pbarid.'").style.width = "'.$percentDone.'%";');
					if ($percentDone == 100) {
							print('document.getElementById("'.$this->pbid.'").style.display = "none";');
					} else {
							print('document.getElementById("'.$this->tbarid.'").style.width = "'.(100-$percentDone).'%";');
					}
					if ($text) {
							print('document.getElementById("'.$this->textid.'").innerHTML = "'.htmlspecialchars($text).'";');
					}
					print('}</script>'."\n");
					$this->flush();
			}
	 
			function flush() {
					print str_pad('', intval(ini_get('output_buffering')))."\n";
					//ob_end_flush();
					flush();
			}
	 
	}

	$upload_path = $_POST[upload_path];
	$bd_code = trim(mysqli_real_escape_string($iw['connect'], $_POST['bd_code']));
	$upload_type = trim(mysqli_real_escape_string($iw['connect'], $_POST['upload_type']));
	$pdf_quality = trim(mysqli_real_escape_string($iw['connect'], $_POST['pdf_quality']));
	$pdf_size = trim(mysqli_real_escape_string($iw['connect'], $_POST['pdf_size']));
	$zip_page = trim(mysqli_real_escape_string($iw['connect'], $_POST['zip_page']));
	$read_direction = trim(mysqli_real_escape_string($iw['connect'], $_POST['read_direction']));
	
	$abs_dir = $iw[path].$upload_path;
	@mkdir($abs_dir, 0777);
	@chmod($abs_dir, 0777);
	
	if($_FILES["bd_file"]["name"] && $_FILES["bd_file"]["size"]>209715200){
		alert("PDF 최대용량은 200MB 이하입니다.","");
	}else if($_FILES["bd_file"]["name"] && $_FILES["bd_file"]["size"]>0){

		$bd_file = $bd_code.".".preg_replace('/^.*\.([^.]+)$/D', '$1',$_FILES["bd_file"]["name"]);
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
			@mkdir($abs_dir.'/page', 0777);
			@chmod($abs_dir.'/page', 0777);
			@mkdir($abs_dir.'/thum', 0777);
			@chmod($abs_dir.'/thum', 0777);
			delete_all($abs_dir.'/page');
			delete_all($abs_dir.'/thum');

			if($upload_type == "pdf"){
				$p->setProgressBarProgress(0.1);
				//usleep(1000000*0.1);

				$imagick = new Imagick($abs_dir.'/'.$bd_file); 
				$count_imagick = $imagick->getNumberImages();

				$pdf_width = $imagick->getImageWidth();
				$pdf_height = $imagick->getImageHeight();
				$tumb_height = intval($pdf_height*(150/$pdf_width));

				/*if($pdf_size == 0 || ($pdf_width <= $pdf_size && $pdf_height <= $pdf_size)){
					$img_width = number_format($pdf_width);
					$img_height = number_format($pdf_height);
				}else */if($pdf_width >= $pdf_height){
					$img_width = intval($pdf_size);
					$img_height = intval($pdf_height*($pdf_size/$pdf_width));
				}else{
					$img_width = intval($pdf_width*($pdf_size/$pdf_height));
					$img_height = intval($pdf_size);
				}
				
				$imagick->clear();
				$imagick->destroy();

				$file_info = $count_imagick.";".$img_width.";".$img_height.";".$read_direction;
				$sql = "update $iw[book_data_table] set
						bd_sample = '$file_info'
						where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
						";
				sql_query($sql);

				for($x = 0; $x < $count_imagick; $x++){
					$p->setProgressBarProgress(($x+1)*100/$count_imagick);
					//usleep(1000000*0.1);

					$imagick = new Imagick();
					$imagick->setResolution(600, 600);
					$imagick->readImage($abs_dir.'/'.$bd_file.'['.$x.']');
					$imagick->setCompression(Imagick::COMPRESSION_JPEG); 
					$imagick->setCompressionQuality($pdf_quality); 
					$imagick->setImageFormat('jpeg');
					$imagick->resizeImage($img_width,$img_height,Imagick::FILTER_LANCZOS,1);
					$imagick->writeImage($abs_dir.'/page/'.intval($x+1).'.jpg');
					//$imagick->resizeImage(150,$tumb_height,Imagick::FILTER_LANCZOS,1);
					//$imagick->writeImage($abs_dir.'/thum/'.intval($x+1).'.jpg');
					$imagick->clear(); 
					$imagick->destroy();
				}
			}else{
				$p->setProgressBarProgress(0.1);

				$zip = new ZipArchive; 
				// 압축파일이 있는 위치를 지정합니다. 
				$res = $zip->open($abs_dir.'/'.$bd_file); 
				if (($res === TRUE) && (file_exists($abs_dir.'/page') === TRUE)) { 
					$zip->extractTo($abs_dir.'/page/'); 
					$zip->close();

					$img_size = GetImageSize($abs_dir.'/page/1.jpg');
					$file_info = $zip_page.";".$img_size[0].";".$img_size[1].";".$read_direction;
					$sql = "update $iw[book_data_table] set
							bd_sample = '$file_info'
							where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
							";
					sql_query($sql);
				} else { 
					// 압축파일 오픈에 실패하면 에러코드를 표시합니다. 
					if(ZIPARCHIVE::ER_NOZIP === $res) { 
						alert("압축파일 에러.","");
					} 
				}
			}
			$p->setProgressBarProgress(100);
			@unlink($abs_dir.'/'.$bd_file);
		}else{
			alert("PDF 첨부에러.","");
		}
	}else{
		$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
		$row = sql_fetch($sql);
		$bd_file = explode(";", $row["bd_sample"]);
		
		$file_info = $bd_file[0].";".$bd_file[1].";".$bd_file[2].";".$read_direction;

		$sql = "update $iw[book_data_table] set
				bd_sample = '$file_info'
				where bd_code = '$bd_code' and ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code = '$iw[member]' 
				";
		sql_query($sql);
	}

	alert("PDF 샘플이 등록되었습니다.","$iw[admin_path]/pdf/pdf_sample_edit.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&idx=$bd_code");
?>



