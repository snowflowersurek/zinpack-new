<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_cg_head.php");

$hs_scrap = $_GET[scrap];
$hm_code = $_GET[menu];

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/main/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/_images';

$sql = "select ts_box_padding from $iw[theme_setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'";
$row = sql_fetch($sql);
$ts_box_padding = $row["ts_box_padding"];

$pixel_width_array = array('96px', '192px', '288px', '384px', '480px', '576px', '672px', '768px', '864px', '960px', '1056px', '1152px');
$pixel_height_array = array('72px', '144px', '216px', '288px', '360px', '432px', '504px', '576px');
?>

<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="hs_form" name="hs_form" action="<?=$iw['admin_path']?>/design_scrap_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
					<input type="hidden" name="hs_scrap" value="<?=$hs_scrap?>" />
					<input type="hidden" name="hm_code" value="<?=$hm_code?>" />
					<div class="form-group">
						<label class="col-sm-2 control-label">스크래퍼명</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="hs_name" maxlength="125">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">형태</label>
						<div class="col-sm-8">
							<label class="middle">
								<input type="radio" name="hs_style" value="3" onClick="javascript:check_style(this.value);" checked>
								<span class="lbl"> 토픽</span>
							</label>
							<label class="middle">
								<input type="radio" name="hs_style" value="2" onClick="javascript:check_style(this.value);">
								<span class="lbl"> 배너</span>
							</label>
							<label class="middle">
								<input type="radio" name="hs_style" value="1" onClick="javascript:check_style(this.value);">
								<span class="lbl"> 슬라이드</span>
							</label>
							<label class="middle">
								<input type="radio" name="hs_style" value="5" onClick="javascript:check_style(this.value);">
								<span class="lbl"> 댓글</span>
							</label>
							<label class="middle">
								<input type="radio" name="hs_style" value="6" onClick="javascript:check_style(this.value);">
								<span class="lbl"> 페이스북</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>

					<div id="wrap_size">
						<div class="form-group">
							<label class="col-sm-2 control-label">크기</label>
							<div class="col-sm-3">
								<select class="col-xs-12" name="hs_size_width" id="hs_size_width">
									<?
										for ($i=1; $i<=12; $i++) {
											echo "<option value='".$i."'>".$i."칸 (".$pixel_width_array[$i-1].")</option>";
										}
									?>
								</select>
							</div>
							<div class="col-sm-3">
								<select class="col-xs-12" name="hs_size_height" id="hs_size_height" onChange="javascript:topic_custom();">
									<?
										for ($i=1; $i<=8; $i++) {
											echo "<option value='".$i."'>".$i."줄 (".$pixel_height_array[$i-1].")</option>";
										}
									?>
								</select>
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">자료</label>
						<div class="col-sm-8">
							<label class="middle">
								<input type="radio" name="hs_type" value="menu" onClick="javascript:check_type(this.value);" checked>
								<span class="lbl"> 분류선택</span>
							</label>
							<label class="middle">
								<input type="radio" name="hs_type" value="custom" onClick="javascript:check_type(this.value);">
								<span class="lbl"> 직접입력</span>
							</label>
							<label class="middle">
								<input type="radio" name="hs_type" value="notice" onClick="javascript:check_type(this.value);">
								<span class="lbl"> 공지사항</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>

					<div id="wrap_category">
						<div class="form-group">
							<label class="col-sm-2 control-label">분류</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" name="hs_menu" id="hs_menu">
									<option value="all">전체</option>
								<?
									$sql = "select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_deep = 1 order by hm_order asc,hm_no asc";
									$result = sql_query($sql);
									while($row = @sql_fetch_array($result)){
										$hm_code = $row["hm_code"];
										$hm_name = stripslashes($row["hm_name"]);
								?>
									<option value="<?=$hm_code?>"><?=$hm_name?></option>
									<?
										$sql2 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 2 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
										$result2 = sql_query($sql2);
										while($row2 = @sql_fetch_array($result2)){
											$hm_code = $row2["hm_code"];
											$hm_name = stripslashes($row2["hm_name"]);
									?>
										<option value="<?=$hm_code?>">└<?=$hm_name?></option>
										<?
											$sql3 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 3 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
											$result3 = sql_query($sql3);
											while($row3 = @sql_fetch_array($result3)){
												$hm_code = $row3["hm_code"];
												$hm_name = stripslashes($row3["hm_name"]);
										?>
											<option value="<?=$hm_code?>">&nbsp;&nbsp;&nbsp;└<?=$hm_name?></option>
											<?
	
												$sql4 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 4 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
												$result4 = sql_query($sql4);
												while($row4 = @sql_fetch_array($result4)){
													$hm_code = $row4["hm_code"];
													$hm_name = stripslashes($row4["hm_name"]);
											?>
												<option value="<?=$hm_code?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└<?=$hm_name?></option>
											<?
												}
											}
										}
									}
								?>
								</select>
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div id="wrap_banner_cnt" style="display:none;">
						<div class="form-group">
							<label class="col-sm-2 control-label">조회 자료 수</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" name="hs_banner_cnt" id="hs_banner_cnt">
									<?
										for ($i=1; $i<=10; $i++) {
											echo "<option value='".$i."'>".$i."건</option>";
										}
									?>
								</select>
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div id="wrap_sort">
						<div class="form-group">
							<label class="col-sm-2 control-label">정렬</label>
							<div class="col-sm-8">
								<label class="middle">
									<input type="radio" name="hs_sort" value="new" checked>
									<span class="lbl"> 최신</span>
								</label>
								<label class="middle">
									<input type="radio" name="hs_sort" value="best">
									<span class="lbl"> 인기</span>
								</label>
								<label class="middle">
									<input type="radio" name="hs_sort" value="random">
									<span class="lbl"> 무작위</span>
								</label>
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div id="wrap_topic">
						<div class="form-group">
							<label class="col-sm-2 control-label">양식</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" name="hs_topic_type" id="hs_topic_type" onChange="javascript:topic_custom();">
									<option value="title" selected>제목</option>
									<option value="content">제목 + 내용</option>
									<option value="image">제목 + 내용 + 썸네일</option>
								</select>
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">박스 간격</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="hs_box_padding" maxlength="2" value="<?=$ts_box_padding?>">px (0 ~ 99)
						</div>
					</div>
					<div class="space-4"></div>

					<div id="color_top">
						<div class="form-group">
							<label class="col-sm-2 control-label">모서리 둥글게</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="hs_border_radius" maxlength="1" value="0">px (0 ~ 9)
							</div>
						</div>
						<div class="space-4"></div>
	
						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 두께</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="hs_border_width" maxlength="1" value="0">px (0 ~ 9)
							</div>
						</div>
						<div class="space-4"></div>
	
						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-3 col-sm-5" type="text" placeholder="#ffffff" data-toggle="tooltip" name="hs_border_color" maxlength="7" value="#ffffff">
							</div>
						</div>
						<div class="space-4"></div>
	
						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-3 col-sm-5" type="text" placeholder="#ffffff" data-toggle="tooltip" name="hs_bg_color" maxlength="7" value="#ffffff">
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div id="color_bottom">
						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="hs_bg_alpha" maxlength="3" value="100">% (0 ~ 100)
							</div>
						</div>
						<div class="space-4"></div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">제목 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-3 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="hs_title_color" maxlength="7" value="#000000">
							</div>
						</div>
						<div class="space-4"></div>
	
						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-3 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="hs_font_color" maxlength="7" value="#000000">
							</div>
						</div>
						<div class="space-4"></div>
	
						<div class="form-group">
							<label class="col-sm-2 control-label">마우스오버 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-3 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="hs_font_hover" maxlength="7" value="#000000">
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div id="topic_default">
						<div class="form-group">
							<label class="col-sm-2 control-label">개요 모양</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-8 col-sm-8" name="hs_topic_bullet" maxlength="100"> 최대 100자
							</div>
						</div>
						<div class="space-4"></div>
	
						<div class="form-group">
							<label class="col-sm-2 control-label">더보기 모양</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-8 col-sm-8" name="hs_topic_more" value="▶" maxlength="100"> 최대 100자
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div id="custom_topic" style="display:none;">
						<div class="form-group">
							<label class="col-sm-2 control-label">더보기 링크</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="hs_topic_link" maxlength="255">
							</div>
						</div>
						<div class="space-4"></div>
	
						<div class="form-group">
							<label class="col-sm-2 control-label">노출 갯수</label>
							<div class="col-sm-8" id="hs_topic_total">
								0개
							</div>
						</div>
						<div class="space-4"></div>
	
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span onclick="add_file_topic();" style="cursor:pointer;"><i class="fa fa-plus-square fa-lg"></i></span> 
								<span onclick="del_file_topic();" style="cursor:pointer;"><i class="fa fa-minus-square fa-lg"></i></span>
							</label>
							<div class="col-sm-8">
								<table id="topicFiles"></table>
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div id="custom_image" style="display:none;">
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span onclick="add_file();" style="cursor:pointer;"><i class="fa fa-plus-square fa-lg"></i></span> 
								<span onclick="del_file();" style="cursor:pointer;"><i class="fa fa-minus-square fa-lg"></i></span>
							</label>
							<div class="col-sm-8">
								<table id="variableFiles"></table>
							</div>
						</div>
					</div>

					<div id="custom_sns" style="display:none;">
						<div class="form-group">
							<label class="col-sm-2 control-label">페이지 URL</label>
							<div class="col-sm-8">
								<input type="text" placeholder="예: https://www.facebook.com/facebook" class="col-xs-12 col-sm-12" name="hs_sns_url" maxlength="255">
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
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
		var upload_count = 10;
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
		
		objCell.innerHTML = "<span class='help-block col-xs-8'><b>"+(flen+1)+". 링크URL</b></span>";
		objCell.innerHTML += "<input type='text' placeholder='순서(0~9)' class='col-xs-4' name='hs_file_sort[]' maxlength='1' value='"+(flen)+"'>";
		objCell.innerHTML += "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='hs_link[]' maxlength='255'>";
		
		objCell.innerHTML += "<span class='help-block col-xs-12'>이미지</span>";
		objCell.innerHTML += "<input type='file' class='col-xs-12 col-sm-12' name='hs_file[]'>";

		flen++;
		
		var iframeHeight = parent.document.getElementById("bannerFrame").height * 1;
		parent.document.getElementById("bannerFrame").height = iframeHeight + 60;
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

			var iframeHeight = parent.document.getElementById("bannerFrame").height * 1;
			parent.document.getElementById("bannerFrame").height = iframeHeight - 60;
		}
	}

	var flen_topic = 0;
	function add_file_topic()
	{
		var upload_count = 21;
		if (upload_count && flen_topic >= upload_count)
		{
			alert(upload_count+"개 까지만 등록 가능합니다.");
			return;
		}

		var objTbl;
		var objRow;
		var objCell;
		if (document.getElementById)
			objTbl = document.getElementById("topicFiles");
		else
			objTbl = document.all["topicFiles"];

		objRow = objTbl.insertRow(objTbl.rows.length);
		objCell = objRow.insertCell(0);
		
		objCell.innerHTML = "<span class='help-block col-xs-12'><b>"+(flen_topic+1)+". 링크URL</b></span>";
		objCell.innerHTML += "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='hs_link_topic[]' maxlength='255'>";

		objCell.innerHTML += "<span class='help-block col-xs-12'>제목</span>";
		objCell.innerHTML += "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='hs_title_topic[]' maxlength='125'>";
		
		if(flen_topic < 7){
			objCell.innerHTML += "<span class='help-block col-xs-12'>내용</span>";
			objCell.innerHTML += "<input type='text' placeholder='입력' class='col-xs-12 col-sm-12' name='hs_content_topic[]'>";

			objCell.innerHTML += "<span class='help-block col-xs-12'>썸네일 (60 x 60 px)</span>";
			objCell.innerHTML += "<input type='file' class='col-xs-12 col-sm-12' name='hs_file_topic[]'>";
		}

		flen_topic++;
		
		var iframeHeight = parent.document.getElementById("bannerFrame").height * 1;
		parent.document.getElementById("bannerFrame").height = iframeHeight + 280;
	}
	function del_file_topic()
	{
		// file_length 이하로는 필드가 삭제되지 않아야 합니다.
		var file_length = 0;
		var objTbl = document.getElementById("topicFiles");
		if (objTbl.rows.length > file_length)
		{
			objTbl.deleteRow(objTbl.rows.length - 1);
			flen_topic--;

			var iframeHeight = parent.document.getElementById("bannerFrame").height * 1;
			parent.document.getElementById("bannerFrame").height = iframeHeight - 280;
		}
	}

	function topic_custom() {
		if(hs_form.hs_style[0].checked == true && hs_form.hs_type[1].checked == true){
			var upload_count;
			var size = hs_form.hs_size_height.value;
			var topic = document.getElementById('hs_topic_type').value;
			if(topic == "title"){
				upload_count = (size-1) * 3;
			}else{
				upload_count = size - 1;
			}
			document.getElementById('hs_topic_total').innerHTML = upload_count+"개";
		}
	}

	function check_style(state) {
		if(state == "1"){
			hs_form.hs_type[0].disabled = true;
			hs_form.hs_type[1].checked = true;
			hs_form.hs_type[2].disabled = true;
			document.getElementById('wrap_size').style.display = "none";
			document.getElementById('color_top').style.display = "none";
			document.getElementById('color_bottom').style.display = "none";
			document.getElementById('wrap_topic').style.display = "none";
			document.getElementById('topic_default').style.display = "none";
			check_type("custom");
		}else if(state == "2"){
			hs_form.hs_type[0].disabled = false;
			hs_form.hs_type[1].disabled = false;
			hs_form.hs_type[2].disabled = true;
			hs_form.hs_type[0].checked = true;
			document.getElementById('wrap_size').style.display = "";
			document.getElementById('color_top').style.display = "";
			document.getElementById('color_bottom').style.display = "none";
			document.getElementById('wrap_topic').style.display = "none";
			document.getElementById('topic_default').style.display = "none";
			check_type("menu");
		}else if(state == "3"){
			hs_form.hs_type[0].disabled = false;
			hs_form.hs_type[1].disabled = false;
			hs_form.hs_type[2].disabled = false;
			hs_form.hs_type[0].checked = true;
			document.getElementById('wrap_size').style.display = "";
			document.getElementById('color_top').style.display = "";
			document.getElementById('color_bottom').style.display = "";
			document.getElementById('wrap_topic').style.display = "";
			document.getElementById('topic_default').style.display = "";
			check_type("menu");
		}else if(state == "5"){
			hs_form.hs_type[0].disabled = false;
			hs_form.hs_type[1].disabled = true;
			hs_form.hs_type[2].disabled = true;
			hs_form.hs_type[0].checked = true;
			document.getElementById('wrap_size').style.display = "";
			document.getElementById('color_top').style.display = "";
			document.getElementById('color_bottom').style.display = "";
			document.getElementById('wrap_topic').style.display = "none";
			document.getElementById('topic_default').style.display = "none";
			check_type("menu");
		}else if(state == "6"){
			hs_form.hs_type[0].disabled = true;
			hs_form.hs_type[1].disabled = false;
			hs_form.hs_type[2].disabled = true;
			hs_form.hs_type[1].checked = true;
			document.getElementById('wrap_size').style.display = "";
			document.getElementById('color_top').style.display = "";
			document.getElementById('color_bottom').style.display = "none";
			document.getElementById('wrap_topic').style.display = "none";
			document.getElementById('topic_default').style.display = "none";
			check_type("facebook");
		}
	}
	function check_type(state) {
		document.getElementById('custom_sns').style.display = "none";
		document.getElementById('wrap_banner_cnt').style.display = "none";
		
		if(state == "menu"){
			document.getElementById('wrap_category').style.display = "";
			document.getElementById('custom_topic').style.display = "none";
			document.getElementById('custom_image').style.display = "none";
			if(hs_form.hs_style[3].checked == true){
				document.getElementById('wrap_sort').style.display = "none";
			}else{
				document.getElementById('wrap_sort').style.display = "";
			}
			if(hs_form.hs_style[1].checked == true){
				document.getElementById('wrap_banner_cnt').style.display = "";
			}
		}else if(state == "notice"){
			document.getElementById('wrap_category').style.display = "none";
			document.getElementById('wrap_sort').style.display = "";
			document.getElementById('custom_topic').style.display = "none";
			document.getElementById('custom_image').style.display = "none";
		}else if(state == "custom"){
			document.getElementById('wrap_category').style.display = "none";
			document.getElementById('wrap_sort').style.display = "none";
			if(hs_form.hs_style[0].checked == true){
				document.getElementById('custom_topic').style.display = "";
				document.getElementById('custom_image').style.display = "none";
				topic_custom();
			}else if(hs_form.hs_style[1].checked == true){
				document.getElementById('custom_topic').style.display = "none";
				document.getElementById('custom_image').style.display = "";
			}else if(hs_form.hs_style[2].checked == true){
				document.getElementById('custom_topic').style.display = "none";
				document.getElementById('custom_image').style.display = "";
			}
		}else if(state == "facebook"){
			document.getElementById('wrap_category').style.display = "none";
			document.getElementById('wrap_sort').style.display = "none";
			document.getElementById('custom_topic').style.display = "none";
			document.getElementById('custom_image').style.display = "none";
			document.getElementById('custom_sns').style.display = "";
		}
	}

	function check_form() {
		if (hs_form.hs_name.value == ""){
			alert("스크래퍼명을 입력하여 주십시오.");
			hs_form.hs_name.focus();
			return;
		}
		if (hs_form.hs_box_padding.value == ""){
			alert("박스 간격을 입력하여 주십시오.");
			hs_form.hs_box_padding.focus();
			return;
		}
		if (document.getElementById('color_top').style.display != "none"){
			if (hs_form.hs_border_radius.value == ""){
				alert("테두리 둥글게를 입력하여 주십시오.");
				hs_form.hs_border_radius.focus();
				return;
			}
			if (hs_form.hs_border_width.value == ""){
				alert("테두리 두께를 입력하여 주십시오.");
				hs_form.hs_border_width.focus();
				return;
			}
			if (hs_form.hs_border_color.value == ""){
				alert("테두리 색상을 입력하여 주십시오.");
				hs_form.hs_border_color.focus();
				return;
			}
			if (hs_form.hs_bg_color.value == ""){
				alert("배경 색상을 입력하여 주십시오.");
				hs_form.hs_bg_color.focus();
				return;
			}
		}
		if (document.getElementById('color_bottom').style.display != "none"){
			if (hs_form.hs_bg_alpha.value == "" || hs_form.hs_bg_alpha.value > 100){
				alert("배경 투명도를 입력하여 주십시오.");
				hs_form.hs_bg_alpha.focus();
				return;
			}
			if (hs_form.hs_title_color.value == ""){
				alert("제목 색상을 입력하여 주십시오.");
				hs_form.hs_title_color.focus();
				return;
			}
			if (hs_form.hs_font_color.value == ""){
				alert("글자 색상을 입력하여 주십시오.");
				hs_form.hs_font_color.focus();
				return;
			}
			if (hs_form.hs_font_hover.value == ""){
				alert("마우스오버 색상을 입력하여 주십시오.");
				hs_form.hs_font_hover.focus();
				return;
			}
		}
		
		if (document.getElementById('topic_default').style.display != "none"){
			if (hs_form.hs_topic_more.value == ""){
				alert("더보기 모양을 입력하여 주십시오.");
				hs_form.hs_topic_more.focus();
				return;
			}
		}

		if (document.getElementById('custom_image').style.display != "none"){
			for(var a=0; a < flen; a++){
				if (document.getElementsByName('hs_file_sort[]')[a].value == "") {
					alert('노출순서를 입력하여 주십시오.');
					document.getElementsByName('hs_file_sort[]')[a].focus();
					return;
				}
// 				if (document.getElementsByName('hs_link[]')[a].value == "") {
// 					alert('링크URL을 입력하여 주십시오.');
// 					document.getElementsByName('hs_link[]')[a].focus();
// 					return;
// 				}
				if (document.getElementsByName('hs_file[]')[a].value == ""){
					alert("이미지를 첨부하여 주십시오.");
					document.getElementsByName('hs_file[]')[a].focus();
					return;
				}
			}
		}
		if (document.getElementById('custom_topic').style.display != "none"){
			var topic = document.getElementById('hs_topic_type').value;
			for(var a=0; a < flen_topic; a++){
// 				if (document.getElementsByName('hs_link_topic[]')[a].value == "") {
// 					alert('링크URL을 입력하여 주십시오.');
// 					document.getElementsByName('hs_link_topic[]')[a].focus();
// 					return;
// 				}
				if (document.getElementsByName('hs_title_topic[]')[a].value == "") {
					alert('제목을 입력하여 주십시오.');
					document.getElementsByName('hs_title_topic[]')[a].focus();
					return;
				}
				if(a < 7){
					if(topic == "image" || topic == "content"){
						if (document.getElementsByName('hs_content_topic[]')[a].value == "") {
							alert('내용을 입력하여 주십시오.');
							document.getElementsByName('hs_content_topic[]')[a].focus();
							return;
						}
					}
					if(topic == "image"){
						if (document.getElementsByName('hs_file_topic[]')[a].value == ""){
							alert("썸네일을 첨부하여 주십시오.");
							document.getElementsByName('hs_file_topic[]')[a].focus();
							return;
						}
					}
				}
			}
		}

		if (document.getElementById('custom_sns').style.display != "none"){
			if (hs_form.hs_sns_url.value == ""){
				alert("페이스북 URL을 입력하여 주십시오.");
				hs_form.hs_sns_url.focus();
				return;
			}

			if (hs_form.hs_size_width.value < 3) {
				alert("크기를 가로 3칸 이상 선택하여 주십시오.");
				hs_form.hs_size_width.focus();
				return;
			}

			if (hs_form.hs_size_height.value < 3) {
				alert("크기를 세로 3줄 이상 선택하여 주십시오.");
				hs_form.hs_size_height.focus();
				return;
			}
		}
		
		hs_form.submit();
	}
</script>

<?
include_once("_cg_tail.php");
?>