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

include_once("_head.php");

$sql = "select * from $iw[book_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["bd_no"]) alert("잘못된 접근입니다!","");

$bd_content = stripslashes($row["bd_content"]);
$check_cg_code = $row["cg_code"];

$bd_subject = $row["bd_subject"];
$bd_subject = str_replace("\'", '&#039;', $bd_subject);
$bd_subject = str_replace('\"', '&quot;', $bd_subject);
?>
<script language="Javascript" src="/include/ckeditor/ckeditor.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-newspaper-o"></i>
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
				수정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="bd_form" name="bd_form" action="<?=$iw['admin_path']?>/book_data_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="bd_code" value="<?=$bd_code?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
				<input type="hidden" name="bd_image_old" value="<?=$row["bd_image"]?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">카테고리</label>
						<div class="col-sm-11">
							<select class="col-xs-12 col-sm-8" name="cg_code">
								<option value="">선택</option>
								<?
									$sql1 = "select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_deep = 1 order by hm_order asc,hm_no asc";
									$result1 = sql_query($sql1);
									while($row1 = @sql_fetch_array($result1)){
										$cg_code = $row1["cg_code"];
										$hm_code = $row1["hm_code"];
										$hm_name1 = $row1["hm_name"];
										$state_sort = $row1["state_sort"];
										
										if($state_sort == $iw[type]){
										$rows = sql_fetch("select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code'");
										$cg_level_write = $rows["cg_level_write"];
								?>
									<option value="<?=$cg_code?>" <?if($iw[mb_level] < $cg_level_write){?>disabled<?}else if($check_cg_code == $cg_code){?>selected<?}?>><?=$hm_name1?></option>
									<?
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
										<option value="<?=$cg_code?>" <?if($iw[mb_level] < $cg_level_write){?>disabled<?}else if($check_cg_code == $cg_code){?>selected<?}?>><?=$hm_name1?> > <?=$hm_name2?></option>
										<?
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
											<option value="<?=$cg_code?>" <?if($iw[mb_level] < $cg_level_write){?>disabled<?}else if($check_cg_code == $cg_code){?>selected<?}?>><?=$hm_name1?> > <?=$hm_name2?> > <?=$hm_name3?></option>
											<?
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
												<option value="<?=$cg_code?>" <?if($iw[mb_level] < $cg_level_write){?>disabled<?}else if($check_cg_code == $cg_code){?>selected<?}?>><?=$hm_name1?> > <?=$hm_name2?> > <?=$hm_name3?> > <?=$hm_name4?></option>
											<?
													}
												}
											}
										}
									}
								?>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">스타일</label>
						<div class="col-sm-11">
							<?
								if($row["bd_type"] == 1){
									echo "PDF";
								}else if($row["bd_type"] == 2){
									echo "미디어";
								}else if($row["bd_type"] == 3){
									echo "블로그";
								}else if($row["bd_type"] == 4){
									echo "논문";
								}
							?>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="bd_subject" maxlength="100" value="<?=$bd_subject?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">저자</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="bd_author" maxlength="100" value="<?=$row["bd_author"]?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">출판사</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="bd_publisher" maxlength="100" value="<?=$row["bd_publisher"]?>">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">검색태그</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="bd_tag" maxlength="100" value="<?=$row["bd_tag"]?>">
							<span class="help-block col-xs-12">
								검색태그를 등록하시면 검색태그를 기준으로 검색이 가능합니다.
								<br />검색태그는 최대 100자까지 등록이 가능합니다.
								<br />검색태그는 이북명과 다르게 이북몰에서 노출되지 않습니다.
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매가격</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" name="bd_price" maxlength="10" value="<?=$row["bd_price"]?>"> Point
							<span class="help-block col-xs-12">무료는 0 Point</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">이북 표지</label>
						<div class="col-sm-11">
							<input type="file" class="col-xs-12 col-sm-8" name="bd_image"> <?if($row["bd_image"]){?><a href="<?=$iw["path"].$upload_path."/".$row["bd_image"]?>" target="_blank">기존 이미지</a><?}?>
							<span class="help-block col-xs-12">최적사이즈(pixel) 300 X 400</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이북설명</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents1" name="contents1" height="300px"><?=$bd_content?></textarea>
						</div>
					</div>
					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/book_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$bd_code?>'">
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
		if (bd_form.cg_code.value == ""){
			alert("카테고리를 선택하여 주십시오.");
			bd_form.cg_code.focus();
			return;
		}
		if (bd_form.bd_subject.value == "") {
			alert('제목을 입력하여 주십시오.');
			bd_form.bd_subject.focus();
			return;
		}
		if (bd_form.bd_author.value == "") {
			alert('저자를 입력하여 주십시오.');
			bd_form.bd_author.focus();
			return;
		}
		if (bd_form.bd_publisher.value == "") {
			alert('출판사를 입력하여 주십시오.');
			bd_form.bd_publisher.focus();
			return;
		}
		if (bd_form.bd_tag.value == "") {
			alert('검색태그를 입력하여 주십시오.');
			bd_form.bd_tag.focus();
			return;
		}
	
		var ls_str = bd_form.bd_tag.value; // 이벤트가 일어난 컨트롤의 value 값
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
			bd_form.bd_tag.focus();
			return;
		}

		if (bd_form.bd_price.value == "") {
			alert('판매가격을 입력하여 주십시오.');
			bd_form.bd_price.focus();
			return;
		}

		var e1 = bd_form.bd_price;
		var num ="0123456789";
		event.returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			bd_form.bd_price.focus();
			return;
		}
		if (e1.value < 0) {
			alert('최소 0 이상의 가격을 입력하여 주십시오.');
			bd_form.bd_price.focus();
			return;
		}

		if (bd_form.bd_image.value != "" && !bd_form.bd_image.value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
			alert('이미지 파일만 업로드 가능합니다.');
			bd_form.bd_image.focus();
			return;
		}
		if (CKEDITOR.instances.contents1.getData() == "") {
			alert("이북설명을 입력하여 주십시오.");
			CKEDITOR.instances.contents1.focus();
			return;
		}
		bd_form.submit();
	}

</script>

<?
include_once("_tail.php");
?>