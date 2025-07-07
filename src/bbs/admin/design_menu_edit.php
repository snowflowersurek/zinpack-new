<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
include_once("_cg_head.php");

$row = sql_fetch(" select * from $iw[home_menu_table] where hm_code = '$_GET[menu]' and ep_code = '$iw[store]' and gp_code = '$iw[group]'");
$hm_code = $row[hm_code];
$cg_code = $row[cg_code];
$state_sort = $row[state_sort];
$hm_name = $row[hm_name];
$hm_list_scrap = $row[hm_list_scrap];
$hm_view_scrap = $row[hm_view_scrap];
$hm_list_style = $row[hm_list_style];
$hm_link = $row[hm_link];
$hm_view_size = $row[hm_view_size];
$hm_view_scrap_mobile = $row[hm_view_scrap_mobile];
$hm_list_order = $row[hm_list_order];

$hm_name = str_replace("\'", '&#039;', $hm_name);
$hm_name = str_replace('\"', '&quot;', $hm_name);

$category_mcb = "<option value=''>선택</option>";
$category_publishing = "<option value=''>선택</option>";
$category_publishing_brand = "<option value=''>선택</option>";
$category_publishing_contest = "<option value=''>선택</option>";
$category_author = "<option value='author' selected>저자</option>";
$category_exhibit = "<option value='exhibit' selected>그림전시</option>";
$category_shop = "<option value=''>선택</option>";
$category_doc = "<option value=''>선택</option>";
$category_book = "<option value=''>선택</option>";
$category_about = "<option value=''>선택</option>";

