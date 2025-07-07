<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");

$sql = "select * from $iw[theme_setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'";
$row = sql_fetch($sql);
if (!$row["ts_no"]) alert("잘못된 접근입니다!","");
$ts_no = $row["ts_no"];
$ts_body_back_color = $row["ts_body_back_color"];
$ts_menu_border_radius = $row["ts_menu_border_radius"];
$ts_menu_border_width = $row["ts_menu_border_width"];
$ts_menu_back_opacity = $row["ts_menu_back_opacity"];
$ts_menu_back_color = $row["ts_menu_back_color"];
$ts_menu_font_color = $row["ts_menu_font_color"];
$ts_menu_border_color = $row["ts_menu_border_color"];
$ts_menu_back_opacity_over = $row["ts_menu_back_opacity_over"];
$ts_menu_back_color_over = $row["ts_menu_back_color_over"];
$ts_menu_font_color_over = $row["ts_menu_font_color_over"];
$ts_menu_border_color_over = $row["ts_menu_border_color_over"];
$ts_navi_border_radius = $row["ts_navi_border_radius"];
$ts_navi_border_width = $row["ts_navi_border_width"];
$ts_navi_back_opacity = $row["ts_navi_back_opacity"];
$ts_navi_back_color = $row["ts_navi_back_color"];
$ts_navi_font_color = $row["ts_navi_font_color"];
$ts_navi_border_color = $row["ts_navi_border_color"];
$ts_box_border_radius = $row["ts_box_border_radius"];
$ts_box_border_width = $row["ts_box_border_width"];
$ts_box_back_opacity = $row["ts_box_back_opacity"];
$ts_box_back_color = $row["ts_box_back_color"];
$ts_box_font_color = $row["ts_box_font_color"];
$ts_box_border_color = $row["ts_box_border_color"];
$ts_footer_border_radius = $row["ts_footer_border_radius"];
$ts_footer_border_width = $row["ts_footer_border_width"];
$ts_footer_back_opacity = $row["ts_footer_back_opacity"];
$ts_footer_back_color = $row["ts_footer_back_color"];
$ts_footer_font_color = $row["ts_footer_font_color"];
$ts_footer_border_color = $row["ts_footer_border_color"];
$ts_button_border_radius = $row["ts_button_border_radius"];
$ts_button_border_width = $row["ts_button_border_width"];
$ts_button_back_opacity = $row["ts_button_back_opacity"];
$ts_button_back_color = $row["ts_button_back_color"];
$ts_button_font_color = $row["ts_button_font_color"];
$ts_button_border_color = $row["ts_button_border_color"];
$ts_button_back_opacity_over = $row["ts_button_back_opacity_over"];
$ts_button_back_color_over = $row["ts_button_back_color_over"];
$ts_button_font_color_over = $row["ts_button_font_color_over"];
$ts_button_border_color_over = $row["ts_button_border_color_over"];
$ts_page_border_radius = $row["ts_page_border_radius"];
$ts_page_border_width = $row["ts_page_border_width"];
$ts_page_back_opacity = $row["ts_page_back_opacity"];
$ts_page_back_color = $row["ts_page_back_color"];
$ts_page_font_color = $row["ts_page_font_color"];
$ts_page_border_color = $row["ts_page_border_color"];
$ts_page_back_opacity_over = $row["ts_page_back_opacity_over"];
$ts_page_back_color_over = $row["ts_page_back_color_over"];
$ts_page_font_color_over = $row["ts_page_font_color_over"];
$ts_page_border_color_over = $row["ts_page_border_color_over"];
$ts_box_padding = $row["ts_box_padding"];

