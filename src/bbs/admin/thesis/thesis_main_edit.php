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

$sql = "select * from $iw[book_main_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["bn_no"]) alert("잘못된 접근입니다!","");

$bn_sub_title = $row["bn_sub_title"];
$bn_sub_title = str_replace("\'", '&#039;', $bn_sub_title);
$bn_sub_title = str_replace('\"', '&quot;', $bn_sub_title);
?>
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="bn_form" name="bn_form" action="<?=$iw['admin_path']?>/thesis/thesis_main_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="bd_code" value="<?=$row['bd_code']?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
				<input type="hidden" name="bn_logo_old" value="<?=$row['bn_logo']?>" />
				<input type="hidden" name="bn_display" value="<?=$row['bn_display']?>" />
					<div class="form-group">
						<label class="col-sm-2 control-label">PDF 파일</label>
						<div class="col-sm-8">
							<input type="file" class="col-xs-12 col-sm-12" name="bn_file">
							<span class="help-block col-xs-12">
								최대 파일크기는 200M byte 이하로 제한되어 있습니다.
								<?php if($row["bn_file"]){?><br/><a href="<?=$upload_path?>/<?=$row["bn_file"]?>" target="_blank">PDF파일 보기</a><?php }?>
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">상단로고</label>
						<div class="col-sm-8">
							<input type="file" class="col-xs-12 col-sm-12" name="bn_logo">
							<span class="help-block col-xs-12">
								사이즈(pixel) 500 X 150
								<?php if($row["bn_logo"]){?><br/><a href="<?=$iw["path"].$upload_path."/".$row["bn_logo"]?>" target="_blank">기존 이미지</a><?php }?>
							</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">보조 제목</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="bn_sub_title" maxlength="30" value="<?=$bn_sub_title?>">
							<span class="help-block col-xs-12">글자수 20Byte 이하</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">배경색상</label>
						<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
							<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#ffffff" data-toggle="tooltip" name="bn_color" value="<?=$row["bn_color"]?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">글자색상</label>
						<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
							<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="bn_font" value="<?=$row["bn_font"]?>">
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
	function check_form() {
		<?php if($row['bn_display']!=1){?>
			if (bn_form.bn_file.value == ""){
				alert("PDF 파일을 입력하여 주십시오.");
				bn_form.bn_file.focus();
				return;
			}
			if (bn_form.bn_logo.value == ""){
				alert("상단로고를 입력하여 주십시오.");
				bn_form.bn_logo.focus();
				return;
			}
		<?php }?>
		if (bn_form.bn_file.value && !bn_form.bn_file.value.match(/(.pdf|.PDF)/)) { 
			alert('PDF 파일만 업로드 가능합니다.');
			bn_form.bn_file.focus();
			return;
		}
		if (bn_form.bn_logo.value && !bn_form.bn_logo.value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
			alert('이미지 파일만 업로드 가능합니다.');
			bn_form.bn_logo.focus();
			return;
		}

		var ls_str = bn_form.bn_sub_title.value; // 이벤트가 일어난 컨트롤의 value 값
		var li_str_len = ls_str.length; // 전체길이
		// 변수초기화
		var li_max = 20; // 제한할 글자수 크기
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
			alert('보조 제목은 20Byte이하로 입력하여 주십시오.');
			bn_form.bn_sub_title.focus();
			return;
		}
		if (bn_form.bn_color.value == ""){
			alert("배경색상을 입력하여 주십시오.");
			bn_form.bn_color.focus();
			return;
		}
		if (bn_form.bn_font.value == ""){
			alert("글자색상을 입력하여 주십시오.");
			bn_form.bn_font.focus();
			return;
		}
		bn_form.submit();
	}
</script>

<?php
include_once($iw['admin_path']."/_cg_tail.php");
?>



