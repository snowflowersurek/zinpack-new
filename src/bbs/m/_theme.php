<?php
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

$sql = "select * from $iw[theme_setting_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'";
$row = sql_fetch($sql);

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
<style type="text/css">
/*배경*/
.zinpack-style{
	background-color:<?=$ts_body_back_color;?>;
}

/*타일 간격*/
.masonry-item{
	padding:<?=$ts_box_padding?>px;
}

/*테마 박스*/
.zinpack-style .br-theme{
	-moz-border-radius:<?=$ts_box_border_radius?>px;
	-webkit-border-radius:<?=$ts_box_border_radius?>px;
	border-radius:<?=$ts_box_border_radius?>px;
	border:<?=$ts_box_border_width?>px solid <?=$ts_box_border_color?>;
	background:rgba(<?=$box_back_color[red]?>,<?=$box_back_color[green]?>,<?=$box_back_color[blue]?>,<?=$ts_box_back_opacity/100?>);
	color:<?=$ts_box_font_color?>;
}
/*테마 박스*/
.zinpack-style .br-theme p{
	color:<?=$ts_box_font_color?>;
}
/*하단 박스*/
.zinpack-style .br-footer{
	-moz-border-radius:<?=$ts_footer_border_radius?>px;
	-webkit-border-radius:<?=$ts_footer_border_radius?>px;
	border-radius:<?=$ts_footer_border_radius?>px;
	border:<?=$ts_footer_border_width?>px solid <?=$ts_footer_border_color?>;
	background:rgba(<?=$footer_back_color[red]?>,<?=$footer_back_color[green]?>,<?=$footer_back_color[blue]?>,<?=$ts_footer_back_opacity/100?>);
	color:<?=$ts_footer_font_color?>;
}