$menu_back_color = hex2RGB($ts_menu_back_color);
$menu_back_color_over = hex2RGB($ts_menu_back_color_over);
$navi_back_color = hex2RGB($ts_navi_back_color);
$box_back_color = hex2RGB($ts_box_back_color);
$footer_back_color = hex2RGB($ts_footer_back_color);
$button_back_color = hex2RGB($ts_button_back_color);
$button_back_color_over = hex2RGB($ts_button_back_color_over);
$page_back_color = hex2RGB($ts_page_back_color);
$page_back_color_over = hex2RGB($ts_page_back_color_over);
?>
<style>
	.ts_back{
		background-color:<?=$ts_body_back_color?>;
	}
	.ts_menu{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_menu_border_radius?>px;
		-webkit-border-radius:<?=$ts_menu_border_radius?>px;
		border-radius:<?=$ts_menu_border_radius?>px;
		border:<?=$ts_menu_border_width?>px solid <?=$ts_menu_border_color?>;
		background:rgba(<?=$menu_back_color[red]?>,<?=$menu_back_color[green]?>,<?=$menu_back_color[blue]?>,<?=$ts_menu_back_opacity/100?>);
		color:<?=$ts_menu_font_color?>;
	}
	.ts_menu_2{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_menu_border_radius?>px;
		-webkit-border-radius:<?=$ts_menu_border_radius?>px;
		border-radius:<?=$ts_menu_border_radius?>px;
		border:<?=$ts_menu_border_width?>px solid <?=$ts_menu_border_color_over?>;
		background:rgba(<?=$menu_back_color_over[red]?>,<?=$menu_back_color_over[green]?>,<?=$menu_back_color_over[blue]?>,<?=$ts_menu_back_opacity_over/100?>);
		color:<?=$ts_menu_font_color_over?>;
	}
	.ts_navi{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_navi_border_radius?>px;
		-webkit-border-radius:<?=$ts_navi_border_radius?>px;
		border-radius:<?=$ts_navi_border_radius?>px;
		border:<?=$ts_navi_border_width?>px solid <?=$ts_navi_border_color?>;
		background:rgba(<?=$navi_back_color[red]?>,<?=$navi_back_color[green]?>,<?=$navi_back_color[blue]?>,<?=$ts_navi_back_opacity/100?>);
		color:<?=$ts_navi_font_color?>;
	}
	.ts_box{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_box_border_radius?>px;
		-webkit-border-radius:<?=$ts_box_border_radius?>px;
		border-radius:<?=$ts_box_border_radius?>px;
		border:<?=$ts_box_border_width?>px solid <?=$ts_box_border_color?>;
		background:rgba(<?=$box_back_color[red]?>,<?=$box_back_color[green]?>,<?=$box_back_color[blue]?>,<?=$ts_box_back_opacity/100?>);
		color:<?=$ts_box_font_color?>;
	}
	.ts_footer{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_footer_border_radius?>px;
		-webkit-border-radius:<?=$ts_footer_border_radius?>px;
		border-radius:<?=$ts_footer_border_radius?>px;
		border:<?=$ts_footer_border_width?>px solid <?=$ts_footer_border_color?>;
		background:rgba(<?=$footer_back_color[red]?>,<?=$footer_back_color[green]?>,<?=$footer_back_color[blue]?>,<?=$ts_footer_back_opacity/100?>);
		color:<?=$ts_footer_font_color?>;
	}
	.ts_button{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_button_border_radius?>px;
		-webkit-border-radius:<?=$ts_button_border_radius?>px;
		border-radius:<?=$ts_button_border_radius?>px;
		border:<?=$ts_button_border_width?>px solid <?=$ts_button_border_color?>;
		background:rgba(<?=$button_back_color[red]?>,<?=$button_back_color[green]?>,<?=$button_back_color[blue]?>,<?=$ts_button_back_opacity/100?>);
		color:<?=$ts_button_font_color?>;
	}
	.ts_button_2{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_button_border_radius?>px;
		-webkit-border-radius:<?=$ts_button_border_radius?>px;
		border-radius:<?=$ts_button_border_radius?>px;
		border:<?=$ts_button_border_width?>px solid <?=$ts_button_border_color_over?>;
		background:rgba(<?=$button_back_color_over[red]?>,<?=$button_back_color_over[green]?>,<?=$button_back_color_over[blue]?>,<?=$ts_button_back_opacity_over/100?>);
		color:<?=$ts_button_font_color_over?>;
	}
	.ts_page{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_page_border_radius?>px;
		-webkit-border-radius:<?=$ts_page_border_radius?>px;
		border-radius:<?=$ts_page_border_radius?>px;
		border:<?=$ts_page_border_width?>px solid <?=$ts_page_border_color?>;
		background:rgba(<?=$page_back_color[red]?>,<?=$page_back_color[green]?>,<?=$page_back_color[blue]?>,<?=$ts_page_back_opacity/100?>);
		color:<?=$ts_page_font_color?>;
	}
	.ts_page_2{
		width:150px;
		padding:5px 10px;
		text-align: center;
		-moz-border-radius:<?=$ts_page_border_radius?>px;
		-webkit-border-radius:<?=$ts_page_border_radius?>px;
		border-radius:<?=$ts_page_border_radius?>px;
		border:<?=$ts_page_border_width?>px solid <?=$ts_page_border_color_over?>;
		background:rgba(<?=$page_back_color_over[red]?>,<?=$page_back_color_over[green]?>,<?=$page_back_color_over[blue]?>,<?=$ts_page_back_opacity_over/100?>);
		color:<?=$ts_page_font_color_over?>;
	}				
