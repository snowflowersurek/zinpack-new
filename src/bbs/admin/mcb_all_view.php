<?php
include_once("_common.php");
if ($iw[type] != "mcb" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]";

$md_code = $_GET["idx"];

$sql = "select * from $iw[mcb_data_table] where ep_code = '$iw[store]' and md_code = '$md_code'";
$row = sql_fetch($sql);
if (!$row["md_no"]) alert("잘못된 접근입니다!","");

$mb_code = $row["mb_code"];
$md_type = $row["md_type"];
$md_subject = stripslashes($row["md_subject"]);
$md_youtube = $row["md_youtube"];
$md_attach = $row["md_attach"];
$md_attach_name = $row["md_attach_name"];
$md_ip = $row["md_ip"];
$md_hit = $row["md_hit"];
$md_recommend = $row["md_recommend"];
$md_datetime = $row["md_datetime"];
$md_padding = $row["md_padding"];
$md_secret = $row["md_secret"];
$gp_code = $row["gp_code"];

if($row["md_display"]==1){
	$dd_value = 2;
	$dd_btn = "숨김";
}else{
	$dd_value = 1;
	$dd_btn = "노출";
}

if ($gp_code == "all"){
	$upload_path .= "/all";
}else{
	$row2 = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$gp_code'");
	$upload_path .="/$row2[gp_nick]";
}

if($md_type == 1){
	$md_content = str_replace("\r\n", "<br/>", $row["md_content"]);
	$pattern = "/(mms|http|HTTP|https|HTTPS|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/"; 
	$md_content = preg_replace( $pattern, "<a href=\"\\1://\\2\" target=\"_blank\">\\1://\\2</a>", $md_content );

	if(strstr($md_youtube, "youtu.be")){
		$youtube = explode("youtu.be/",$md_youtube);
		if(strstr($youtube[1], "?")){
			$youtube2 = explode("?",$youtube[1]);
			$youtube_code = $youtube2[0];
		}else{
			$youtube_code = $youtube[1];
		}
	}else if(strstr($md_youtube, "youtube.com")){
		$youtube = explode("v=",$md_youtube);
		if(strstr($youtube[1], "&")){
			$youtube2 = explode("&",$youtube[1]);
			$youtube_code = $youtube2[0];
		}else{
			$youtube_code = $youtube[1];
		}
	}
}else if($md_type == 2){
	$md_content = $row["md_content"];
}

$sql2 = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$mb_code'";
$row2 = sql_fetch($sql2);
$mb_nick = $row2["mb_nick"];
?>
<style>
	video { max-width: 700px; height: auto;}
	.video-container { padding-bottom:56.25%; padding-top:30px; height:0; overflow: hidden;}
	.video-container iframe, .video-container object, .video-container embed {max-width:700px; position:absolute; top:0; left:0; width:100%; height:100%;}
</style>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-clipboard"></i>
			게시판
		</li>
		<li class="active">전체 게시글 조회</li>
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
			전체 게시글 조회
			<small>
				<i class="fa fa-angle-double-right"></i>
				게시글 정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">게시글코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$md_code?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">조회수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$md_hit?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">추천수</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$md_recommend?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">등록일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$md_datetime?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">작성자</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_nick?> (<?=$md_ip?>)</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">글쓰기 방식</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?
									if($md_type == 1){
										echo "간편모드";
									}else{
										echo "웹에디터";
									}
								?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">카테고리</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?
									$cg_code = $row["cg_code"];
									$gp_code = $row["gp_code"];

									$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and state_sort = '$iw[type]' and cg_code = '$cg_code'";
									$row2 = sql_fetch($sql2);
									$hm_upper_code = $row2["hm_upper_code"];
									$dd_category = $row2["hm_name"];
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$dd_category = $row2["hm_name"]." > ".$dd_category;
									}
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$dd_category = $row2["hm_name"]." > ".$dd_category;
									}
									if ($hm_upper_code != ""){
										$sql2 = " select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code = '$gp_code' and hm_code = '$hm_upper_code'";
										$row2 = sql_fetch($sql2);
										$hm_upper_code = $row2["hm_upper_code"];
										$dd_category = $row2["hm_name"]." > ".$dd_category;
									}
									echo "$dd_category";
								?>
							</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">제목</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$md_subject?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">옵션</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">좌우여백( <?if($md_padding==1){?>O<?}else{?>X<?}?> ) / 비밀글( <?if($md_secret==1){?>O<?}else{?>X<?}?> )</p>
						</div>
					</div>

				<?if($md_type == 1){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">유튜브영상</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?if($youtube_code){?>
								<div class="video-container">
									<iframe type="text/html" width="700" height="400" src="//www.youtube.com/embed/<?=$youtube_code?>?autoplay=1" frameborder="0"/></iframe>
								</div>
								<?}?>
							</p>
						</div>
					</div>
				
					<div class="form-group">
						<label class="col-sm-1 control-label">이미지</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<div class="html_edit_wrap" style="max-width:700px;">
									<?
										for ($i=1; $i<=10; $i++) {
											if($row["md_file_".$i]){
												?><div class="image-container"><img src="<?=$upload_path?>/<?=$md_code?>/<?=$row["md_file_".$i]?>"/></div><?
											}
										}
									?>
								</div>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">본문</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$md_content?></p>
						</div>
					</div>
				<?}else if($md_type == 2){?>
					<div class="form-group">
						<label class="col-sm-1 control-label">본문</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><div class="html_edit_wrap" style="max-width:1000px;"><?=stripslashes($md_content);?></div></p>
						</div>
					</div>
				<?}?>
					<div class="form-group">
						<label class="col-sm-1 control-label">첨부파일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<a href="javascript:file_download('<?=$iw['admin_path']?>/mcb_data_download.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$md_code?>', '<?=urlencode($md_attach_name)?>');"><?=$md_attach_name?></a> 
							</p>
						</div>
					</div>
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-primary" type="button" onclick="javascript:exposure_edit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$md_code?>','<?=$dd_value?>');">
							<i class="fa fa-undo"></i>
							<?=$dd_btn?> 변경
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw['admin_path']?>/mcb_all_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
							<i class="fa fa-undo"></i>
							목록
						</button>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<script type="text/javascript">
	function exposure_edit(type,ep,gp,no,dis) {
		if (dis == 1) {
			confirm_text = "해당 게시글을 노출로 변경하시겠습니까?"
		}else{
			confirm_text = "해당 게시글을 숨김으로 변경하시겠습니까?"
		}
		if (confirm(confirm_text)) {
			location.href="mcb_all_display.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+no+"&dis="+dis;
		}
	}

	function file_download(link, file) {
		document.location.href=link;
	}
</script>

<?
include_once("_tail.php");
?>