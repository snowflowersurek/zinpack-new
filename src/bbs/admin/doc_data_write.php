<?php
include_once("_common.php");
if ($iw[type] != "doc" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

$dd_code = "dd".uniqid(rand());

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/doc/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$dd_code;

set_cookie("iw_upload",$upload_path,time()+36000);

include_once("_head.php");
?>
<script src="/include/ckeditor/ckeditor5.js"></script>
<script src="/include/ckeditor/ckeditor5-adapter.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-inbox"></i>
			컨텐츠몰
		</li>
		<li class="active">컨텐츠 관리</li>
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
			컨텐츠 관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				신규등록
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="dd_form" name="dd_form" action="<?=$iw['admin_path']?>/doc_data_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="dd_code" value="<?=$dd_code?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">카테고리</label>
						<div class="col-sm-11">
							<select class="col-xs-12 col-sm-8" name="cg_code">
								<option value="">선택</option>
								<?php
									$sql = "select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_deep = 1 order by hm_order asc,hm_no asc";
									$result = sql_query($sql);
									while($row = @sql_fetch_array($result)){
										$cg_code = $row["cg_code"];
										$hm_code = $row["hm_code"];
										$hm_name1 = $row["hm_name"];
										$state_sort = $row["state_sort"];
										
										if($state_sort == $iw[type]){
										$rows = sql_fetch("select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'");
										$cg_level_write = $rows["cg_level_write"];
								?>
									<option value="<?=$cg_code?>" <?php if{?>disabled<?php }?>><?=$hm_name1?></option>
									<?php
										}
										$sql2 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 2 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
										$result2 = sql_query($sql2);
										$middle_num = 0;
										while($row2 = @sql_fetch_array($result2)){
											$cg_code = $row2["cg_code"];
											$hm_code = $row2["hm_code"];
											$hm_name2 = $row2["hm_name"];
											$state_sort = $row2["state_sort"];
											
											if($state_sort == $iw[type]){
											$rows = sql_fetch("select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'");
											$cg_level_write = $rows["cg_level_write"];
									?>
										<option value="<?=$cg_code?>" <?php if{?>disabled<?php }?>><?=$hm_name1?> > <?=$hm_name2?></option>
										<?php
											}
											$sql3 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 3 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
											$result3 = sql_query($sql3);
											$small_num = 0;
											while($row3 = @sql_fetch_array($result3)){
												$cg_code = $row3["cg_code"];
												$hm_code = $row3["hm_code"];
												$hm_name3 = $row3["hm_name"];
												$state_sort = $row3["state_sort"];
												
												if($state_sort == $iw[type]){
												$rows = sql_fetch("select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'");
												$cg_level_write = $rows["cg_level_write"];
										?>
											<option value="<?=$cg_code?>" <?php if{?>disabled<?php }?>><?=$hm_name1?> > <?=$hm_name2?> > <?=$hm_name3?></option>
											<?php
												}
												$sql4 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 4 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
												$result4 = sql_query($sql4);
												$tint_num = 0;
												while($row4 = @sql_fetch_array($result4)){
													$cg_code = $row4["cg_code"];
													$hm_code = $row4["hm_code"];
													$hm_name4 = $row4["hm_name"];
													$state_sort = $row4["state_sort"];
													
													if($state_sort == $iw[type]){
													$rows = sql_fetch("select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'");
													$cg_level_write = $rows["cg_level_write"];
											?>
												<option value="<?=$cg_code?>" <?php if{?>disabled<?php }?>><?=$hm_name1?> > <?=$hm_name2?> > <?=$hm_name3?> > <?=$hm_name4?></option>
											<?php } ?><?php }
											}
										}
									}
								?>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠 파일</label>
						<div class="col-sm-11">
							<input type="file" class="col-xs-12 col-sm-8" name="dd_file">
							<span class="help-block col-xs-12">자료의 최대 파일크기는 200M byte 이하로 제한되어 있습니다.</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠 표지</label>
						<div class="col-sm-11">
							<input type="file" class="col-xs-12 col-sm-8" name="dd_image">
							<!--<span class="help-block col-xs-12">이미지사이즈(pixel) 300 X 400</span>-->
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠명</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="dd_subject" maxlength="100">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠 분량</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" name="dd_amount" maxlength="5">
							<select name="dd_type">
								<option value="">선택</option>
								<option value="1">쪽</option>
								<option value="2">분</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매가격</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" name="dd_price" maxlength="10"> Point
							<span class="help-block col-xs-12">무료는 0 Point</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">다운로드 유효기간</label>
						<div class="col-sm-11">
							<select name="dd_download">
								<option value="0">무제한</option>
								<?php for($i=1; $i<=100; $i++) {?>
									<option value="<?=$i?>"><?=$i?> 일</option>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">검색태그</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="dd_tag" maxlength="100">
							<span class="help-block col-xs-12">
								검색태그를 등록하시면 검색태그를 기준으로 검색이 가능합니다.
								<br />검색태그는 최대 100자까지 등록이 가능합니다.
								<br />검색태그는 컨텐츠명과 다르게 컨텐츠몰에서 노출되지 않습니다.
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠설명</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents1" name="contents1" height="300px"></textarea>
						</div>
					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/doc_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
								<i class="fa fa-undo"></i>
								취소
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
		if (dd_form.cg_code.value == ""){
			alert("카테고리를 선택하여 주십시오.");
			dd_form.cg_code.focus();
			return;
		}
		if (dd_form.dd_file.value == "") {
			alert('컨텐츠를 첨부하여 주십시오.');
			dd_form.dd_file.focus();
			return;
		}
		if (dd_form.dd_image.value && !dd_form.dd_image.value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
			alert('이미지 파일만 업로드 가능합니다.');
			dd_form.dd_image.focus();
			return;
		}
		if (dd_form.dd_subject.value == "") {
			alert('컨텐츠명을 입력하여 주십시오.');
			dd_form.dd_subject.focus();
			return;
		}
		if (dd_form.dd_amount.value == "") {
			alert('컨텐츠분량을 입력하여 주십시오.');
			dd_form.dd_amount.focus();
			return;
		}
		var e1 = dd_form.dd_amount;
		var num ="0123456789";
		event.returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			dd_form.dd_amount.focus();
			return;
		}
		if (e1.value < 1) {
			alert('1 이상의 숫자만 입력하여 주십시오.');
			dd_form.dd_amount.focus();
			return;
		}
		if (dd_form.dd_type.value == "") {
			alert('컨텐츠분량의 타입을 선택택하여 주십시오.');
			dd_form.dd_type.focus();
			return;
		}
		if (dd_form.dd_price.value == "") {
			alert('판매가격을 입력하여 주십시오.');
			dd_form.dd_price.focus();
			return;
		}
		e1 = dd_form.dd_price;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			dd_form.dd_price.focus();
			return;
		}
		if (e1.value < 0) {
			alert('최소 0 이상의 가격을 입력하여 주십시오.');
			dd_form.dd_price.focus();
			return;
		}
		if (dd_form.dd_tag.value == "") {
			alert('검색태그를 입력하여 주십시오.');
			dd_form.dd_tag.focus();
			return;
		}
	
		var ls_str = dd_form.dd_tag.value; // 이벤트가 일어난 컨트롤의 value 값
		var li_str_len = ls_str.length; // 전체길이

		// 변수초기화
		var li_max = 100; // 제한할 글자수 크기
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
			alert('검색태그는 100자이하로 입력하여 주십시오.');
			dd_form.dd_tag.focus();
			return;
		}
		if (CKEDITOR.instances.contents1.getData() == "") {
			alert("컨텐츠설명을 입력하여 주십시오.");
			CKEDITOR.instances.contents1.focus();
			return;
		}
		dd_form.submit();
	}
</script>
 
<?php
include_once("_tail.php");
?>



