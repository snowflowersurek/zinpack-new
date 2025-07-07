<?php
include_once("_common.php");
if ($iw[type] != "shop" || !($iw[level] == "seller" || $iw[level] == "admin")) alert("잘못된 접근입니다!","");

$sd_code = "sd".uniqid(rand());

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/shop/".$row[ep_nick];

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= '/'.$sd_code;
set_cookie("iw_upload",$upload_path,time()+36000);

include_once("_head.php");
?>
<script language="Javascript" src="/include/ckeditor/ckeditor.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-shopping-cart"></i>
			쇼핑몰
		</li>
		<li class="active">상품관리</li>
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
			상품관리
			<small>
				<i class="fa fa-angle-double-right"></i>
				등록
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="sd_form" name="sd_form" action="<?=$iw['admin_path']?>/shop_data_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="sd_code" value="<?=$sd_code?>" />
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
						<label class="col-sm-1 control-label">소비자가</label>
						<div class="col-sm-11">
							<?php if($iw[language]=="ko"){?>
								<input type="text" placeholder="입력" name="sd_price" maxlength="10"> 원
							<?php }else if($iw[language]=="en"){?>
								US$ <input type="text" placeholder="입력" name="sd_price" maxlength="8" style="text-align:right"> . <input type="text" placeholder="입력" name="sd_price_2" maxlength="2" size='2'>
							<?php }?>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">판매가격</label>
						<div class="col-sm-11">
							<?php if($iw[language]=="ko"){?>
								<input type="text" placeholder="입력" name="sd_sale" maxlength="10"> 원
							<?php }else if($iw[language]=="en"){?>
								US$ <input type="text" placeholder="입력" name="sd_sale" maxlength="8" style="text-align:right"> . <input type="text" placeholder="입력" name="sd_sale_2" maxlength="2" size='2'>
							<?php }?>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품명</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="sd_subject" maxlength="100">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품태그</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="sd_tag" maxlength="100">
							<span class="help-block col-xs-12">
								상품태그를 등록하시면 검색태그를 기준으로 검색이 가능합니다.
								<br />상품태그는 최대 100자까지 등록이 가능합니다.
								<br />상품태그는 컨텐츠명과 다르게 컨텐츠몰에서 노출되지 않습니다.
							</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">최대수량</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" name="sd_max" maxlength="4"> 개 이하
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">배송코드</label>
						<div class="col-sm-11">
							<select name="sy_code">
								<option value="">선택</option>
								<?php
									$sql2 = "select * from $iw[shop_delivery_table] where ep_code = '$iw[store]' and mb_code='$iw[member]' order by sy_no asc";
									$result2 = sql_query($sql2);

									while($row2 = @sql_fetch_array($result2)){
										$sy_code = $row2["sy_code"];
										$sy_price = $row2["sy_price"];
										$sy_max = $row2["sy_max"];
										$sy_display = $row2["sy_display"];
								?>
									<option value="<?=$sy_code?>">[<?=$sy_code?>] <?=national_money($iw[language], $sy_price);?> (
									<?php if($sy_display == 1){?>
										<?=national_money($iw[language], $sy_max);?> 이상 무료배송
									<?php }else{?>
										<?=$sy_max?> <?=national_language($iw[language],"a0215","개");?> 이하 묶음배송
									<?php }?>)
									</option>
								<?php
									}
								?>
							</select>
							<span class="help-block col-xs-12">배송코드관리에서 생성 - 동일 코드의 최대수량으로 묶음배송 가능</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품정보</label>
						<div class="col-sm-11">
							<textarea name="sd_information" class="col-xs-12 col-sm-8" style="height:100px;"></textarea>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품설명</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents1" name="contents1" height="300px"></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상품메인</label>
						<div class="col-sm-11">
							<input type="file" class="col-xs-12 col-sm-8" name="sd_image">
							<!--<span class="help-block col-xs-12">이미지사이즈(pixel) 450 X 450</span>-->
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">
							상품옵션
							<span onclick="add_file();" style="cursor:pointer;"><img src="<?=$iw['bbs_img_path']?>/admin_list_plus.gif" /></span> 
							<span onclick="del_file();" style="cursor:pointer;"><img src="<?=$iw['bbs_img_path']?>/admin_list_minus.gif" /></span>
						</label>
						<div class="col-sm-11">
							<div style="max-width:1000px;">
								<table class="table table-striped table-bordered table-hover dataTable" id="variableFiles"></table>
							</div>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/shop_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
	var flen = 0;
	function add_file(delete_code)
	{
		var upload_count = 100;
		if (upload_count && flen >= upload_count)
		{
			alert("옵션은 "+upload_count+"개 까지만 등록 가능합니다.");
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

		objCell.innerHTML = flen+1+". 옵션명<input type='text' placeholder='입력' name='so_name[]'>";
		objCell.innerHTML += " 수량<input type='text' placeholder='입력' maxlength='10' name='so_amount[]'>";

		<?php if($iw[language]=="ko"){?>
			objCell.innerHTML += " 가격<input type='text' placeholder='입력' maxlength='10' name='so_price[]'>";
		<?php }else if($iw[language]=="en"){?>
			objCell.innerHTML += " 가격<input type='text' placeholder='입력' maxlength='8' name='so_price[]' style='text-align:right'> . <input type='text' placeholder='입력' name='so_price_2[]' maxlength='2' size='2'>";
		<?php }?>

			objCell.innerHTML += " <select name='so_taxfree[]'><option value='0'>부가세포함</option><option value='1'>면세상품</option></select>";
		
		flen++;
	}

	function del_file()
	{
		// file_length 이하로는 필드가 삭제되지 않아야 합니다.
		var file_length = 0;
		var objTbl = document.getElementById("variableFiles");
		if (objTbl.rows.length - 1 > file_length)
		{
			objTbl.deleteRow(objTbl.rows.length - 1);
			flen--;
		}
	}
	add_file();

	function check_form() {
		if (sd_form.cg_code.value == ""){
			alert("카테고리를 선택하여 주십시오.");
			sd_form.cg_code.focus();
			return;
		}

		var e1;
		var num ="0123456789";
		event.returnValue = true;

		if (sd_form.sd_price.value == "") {
			alert('소비자가를 입력하여 주십시오.');
			sd_form.sd_price.focus();
			return;
		}
		e1 = sd_form.sd_price;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			sd_form.sd_price.focus();
			return;
		}
		if (sd_form.sd_sale.value == "") {
			alert('판매가격을 입력하여 주십시오.');
			sd_form.sd_sale.focus();
			return;
		}
		e1 = sd_form.sd_sale;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			sd_form.sd_sale.focus();
			return;
		}
	<?php if($iw[language]=="en"){?>
		if (sd_form.sd_price_2.value.length < 2) {
			alert('소수점이하는 2글자 입력하여 주십시오.');
			sd_form.sd_price_2.focus();
			return;
		}
		e1 = sd_form.sd_price_2;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
		if (sd_form.sd_sale_2.value.length < 2) {
			alert('소수점이하는 2글자 입력하여 주십시오.');
			sd_form.sd_sale_2.focus();
			return;
		}
		e1 = sd_form.sd_sale_2;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			e1.focus();
			return;
		}
	<?php }?>
		if (sd_form.sd_subject.value == "") {
			alert('상품명을 입력하여 주십시오.');
			sd_form.sd_subject.focus();
			return;
		}
		if (sd_form.sd_tag.value == "") {
			alert('상품태그를 입력하여 주십시오.');
			sd_form.sd_tag.focus();
			return;
		}
		var ls_str = sd_form.sd_tag.value; // 이벤트가 일어난 컨트롤의 value 값
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
			alert('상품태그는 100자이하로 입력하여 주십시오.');
			sd_form.sd_tag.focus();
			return;
		}
		if (sd_form.sd_max.value == "") {
			alert('최대수량을 입력하여 주십시오.');
			sd_form.sd_max.focus();
			return;
		}
		if (sd_form.sy_code.value == "") {
			alert('배송코드를 선택하여 주십시오.');
			sd_form.sy_code.focus();
			return;
		}
		if (sd_form.sd_information.value == "") {
			alert('상품정보를 입력하여 주십시오.');
			sd_form.sd_information.focus();
			return;
		}
		e1 = sd_form.sd_max;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			sd_form.sd_max.focus();
			return;
		}
		if (CKEDITOR.instances.contents1.getData() == "") {
			alert("상품설명을 입력하여 주십시오.");
			CKEDITOR.instances.contents1.focus();
			return;
		}
		if (sd_form.sd_image.value == "") {
			alert('이미지를 등록하여 주십시오.');
			sd_form.sd_image.focus();
			return;
		}
		if(!sd_form.sd_image.value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
			alert('이미지 파일만 업로드 가능합니다.');
			sd_form.sd_image.focus();
			return;
		}
		for(var a=0; a < flen; a++){
			if (document.getElementsByName('so_name[]')[a].value == "") {
				alert('옵션명을 입력하여 주십시오.');
				document.getElementsByName('so_name[]')[a].focus();
				return;
			}
			if (document.getElementsByName('so_amount[]')[a].value == "") {
				alert('수량을 입력하여 주십시오.');
				document.getElementsByName('so_amount[]')[a].focus();
				return;
			}
			e1 = document.getElementsByName('so_amount[]')[a];
			for (var i=0;i<e1.value.length;i++){
				if(-1 == num.indexOf(e1.value.charAt(i)))
				event.returnValue = false;
			}
			if (!event.returnValue){
				alert('숫자로만 입력가능한 항목입니다.');
				e1.focus();
				return;
			}
			if (document.getElementsByName('so_price[]')[a].value == "") {
				alert('가격을 입력하여 주십시오.');
				document.getElementsByName('so_price[]')[a].focus();
				return;
			}
			e1 = document.getElementsByName('so_price[]')[a];
			for (var i=0;i<e1.value.length;i++){
				if(-1 == num.indexOf(e1.value.charAt(i)))
				event.returnValue = false;
			}
			if (!event.returnValue){
				alert('숫자로만 입력가능한 항목입니다.');
				e1.focus();
				return;
			}
		<?php if($iw[language]=="en"){?>
			if (document.getElementsByName('so_price_2[]')[a].value.length < 2) {
				alert('소수점이하는 2글자 입력하여 주십시오.');
				document.getElementsByName('so_price_2[]')[a].focus();
				return;
			}
			e1 = document.getElementsByName('so_price_2[]')[a];
			for (var i=0;i<e1.value.length;i++){
				if(-1 == num.indexOf(e1.value.charAt(i)))
				event.returnValue = false;
			}
			if (!event.returnValue){
				alert('숫자로만 입력가능한 항목입니다.');
				e1.focus();
				return;
			}
		<?php }?>
		}
		sd_form.submit();
	}
</script>
 
<?php
include_once("_tail.php");
?>



