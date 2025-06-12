<?
if (!defined("_INFOWAY_")) exit; // 개별 페이지 접근 불가

$ep_row = sql_fetch("select * from $iw[enterprise_table] where ep_code = '$iw[store]'");
$ep_jointype = $ep_row["ep_jointype"];
?>
	<footer>
		<div class="row">
			<div class="col-sm-12">
				<div class="btn-list text-center btn-toolbar">
				<?if($iw['level'] != "guest"){?>
					<a class="btn btn-theme btn-sm" href="<?=$iw['m_path']?>/all_logout.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0014","로그아웃");?></a>
					<a class="btn btn-theme btn-sm" href="<?=$iw['m_path']?>/all_member_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0015","회원정보");?></a>
				<?}else{?>
					<a class="btn btn-theme btn-sm" href="<?=$iw['m_path']?>/all_login.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&re_url=<?=$iw[re_url]?>"><?=national_language($iw[language],"a0016","로그인");?></a>
					<?if ($ep_jointype != 0) {?>
					<a class="btn btn-theme btn-sm" href="<?=$iw['m_path']?>/all_join.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0017","회원가입");?></a>
					<?}?>
				<?}?>
				<?if($iw['gp_level'] == "gp_guest" && $iw['group'] != "all"){?>
					<a class="btn btn-theme btn-sm" href="<?=$iw['m_path']?>/all_group_join.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0019","그룹가입");?></a>
				<?}else if($iw['level'] != "super" && $iw['level'] != "guest"){?>
					<a class="btn btn-theme btn-sm" href="<?=$iw['admin_path']?>/main.php?type=dashboard&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0018","관리");?></a>
				<?}?>
					<a class="btn btn-theme btn-sm" href="<?=$iw['m_path']?>/all_notice_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0012","공지사항");?></a>
				</div>
			</div>
		</div>

		<div class="box br-footer">
			<ul class="list-inline">
				<?
					$row = sql_fetch(" select ep_footer,ep_terms_display from $iw[enterprise_table] where ep_code = '$iw[store]'");
					$ep_footer = stripslashes($row["ep_footer"]);
					$ep_terms_display = stripslashes($row["ep_terms_display"]);
				?>
				<li><?=$ep_footer?></li>
			</ul>
			<?php if ($ep_terms_display == 1) {?>
			<ul class="list-inline">
				<li><a href="<?=$iw['m_path']?>/all_policy_agreement.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0027","이용약관");?></a></li>
				<li><a href="<?=$iw['m_path']?>/all_policy_private.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0028","개인정보 처리방침");?></a></li>
				<?if($iw[language]=="ko"){?>
				<li><a href="<?=$iw['m_path']?>/all_policy_email.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>">이메일주소 무단수집거부</a></li>
				<?}?>
				<li><a href="http://www.wizwindigital.com" target="_blank">© WIZWINDIGITAL Corp.</a></li>
			</ul>
			<?php }?>
		</div>

	</footer>
</div> <!-- / main container -->
	
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/masonry.pkgd.min.js"></script>
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/jquery.backstretch.min.js"></script>
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/cbpHorizontalMenu.min.js"></script>
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/jquery.scrollbox.js"></script>
	<script type="text/javascript" src="<?=$iw[design_path]?>/js/ekko-lightbox.min.js"></script>
    <script type="text/javascript" src="<?=$iw[design_path]?>/js/main_site.js"></script>
	<?if($st_menu_position==0){?>
		<script type="text/javascript" src="<?=$iw[design_path]?>/js/search-stick.js?v=20180508"></script>
	<?}else{?>
		<script type="text/javascript" src="<?=$iw[design_path]?>/js/search-stick-2.js?v=202305231"></script>
	<?}?>
	
	<?if($backstretch_item){?>
		<script language="JavaScript">
			$.backstretch([
				<?=$backstretch_item;?>
			],{
				duration: 30000, fade: 750
			});
		</script>
	<?}?>
	<script language="JavaScript">
		function copy_trackback() {
			var trb = document.location.href;
			var IE=(document.all)?true:false;
			if (IE) {
				if(confirm("<?=national_language($iw[language],'a0254','URL 주소를 클립보드에 복사하시겠습니까?');?>"))
					window.clipboardData.setData("Text", trb);
			} else {
				temp = prompt("<?=national_language($iw[language],'a0255','URL 주소입니다. Ctrl+C를 눌러 클립보드로 복사하세요.');?>", trb);
			}
		}
		function rss_feed(url,ep,gp,cg) {
			trb = "http://www"+ url +"/rss/?ep="+ ep +"&gp="+ gp +"&cg="+ cg +"&limit=10";
			var IE=(document.all)?true:false;
			if (IE) {
				if(confirm("RSS Feed 주소를 클립보드에 복사하시겠습니까?"))
					window.clipboardData.setData("Text", trb);
			} else {
				temp = prompt("RSS Feed 주소입니다. Ctrl+C를 눌러 클립보드로 복사하세요.", trb);
			}
		}

		$(function() {
			cbpHorizontalMenu.init();
		});
	</script>
<?
include_once("_tail_sub.php");
?>