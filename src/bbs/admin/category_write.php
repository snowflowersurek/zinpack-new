<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");
include_once("_cg_head.php");

$sql = "select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = 'main'";
$row = sql_fetch($sql);
if (!$row["cg_no"]) alert("잘못된 접근입니다!","");
$cg_no = $row[cg_no];
$cg_level_write = $row[cg_level_write];
$cg_level_comment = $row[cg_level_comment];
$cg_level_read = $row[cg_level_read];
$cg_level_upload = $row[cg_level_upload];
$cg_level_download = $row[cg_level_download];
$cg_date = $row[cg_date];
$cg_writer = $row[cg_writer];
$cg_hit = $row[cg_hit];
$cg_comment = $row[cg_comment];
$cg_recommend = $row[cg_recommend];
$cg_point_btn = $row[cg_point_btn];
$cg_comment_view = $row[cg_comment_view];
$cg_recommend_view = $row[cg_recommend_view];
$cg_url = $row[cg_url];
$cg_facebook = $row[cg_facebook];
$cg_twitter = $row[cg_twitter];
$cg_googleplus = $row[cg_googleplus];
$cg_pinterest = $row[cg_pinterest];
$cg_linkedin = $row[cg_linkedin];
$cg_delicious = $row[cg_delicious];
$cg_tumblr = $row[cg_tumblr];
$cg_digg = $row[cg_digg];
$cg_stumbleupon = $row[cg_stumbleupon];
$cg_reddit = $row[cg_reddit];
$cg_sinaweibo = $row[cg_sinaweibo];
$cg_qzone = $row[cg_qzone];
$cg_renren = $row[cg_renren];
$cg_tencentweibo = $row[cg_tencentweibo];
$cg_kakaotalk = $row[cg_kakaotalk];
$cg_line = $row[cg_line];
?>

<div class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="cg_form" name="cg_form" action="<?=$iw['admin_path']?>/category_write_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">

					<div class="form-group">
						<label class="col-sm-2 control-label">분류명</label>
						<div class="col-sm-8">
							<input type="text" placeholder="입력" class="col-xs-12 col-sm-12" name="cg_name">
						</div>
					</div>
					<div class="space-4"></div>
					
					<?php if($iw[type]!="publishing_contest"){?>
					<div class="form-group">
						<label class="col-sm-2 control-label">글쓰기 권한</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-8" name="cg_level_write">
								<?php
									$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
									$result = sql_query($sql);
									while($row = @sql_fetch_array($result)){
								?>
									<option value="<?=$row["gl_level"]?>" <?php if{?>selected<?php }?>><?=$row["gl_name"]?></option>
								<?php
									}
								?>
							</select> 이상
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">덧글쓰기 권한</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-8" name="cg_level_comment">
								<?php
									$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
									$result = sql_query($sql);
									while($row = @sql_fetch_array($result)){
								?>
									<option value="<?=$row["gl_level"]?>" <?php if{?>selected<?php }?>><?=$row["gl_name"]?></option>
								<?php
									}
								?>
							</select> 이상
						</div>
					</div>
					<div class="space-4"></div>
					<?php }?>

					<div class="form-group">
						<label class="col-sm-2 control-label">읽기 권한</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-8" name="cg_level_read">
									<option value="10" <?php if{?>selected<?php }?>>비회원</option>
								<?php
									$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
									$result = sql_query($sql);
									while($row = @sql_fetch_array($result)){
								?>
									<option value="<?=$row["gl_level"]?>" <?php if{?>selected<?php }?>><?=$row["gl_name"]?></option>
								<?php
									}
								?>
							</select> 이상
						</div>
					</div>
					<div class="space-4"></div>

					<?php if($iw[type]=="mcb"){?>
					<div class="form-group">
						<label class="col-sm-2 control-label">업로드 권한</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-8" name="cg_level_upload">
								<?php
									$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
									$result = sql_query($sql);
									while($row = @sql_fetch_array($result)){
								?>
									<option value="<?=$row["gl_level"]?>" <?php if{?>selected<?php }?>><?=$row["gl_name"]?></option>
								<?php
									}
								?>
							</select> 이상
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">다운로드 권한</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-8" name="cg_level_download">
								<option value="10" <?php if{?>selected<?php }?>>비회원</option>
								<?php
									$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
									$result = sql_query($sql);
									while($row = @sql_fetch_array($result)){
								?>
									<option value="<?=$row["gl_level"]?>" <?php if{?>selected<?php }?>><?=$row["gl_name"]?></option>
								<?php
									}
								?>
							</select> 이상
						</div>
					</div>
					<div class="space-4"></div>
					<?php }else if($iw[type]=="book"){?>
					<div class="form-group">
						<label class="col-sm-2 control-label">샘플보기 권한</label>
						<div class="col-sm-8">
							<select class="col-xs-12 col-sm-8" name="cg_level_download">
								<option value="10" <?php if{?>selected<?php }?>>비회원</option>
								<?php
									$sql = "select * from $iw[group_level_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and gl_display=1 order by gl_no asc";
									$result = sql_query($sql);
									while($row = @sql_fetch_array($result)){
								?>
									<option value="<?=$row["gl_level"]?>" <?php if{?>selected<?php }?>><?=$row["gl_name"]?></option>
								<?php
									}
								?>
							</select> 이상
						</div>
					</div>
					<div class="space-4"></div>
					<?php } ?><?php if($iw[type]!="publishing_contest"){?>
					<div class="form-group">
						<label class="col-sm-2 control-label">기본 정보</label>
						<div class="col-sm-8">
						<?php if($iw[type]!="shop" && $iw[type]!="publishing"){?>
							<label class="middle">
								<input type="checkbox" name="cg_date" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 날짜</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_writer" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 글쓴이</span>
							</label>
						<?php }?>
							<label class="middle">
								<input type="checkbox" name="cg_hit" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 조회수</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_comment" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 댓글수</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_recommend" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 추천수</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label">부가 기능</label>
						<div class="col-sm-8">
						<?php if($iw[type]!="shop" && $iw[type]!="publishing"){?>
							<label class="middle">
								<input type="checkbox" name="cg_point_btn" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 포인트 선물하기</span>
							</label>
						<?php }?>
							<label class="middle">
								<input type="checkbox" name="cg_comment_view" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 댓글</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_recommend_view" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl">추천</span>
							</label>
						</div>
					</div>
					<div class="space-4"></div>
					<?php }?>

					<div class="form-group" style="display:none;">
						<label class="col-sm-2 control-label">SNS 공유</label>
						<div class="col-sm-8">
							<label class="middle">
								<input type="checkbox" name="cg_url" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> URL복사</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_facebook" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 페이스북</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_twitter" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 트위터</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_googleplus" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> Google+</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_pinterest" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> Pinterest</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_linkedin" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> Linkedin</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_delicious" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> Delicious</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_tumblr" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> Tumblr</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_digg" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> Digg</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_stumbleupon" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> StumbleUpon</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_reddit" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> Reddit</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_sinaweibo" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 新浪微博</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_qzone" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> QQ 空间</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_renren" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 人人网</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_tencentweibo" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 腾讯微博</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_kakaotalk" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 카카오톡</span>
							</label>
							<label class="middle">
								<input type="checkbox" name="cg_line" value="1" <?php if{?>checked<?php }?>>
								<span class="lbl"> 라인</span>
							</label>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								등록
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
		if (cg_form.cg_name.value == ""){
			alert("분류명을 입력하여 주십시오.");
			cg_form.cg_name.focus();
			return;
		}
		cg_form.submit();
	}
</script>

<?php
include_once("_cg_tail.php");
?>