$result = sql_query("select cg_code,cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'mcb' order by cg_name asc");
while($row = @sql_fetch_array($result)){
	$category_mcb .= "<option value='".$row[cg_code]."'";
	if($cg_code==$row[cg_code]) $category_mcb .= "selected";
	$category_mcb .= ">".$row[cg_name]."</option>";
}
$result = sql_query("select cg_code,cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'publishing' order by cg_name asc");
while($row = @sql_fetch_array($result)){
	$category_publishing .= "<option value='".$row[cg_code]."'";
	if($cg_code==$row[cg_code]) $category_publishing .= "selected";
	$category_publishing .= ">".$row[cg_name]."</option>";
}
$result = sql_query("select cg_code,cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'publishing_brand' order by cg_name asc");
while($row = @sql_fetch_array($result)){
	$category_publishing_brand .= "<option value='".$row[cg_code]."'";
	if($cg_code==$row[cg_code]) $category_publishing_brand .= "selected";
	$category_publishing_brand .= ">".$row[cg_name]."</option>";
}
$result = sql_query("select cg_code,cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'publishing_contest' order by cg_name asc");
while($row = @sql_fetch_array($result)){
	$category_publishing_contest .= "<option value='".$row[cg_code]."'";
	if($cg_code==$row[cg_code]) $category_publishing_contest .= "selected";
	$category_publishing_contest .= ">".$row[cg_name]."</option>";
}
$result = sql_query("select cg_code,cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'shop' order by cg_name asc");
while($row = @sql_fetch_array($result)){
	$category_shop .= "<option value='".$row[cg_code]."'";
	if($cg_code==$row[cg_code]) $category_shop .= "selected";
	$category_shop .= ">".$row[cg_name]."</option>";
}
$result = sql_query("select cg_code,cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'doc' order by cg_name asc");
while($row = @sql_fetch_array($result)){
	$category_doc .= "<option value='".$row[cg_code]."'";
	if($cg_code==$row[cg_code]) $category_doc .= "selected";
	$category_doc .= ">".$row[cg_name]."</option>";
}
$result = sql_query("select cg_code,cg_name from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'book' order by cg_name asc");
while($row = @sql_fetch_array($result)){
	$category_book .= "<option value='".$row[cg_code]."'";
	if($cg_code==$row[cg_code]) $category_book .= "selected";
	$category_book .= ">".$row[cg_name]."</option>";
}
$result = sql_query("select ad_code,ad_subject from $iw[about_data_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and mb_code='$iw[member]' order by ad_subject asc");
while($row = @sql_fetch_array($result)){
	$category_about .= "<option value='".$row[ad_code]."'";
	if($cg_code==$row[ad_code]) $category_about .= "selected";
	$category_about .= ">".$row[ad_subject]."</option>";
}
?>
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="cg_form" name="cg_form" action="<?=$iw['admin_path']?>/design_menu_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="hm_code" value="<?=$hm_code?>" />
					<div class="form-group">
						<label class="col-sm-2 control-label">메뉴명</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="hm_name" value="<?=$hm_name?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">종류</label>
						<div class="col-sm-8">
							<input type="hidden" name="state_sort" id="state_sort" value="<?=$state_sort?>" />
						<?php if($iw[mcb]==1){?>
							<label class="middle">
								<input type="radio" name="menu_type" value="mcb" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 게시판</span>
							</label>
						<?php } ?><?php if($iw[publishing]==1){?>
							<label class="middle">
								<input type="radio" name="menu_type" value="publishing" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 출판도서</span>
							</label>
						<?php } ?><?php if($iw[shop]==1){?>
							<label class="middle">
								<input type="radio" name="menu_type" value="shop" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 쇼핑몰</span>
							</label>
						<?php } ?><?php if($iw[doc]==1){?>
							<label class="middle">
								<input type="radio" name="menu_type" value="doc" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 컨텐츠몰</span>
							</label>
						<?php } ?><?php if($iw[book]==1){?>
							<label class="middle">
								<input type="radio" name="menu_type" value="book" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 이북몰</span>
							</label>
						<?php }?>
							<label class="middle">
								<input type="radio" name="menu_type" value="about" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 독립페이지</span>
							</label>
							<label class="middle">
								<input type="radio" name="menu_type" value="open" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 분류(목록)</span>
							</label>
							<label class="middle">
								<input type="radio" name="menu_type" value="close" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 분류(비노출)</span>
							</label>
							<label class="middle">
								<input type="radio" name="menu_type" value="link" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 링크</span>
							</label>
							<label class="middle">
								<input type="radio" name="menu_type" value="scrap" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 스크래퍼</span>
							</label>
							<label class="middle">
								<input type="radio" name="menu_type" value="main" onClick="setMenuType(this.value);" <?php if{?>checked<?php }?>>
								<span class="lbl"> 메인</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div id="wrap_publishing" style="display:none;">
						<div class="form-group">
							<label class="col-sm-2 control-label">출판도서 메뉴</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" id="publishing_menu" onchange="setMenuType(this.value);">
									<option value="publishing" <?php if{?>selected<?php }?>>도서</option>
									<option value="publishing_brand" <?php if{?>selected<?php }?>>브랜드(출판사)</option>
									<option value="author" <?php if{?>selected<?php }?>>저자</option>
									<option value="publishing_contest" <?php if{?>selected<?php }?>>공모전</option>
									<option value="exhibit" <?php if{?>selected<?php }?>>그림전시</option>
									<option value="exhibit_monthly" <?php if{?>selected<?php }?>>월별 그림전시 현황</option>
									<option value="exhibit_application" <?php if{?>selected<?php }?>>그림전시 신청</option>
									<option value="exhibit_status" <?php if{?>selected<?php }?>>그림전시 나의 신청현황</option>
									<option value="lecture_application" <?php if{?>selected<?php }?>>작가강연 신청</option>
									<option value="lecture_status" <?php if{?>selected<?php }?>>작가강연 나의 신청현황</option>
								</select>
							</div>
						</div>
						<div class="space-4"></div>
					</div>
					
					<div id="wrap_category">
						<div class="form-group">
							<label class="col-sm-2 control-label">분류</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" name="cg_code" id="cg_code">
									<option value="">선택</option>
								</select>
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div id="wrap_list">
						<div class="form-group">
							<label class="col-sm-2 control-label">리스트 형식</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" name="hm_list_style">
									<option value="1" <?php if{?>selected<?php }?>>일반</option>
									<option value="2" <?php if{?>selected<?php }?>>2단</option>
									<option value="3" <?php if{?>selected<?php }?>>3단(정렬)</option>
									<option value="4" <?php if{?>selected<?php }?>>3단(쌓기)</option>
									<option value="5" <?php if{?>selected<?php }?>>3단(고정)</option>
									<option value="6" <?php if{?>selected<?php }?>>6단(정렬)</option>
									<option value="7" <?php if{?>selected<?php }?>>6단(쌓기)</option>
									<option value="8" <?php if{?>selected<?php }?>>6단(고정)</option>
								</select>
							</div>
						</div>
						<div class="space-4"></div>

						<div class="form-group">
							<label class="col-sm-2 control-label">우선 순위</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" name="hm_list_order">
									<option value="1" <?php if{?>selected<?php }?>>최신 작성순</option>
									<option value="2" <?php if{?>selected<?php }?>>최신 수정순</option>
								</select>
							</div>
						</div>
						<div class="space-4"></div>

						<div class="form-group">
							<label class="col-sm-2 control-label">목록 스크래퍼</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" name="hm_list_scrap">
									<option value="0" <?php if{?>selected<?php }?>>없음</option>
									<option value="1" <?php if{?>selected<?php }?>>노출</option>
								</select>
							</div>
						</div>
						<div class="space-4"></div>
					</div>
					<div id="wrap_scrap">
						<div class="form-group">
							<label class="col-sm-2 control-label">본문 스크래퍼</label>
							<div class="col-sm-8">
								<select class="col-xs-12 col-sm-8" name="hm_view_scrap" onChange="javascript:check_select(this.value);">
									<option value="0" <?php if{?>selected<?php }?>>없음</option>
									<option value="1" <?php if{?>selected<?php }?>>노출</option>
								</select>
							</div>
						</div>
						<div class="space-4"></div>

						<div id="wrap_scrap_option" <?php if{?>style="display:none;"<?php }?>>
							<div class="form-group">
								<label class="col-sm-2 control-label">본문 가로사이즈</label>
								<div class="col-sm-8">
									<select class="col-xs-12 col-sm-8" name="hm_view_size">
										<option value="1" <?php if{?>selected<?php }?>>1칸</option>
										<option value="2" <?php if{?>selected<?php }?>>2칸</option>
										<option value="3" <?php if{?>selected<?php }?>>3칸</option>
										<option value="4" <?php if{?>selected<?php }?>>4칸</option>
										<option value="5" <?php if{?>selected<?php }?>>5칸</option>
										<option value="6" <?php if{?>selected<?php }?>>6칸</option>
										<option value="7" <?php if{?>selected<?php }?>>7칸</option>
										<option value="8" <?php if{?>selected<?php }?>>8칸</option>
										<option value="9" <?php if{?>selected<?php }?>>9칸</option>
										<option value="10" <?php if{?>selected<?php }?>>10칸</option>
										<option value="11" <?php if{?>selected<?php }?>>11칸</option>
										<option value="12" <?php if{?>selected<?php }?>>12칸</option>
									</select>
								</div>
							</div>
							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-2 control-label">본문 스크래퍼 모바일</label>
								<div class="col-sm-8">
									<select class="col-xs-12 col-sm-8" name="hm_view_scrap_mobile">
										<option value="0" <?php if{?>selected<?php }?>>노출</option>
										<option value="1" <?php if{?>selected<?php }?>>숨김</option>
									</select>
								</div>
							</div>
							<div class="space-4"></div>
						</div>
					</div>

					<div id="wrap_link" style="display:none;">
						<div class="form-group">
							<label class="col-sm-2 control-label">링크URL</label>
							<div class="col-sm-8">
								<input type="text" placeholder="http://" class="col-xs-12 col-sm-12" name="hm_link" value="<?=$hm_link?>">
							</div>
						</div>
						<div class="space-4"></div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								수정하기
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

	setMenuType("<?=$state_sort?>");
	
	if (typeof $("input:radio[name=menu_type]:checked").val() === "undefined" && $("#publishing_menu").val() != "") {
		$("input:radio[name=menu_type][value=publishing]").prop("checked", true);
	}
	
	function setMenuType(menu_type) {
		$("#state_sort").val(menu_type);
		$("#cg_code").html("");
		$("#wrap_publishing").hide();
		$("#wrap_category").hide();
		$("#wrap_list").hide();
		$("#wrap_scrap").hide();
		$("#wrap_link").hide();
		
		switch (menu_type) {
			case "mcb":
				$("#cg_code").html("<?=$category_mcb?>");
				$("#wrap_category").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "publishing":
				$("#cg_code").html("<?=$category_publishing?>");
				$("#publishing_menu").val(menu_type);
				$("#wrap_publishing").show();
				$("#wrap_category").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "publishing_brand":
				$("#cg_code").html("<?=$category_publishing_brand?>");
				$("#wrap_publishing").show();
				$("#wrap_category").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "publishing_contest":
				$("#cg_code").html("<?=$category_publishing_contest?>");
				$("#wrap_publishing").show();
				$("#wrap_category").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "author":
				$("#cg_code").html("<?=$category_author?>");
				$("#wrap_publishing").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "exhibit":
				$("#cg_code").html("<?=$category_exhibit?>");
				$("#wrap_publishing").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "exhibit_monthly":
				$("#wrap_publishing").show();
				break;
				
			case "exhibit_application":
				$("#wrap_publishing").show();
				break;
				
			case "exhibit_status":
				$("#wrap_publishing").show();
				break;
				
			case "lecture_application":
				$("#wrap_publishing").show();
				break;
				
			case "lecture_status":
				$("#wrap_publishing").show();
				break;
				
			case "shop":
				$("#cg_code").html("<?=$category_shop?>");
				$("#wrap_category").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "doc":
				$("#cg_code").html("<?=$category_doc?>");
				$("#wrap_category").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "book":
				$("#cg_code").html("<?=$category_book?>");
				$("#wrap_category").show();
				$("#wrap_list").show();
				$("#wrap_scrap").show();
				break;
				
			case "about":
				$("#cg_code").html("<?=$category_about?>");
				$("#wrap_category").show();
				break;
				
			case "open":
				$("#wrap_list").show();
				break;
				
			case "close":
				break;
				
			case "link":
				$("#wrap_link").show();
				break;
				
			case "scrap":
				break;
				
			case "main":
				break;
		}
	}

	function check_select(slt) {
		if(slt=="0"){
			document.getElementById('wrap_scrap_option').style.display = "none";
		}else{
			document.getElementById('wrap_scrap_option').style.display = "";
		}
	}

	function check_form() {
		if (cg_form.hm_name.value == ""){
			alert("메뉴명을 입력해주세요");
			cg_form.hm_name.focus();
			return;
		}
		if (cg_form.state_sort.value == "") {
			alert("메뉴종류를 선택해주세요");
			return;
		}
		if (document.getElementById('wrap_category').style.display != "none" && cg_form.cg_code.value == ""){
			alert("분류를 선택해주세요");
			cg_form.cg_code.focus();
			return;
		}
		if (document.getElementById('wrap_link').style.display != "none" && cg_form.hm_link.value == ""){
			alert("링크를 입력해주세요");
			cg_form.hm_link.focus();
			return;
		}
		cg_form.submit();
	}
</script>

<?php
include_once("_cg_tail.php");
?>



