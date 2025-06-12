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

include_once($iw['admin_path']."/_head.php");

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("잘못된 접근입니다!","");
$bd_file = explode(";", $row["bd_file"]);
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-home"></i>
			이북몰
		</li>
		<li class="active">이북 관리</li>
	</ul><!-- .breadcrumb -->

	<!--<div class="nav-search" id="nav-search">
		<form class="form-search">
			<span class="input-icon">
				<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off">
				<i class="fa fa-search"></i>
			</span>
		</form>
	</div>--><!-- #nav-search -->
</div>
<div class="page-content">
	<div class="page-header">
		<h1>
			이북 관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				PDF 원본
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="bd_form" name="bd_form" action="<?=$iw['admin_path']?>/pdf/pdf_data_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="bd_code" value="<?=$bd_code?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
					<div class="form-group">
						<label class="col-sm-3 control-label">이북코드</label>
						<div class="col-sm-9">
							<?=$row["bd_code"]?>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-3 control-label">제목</label>
						<div class="col-sm-9">
							<?=stripslashes($row["bd_subject"])?>
						</div>
					</div>
					<div class="space-4"></div>

					<?if($row["bd_file"]){?>
					<div class="form-group">
						<label class="col-sm-3 control-label">등록된 PDF</label>
						<div class="col-sm-9">
							<?=$bd_file[0]?> page (<?=$bd_file[1]?>*<?=$bd_file[2]?>)
						</div>
					</div>
					<div class="space-4"></div>
					<?}?>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">읽는 방향</label>
						<div class="col-sm-9">
							<label class="middle">
								<input type="radio" name="read_direction" value="1" <?if($bd_file[3]!=2){?>checked<?}?>>
								<span class="lbl"> 오른쪽 넘김</span>
							</label>
							<label class="middle">
								<input type="radio" name="read_direction" value="2" <?if($bd_file[3]==2){?>checked<?}?>>
								<span class="lbl"> 왼쪽 넘김</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-3 control-label">업로드 방식</label>
						<div class="col-sm-9">
							<label class="middle">
								<input type="radio" name="upload_type" value="pdf" onClick="javascript:check_type(this.value);" checked>
								<span class="lbl"> PDF</span>
							</label>
							<label class="middle">
								<input type="radio" name="upload_type" value="zip" onClick="javascript:check_type(this.value);">
								<span class="lbl"> ZIP (JPG 압축파일)</span>
							</label>
							<span class="help-block col-xs-12">
								ZIP파일의 경우 1.jpg, 2.jpg, 3.jpg ...파일명으로 등록
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-3 control-label" id="file_title">PDF파일</label>
						<div class="col-sm-9">
							<input type="file" class="col-xs-12 col-sm-8" name="bd_file">
							<span class="help-block col-xs-12">
								최대 파일크기는 200M byte 이하로 제한되어 있습니다.
							</span>
						</div>
					</div>
					<div class="space-4"></div>

				<div id="wrap_pdf">
					<div class="form-group">
						<label class="col-sm-3 control-label">PDF품질</label>
						<div class="col-sm-9">
							<label class="middle">
								<input type="radio" name="pdf_quality" value="30" checked>
								<span class="lbl"> 표준</span>
							</label>
							<label class="middle">
								<input type="radio" name="pdf_quality" value="50">
								<span class="lbl"> 고품질</span>
							</label>
							<label class="middle">
								<input type="radio" name="pdf_quality" value="100">
								<span class="lbl"> 최고품질</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-3 control-label">PDF사이즈</label>
						<div class="col-sm-9">
							<label class="middle">
								<input type="radio" name="pdf_size" value="1000" checked>
								<span class="lbl"> 표준</span>
							</label>
							<label class="middle">
								<input type="radio" name="pdf_size" value="1200">
								<span class="lbl"> 크게</span>
							</label>
							<label class="middle">
								<input type="radio" name="pdf_size" value="1500">
								<span class="lbl"> 아주크게</span>
							</label>
							<!--<label class="middle">
								<input type="radio" name="pdf_size" value="0">
								<span class="lbl"> 원본</span>
							</label>-->
						</div>
					</div>
				</div>
				<div id="wrap_zip" style="display:none;">
					<div class="form-group">
						<label class="col-sm-3 control-label">페이지 수</label>
						<div class="col-sm-9">
							<input type="text" placeholder="입력" name="zip_page" maxlength="4"> PAGE
						</div>
					</div>
					<div class="space-4"></div>
				</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9" id="submit_btn">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								원본 등록
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/book_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
								<i class="fa fa-undo"></i>
								취소
							</button>
							<button class="btn btn-success" type="button" onclick="location='<?=$iw['admin_path']?>/pdf/pdf_sample_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>'">
								<i class="fa fa-undo"></i>
								샘플
							</button>
						</div>
						<div class="col-md-offset-3 col-md-9" id="upload_btn" style="display:none;">
							<div class="btn btn-danger">
								<i class='fa fa-spinner fa-spin'></i> 업로드 진행중..
							</div>
						</div>
					</div>
				</form>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<script type="text/javascript">
	function check_type(state) {
		if(state == "pdf"){
			document.getElementById('wrap_pdf').style.display = "";
			document.getElementById('wrap_zip').style.display = "none";
			document.getElementById('file_title').innerHTML = "PDF파일";
		}else if(state == "zip"){
			document.getElementById('wrap_pdf').style.display = "none";
			document.getElementById('wrap_zip').style.display = "";
			document.getElementById('file_title').innerHTML = "ZIP파일";
		}
	}

	function check_form() {		
		if (document.getElementById('wrap_pdf').style.display != "none"){
			if (bd_form.bd_file.value != "" && !bd_form.bd_file.value.match(/(.pdf|.PDF)/)) { 
				alert('PDF 파일만 업로드 가능합니다.');
				bd_form.bd_file.focus();
				return;
			}
		}
		if (document.getElementById('wrap_zip').style.display != "none"){
			if (bd_form.bd_file.value != "" && !bd_form.bd_file.value.match(/(.zip|.ZIP)/)) { 
				alert('ZIP 파일만 업로드 가능합니다.');
				bd_form.bd_file.focus();
				return;
			}
			var e1;
			var num ="0123456789";
			event.returnValue = true;

			if (bd_form.zip_page.value == "") {
				alert('페이지 수를 입력하여 주십시오.');
				bd_form.zip_page.focus();
				return;
			}
			e1 = bd_form.zip_page;
			for (var i=0;i<e1.value.length;i++){
				if(-1 == num.indexOf(e1.value.charAt(i)))
				event.returnValue = false;
			}
			if (!event.returnValue){
				alert('숫자로만 입력가능한 항목입니다.');
				bd_form.zip_page.focus();
				return;
			}
		}
		document.bd_form.bd_file.readOnly ="true";
		document.getElementById('submit_btn').style.display ="none";
		document.getElementById('upload_btn').style.display ="";
		bd_form.submit();
	}
</script>

<?
include_once($iw['admin_path']."/_tail.php");
?>