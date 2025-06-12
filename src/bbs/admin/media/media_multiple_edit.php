<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once($iw['admin_path']."/_cg_head.php");

$bd_code = $_GET["idx"];
$bm_no = $_GET["no"];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/book/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$bd_code;

$sql = "select * from $iw[book_media_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]' and bm_no = '$bm_no'";
$row = sql_fetch($sql);
if (!$row["bm_no"]) alert("잘못된 접근입니다!","");
$bm_no = $row['bm_no'];
$bm_image = $row['bm_image'];
$bm_order = $row['bm_order'];

$sql = "select * from $iw[book_media_detail_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]' and bm_order = '$bm_order' and bmd_order = 0";
$row = sql_fetch($sql);
if (!$row["bmd_no"]) alert("잘못된 접근입니다!","");
$bmd_no = $row['bmd_no'];
$bmd_content = stripslashes($row['bmd_content']);

$bmd_image = $row["bmd_image"];
$bmd_image = str_replace("\'", '&#039;', $bmd_image);
$bmd_image = str_replace('\"', '&quot;', $bmd_image);

$bmd_type = $row["bmd_type"];
$bmd_type = str_replace("\'", '&#039;', $bmd_type);
$bmd_type = str_replace('\"', '&quot;', $bmd_type);
?>
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="bm_form" name="bm_form" action="<?=$iw['admin_path']?>/media/media_multiple_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="bd_code" value="<?=$bd_code?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
				<input type="hidden" name="bm_type" value="2" />
				<input type="hidden" name="bm_order" value="<?=$bm_order?>" />
				<input type="hidden" name="bmd_no_main" value="<?=$bmd_no?>" />
				<input type="hidden" name="bm_no" value="<?=$bm_no?>" />
				<input type="hidden" name="bm_image_old" value="<?=$bm_image?>" />
					<div class="form-group">
						<label class="col-sm-2 control-label">스타일</label>
						<div class="col-sm-8">
							<span class="help-block col-xs-12">이미지/기사</span>
						</div>
					</div>
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label">썸네일</label>
						<div class="col-sm-8">
							<input type="file" class="col-xs-12 col-sm-12" name="bm_image">
							<span class="help-block col-xs-12">
								사이즈(pixel) 240 X 240
								<?if($bm_image){?><br/><a href="<?=$iw["path"].$upload_path."/".$bm_image?>" target="_blank">기존 이미지</a><?}?>
							</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">제목</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="bmd_type_main" value="<?=$bmd_type?>">
							<span class="help-block col-xs-12">
								최대 70Byte 이하
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">기자</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="bmd_image_main" value="<?=$bmd_image?>">
							<span class="help-block col-xs-12">
								최대 30Byte 이하
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">기사내용</label>
						<div class="col-sm-8">
							<textarea name="bmd_content_main" class="col-xs-12 col-sm-12" style="height:100px;resize:none;"><?=$bmd_content?></textarea>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">
							<span onclick="add_file();" style="cursor:pointer;"><i class="fa fa-plus-square fa-lg"></i></span> 
							<span onclick="del_file();" style="cursor:pointer;"><i class="fa fa-minus-square fa-lg"></i></span>
						</label>
						<div class="col-sm-8">
							<table>
							<?
								$file_num=0;
								$sql = "select * from $iw[book_media_detail_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]' and bm_order = '$bm_order' and bmd_order <> 0 order by bmd_order asc";
								$result = sql_query($sql);
								while($row = @sql_fetch_array($result)){
									$bmd_no = $row['bmd_no'];
									$bmd_type = $row['bmd_type'];
									$bmd_image = $row["bmd_image"];
									$bmd_content = $row["bmd_content"];
									$bmd_big_image = $row["bmd_big_image"];
									
									if($bmd_type == 1){
										$bmd_content = stripslashes($row['bmd_content']);
									}else{
										$bmd_content = str_replace("\'", '&#039;', $bmd_content);
										$bmd_content = str_replace('\"', '&quot;', $bmd_content);
									}
									if($bmd_type == 1){
										$type_name = "이미지";
										$file_help = "이미지 - 사이즈(pixel) 730 X 350";
										$text_help = "캡션내용";
									}else if($bmd_type == 2){
										$type_name = "유튜브 동영상";
										$file_help = "동영상 썸네일 - 사이즈(pixel) 730 X 350";
										$text_help = "유튜브 코드 - URL 끝부분 v= 다음에 있는 코드";
									}else if($bmd_type == 3){
										$type_name = "광고 링크";
										$file_help = "광고 이미지 - 사이즈(pixel) 730 X 350";
										$text_help = "광고 링크 URL";
									}else if($bmd_type == 4){
										$type_name = "오디오";
										$file_help = "오디오 파일 - MP3";
										$text_help = "오디오 제목";
									}
									if($bmd_image){
										?>
										<tr>
											<td>
												<input type="hidden" name="bmd_image_old[]" value="<?=$bmd_image?>" />
												<input type="hidden" name="bmd_no[]" value="<?=$bmd_no?>" />
												<input type="hidden" name="bmd_type[]" value="<?=$bmd_type?>" />
												<span class='help-block col-xs-12'><b><?=$file_num+1?>. <?=$type_name?></b> <input type='checkbox' name='bmd_image_delete[]' value="del" /> 삭제</span>
												<?if($bmd_type == 1 || $bmd_type == 4){?>
													<input type="hidden" id="bmd_big_image_old_<?=$file_num?>" name="bmd_big_image_old_<?=$file_num?>" value="<?=$bmd_big_image?>" />
													<span class='help-block col-xs-12'>원본 이미지 - 사이즈(pixel) 1024 X 1024 이하</span>
													<input type='file' class='col-xs-12 col-sm-12' id='bmd_big_image_<?=$file_num?>' name='bmd_big_image_<?=$file_num?>'>
													<?if($bmd_big_image){?>
													<span class='help-block col-xs-12'>
														<a href="<?=$iw["path"].$upload_path."/".$bmd_big_image?>" target="_blank">기존 이미지</a>
													</span>
													<?}?>
												<?}?>
												<span class='help-block col-xs-12'><?=$file_help?></span>
												<input type='file' class='col-xs-12 col-sm-12' name='bmd_image[]'>
												<?if($bmd_image){?>
												<span class='help-block col-xs-12'>
													<a href="<?=$iw["path"].$upload_path."/".$bmd_image?>" target="_blank"><?if($bmd_type == 4){?>오디오 듣기<?}else{?>기존 이미지<?}?></a>
												</span>
												<?}?>
												<span class='help-block col-xs-12'><?=$text_help?></span>
												<?if($bmd_type == 1){?>
													<textarea name='bmd_content[]' class='col-xs-12 col-sm-12' style='height:50px;resize:none;'><?=$bmd_content?></textarea>
												<?}else{?>
													<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='bmd_content[]' value="<?=$bmd_content?>">
												<?}?>
											</td>
										</tr>
										<?
										$file_num++;
									}
								}
							?>
							</table>
							<table id="variableFiles"></table>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-danger" type="button" onclick="javascript:page_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$bm_no?>');">
								삭제
							</button>
							<button class="btn btn-info" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								수정
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
	var flen = <?=$file_num?>;
	parent.document.getElementById("bookFrame").height = (parent.document.getElementById("bookFrame").height * 1)+ (330*flen);
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
		objCell.innerHTML += "<div id='big_file_"+flen+"'><span class='help-block col-xs-12'>원본 이미지 - 사이즈(pixel) 1024 X 1024 이하</span><input type='file' class='col-xs-12 col-sm-12' id='bmd_big_image_"+flen+"' name='bmd_big_image_"+flen+"'></div>";
		objCell.innerHTML += "<span id='file_help_"+flen+"' class='help-block col-xs-12'>이미지 - 사이즈(pixel) 730 X 350</span>";
		objCell.innerHTML += "<input type='file' class='col-xs-12 col-sm-12' name='bmd_image[]'>";
		objCell.innerHTML += "<span id='text_help_"+flen+"' class='help-block col-xs-12'>캡션내용</span>";
		objCell.innerHTML += "<div id='text_wrap_"+flen+"'><textarea name='bmd_content[]' class='col-xs-12 col-sm-12' style='height:50px;resize:none;'></textarea></div>";

		flen++;
		
		var iframeHeight = parent.document.getElementById("bookFrame").height * 1;
		parent.document.getElementById("bookFrame").height = iframeHeight + 290;
	}

	function del_file()
	{
		// file_length 이하로는 필드가 삭제되지 않아야 합니다.
		var file_length = 0;
		var objTbl = document.getElementById("variableFiles");
		if (objTbl.rows.length > file_length)
		{
			objTbl.deleteRow(objTbl.rows.length - 1);
			flen--;

			var iframeHeight = parent.document.getElementById("bookFrame").height * 1;
			parent.document.getElementById("bookFrame").height = iframeHeight - 290;
		}
	}

	function type_change(type,num) {
		var type = type*1;
		var num = num*1;
		switch (type){
			case 1:
				document.getElementById('big_file_'+num).style.display = "";
				document.getElementById('file_help_'+num).innerHTML = "이미지 - 사이즈(pixel) 730 X 350";
				document.getElementById('text_help_'+num).innerHTML = "캡션내용";
				document.getElementById('text_wrap_'+num).innerHTML = "<textarea name='bmd_content[]' class='col-xs-12 col-sm-12' style='height:50px;resize:none;'></textarea>";
				break;
			case 2:
				document.getElementById('big_file_'+num).style.display = "none";
				document.getElementById('file_help_'+num).innerHTML = "동영상 썸네일 - 사이즈(pixel) 730 X 350";
				document.getElementById('text_help_'+num).innerHTML = "유튜브 코드 - URL 끝부분 v= 다음에 있는 코드";
				document.getElementById('text_wrap_'+num).innerHTML = "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='bmd_content[]'>";
				break;
			case 3:
				document.getElementById('big_file_'+num).style.display = "";
				document.getElementById('file_help_'+num).innerHTML = "광고 이미지 - 사이즈(pixel) 730 X 350";
				document.getElementById('text_help_'+num).innerHTML = "광고 링크 URL";
				document.getElementById('text_wrap_'+num).innerHTML = "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='bmd_content[]'>";
				break;
			case 4:
				document.getElementById('big_file_'+num).style.display = "none";
				document.getElementById('file_help_'+num).innerHTML = "오디오 파일 - MP3";
				document.getElementById('text_help_'+num).innerHTML = "오디오 제목";
				document.getElementById('text_wrap_'+num).innerHTML = "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='bmd_content[]'>";
				break;
			default:
				break;
		}
	}

	function check_form() {
		if (bm_form.bm_image.value && !bm_form.bm_image.value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
			alert('이미지 파일만 업로드 가능합니다.');
			bm_form.bm_image.focus();
			return;
		}
		if (bm_form.bmd_type_main.value == ""){
			alert("제목을 입력하여 주십시오.");
			bm_form.bmd_type_main.focus();
			return;
		}

		var ls_str = bm_form.bmd_type_main.value; // 이벤트가 일어난 컨트롤의 value 값
		var li_str_len = ls_str.length; // 전체길이
		// 변수초기화
		var li_max = 70; // 제한할 글자수 크기
		var i = 0; // for문에 사용
		var li_byte = 0; // 한글일경우는 2 그밗에는 1을 더함
		var li_len = 0; // substring하기 위해서 사용
		var ls_one_char = ""; // 한글자씩 검사한다
		var ls_str2 = ""; // 글자수를 초과하면 제한할수 글자전까지만 보여준다.
		for(i=0; i< li_str_len; i++){
			ls_one_char = ls_str.charAt(i);
			if (escape(ls_one_char).length > 4){
				li_byte += 2;
			}else{
				li_byte++;
			}
			if(li_byte <= li_max){
				li_len = i + 1;
			}
		}
		if(li_byte > li_max){
			alert('제목은 70Byte이하로 입력하여 주십시오.');
			bm_form.bmd_type_main.focus();
			return;
		}

		if (bm_form.bmd_image_main.value == ""){
			alert("기자를 입력하여 주십시오.");
			bm_form.bmd_image_main.focus();
			return;
		}

		ls_str = bm_form.bmd_image_main.value; // 이벤트가 일어난 컨트롤의 value 값
		li_str_len = ls_str.length; // 전체길이
		// 변수초기화
		li_max = 30; // 제한할 글자수 크기
		i = 0; // for문에 사용
		li_byte = 0; // 한글일경우는 2 그밗에는 1을 더함
		li_len = 0; // substring하기 위해서 사용
		ls_one_char = ""; // 한글자씩 검사한다
		ls_str2 = ""; // 글자수를 초과하면 제한할수 글자전까지만 보여준다.
		for(i=0; i< li_str_len; i++){
			ls_one_char = ls_str.charAt(i);
			if (escape(ls_one_char).length > 4){
				li_byte += 2;
			}else{
				li_byte++;
			}
			if(li_byte <= li_max){
				li_len = i + 1;
			}
		}
		if(li_byte > li_max){
			alert('기자는 30Byte이하로 입력하여 주십시오.');
			bm_form.bmd_image_main.focus();
			return;
		}

		if (bm_form.bmd_content_main.value == ""){
			alert("기사내용을 입력하여 주십시오.");
			bm_form.bmd_content_main.focus();
			return;
		}
		for(var a=0; a < flen; a++){
			if(document.getElementsByName('bmd_type[]')[a].value == 1 || document.getElementsByName('bmd_type[]')[a].value == 4){
				if (document.getElementById('bmd_big_image_'+a).value == "" && !document.getElementById('bmd_big_image_old_'+a)){
					alert("원본 이미지를 첨부하여 주십시오.");
					document.getElementById('bmd_big_image_'+a).focus();
					return;
				}
				else if(document.getElementById('bmd_big_image_'+a).value && !document.getElementById('bmd_big_image_'+a).value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
					alert("이미지 파일만 업로드 가능합니다.");
					document.getElementById('bmd_big_image_'+a).focus();
					return;
				}
			}
			if(document.getElementsByName('bmd_type[]')[a].value == 4){
				if (document.getElementsByName('bmd_image[]')[a].value == "" && !document.getElementsByName('bmd_image_old[]')[a]){
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
				if (document.getElementsByName('bmd_image[]')[a].value == "" && !document.getElementsByName('bmd_image_old[]')[a]){
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

	function page_delete(type,ep,gp,idx,no) { 
		if (confirm('삭제하시겠습니까?')) {
			location.href="media_multiple_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx+"&no="+no;
		}
	}
</script>

<?
include_once($iw['admin_path']."/_cg_tail.php");
?>