/*상단메뉴 버튼*/
.zinpack-style #main-navbar .nav-pills>li>a,
.zinpack-style #sub-navbar .nav-pills>li>a{
	padding:12px 20px;
	-moz-border-radius:<?=$ts_menu_border_radius?>px;
	-webkit-border-radius:<?=$ts_menu_border_radius?>px;
	border-radius:<?=$ts_menu_border_radius?>px;
	border:<?=$ts_menu_border_width?>px solid <?=$ts_menu_border_color?>;
	background:rgba(<?=$menu_back_color[red]?>,<?=$menu_back_color[green]?>,<?=$menu_back_color[blue]?>,<?=$ts_menu_back_opacity/100?>);
	color:<?=$ts_menu_font_color?>;
}
/*상단메뉴 버튼(마우스오버)*/
.zinpack-style #main-navbar .nav-pills>li:hover>a,
.zinpack-style #sub-navbar .nav-pills>li:hover>a{
	border:<?=$ts_menu_border_width?>px solid <?=$ts_menu_border_color_over?>;
	background:rgba(<?=$menu_back_color_over[red]?>,<?=$menu_back_color_over[green]?>,<?=$menu_back_color_over[blue]?>,<?=$ts_menu_back_opacity_over/100?>);
	color:<?=$ts_menu_font_color_over?>;
}
/*상단메뉴 버튼(현재선택)*/
.zinpack-style #main-navbar .nav-pills>li.active>a,
.zinpack-style #sub-navbar .nav-pills>li.active>a{
	background:rgba(<?=$menu_back_color_over[red]?>,<?=$menu_back_color_over[green]?>,<?=$menu_back_color_over[blue]?>,<?=$ts_menu_back_opacity_over/100?>);
}
.zinpack-style .cbp-hrmenu .cbp-hrsub{
	background:rgba(<?=$menu_back_color_over[red]?>,<?=$menu_back_color_over[green]?>,<?=$menu_back_color_over[blue]?>,<?=$ts_menu_back_opacity_over/100?>);
}
.zinpack-style .cbp-hrmenu .cbp-hrsub-inner,
.zinpack-style .cbp-hrmenu .cbp-hrsub-inner>div a{
	color:<?=$ts_menu_font_color_over?>;
}
.zinpack-style .cbp-hrmenu .cbp-hrsub-inner>div a:hover{
	color:<?=$ts_menu_font_color_over?>;
	opacity: 0.5;
}
/*상단 네비게이션바*/
.zinpack-style .breadcrumb{
	-moz-border-radius:<?=$ts_navi_border_radius?>px;
	-webkit-border-radius:<?=$ts_navi_border_radius?>px;
	border-radius:<?=$ts_navi_border_radius?>px;
	border:<?=$ts_navi_border_width?>px solid <?=$ts_navi_border_color?>;
	background:rgba(<?=$navi_back_color[red]?>,<?=$navi_back_color[green]?>,<?=$navi_back_color[blue]?>,<?=$ts_navi_back_opacity/100?>);
	color:<?=$ts_navi_font_color?>;
}
/*상단 네비게이션바 링크*/
.zinpack-style .breadcrumb>li a{
	color:<?=$ts_navi_font_color?>;
}
/*테마 버튼*/
.zinpack-style .btn-theme{
	-moz-border-radius:<?=$ts_button_border_radius?>px;
	-webkit-border-radius:<?=$ts_button_border_radius?>px;
	border-radius:<?=$ts_button_border_radius?>px;
	border:<?=$ts_button_border_width?>px solid <?=$ts_button_border_color?>;
	background:rgba(<?=$button_back_color[red]?>,<?=$button_back_color[green]?>,<?=$button_back_color[blue]?>,<?=$ts_button_back_opacity/100?>);
	color:<?=$ts_button_font_color?>;
}
/*테마 버튼(마우스오버)*/
.zinpack-style .btn-theme:hover,
.zinpack-style .btn-theme:focus,
.zinpack-style .btn-theme:active,
.zinpack-style .btn-theme.active,
.open>.zinpack-style-0 .btn-theme.dropdown-toggle{
	border:<?=$ts_button_border_width?>px solid <?=$ts_button_border_color_over?>;
	background:rgba(<?=$button_back_color_over[red]?>,<?=$button_back_color_over[green]?>,<?=$button_back_color_over[blue]?>,<?=$ts_button_back_opacity_over/100?>);
	color:<?=$ts_button_font_color_over?>;
}
/*테마 버튼(비활성)*/
.zinpack-style .btn-theme.disabled,
.zinpack-style .btn-theme.disabled:hover,
.zinpack-style .btn-theme.disabled:focus,
.zinpack-style .btn-theme.disabled:active,
.zinpack-style .btn-theme.disabled.active,
.zinpack-style .btn-theme[disabled],
.zinpack-style .btn-theme[disabled]:hover,
.zinpack-style .btn-theme[disabled]:focus,
.zinpack-style .btn-theme[disabled]:active,
.zinpack-style .btn-theme[disabled].active,
fieldset[disabled] .zinpack-style-0 .btn-theme,
fieldset[disabled] .zinpack-style-0 .btn-theme:hover,
fieldset[disabled] .zinpack-style-0 .btn-theme:focus,
fieldset[disabled] .zinpack-style-0 .btn-theme:active,
fieldset[disabled] .zinpack-style-0 .btn-theme.active{
	border:<?=$ts_button_border_width?>px solid <?=$ts_button_border_color?>;
	background:rgba(<?=$button_back_color[red]?>,<?=$button_back_color[green]?>,<?=$button_back_color[blue]?>,<?=$ts_button_back_opacity/100?>);
	color:<?=$ts_button_font_color?>;
}
/*페이지네이션*/
.zinpack-style .pagination{
	-moz-border-radius:<?=$ts_page_border_radius?>px;
	-webkit-border-radius:<?=$ts_page_border_radius?>px;
	border-radius:<?=$ts_page_border_radius?>px;
}
/*페이지네이션 기본*/
.zinpack-style .pagination>li>a,
.zinpack-style .pagination>li>span{
	border:<?=$ts_page_border_width?>px solid <?=$ts_page_border_color?>;
	background:rgba(<?=$page_back_color[red]?>,<?=$page_back_color[green]?>,<?=$page_back_color[blue]?>,<?=$ts_page_back_opacity/100?>);
	color:<?=$ts_page_font_color?>;
}
/*페이지네이션 선택*/
.zinpack-style .pagination>li>a:hover,
.zinpack-style .pagination>li>a:focus,
.zinpack-style .pagination>li>span:hover,
.zinpack-style .pagination>li>span:focus,
.zinpack-style .pagination>.active>a,
.zinpack-style .pagination>.active>a:hover,
.zinpack-style .pagination>.active>a:focus,
.zinpack-style .pagination>.active>span,
.zinpack-style .pagination>.active>span:hover,
.zinpack-style .pagination>.active>span:focus,
.zinpack-style .pagination>.disabled>span,
.zinpack-style .pagination>.disabled>span:hover,
.zinpack-style .pagination>.disabled>span:focus,
.zinpack-style .pagination>.disabled>a,
.zinpack-style .pagination>.disabled>a:hover,
.zinpack-style .pagination>.disabled>a:focus{
	border:<?=$ts_page_border_width?>px solid <?=$ts_page_border_color_over?>;
	background:rgba(<?=$page_back_color_over[red]?>,<?=$page_back_color_over[green]?>,<?=$page_back_color_over[blue]?>,<?=$ts_page_back_opacity_over/100?>);
	color:<?=$ts_page_font_color_over?>;
}

<?php if($st_menu_position==0){?>
.zinpack-style .stick{
	background-color:<?=$ts_body_back_color;?>;
	position:fixed;
}
<?php }else{?>
.zinpack-style #navbar-region{
	background:rgba(<?=$menu_back_color[red]?>,<?=$menu_back_color[green]?>,<?=$menu_back_color[blue]?>,<?=$ts_menu_back_opacity/100?>);
}
.zinpack-style .stick{
	background:rgba(<?=$menu_back_color[red]?>,<?=$menu_back_color[green]?>,<?=$menu_back_color[blue]?>,<?=$ts_menu_back_opacity/100?>);
}
<?php }?>
</style>



