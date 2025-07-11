<?php
include_once("_common.php");
if ($iw[level] == "guest") alert(national_language($iw[language],"a0032","로그인 후 이용해주세요"),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

if ($iw[gp_level] == "gp_guest" && $iw[group] != "all") alert(national_language($iw[language],"a0004","그룹회원만 이용하실 수 있습니다."),"$iw[m_path]/all_group_join.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]");

$row = sql_fetch(" select ep_nick,ep_upload,ep_upload_size from $iw[enterprise_table] where ep_code = '$iw[store]'");
$ep_upload = $row[ep_upload];
$ep_upload_size = $row[ep_upload_size];
$upload_path_write = "/$iw[type]/$row[ep_nick]";

if ($iw[group] == "all"){
	$upload_path_write .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path_write .= "/$row[gp_nick]";
}

session_start();
$session_id='infoway'; //$session id
$iw[CKeditor] = "on";

$md_code = "md".uniqid(rand());
$upload_path_write = $upload_path_write."/".$md_code;
set_cookie("iw_upload",$upload_path_write,time()+36000);

include_once("_head.php");

if(preg_match('/(iPod|iPhone|Mobile|UP.Browser|Android|BlackBerry|Windows CE|Nokia|webOS|Opera Mini|SonyEricsson|opera mobi|Windows Phone)/i', $_SERVER['HTTP_USER_AGENT'])){
	$mobile_check = "ok";
}
?>

<script src="/include/ckeditor/ckeditor5.js"></script>
<script src="/include/ckeditor/ckeditor5-adapter.js"></script>
<script src="/include/ckeditor/mutilple/jquery.min.js"></script>
<script src="/include/ckeditor/mutilple/jquery.wallform.js"></script>
<script>
 $(document).ready(function() { 
	$('#photoimg').die('click').live('change', function()			{ 
		$("#imageform").ajaxForm({target: "#multiple_image", 
			 beforeSubmit:function(){ 
			console.log('ttest');
			$("#imageloadstatus").show();
			 $("#imageloadbutton").hide();
			 }, 
			success:function(){ 
			console.log('test');
			 $("#imageloadstatus").hide();
			 $("#imageloadbutton").show();
			 CKEDITOR.instances.contents1.setData(CKEDITOR.instances.contents1.getData()+$("#multiple_image").html());
			 $("#multiple_image").html('');
			}, 
			error:function(){ 
			console.log('xtest');
			 $("#imageloadstatus").hide();
			$("#imageloadbutton").show();
			} }).submit();
	});
}); 
</script>

<div class="content">
	<div class="row">
        <div class="col-sm-12">
			<div class="breadcrumb-box input-group">
                <ol class="breadcrumb ">
                    <li><a href="#"><?=national_language($iw[language],"a0198","글쓰기");?></a></li>
                </ol>
				<span class="input-group-btn">
					<a class="btn btn-theme" href="<?=$iw['m_path']?>/mcb_data_write.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0198","글쓰기");?>"><i class="fa fa-pencil fa-lg"></i></a>
				</span>
            </div>
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<form id="md_form" name="md_form" action="<?=$iw['m_path']?>/mcb_data_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" enctype="multipart/form-data">
						<input type="hidden" name="md_code" value="<?=$md_code?>" />
						<input type="hidden" name="upload_path" value="<?=$upload_path_write?>" />
						<input type="hidden" name="ep_upload_size" value="<?=$ep_upload_size?>" />
						<?php if(($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all")){?>
						<div class="form-group">
							<label for="md_datetime2">등록일</label>
							<div>
								<input type="text" class="form-control" name="md_datetime2" maxlength="16" style="display: inline-block; width:150px;" />
								예) <?=date("Y-m-d H:i")?>
							</div>
						</div>
						<?php }?>
						<div class="form-group">
							<label for="md_type"><?=national_language($iw[language],"a0267","글쓰기 방식");?></label>
							<div>
							<input type="radio" name="md_type" value="1" id="간편모드" onclick="javascript:type_change(this.value);" checked/>&nbsp;<label for="간편모드"><?=national_language($iw[language],"a0268","간편모드");?></label>&nbsp;&nbsp;&nbsp;
							<input type="radio" name="md_type" value="2" id="웹에디터" onclick="javascript:type_change(this.value);" />&nbsp;
							<label for="웹에디터">
								<?=national_language($iw[language],"a0269","웹에디터"); if($mobile_check == "ok"){?>
								(모바일 미지원)
							<?php }?>
							</label>
							</div>
						</div>
						<div class="form-group">
							<label for="cg_code"><?=national_language($iw[language],"a0013","카테고리");?></label>
							<select class="form-control" name="cg_code" onchange="javascript:cetegory_change(this.value,'<?=$ep_upload?>');">
								<option value=""><?=national_language($iw[language],"a0186","선택");?></option>
								<?php
									$hm_code_check = $_GET[menu];
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
										if($iw[mb_level] < $rows["cg_level_upload"]){
											$cg_level_upload = "n";
										}else{
											$cg_level_upload = "y";
										}
								?>
									<option value="<?=$cg_code?>,<?=$cg_level_upload?>" <?php if{?>disabled<?php }else if($hm_code_check==$hm_code){?>selected<?php }?>><?=$hm_name1?></option>
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
											if($iw[mb_level] < $rows["cg_level_upload"]){
												$cg_level_upload = "n";
											}else{
												$cg_level_upload = "y";
											}
									?>
										<option value="<?=$cg_code?>,<?=$cg_level_upload?>" <?php if{?>disabled<?php }else if($hm_code_check==$hm_code){?>selected<?php }?>><?=$hm_name1?> > <?=$hm_name2?></option>
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
												if($iw[mb_level] < $rows["cg_level_upload"]){
													$cg_level_upload = "n";
												}else{
													$cg_level_upload = "y";
												}
										?>
											<option value="<?=$cg_code?>,<?=$cg_level_upload?>" <?php if{?>disabled<?php }else if($hm_code_check==$hm_code){?>selected<?php }?>><?=$hm_name1?> > <?=$hm_name2?> > <?=$hm_name3?></option>
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
													if($iw[mb_level] < $rows["cg_level_upload"]){
														$cg_level_upload = "n";
													}else{
														$cg_level_upload = "y";
													}
											?>
												<option value="<?=$cg_code?>,<?=$cg_level_upload?>" <?php if{?>disabled<?php }else if($hm_code_check==$hm_code){?>selected<?php }?>><?=$hm_name1?> > <?=$hm_name2?> > <?=$hm_name3?> > <?=$hm_name4?></option>
											<?php } ?><?php }
											}
										}
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="md_subject"><?=national_language($iw[language],"a0126","제목");?></label>
							<input type="text" class="form-control" name="md_subject" placeholder="<?=national_language($iw[language],"a0126","제목");?>" />
						</div>
						<div class="form-group">
							<label>옵션</label>
							<div>
								<label class="middle">
									<input type="checkbox" name="md_padding" value="1">
									<span class="lbl"> 좌우여백</span>
								</label>
								<label class="middle">
									<input type="checkbox" name="md_secret" value="1">
									<span class="lbl"> 비밀글</span>
								</label>
							</div>
						</div>
						
						<div id="upload_form" style="display:none;">
							<div class="form-group">
								<label for="md_attach">파일첨부 (최대 <?=$ep_upload_size?>MB)</label>
								<input type='file' name='md_attach'>
							</div>
						</div>

						<div id="text_only">
							<div class="form-group">
								<label for="md_youtube"><?=national_language($iw[language],"a0187","유튜브URL");?></label>
								<input type="text" class="form-control" name="md_youtube" placeholder="<?=national_language($iw[language],"a0187","유튜브URL");?>" />
							</div>
							<div class="form-group">
								<label><?=national_language($iw[language],"a0270","내용");?></label>
								<textarea name="md_content" class="form-control" style="height:150px;"></textarea>
							</div>
							<div class="form-group">
								<label>
									<?=national_language($iw[language],"a0271","이미지");?>
									<span onclick="add_file();" style="cursor:pointer;"><i class="fa fa-plus-square fa-lg"></i></span> 
									<span onclick="del_file();" style="cursor:pointer;"><i class="fa fa-minus-square fa-lg"></i></span>
								</label>
								<div>
									<table id="variableFiles"></table>
								</div>
							</div>
						</div>
						<div id="web_editor" style="display:none;">
							<div class="form-group">
								<label><?=national_language($iw[language],"a0270","내용");?></label>
								<style TYPE="text/css">.cke_source{color:#000;}</style>
								<textarea class="ckeditor" width="100%" id="contents1" name="contents1" height="300px"></textarea>
							</div>
						</div>
					</form>
					<form id="imageform" method="post" enctype="multipart/form-data" action='/include/ckeditor/mutilple/ajaxImageUpload.php' style="display:none;">
						<div class="form-group">
							<label>이미지 다중 업로드</label>
							<div id='multiple_image'></div>
							<div id='imageloadstatus' style='display:none'><img src="/include/ckeditor/mutilple/loader.gif" alt="Uploading...."/></div>
							<div id='imageloadbutton'><input type="file" name="photos[]"  class='form-control' id="photoimg" multiple="true" /></div>
						</div>
					</form>
					<a href="javascript:check_form();" class="btn btn-theme"><?=national_language($iw[language],"a0259","저장");?></a>
				</div>
			</div>
        </div> <!-- /.col -->
	</div> <!-- /.row -->
</div> <!-- /.content -->
<script type="text/javascript">
	var flen = 0;
	function add_file()
	{
		var upload_count = 10;
		if (upload_count && flen >= upload_count)
		{
			alert(upload_count+"<?=national_language($iw[language],'a0188','개 까지만 등록 가능합니다.');?>");
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

		objCell.innerHTML = flen+1+" - <input type='file' class='form-control' name='md_file[]'><input type='hidden' name='md_file_count[]'>";

		flen++;
	}
	//add_file();
	function del_file()
	{
		// file_length 이하로는 필드가 삭제되지 않아야 합니다.
		var file_length = 0;
		var objTbl = document.getElementById("variableFiles");
		if (objTbl.rows.length > file_length)
		{
			objTbl.deleteRow(objTbl.rows.length - 1);
			flen--;
		}
	}
	function type_change(check)
	{
		if (check == "1"){
			document.getElementById('text_only').style.display="";
			document.getElementById('web_editor').style.display="none";
			document.getElementById('imageform').style.display="none";
			return;
		}else if (check == "2"){
			document.getElementById('text_only').style.display="none";
			document.getElementById('web_editor').style.display="";
			document.getElementById('imageform').style.display="";
			return;
		}
	}
	function cetegory_change(code,upload){
		var jbSplit = code.split( ',' );
		if (upload == "1" && jbSplit[1] == "y"){
			document.getElementById('upload_form').style.display="";
			return;
		}else{
			document.getElementById('upload_form').style.display="none";
			return;
		}
	}
	cetegory_change(md_form.cg_code.value,"<?=$ep_upload?>");

	function check_form() {
		if (md_form.cg_code.value == ""){
			alert("<?=national_language($iw[language],'a0189','카테고리를 선택하여 주십시오.');?>");
			md_form.cg_code.focus();
			return;
		}
		if (md_form.md_subject.value == "") {
			alert("<?=national_language($iw[language],'a0272','제목을 입력하여 주십시오.');?>");
			md_form.md_subject.focus();
			return;
		}
		if (document.getElementById('text_only').style.display != "none"){
			if (md_form.md_content.value == "") {
				alert("<?=national_language($iw[language],'a0273','내용을 입력하여 주십시오.');?>");
				md_form.md_content.focus();
				return;
			}
			for(var a=0; a < flen; a++){
				if (document.getElementsByName('md_file[]')[a].value == ""){
					alert("<?=national_language($iw[language],'a0190','이미지를 첨부하여 주십시요');?>");
					document.getElementsByName('md_file[]')[a].focus();
					return;
				}
				else if(!document.getElementsByName('md_file[]')[a].value.match(/(.png|.PNG|.jpg|.JPG|.jpeg|.JPEG|.gif|.GIF)/)) { 
					alert("<?=national_language($iw[language],'a0191','이미지 파일만 업로드 가능합니다.');?>");
					document.getElementsByName('md_file[]')[a].focus();
					return;
				}
			}
		}else{
			if (CKEDITOR.instances.contents1.getData() == "") {
				alert("<?=national_language($iw[language],'a0273','내용을 입력하여 주십시오.');?>");
				CKEDITOR.instances.contents1.focus();
				return;
			}
		}
		md_form.submit();
	}

</script>

<?php
include_once("_tail.php");
?>