</style>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-object-group"></i>
			디자인 설정
		</li>
		<li class="active">테마디자인</li>
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
			테마디자인
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
				<form class="form-horizontal" id="ts_form" name="ts_form" action="<?=$iw['admin_path']?>/design_theme_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="ts_no" value="<?=$ts_no?>" />
					<div class="table-header">
						배경
					</div>
					<div class="form-horizontal no-input-detail">
						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_body_back_color" maxlength="7" value="<?=$ts_body_back_color?>" onChange="javascript:back_set(this.value);">
							</div>
						</div>
					</div>

					<div class="table-header">
						박스 공통
					</div>
					<div class="form-horizontal no-input-detail">
						<div class="form-group">
							<label class="col-sm-2 control-label">간격</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_box_padding" maxlength="2" value="<?=$ts_box_padding?>">px (0 ~ 99)
							</div>
						</div>
					</div>

					<div class="table-header">
						상단메뉴
					</div>
					<div class="form-horizontal no-input-detail">
						<div class="form-group">
							<label class="col-sm-2 control-label">모서리 둥글게</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_menu_border_radius" maxlength="1" value="<?=$ts_menu_border_radius?>">px (0 ~ 9)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 두께</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_menu_border_width" maxlength="1" value="<?=$ts_menu_border_width?>">px (0 ~ 9)
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_menu_border_color" maxlength="7" value="<?=$ts_menu_border_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_menu_back_opacity" maxlength="3" value="<?=$ts_menu_back_opacity?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_menu_back_color" maxlength="7" value="<?=$ts_menu_back_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_menu_font_color" maxlength="7" value="<?=$ts_menu_font_color?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_menu">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_menu_border_color_over" maxlength="7" value="<?=$ts_menu_border_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도(마우스오버)</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_menu_back_opacity_over" maxlength="3" value="<?=$ts_menu_back_opacity_over?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_menu_back_color_over" maxlength="7" value="<?=$ts_menu_back_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_menu_font_color_over" maxlength="7" value="<?=$ts_menu_font_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기(마우스오버)</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_menu_2">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-8">
								<button class="btn btn-info" type="button" onclick="javascript:demo_set('ts_menu');">
									<i class="fa fa-check"></i>
									미리보기 적용
								</button>
							</div>
						</div>
					</div>
					
					<div class="table-header">
						네비게이션
					</div>
					<div class="form-horizontal no-input-detail">
						<div class="form-group">
							<label class="col-sm-2 control-label">모서리 둥글게</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_navi_border_radius" maxlength="1" value="<?=$ts_navi_border_radius?>">px (0 ~ 9)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 두께</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_navi_border_width" maxlength="1" value="<?=$ts_navi_border_width?>">px (0 ~ 9)
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_navi_border_color" maxlength="7" value="<?=$ts_navi_border_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_navi_back_opacity" maxlength="3" value="<?=$ts_navi_back_opacity?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_navi_back_color" maxlength="7" value="<?=$ts_navi_back_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_navi_font_color" maxlength="7" value="<?=$ts_navi_font_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_navi">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-8">
								<button class="btn btn-info" type="button" onclick="javascript:demo_set('ts_navi');">
									<i class="fa fa-check"></i>
									미리보기 적용
								</button>
							</div>
						</div>
					</div>

					<div class="table-header">
						기본 박스
					</div>
					<div class="form-horizontal no-input-detail">
						<div class="form-group">
							<label class="col-sm-2 control-label">모서리 둥글게</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_box_border_radius" maxlength="1" value="<?=$ts_box_border_radius?>">px (0 ~ 9)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 두께</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_box_border_width" maxlength="1" value="<?=$ts_box_border_width?>">px (0 ~ 9)
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_box_border_color" maxlength="7" value="<?=$ts_box_border_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_box_back_opacity" maxlength="3" value="<?=$ts_box_back_opacity?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_box_back_color" maxlength="7" value="<?=$ts_box_back_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_box_font_color" maxlength="7" value="<?=$ts_box_font_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_box">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-8">
								<button class="btn btn-info" type="button" onclick="javascript:demo_set('ts_box');">
									<i class="fa fa-check"></i>
									미리보기 적용
								</button>
							</div>
						</div>
					</div>

					<div class="table-header">
						하단 박스
					</div>
					<div class="form-horizontal no-input-detail">
						<div class="form-group">
							<label class="col-sm-2 control-label">모서리 둥글게</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_footer_border_radius" maxlength="1" value="<?=$ts_footer_border_radius?>">px (0 ~ 9)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 두께</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_footer_border_width" maxlength="1" value="<?=$ts_footer_border_width?>">px (0 ~ 9)
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_footer_border_color" maxlength="7" value="<?=$ts_footer_border_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_footer_back_opacity" maxlength="3" value="<?=$ts_footer_back_opacity?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_footer_back_color" maxlength="7" value="<?=$ts_footer_back_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_footer_font_color" maxlength="7" value="<?=$ts_footer_font_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_footer">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-8">
								<button class="btn btn-info" type="button" onclick="javascript:demo_set('ts_footer');">
									<i class="fa fa-check"></i>
									미리보기 적용
								</button>
							</div>
						</div>
					</div>

					<div class="table-header">
						일반 버튼
					</div>
					<div class="form-horizontal no-input-detail">
						<div class="form-group">
							<label class="col-sm-2 control-label">모서리 둥글게</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_button_border_radius" maxlength="1" value="<?=$ts_button_border_radius?>">px (0 ~ 9)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 두께</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_button_border_width" maxlength="1" value="<?=$ts_button_border_width?>">px (0 ~ 9)
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_button_border_color" maxlength="7" value="<?=$ts_button_border_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_button_back_opacity" maxlength="3" value="<?=$ts_button_back_opacity?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_button_back_color" maxlength="7" value="<?=$ts_button_back_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_button_font_color" maxlength="7" value="<?=$ts_button_font_color?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_button">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_button_border_color_over" maxlength="7" value="<?=$ts_button_border_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도(마우스오버)</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_button_back_opacity_over" maxlength="3" value="<?=$ts_button_back_opacity_over?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_button_back_color_over" maxlength="7" value="<?=$ts_button_back_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_button_font_color_over" maxlength="7" value="<?=$ts_button_font_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기(마우스오버)</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_button_2">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-8">
								<button class="btn btn-info" type="button" onclick="javascript:demo_set('ts_button');">
									<i class="fa fa-check"></i>
									미리보기 적용
								</button>
							</div>
						</div>
					</div>

					<div class="table-header">
						페이지네이션
					</div>
					<div class="form-horizontal no-input-detail">
						<div class="form-group">
							<label class="col-sm-2 control-label">모서리 둥글게</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_page_border_radius" maxlength="1" value="<?=$ts_page_border_radius?>">px (0 ~ 9)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 두께</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_page_border_width" maxlength="1" value="<?=$ts_page_border_width?>">px (0 ~ 9)
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_page_border_color" maxlength="7" value="<?=$ts_page_border_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_page_back_opacity" maxlength="3" value="<?=$ts_page_back_opacity?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_page_back_color" maxlength="7" value="<?=$ts_page_back_color?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_page_font_color" maxlength="7" value="<?=$ts_page_font_color?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_page">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">테두리 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_page_border_color_over" maxlength="7" value="<?=$ts_page_border_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 투명도(마우스오버)</label>
							<div class="col-sm-8">
								<input type="text" placeholder="입력" class="col-xs-2 col-sm-2" name="ts_page_back_opacity_over" maxlength="3" value="<?=$ts_page_back_opacity_over?>">% (0 ~ 100)
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">배경 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_page_back_color_over" maxlength="7" value="<?=$ts_page_back_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">글자 색상(마우스오버)</label>
							<div class="col-sm-8 bootstrap-colorpicker colorpicker-element" data-colorpicker-guid="1">
								<input class="form-tooltip col-xs-10 col-sm-5" type="text" placeholder="#000000" data-toggle="tooltip" name="ts_page_font_color_over" maxlength="7" value="<?=$ts_page_font_color_over?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">미리보기(마우스오버)</label>
							<div class="col-sm-8 ts_back">
								<div class="ts_page_2">가나다abc123</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-8">
								<button class="btn btn-info" type="button" onclick="javascript:demo_set('ts_page');">
									<i class="fa fa-check"></i>
									미리보기 적용
								</button>
							</div>
						</div>
					</div>

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
	var field_submit = "true";
	function check_form() {
		field_submit = "true";
		check_field(ts_form.ts_body_back_color);
		check_field(ts_form.ts_menu_border_radius);
		check_field(ts_form.ts_menu_border_width);
		check_field(ts_form.ts_menu_back_opacity);
		check_field(ts_form.ts_menu_back_color);
		check_field(ts_form.ts_menu_font_color);
		check_field(ts_form.ts_menu_border_color);
		check_field(ts_form.ts_menu_back_opacity_over);
		check_field(ts_form.ts_menu_back_color_over);
		check_field(ts_form.ts_menu_font_color_over);
		check_field(ts_form.ts_menu_border_color_over);
		check_field(ts_form.ts_navi_border_radius);
		check_field(ts_form.ts_navi_border_width);
		check_field(ts_form.ts_navi_back_opacity);
		check_field(ts_form.ts_navi_back_color);
		check_field(ts_form.ts_navi_font_color);
		check_field(ts_form.ts_navi_border_color);
		check_field(ts_form.ts_box_border_radius);
		check_field(ts_form.ts_box_border_width);
		check_field(ts_form.ts_box_back_opacity);
		check_field(ts_form.ts_box_back_color);
		check_field(ts_form.ts_box_font_color);
		check_field(ts_form.ts_box_border_color);
		check_field(ts_form.ts_footer_border_radius);
		check_field(ts_form.ts_footer_border_width);
		check_field(ts_form.ts_footer_back_opacity);
		check_field(ts_form.ts_footer_back_color);
		check_field(ts_form.ts_footer_font_color);
		check_field(ts_form.ts_footer_border_color);
		check_field(ts_form.ts_button_border_radius);
		check_field(ts_form.ts_button_border_width);
		check_field(ts_form.ts_button_back_opacity);
		check_field(ts_form.ts_button_back_color);
		check_field(ts_form.ts_button_font_color);
		check_field(ts_form.ts_button_border_color);
		check_field(ts_form.ts_button_back_opacity_over);
		check_field(ts_form.ts_button_back_color_over);
		check_field(ts_form.ts_button_font_color_over);
		check_field(ts_form.ts_button_border_color_over);
		check_field(ts_form.ts_page_border_radius);
		check_field(ts_form.ts_page_border_width);
		check_field(ts_form.ts_page_back_opacity);
		check_field(ts_form.ts_page_back_color);
		check_field(ts_form.ts_page_font_color);
		check_field(ts_form.ts_page_border_color);
		check_field(ts_form.ts_page_back_opacity_over);
		check_field(ts_form.ts_page_back_color_over);
		check_field(ts_form.ts_page_font_color_over);
		check_field(ts_form.ts_page_border_color_over);

		check_number(ts_form.ts_menu_border_radius);
		check_number(ts_form.ts_menu_border_width);
		check_number(ts_form.ts_menu_back_opacity);
		check_number(ts_form.ts_menu_back_opacity_over);
		check_number(ts_form.ts_navi_border_radius);
		check_number(ts_form.ts_navi_border_width);
		check_number(ts_form.ts_navi_back_opacity);
		check_number(ts_form.ts_box_border_radius);
		check_number(ts_form.ts_box_border_width);
		check_number(ts_form.ts_box_back_opacity);
		check_number(ts_form.ts_footer_border_radius);
		check_number(ts_form.ts_footer_border_width);
		check_number(ts_form.ts_footer_back_opacity);
		check_number(ts_form.ts_button_border_radius);
		check_number(ts_form.ts_button_border_width);
		check_number(ts_form.ts_button_back_opacity);
		check_number(ts_form.ts_button_back_opacity_over);
		check_number(ts_form.ts_page_border_radius);
		check_number(ts_form.ts_page_border_width);
		check_number(ts_form.ts_page_back_opacity);
		check_number(ts_form.ts_page_back_opacity_over);
		
		if(field_submit == "true"){
			ts_form.submit();
		}
	}
	
	function check_field(field) {
		if(field_submit == "true"){
			var e1 = field;
			if (e1.value == ""){
				alert("값을 입력하여 주십시오.");
				e1.focus();
				field_submit = "false";
			}
		}
	}
	function check_number(field) {
		if(field_submit == "true"){
			var e1 = field;
			var num ="0123456789";
			event.returnValue = true;

			for (var i=0;i<e1.value.length;i++){
				if(-1 == num.indexOf(e1.value.charAt(i)))
				event.returnValue = false;
			}
			if (!event.returnValue){
				alert('숫자로만 입력가능한 항목입니다.');
				e1.focus();
				field_submit = "false";
			}
		}
	}

	function HexToR(h) { return parseInt((cutHex(h)).substring(0,2),16) }
	function HexToG(h) { return parseInt((cutHex(h)).substring(2,4),16) }
	function HexToB(h) { return parseInt((cutHex(h)).substring(4,6),16) }
	function cutHex(h) { return (h.charAt(0)=="#") ? h.substring(1,7) : h}

	function demo_set(class_name){
		var cls = "."+class_name;
		var back = $("input[name = '"+class_name+"_back_color']").val();
		$(cls).css("-moz-border-radius", $("input[name = '"+class_name+"_border_radius']").val()+"px");
		$(cls).css("-webkit-border-radius", $("input[name = '"+class_name+"_border_radius']").val()+"px");
		$(cls).css("border-radius", $("input[name = '"+class_name+"_border_radius']").val()+"px");
		$(cls).css("border", $("input[name = '"+class_name+"_border_width']").val()+"px solid "+$("input[name = '"+class_name+"_border_color']").val());
		$(cls).css("background", "rgba("+HexToR(back)+","+HexToG(back)+","+HexToB(back)+","+($("input[name = '"+class_name+"_back_opacity']").val())/100+")");
		$(cls).css("color", $("input[name = '"+class_name+"_font_color']").val());
		
		if(class_name == "ts_menu" || class_name == "ts_button" || class_name == "ts_page"){
			cls = "."+class_name+"_2";
			back = $("input[name = '"+class_name+"_back_color_over']").val();
			$(cls).css("-moz-border-radius", $("input[name = '"+class_name+"_border_radius']").val()+"px");
			$(cls).css("-webkit-border-radius", $("input[name = '"+class_name+"_border_radius']").val()+"px");
			$(cls).css("border-radius", $("input[name = '"+class_name+"_border_radius']").val()+"px");
			$(cls).css("border", $("input[name = '"+class_name+"_border_width']").val()+"px solid "+$("input[name = '"+class_name+"_border_color_over']").val());
			$(cls).css("background", "rgba("+HexToR(back)+","+HexToG(back)+","+HexToB(back)+","+($("input[name = '"+class_name+"_back_opacity_over']").val())/100+")");
			$(cls).css("color", $("input[name = '"+class_name+"_font_color_over']").val());
		}
	}
	function back_set(val){
		$(".ts_back").css("background-color", val);
	}
</script>
 
<?php
include_once("_tail.php");
?>



