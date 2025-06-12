<?php
include_once("_common.php");
if ($iw[type] != "book" || ($iw[level] != "seller" && $iw[level] != "member")) alert("잘못된 접근입니다!","");

include_once($iw['admin_path']."/_cg_head.php");

$bd_code = $_GET["idx"];
$bt_no = $_GET["no"];

$sql = "select * from $iw[book_thesis_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and bd_code = '$bd_code' and mb_code = '$iw[member]' and bt_no = '$bt_no'";
$row = sql_fetch($sql);
if (!$row["bt_no"]) alert("잘못된 접근입니다!","");

$bt_title_kr = $row["bt_title_kr"];
$bt_title_kr = str_replace("\'", '&#039;', $bt_title_kr);
$bt_title_kr = str_replace('\"', '&quot;', $bt_title_kr);

$bt_sub_kr = $row["bt_sub_kr"];
$bt_sub_kr = str_replace("\'", '&#039;', $bt_sub_kr);
$bt_sub_kr = str_replace('\"', '&quot;', $bt_sub_kr);

$bt_title_us = $row["bt_title_us"];
$bt_title_us = str_replace("\'", '&#039;', $bt_title_us);
$bt_title_us = str_replace('\"', '&quot;', $bt_title_us);

$bt_sub_us = $row["bt_sub_us"];
$bt_sub_us = str_replace("\'", '&#039;', $bt_sub_us);
$bt_sub_us = str_replace('\"', '&quot;', $bt_sub_us);

$bt_person = $row["bt_person"];
$bt_person = str_replace("\'", '&#039;', $bt_person);
$bt_person = str_replace('\"', '&quot;', $bt_person);
?>
<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="bt_form" name="bt_form" action="<?=$iw['admin_path']?>/thesis/thesis_page_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
				<input type="hidden" name="bd_code" value="<?=$row['bd_code']?>" />
				<input type="hidden" name="bt_no" value="<?=$row['bt_no']?>" />
					<div class="form-group">
						<label class="col-sm-2 control-label">주제목(한글)</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="bt_title_kr" value="<?=$bt_title_kr?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">부제목(한글)</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="bt_sub_kr" value="<?=$bt_sub_kr?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">주제목(영문)</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="bt_title_us" value="<?=$bt_title_us?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">부제목(영문)</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="bt_sub_us" value="<?=$bt_sub_us?>">
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">작성자</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="bt_person" value="<?=$bt_person?>">
						</div>
					</div>
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">PDF 페이지</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-4" name="bt_page" maxlength="3" value="<?=$row['bt_page']?>">
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-danger" type="button" onclick="javascript:page_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$bd_code?>','<?=$row['bt_no']?>');">
								삭제
							</button>
							<button class="btn btn-info" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								수정
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
		if (bt_form.bt_person.value == ""){
			alert("작성자를 입력하여 주십시오.");
			bt_form.bt_person.focus();
			return;
		}
		if (bt_form.bt_page.value == ""){
			alert("PDF 페이지를 입력하여 주십시오.");
			bt_form.bt_page.focus();
			return;
		}
		var e1 = bt_form.bt_page;
		var num ="0123456789";
		event.returnValue = true;
		for (var i=0;i<e1.value.length;i++){
			if(-1 == num.indexOf(e1.value.charAt(i)))
			event.returnValue = false;
		}
		if (!event.returnValue){
			alert('숫자로만 입력가능한 항목입니다.');
			bt_form.bt_page.focus();
			return;
		}
		bt_form.submit();
	}

	function page_delete(type,ep,gp,idx,no) { 
		if (confirm('삭제하시겠습니까?')) {
			location.href="thesis_page_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx+"&no="+no;
		}
	}
</script>

<?
include_once($iw['admin_path']."/_cg_tail.php");
?>