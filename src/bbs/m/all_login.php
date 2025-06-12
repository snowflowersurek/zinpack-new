<?php
include_once("_common_guest.php");
include_once("_head.php");
if($iw[level] != "guest") goto_url("$iw[m_path]/main.php?type=main&ep=$iw[store]&gp=$iw[group]");

$ep_row = sql_fetch("select * from $iw[enterprise_table] where ep_code = '$iw[store]'");
$ep_jointype = $ep_row["ep_jointype"];
?>
<div class="breadcrumb-box input-group">
	<ol class="breadcrumb ">
		<li><a href="#"><?=national_language($iw[language],"a0016","로그인");?></a></li>
	</ol>
</div>
<div class="content">
	<?php
		if ($iw[store] == "ep801870536594c8ad1cd3b5") {
			echo '<div class="alert-danger text-center" style="padding:16px 20px;">매거진 정기구독을 위한 회원가입은 <a href="https://www.badamgz.com" class="text-danger"><strong style="text-decoration:underline">badamgz.com(클릭)</strong></a>으로 부탁드립니다.</div>';
		}
	?>
	<div class="box br-theme">
		<form id="mb_form" name="mb_form" action="<?=$iw['m_path']?>/all_login_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
		<input type="hidden" name="re_url" value="<?=$_GET[re_url]?>" />
			<div class="form-group">
				<label for="login_name"><?=national_language($iw[language],"a0065","아이디(이메일주소)");?></label>
				<input type="text" class="form-control" name="mb_mail" />
			</div>
			<div class="form-group">
				<label for="login_pass"><?=national_language($iw[language],"a0067","비밀번호");?></label>
				<input type="password" class="form-control" name="mb_password" onkeydown="javascript: if (event.keyCode == 13) {check_form();}" />
			</div>
			<div class="btn-list">
				<a class="btn btn-theme" href="javascript:check_form();"><?=national_language($iw[language],"a0016","로그인");?></a><br/><br/>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_member_lost.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0106","아이디/비밀번호 찾기");?></a>
				<?if ($ep_jointype != 0) {?>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_join.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=national_language($iw[language],"a0017","회원가입");?></a>
				<?}?>
			</div>
		</form>
	</div> <!-- /.box -->
</div>

<script type="text/javascript">
	function check_form() {
		if (mb_form.mb_mail.value == ""){
			alert("<?=national_language($iw[language],'a0082','아이디(이메일주소)를 입력하여 주십시오.');?>");
			mb_form.mb_mail.focus();
			return;
		}
		if (mb_form.mb_mail.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("<?=national_language($iw[language],'a0083','잘못된 이메일 주소입니다.');?>");
			mb_form.mb_mail.focus();
			return;
		}
		if (mb_form.mb_password.value == ""){
			alert("<?=national_language($iw[language],'a0107','패스워드를 입력하여 주십시오.');?>");
			mb_form.mb_password.focus();
			return;
		}
		mb_form.submit();
	}
</script>
 
<?
include_once("_tail.php");
?>