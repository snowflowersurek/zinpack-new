<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once($iw['admin_path']."/_cg_head.php");

$bd_code = $_GET["idx"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("잘못된 접근입니다!","");
?>
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="bm_form" name="bm_form" action="<?=$iw['admin_path']?>/media/media_image_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="bd_code" value="<?=$row['bd_code']?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
				<input type="hidden" name="bm_type" value="2" />
					<div class="form-group">
						<label class="col-sm-2 control-label">스타일</label>
						<div class="col-sm-8">
							<span class="help-block col-xs-12">이미지</span>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">썸네일</label>
						<div class="col-sm-8">
							<input type="file" class="col-xs-12 col-sm-12" name="bm_image">
							<span class="help-block col-xs-12">
								사이즈(pixel) 240 X 240
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">
							<span onclick="add_file();" style="cursor:pointer;"><i class="fa fa-plus-square fa-lg"></i></span> 
							<span onclick="del_file();" style="cursor:pointer;"><i class="fa fa-minus-square fa-lg"></i></span>
						</label>
						<div class="col-sm-8">
							<table id="variableFiles"></table>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
							</button>
						</div>
					</div>
				</form>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<script type="text/javascript">
	var flen = 0;
	function add_file()
	{
		var upload_count = 20;
		if (upload_count && flen >= upload_count)
		{
			alert(upload_count+"개 까지만 등록 가능합니다.");
			return;
		}

		var objTbl;
		var objRow;
		var objCell;
		if (document.getElementById)
			objTbl = document.getElementById("variableFiles");
		else
			objTbl = document.all["variableFiles"];

		objRow = objTbl.insertRow(objTbl.rows.length);
		objCell = objRow.insertCell(0);
		
		objCell.innerHTML = "<span class='help-block col-xs-12'><b>"+(flen+1)+". 미디어 타입</b></span>";
		if(flen == 0){
			objCell.innerHTML += "<select class='col-xs-12 col-sm-12' name='bmd_type[]' onchange='javascript:type_change(this.value,\""+flen+"\");'><option value='1'>이미지</option><option value='2'>유튜브 동영상</option><option value='3'>광고 링크</option><option value='4'>오디오</option></select>";
		}else{
			objCell.innerHTML += "<select class='col-xs-12 col-sm-12' name='bmd_type[]' onchange='javascript:type_change(this.value,\""+flen+"\");'><option value='1'>이미지</option><option value='2'>유튜브 동영상</option><option value='3'>광고 링크</option></select>";
		}
		objCell.innerHTML += "<span id='file_help_"+flen+"' class='help-block col-xs-12'>이미지 - 사이즈(pixel) 1024 X 1024 이하</span>";
		objCell.innerHTML += "<input type='file' class='col-xs-12 col-sm-12' name='bmd_image[]'>";
		objCell.innerHTML += "<span id='text_help_"+flen+"' class='help-block col-xs-12'>캡션내용</span>";
		objCell.innerHTML += "<div id='text_wrap_"+flen+"'><textarea name='bmd_content[]' class='col-xs-12 col-sm-12' style='height:50px;resize:none;'></textarea></div>";

		flen++;
		
		var iframeHeight = parent.document.getElementById("bookFrame").height * 1;
		parent.document.getElementById("bookFrame").height = iframeHeight + 205;
	}
	add_file();
	function del_file()
	{
		// file_length 이하로는 필드가 삭제되지 않아야 합니다.
		var file_length = 1;
		var objTbl = document.getElementById("variableFiles");
		if (objTbl.rows.length > file_length)
		{
			objTbl.deleteRow(objTbl.rows.length - 1);
			flen--;

			var iframeHeight = parent.document.getElementById("bookFrame").height * 1;
			parent.document.getElementById("bookFrame").height = iframeHeight - 205;
		}
	}

	function type_change(type,num) {
		var type = type*1;
		var num = num*1;
		switch (type){
			case 1:
				document.getElementById('file_help_'+num).innerHTML = "이미지 - 사이즈(pixel) 1024 X 1024 이하";
				document.getElementById('text_help_'+num).innerHTML = "캡션내용";
				document.getElementById('text_wrap_'+num).innerHTML = "<textarea name='bmd_content[]' class='col-xs-12 col-sm-12' style='height:50px;resize:none;'></textarea>";
				break;
			case 2:
				document.getElementById('file_help_'+num).innerHTML = "동영상 썸네일 - 사이즈(pixel) 1024 X 1024 이하";
				document.getElementById('text_help_'+num).innerHTML = "유튜브 코드 - URL 끝부분 v= 다음에 있는 코드";
				document.getElementById('text_wrap_'+num).innerHTML = "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='bmd_content[]'>";
				break;
			case 3:
				document.getElementById('file_help_'+num).innerHTML = "광고 이미지 - 사이즈(pixel) 1024 X 1024 이하";
				document.getElementById('text_help_'+num).innerHTML = "광고 링크 URL";
				document.getElementById('text_wrap_'+num).innerHTML = "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='bmd_content[]'>";
				break;
			case 4:
				document.getElementById('file_help_'+num).innerHTML = "오디오 파일 - MP3";
				document.getElementById('text_help_'+num).innerHTML = "오디오 제목";
				document.getElementById('text_wrap_'+num).innerHTML = "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='bmd_content[]'>";
				break;
			default:
				break;
		}
	}

	function check_form() {
		if (bm_form.bm_image.value == ""){
			alert("썸네일을 입력하여 주십시오.");
			bm_form.bm_image.focus();
			return;
		}
		if (bm_form.bm_image.value && !bm_form.bm_image.value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
			alert('이미지 파일만 업로드 가능합니다.');
			bm_form.bm_image.focus();
			return;
		}
		for(var a=0; a < flen; a++){
			if(document.getElementsByName('bmd_type[]')[a].value == 4){
				if (document.getElementsByName('bmd_image[]')[a].value == ""){
					alert("오디오를 첨부하여 주십시오.");
					document.getElementsByName('bmd_image[]')[a].focus();
					return;
				}
				else if(document.getElementsByName('bmd_image[]')[a].value && !document.getElementsByName('bmd_image[]')[a].value.match(/(.mp3|.MP3)/)) { 
					alert("MP3 파일만 업로드 가능합니다.");
					document.getElementsByName('bmd_image[]')[a].focus();
					return;
				}
			}else{
				if (document.getElementsByName('bmd_image[]')[a].value == ""){
					alert("이미지를 첨부하여 주십시오.");
					document.getElementsByName('bmd_image[]')[a].focus();
					return;
				}
				else if(document.getElementsByName('bmd_image[]')[a].value && !document.getElementsByName('bmd_image[]')[a].value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
					alert("이미지 파일만 업로드 가능합니다.");
					document.getElementsByName('bmd_image[]')[a].focus();
					return;
				}
			}
			if (document.getElementsByName('bmd_content[]')[a].value == ""){
				alert("내용을 입력하여 주십시오.");
				document.getElementsByName('bmd_content[]')[a].focus();
				return;
			}
		}
		bm_form.submit();
	}
</script>

<?php
include_once($iw['admin_path']."/_cg_tail.php");
?>



