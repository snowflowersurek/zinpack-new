<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/main/$row[ep_nick]";

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}
$upload_path .= "/_images";
set_cookie("iw_upload",$upload_path,time()+36000);

include_once("_head.php");

$sql = "select * from $iw[setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'";
$row = sql_fetch($sql);
if (!$row["st_no"]) alert("잘못된 접근입니다!","");

$st_no = $row["st_no"];
$st_title = stripslashes($row["st_title"]);
$st_content = stripslashes($row["st_content"]);
$st_top_img = $row["st_top_img"];
$st_top_align = $row["st_top_align"];
$st_favicon = $row["st_favicon"];
$st_mcb_name = $row["st_mcb_name"];
$st_publishing_name = $row["st_publishing_name"];
$st_doc_name = $row["st_doc_name"];
$st_shop_name = $row["st_shop_name"];
$st_book_name = $row["st_book_name"];
$state_sort = $row["state_sort"];
$st_background = explode(",",$row["st_background"]);
$hm_code_check = $row["hm_code"];
$st_menu_position = $row["st_menu_position"];
$st_menu_mobile = $row["st_menu_mobile"];
$st_navigation = $row["st_navigation"];
$st_slide_size = $row["st_slide_size"];
$st_sns_share = $row["st_sns_share"];
?>
<script language="Javascript" src="/include/ckeditor/ckeditor.js"></script>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-object-group"></i>
			디자인 설정
		</li>
		<li class="active">기본</li>
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
			기본
			<small>
				<i class="fa fa-angle-double-right"></i>
				설정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="st_form" name="st_form" action="<?=$iw['admin_path']?>/design_default_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="st_no" value="<?=$st_no?>" />
				<input type="hidden" name="upload_path" value="<?=$upload_path?>" />
				<input type="hidden" name="st_top_img_old" value="<?=$st_top_img?>" />
				<input type="hidden" name="st_favicon_old" value="<?=$st_favicon?>" />
					<div class="form-group">
						<label class="col-sm-1 control-label">타이틀</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="st_title" value="<?=$st_title?>" maxlength="120">
							<span class="help-block col-xs-12">웹사이트 제목</span>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">설명</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-8" name="st_content" value="<?=$st_content?>" maxlength="120">
							<span class="help-block col-xs-12">웹사이트 소개글</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상단 로고</label>
						<div class="col-sm-11">
							<input type="file" class="col-xs-12 col-sm-8" name="st_top_img">
							<span class="help-block col-xs-12">이미지사이즈(pixel) 가로 900이하 / 세로 40이하</span>
						</div>
					</div>

					<?if($st_top_img){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">등록된 이미지</label>
						<div class="col-sm-11">
							<p class="col-xs-12 form-control-static">
								<div class="html_edit_wrap" style="max-width:200px;">
									<img src="<?=$iw["path"].$upload_path."/".$row["st_top_img"]?>" />
								</div>
							</p>
						</div>
					</div>
					<?}?>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">상단로고 정렬</label>
						<div class="col-sm-11">
							<select name="st_top_align">
								<option value="left" <?if($st_top_align=="left"){?>selected<?}?>>왼쪽</option>
								<option value="center" <?if($st_top_align=="center"){?>selected<?}?>>가운데</option>
								<option value="right" <?if($st_top_align=="right"){?>selected<?}?>>오른쪽</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">파비콘</label>
						<div class="col-sm-11">
							<input type="file" class="col-xs-12 col-sm-8" name="st_favicon">
							<span class="help-block col-xs-12">파일(ICO), 이미지사이즈(pixel) 34 X 34</span>
						</div>
					</div>

					<?if($st_favicon){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">등록된 파비콘</label>
						<div class="col-sm-11">
							<p class="col-xs-12 form-control-static">
								<img src="<?=$iw["path"].$upload_path."/".$row["st_favicon"]?>" />
							</p>
						</div>
					</div>
					<?}?>

					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">배경 이미지</label>
						<div class="col-sm-11">
							<?
								for ($i=0; $i<5; $i++) {
							?>
								<input type="hidden" name="st_background_before[]" value="<?=$st_background[$i]?>">
								<input type="file" class="col-xs-12 col-sm-8" name="st_background[]">
								<?if($st_background[$i]){?>
								<span class="help-block col-xs-12">
									<a href="<?=$upload_path?>/<?=$st_background[$i]?>" target="_blank"><?=$st_background[$i]?></a>
									<input type="checkbox" name="st_background_del[<?=$i?>]" value="del" /> 삭제</td>
								</span>
								<?}?>
							<?
								}
							?>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">메뉴 위치</label>
						<div class="col-sm-11">
							<select name="st_menu_position">
								<option value="0" <?if($st_menu_position=="0"){?>selected<?}?>>로고 하단</option>
								<option value="1" <?if($st_menu_position=="1"){?>selected<?}?>>로고 우측</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">모바일 메뉴 스타일</label>
						<div class="col-sm-11">
							<select name="st_menu_mobile">
								<option value="1" <?if($st_menu_mobile=="1"){?>selected<?}?>>고정</option>
								<option value="0" <?if($st_menu_mobile=="0"){?>selected<?}?>>가변</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">네비게이션</label>
						<div class="col-sm-11">
							<select name="st_navigation">
								<option value="1" <?if($st_navigation=="1"){?>selected<?}?>>노출</option>
								<option value="0" <?if($st_navigation=="0"){?>selected<?}?>>숨김</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

				<?if($iw[mcb]==1){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">네비게이션(게시판)</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-4" name="st_mcb_name" value="<?=$st_mcb_name?>">
						</div>
					</div>
					<div class="space-4"></div>
				<?}?>
				<?if($iw[publishing]==1){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">네비게이션(출판도서)</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-4" name="st_publishing_name" value="<?=$st_publishing_name?>">
						</div>
					</div>
					<div class="space-4"></div>
				<?}?>
				<?if($iw[shop]==1){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">네비게이션(쇼핑)</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-4" name="st_shop_name" value="<?=$st_shop_name?>">
						</div>
					</div>
					<div class="space-4"></div>
				<?}?>
				<?if($iw[doc]==1){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">네비게이션(컨텐츠)</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-4" name="st_doc_name" value="<?=$st_doc_name?>">
						</div>
					</div>
					<div class="space-4"></div>
				<?}?>
				<?if($iw[book]==1){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">네비게이션(이북)</label>
						<div class="col-sm-11">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-4" name="st_book_name" value="<?=$st_book_name?>">
						</div>
					</div>
					<div class="space-4"></div>
				<?}?>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">소셜공유 버튼</label>
						<div class="col-sm-11">
							<select name="st_sns_share">
								<option value="0" <?if($st_sns_share=="0"){?>selected<?}?>>기본(고유색상)</option>
								<option value="1" <?if($st_sns_share=="1"){?>selected<?}?>>테마색상</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">슬라이드 넓이</label>
						<div class="col-sm-11">
							<select name="st_slide_size">
								<option value="0" <?if($st_slide_size=="0"){?>selected<?}?>>양쪽 맞춤</option>
								<option value="1" <?if($st_slide_size=="1"){?>selected<?}?>>양쪽 채움(와이드)</option>
							</select>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">시작페이지</label>
						<div class="col-sm-11">
							<select name="hm_code">
								<option value="main" <?if($hm_code_check == "main"){?>selected<?}?>>메인 랜딩(기본)</option>
								<?
									$sql1 = "select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and hm_deep = 1 order by hm_order asc,hm_no asc";
									$result1 = sql_query($sql1);
									while($row1 = @sql_fetch_array($result1)){
										$hm_code = $row1["hm_code"];
										$hm_name1 = $row1["hm_name"];
										$state_sort = $row1["state_sort"];
										
										if($state_sort != "link" && $state_sort != "close" && $state_sort != "main"){
								?>
									<option value="<?=$hm_code?>" <?if($hm_code == $hm_code_check){?>selected<?}?>><?=$hm_name1?></option>
									<?
										}
										$sql2 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 2 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
										$result2 = sql_query($sql2);
										$middle_num = 0;
										while($row2 = @sql_fetch_array($result2)){
											$hm_code = $row2["hm_code"];
											$hm_name2 = $row2["hm_name"];
											$state_sort = $row2["state_sort"];
											
											if($state_sort != "link" && $state_sort != "close" && $state_sort != "main"){
									?>
										<option value="<?=$hm_code?>" <?if($hm_code == $hm_code_check){?>selected<?}?>><?=$hm_name1?> > <?=$hm_name2?></option>
										<?
											}
											$sql3 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 3 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
											$result3 = sql_query($sql3);
											$small_num = 0;
											while($row3 = @sql_fetch_array($result3)){
												$hm_code = $row3["hm_code"];
												$hm_name3 = $row3["hm_name"];
												$state_sort = $row3["state_sort"];
												
												if($state_sort != "link" && $state_sort != "close" && $state_sort != "main"){
										?>
											<option value="<?=$hm_code?>" <?if($hm_code == $hm_code_check){?>selected<?}?>><?=$hm_name1?> > <?=$hm_name2?> > <?=$hm_name3?></option>
											<?
												}
												$sql4 = "select * from $iw[home_menu_table] where hm_upper_code = '$hm_code' and hm_deep = 4 and ep_code = '$iw[store]' and gp_code = '$iw[group]' order by hm_order asc,hm_no asc";
												$result4 = sql_query($sql4);
												$tint_num = 0;
												while($row4 = @sql_fetch_array($result4)){
													$hm_code = $row4["hm_code"];
													$hm_name4 = $row4["hm_name"];
													$state_sort = $row4["state_sort"];
													
													if($state_sort != "link" && $state_sort != "close" && $state_sort != "main"){
											?>
												<option value="<?=$hm_code?>" <?if($hm_code == $hm_code_check){?>selected<?}?>><?=$hm_name1?> > <?=$hm_name2?> > <?=$hm_name3?> > <?=$hm_name4?></option>
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
				<?
					if($iw[group] == "all"){
						$sql = "select ep_footer,ep_copy_off,ep_terms_display from $iw[enterprise_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
						$row = sql_fetch($sql);
						$ep_copy_off = $row["ep_copy_off"];
						$ep_terms_display = $row["ep_terms_display"];
				?>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">복제 방지</label>
						<div class="col-sm-11">
							<select name="ep_copy_off">
								<option value="0" <?if($ep_copy_off=="0"){?>selected<?}?>>OFF</option>
								<option value="1" <?if($ep_copy_off=="1"){?>selected<?}?>>ON</option>
							</select>
							<span class="help-block col-xs-12">마우스 드래그, 마우스 오른쪽, ctrl + c 키 방지</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사이트 하단정보</label>
						<div class="col-xs-12 col-sm-11">
							<textarea class="ckeditor" width="100%" id="contents1" name="contents1" height="300px"><?=stripslashes($row["ep_footer"]);?></textarea>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이용약관 자동표시</label>
						<div class="col-sm-11">
							<select name="ep_terms_display">
								<option value="0" <?if($ep_terms_display=="0"){?>selected<?}?>>OFF</option>
								<option value="1" <?if($ep_terms_display=="1"){?>selected<?}?>>ON</option>
							</select>
						</div>
					</div>
				<?}?>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장하기
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
		if (st_form.st_title.value == ""){
			alert("타이틀을 입력하여 주십시오.");
			st_form.st_title.focus();
			return;
		}
		if (st_form.st_mcb_name && st_form.st_mcb_name.value == ""){
			alert("네비게이션(게시판)를 입력하여 주십시오.");
			st_form.st_mcb_name.focus();
			return;
		}
		if (st_form.st_publishing_name && st_form.st_publishing_name.value == ""){
			alert("네비게이션(출판도서)를 입력하여 주십시오.");
			st_form.st_publishing_name.focus();
			return;
		}
		if (st_form.st_doc_name && st_form.st_doc_name.value == ""){
			alert("네비게이션(컨텐츠)를 입력하여 주십시오.");
			st_form.st_doc_name.focus();
			return;
		}
		if (st_form.st_shop_name && st_form.st_shop_name.value == ""){
			alert("네비게이션(쇼핑)를 입력하여 주십시오.");
			st_form.st_shop_name.focus();
			return;
		}
		if (st_form.st_book_name && st_form.st_book_name.value == ""){
			alert("네비게이션(이북)를 입력하여 주십시오.");
			st_form.st_book_name.focus();
			return;
		}
		st_form.submit();
	}
</script>
 
<?
include_once("_tail.php");
?>