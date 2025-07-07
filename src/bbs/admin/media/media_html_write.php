<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

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

set_cookie("iw_upload",$upload_path,time()+36000);

include_once($iw['admin_path']."/_cg_head.php");

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("잘못된 접근입니다!","");
?>
<script language="Javascript" src="/webnote/webnote.js"></script>

<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="bm_form" name="bm_form" action="<?=$iw['admin_path']?>/media/media_html_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="bd_code" value="<?=$row['bd_code']?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
				<input type="hidden" name="bm_type" value="7" />
				<input type="hidden" name="bmd_order" value="0" />
					<div class="form-group">
						<label class="col-sm-2 control-label">스타일</label>
						<div class="col-sm-8">
							<span class="help-block col-xs-12">HTML 에디터</span>
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
						<label class="col-sm-2 control-label">제목</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="bmd_type">
							<span class="help-block col-xs-12">
								최대 70Byte 이하
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">내용</label>
						<div class="col-sm-8">
							<textarea name="contents1" style="height:300px;width:100%;" editor="webnote" tools="deny:strikethrough,superscript,subscript,indent,outdent,insertfirstparagraph,insertlastparagraph,images,textbox,note,table,sourcebox,emoticon,special_chars,fullscreen"></textarea>
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
		if (bm_form.bmd_type.value == ""){
			alert("제목을 입력하여 주십시오.");
			bm_form.bmd_type.focus();
			return;
		}

		var ls_str = bm_form.bmd_type.value; // 이벤트가 일어난 컨트롤의 value 값
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
			bm_form.bmd_type.focus();
			return;
		}
		if (bm_form.contents1.value == "") {
			alert("내용을 입력하여 주십시오.");
			focusWebNote("contents1");
			return;
		}
		bm_form.submit();
	}
</script>

<?php
include_once($iw['admin_path']."/_cg_tail.php");
?>



