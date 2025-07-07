<?php
include_once("_common_guest.php");
include_once("_head.php");
?>
<div class="breadcrumb-box input-group">
	<ol class="breadcrumb ">
		<li><a href="#"><?=national_language($iw[language],"a0106","아이디/비밀번호 찾기");?></a></li>
	</ol>
</div>
<div class="content">
	<div class="box br-theme">
		<form id="mb_form" name="mb_form" action="<?=$iw['m_path']?>/all_member_lost_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
			<div class="form-group">
				<label for="login_name"><?=national_language($iw[language],"a0065","아이디(이메일주소)");?></label>
				<input type="text" class="form-control" name="mb_mail" placeholder="" />
			</div>
			<div class="form-group">
				<label for="login_pass"><?=national_language($iw[language],"a0070","이름");?></label>
				<input type="text" class="form-control" name="mb_name" onkeydown="javascript: if (event.keyCode == 13) {check_form();}" placeholder="" />
			</div>
			<span class="help-block"><?=national_language($iw[language],"a0117","해당 이메일로 변경된 비밀번호를 전송합니다.");?></span>
			<div class="btn-list">
				<a class="btn btn-theme" href="javascript:check_form();"><?=national_language($iw[language],"a0118","전송");?></a><br/><br/>
			</div>
		</form>
	</div> <!-- /.box -->
</div>

<script type="text/javascript">
	function check_form() {
		if (mb_form.mb_mail.value == ""){
			alert("<?=national_language($iw[language],'a0082','이메일을 입력하여 주십시오.');?>");
			mb_form.mb_mail.focus();
			return;
		}
		if (mb_form.mb_mail.value.search(/^((\w|[\-\.])+)@((\w|[\-\.][^(\.)\1])+)\.([A-Za-z]+)$/)== -1){
			alert("<?=national_language($iw[language],'a0083','잘못된 이메일 주소입니다.');?>");
			mb_form.mb_mail.focus();
			return;
		}
		if (mb_form.mb_name.value == ""){
			alert("<?=national_language($iw[language],'a0086','이름을 입력하여 주십시오.');?>");
			mb_form.mb_name.focus();
			return;
		}
		mb_form.submit();
	}
</script>
 
<?php
include_once("_tail.php");
?>



