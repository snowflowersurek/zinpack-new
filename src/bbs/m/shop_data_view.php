<?php
include_once("_common.php");
include_once("_head_sub.php");

$row = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = "/$iw[type]/$row[ep_nick]";

if ($iw[group] == "all"){
	$upload_path .= "/all";
}else{
	$row = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
	$upload_path .= "/$row[gp_nick]";
}

$sd_code = $_GET["item"];

$sql = "select * from $iw[shop_data_table] where ep_code = '$iw[store]' and sd_code = '$sd_code'";
$row = sql_fetch($sql);
if (!$row["sd_no"]) alert(national_language($iw[language],"a0035","잘못된 접근입니다."),"");

if ($row["sd_display"]!=1){
	$sale_ok = "no";
}else{
	$sale_ok = "yes";
}

$sd_no = $row["sd_no"];
$mb_code = $row["mb_code"];
$sd_code = $row["sd_code"];
$cg_code_view = $row["cg_code"];
$sd_image = $row["sd_image"];
$sd_subject = stripslashes($row["sd_subject"]);
$sd_sale = $row["sd_sale"];
$sd_price = $row["sd_price"];
$sd_percent = floor(100-($sd_sale/$sd_price*100));
$sd_hit = number_format($row["sd_hit"]);
$sd_recommend = $row["sd_recommend"];
$sd_max = $row["sd_max"];
$sd_content = stripslashes($row["sd_content"]);
$sd_delivery_type = $row["sd_delivery_type"];
$sd_delivery_price = $row["sd_delivery_price"];
$sy_code = $row["sy_code"];
$sd_information = str_replace("\r\n", "<br/>", $row["sd_information"]);

$kakaoContent = strip_tags($row["sd_content"]);
$kakaoContent = str_replace("&amp;", "＆",$kakaoContent);
$kakaoContent = str_replace("&quot;", "“",$kakaoContent);
$kakaoContent = str_replace("&#39;", "‘",$kakaoContent);
$kakaoContent = str_replace("&nbsp;", " ",$kakaoContent);
$kakaoContent = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",cut_str(str_replace("\r\n", "",$kakaoContent),200));

$sql2 = " select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code_view'";
$row2 = sql_fetch($sql2);
$cg_hit = $row2[cg_hit];
$cg_comment = $row2[cg_comment];
$cg_recommend = $row2[cg_recommend];
$cg_point_btn = $row2[cg_point_btn];
$cg_comment_view = $row2[cg_comment_view];
$cg_recommend_view = $row2[cg_recommend_view];
$cg_url = $row2[cg_url];
$cg_facebook = $row2[cg_facebook];
$cg_twitter = $row2[cg_twitter];
$cg_googleplus = $row2[cg_googleplus];
$cg_pinterest = $row2[cg_pinterest];
$cg_linkedin = $row2[cg_linkedin];
$cg_delicious = $row2[cg_delicious];
$cg_tumblr = $row2[cg_tumblr];
$cg_digg = $row2[cg_digg];
$cg_stumbleupon = $row2[cg_stumbleupon];
$cg_reddit = $row2[cg_reddit];
$cg_sinaweibo = $row2[cg_sinaweibo];
$cg_qzone = $row2[cg_qzone];
$cg_renren = $row2[cg_renren];
$cg_tencentweibo = $row2[cg_tencentweibo];
$cg_kakaotalk = $row2[cg_kakaotalk];
$cg_line = $row2[cg_line];
$cg_level_comment = $row2[cg_level_comment];

if ($row2[cg_level_read] != 10 && $iw[level]=="guest"){
	alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
}else if($row2[cg_level_read] != 10 && $iw[mb_level] < $row2[cg_level_read]){
	alert(national_language($iw[language],"a0169","해당 카테고리에 읽기 권한이 없습니다."),$iw[m_path]."/shop_data_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$cg_code_view);
}

$sql = "select * from $iw[shop_seller_table] where ep_code = '$iw[store]' and mb_code = '$mb_code' and ss_display = 1";
$row = sql_fetch($sql);

$ss_name = $row["ss_name"];
$ss_tel = $row["ss_tel"];


$rowpath = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
$upload_path = $iw["path"].$upload_path.'/'.$sd_code.'/'.$sd_image;

$kakaoContent = national_money($iw[language], $sd_sale)." ".$kakaoContent;
$kakaoImg = $iw[url].'/shop/'.$rowpath[ep_nick].'/'.$sd_code.'/'.$sd_image;
$kakaoURL = get_shortURL(urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));

$fbTitleQ = $sd_subject;
$fbTitle = strip_tags($sd_subject);
$fbTitle = str_replace("&amp;", "＆",$fbTitle);
$fbTitle = str_replace("&quot;", "“",$fbTitle);
$fbTitle = str_replace("&#39;", "‘",$fbTitle);
$fbTitle = str_replace("&nbsp;", " ",$fbTitle);
$fbTitle = preg_replace("/[^A-Za-z90-9가-힣＆“‘]/i"," ",$fbTitle);
$fbDescription = $kakaoContent;
$fbImage = $kakaoImg;
$fbUrl = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$sql2 = "select count(*) as cnt from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$sd_code' and cm_display = 1";
$result2 = sql_fetch($sql2);
$reply_count = number_format($result2[cnt]);

include_once("_head_share.php");
?>
<style>
	.image-container		{ position:relative; max-width: 1100px;}
	.image-container img	{ max-width:100%; height: auto; overflow: hidden;}
	.video-container		{ position:relative; padding-bottom:56.25%; padding-top:30px; height:0; overflow: hidden;}
	.video-container video	{ max-width: 1100px; height: auto;}
	.video-container iframe, .video-container object, .video-container embed { position:absolute; top:0; left:0; width:100%; height:100%;}
	.sd_information td { padding:0 20px 0 0;}
</style>

<script type="text/javascript">
	var user_level = '<?=$iw[level]?>';
	var total_max = <?=$sd_max?>;

	var option_arr = new Array();
	function add_aoption_arr(so_i,so_no,so_name,so_amount,so_price)
	{
		option_arr[so_i] = new Array();
		option_arr[so_i][0] = so_no;
		option_arr[so_i][1] = so_name;
		option_arr[so_i][2] = so_amount;
		option_arr[so_i][3] = so_price;
		option_arr[so_i][4] = 0;
		option_arr[so_i][5] = 0;
	}
	var cart_amount = 0;
	function cart_amount_init(rowsum)
	{
		cart_amount = rowsum;
	}
</script>
<?
	$rowsum = sql_fetch(" select sum(sc_amount) as sumount from $iw[shop_cart_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and sd_code = '$sd_code'");
	echo "<script>cart_amount_init('$rowsum[sumount]');</script>";
?>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<?
					$row2 = sql_fetch(" select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cg_code = '$cg_code_view'");
					$hm_code = $row2[hm_code];
					$hm_view_scrap = $row2[hm_view_scrap];
					$hm_view_size = $row2[hm_view_size];
					$hm_view_scrap_mobile = $row2[hm_view_scrap_mobile];
					if($hm_view_size==12){$hm_view_size = "full";}
					if ($hm_view_scrap!=1){$hm_view_size = "full";}
					if (preg_match('/iPad/',$_SERVER['HTTP_USER_AGENT']) ){$hm_view_size = "full";}

					$navi_cate = "<li><a href='".$iw[m_path]."/all_data_list.php?type=".$iw[type]."&ep=".$iw[store]."&gp=".$iw[group]."&menu=".$hm_code."'>".stripslashes($row2[hm_name])."</a></li>";
					echo $navi_cate;
				?>
			</ol>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_cart_form.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0009","장바구니");?>"><i class="fa fa-shopping-cart fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0008","구매내역");?>"><i class="fa fa-truck fa-lg"></i></a>
			</span>
		</div>
		<form id="sd_form" name="sd_form" action="<?=$iw['m_path']?>/shop_data_view_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
			<input type="hidden" name="sd_code" value="<?=$sd_code?>" />
			<input type="hidden" name="sd_mb_code" value="<?=$mb_code?>" />
		<!-- 우측에 이미지가 있는 상세 글 페이지 -->
		
		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
		<div class="grid-sizer"></div>

			<div class="masonry-item w-4 h-full">
				<div class="box box-media br-theme">
					<?if($sd_price != $sd_sale){?>
					<span class="sale-tag">
						<i class="fa fa-certificate fa-5x"></i>
						<i class="text-icon"><?=$sd_percent?>%</i>
					</span>
					<?}?>
					<img class="media-object img-responsive" src="<?=$upload_path?>" alt="">
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			<div class="masonry-item w-8 h-full">
				<div class="box br-theme">
					<h3 class="media-heading">
						<?=$sd_subject?>
					</h3>
					<div class="content-info">
						<ul class="list-inline">
							<?if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$sd_hit?></li><?}?>
							<?if($cg_comment_view==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?}?>
							<?if($cg_recommend_view==1){?><li><i class="fa fa-thumbs-up"></i> <span id="recommend_board_1"><?=$sd_recommend?></span></li><?}?>
						</ul>
					</div>
					<?if($sd_price != $sd_sale){?><p class="line-through"><small><?=national_money($iw[language], $sd_price);?></small></p><?}?>
					<p><?=national_money($iw[language], $sd_sale);?></p>
					<div class="content-info">
						<ul class="list-inline">
							<?
								$row2 = sql_fetch(" select * from $iw[shop_delivery_table] where ep_code = '$iw[store]' and mb_code = '$mb_code' and sy_code='$sy_code' ");
								$sy_code = $row2["sy_code"];
								$sy_price = $row2["sy_price"];
								$sy_max = $row2["sy_max"];
								$sy_display = $row2["sy_display"];
							?>
							<li><i class="fa fa-truck"></i>
								<?=national_language($iw[language],"a0293","배송비");?> : <?=national_money($iw[language], $sy_price);?> 
							</li>
							<li><i class="fa fa-info-circle"></i> 배송코드 : <?=substr($mb_code, 3, 3).substr($mb_code,-3,3);?>-<?=$sy_code?> 
								(<?if($sy_display == 1){?>
									<?=national_money($iw[language], $sy_max);?> 이상 무료배송
								<?}else{?>
									<?=$sy_max?> <?=national_language($iw[language],"a0215","개");?> 이하 묶음배송
								<?}?>)
							</li>
						</ul>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
			<div class="masonry-item w-8 h-full">
				<div class="box br-theme">
					<h3 class="box-title"><?=national_language($iw[language],"a0236","상품 정보");?></h3>
					<div class="content-info sd_information">
						<?=$sd_information?>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="col-md-6">
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<div class="form-group">
						<label for=""><?=national_language($iw[language],"a0231","상품옵션을 선택해 주세요. - 1인당 최대");?> <?=$sd_max?><?=national_language($iw[language],"a0215","개");?></label>
						<select class="form-control" name="so_group" id="so_group" onchange="javascript:add_file(this.value);">
							<option value=""><?=national_language($iw[language],"a0232","상품 선택");?></option>
						<?
							$sql = "select * from $iw[shop_option_table] where ep_code = '$iw[store]' and sd_code = '$sd_code' order by so_no asc";
							$result = sql_query($sql);
							$i=0;
							while($row = @sql_fetch_array($result)){
								$so_no = $row["so_no"];
								$so_amount = $row["so_amount"];
								$so_name = $row["so_name"];
								$so_price = $row["so_price"];

								echo "<script>add_aoption_arr('$i','$so_no','$so_name','$so_amount','$so_price');</script>";
						?>
							<option value="<?=$i?>" <?if($so_amount<=0){?>disabled<?}?>>&nbsp;<?=$so_name?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?if($so_amount<=0){?><?=national_language($iw[language],"a0233","매진");?><?}else{?><?=number_format($so_amount)?><?=national_language($iw[language],"a0234","개 남음");?><?}?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?=national_money($iw[language], $so_price);?></option>
						<?
								$i++;
							}
						?>
						</select>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		</div>
		<div class="col-md-6">
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<table class="table">
						<tbody id="productBrowser">
							<!-- 상품옵션 자동입력 -->
						</tbody>
					</table>
					<table class="table">
						<tfoot class="text-right">
							<tr>
								<td colspan="2"><?=national_language($iw[language],"a0224","총 상품금액");?> : </td>
								<td id="priceTotal"><?if($iw[language]=="en"){?>US$ <?}?>0<?if($iw[language]=="ko"){?>원<?}?></td>
							</tr>
						</tfoot>
					</table>
					<div class="btn-list text-right">
						<?if($sale_ok=="yes"){?>
							<a class="btn btn-theme" href="javascript:check_shop();"><?=national_language($iw[language],"a0302","장바구니에 넣기");?></a>
						<?}else{?>
							<button class="btn btn-theme"><?=national_language($iw[language],"a0235","판매종료");?></button>
						<?}?>
					</div>
				</div> <!-- /.box -->
			</div> <!-- /.masonry-item -->
		</div>
		
		<div class="masonry-item w-<?=$hm_view_size?> h-full">
			<div class="box br-default">
				<div class='image-container'><?=$sd_content?></div>
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
		</form>

		<?if($hm_view_scrap_mobile==1){?>
			<style>
				@media (min-width:768px){
					.scrap-wrap	{display:;}
				}
				@media (max-width:767px){
					.scrap-wrap	{display:none;}
				}
			</style>
		<?}?>
		<?
			if ($hm_view_scrap==1){
				$scrap_type = "view";
				include_once("all_home_scrap.php");
			}
		?>

		<div class="masonry-item w-full h-full">
			<div class="box br-theme text-right">
				<?if($cg_recommend_view==1){?><a class="btn btn-theme btn-sm" href="javascript:recommend_click('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','board','<?=$sd_no?>');" title="추천"><i class="fa fa-thumbs-up fa-lg"></i> <span id="recommend_board_2"><?=$sd_recommend?></span></a><?}?>
				<?if($cg_url==1){?><a class="btn btn-theme btn-sm" href="javascript:copy_trackback();" title="<?=national_language($iw[language],"a0301","URL 복사");?>"><i class="fa fa-link fa-lg"></i></a><?}?>
			<?if($st_sns_share==1){?>
				<?if($cg_facebook==1){?><a class="btn btn-theme btn-sm" href="javascript:executeFaceBookLink('<?=$fbUrl?>');" title="<?=national_language($iw[language],"a0305","페이스북");?>"><i class="fa fa-facebook fa-lg"></i></a><?}?>
				<?if($cg_twitter==1){?><a class="btn btn-theme btn-sm" href="javascript:executeTwitterLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$kakaoURL?>');" title="<?=national_language($iw[language],"a0306","트위터");?>"><i class="fa fa-twitter fa-lg"></i></a><?}?>
				<?if($cg_googleplus==1){?><a class="btn btn-theme btn-sm" href="javascript:executeGooglePlusLink('<?=$fbUrl?>');" title="Google+"><i class="fa fa-google-plus fa-lg"></i></a><?}?>
				<?if($cg_pinterest==1){?><a class="btn btn-theme btn-sm" href="javascript:executePinterestLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>','<?=$kakaoImg?>');" title="Pinterest"><i class="fa fa-pinterest fa-lg"></i></a><?}?>
				<?if($cg_linkedin==1){?><a class="btn btn-theme btn-sm" href="javascript:executeLinkedInLink('<?=$fbTitle?>','<?=$kakaoContent?>','<?=$fbUrl?>');" title="Linkedin"><i class="fa fa-linkedin fa-lg"></i></a><?}?>
				<?if($cg_delicious==1){?><a class="btn btn-theme btn-sm" href="javascript:executeDeliciousLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Delicious"><i class="fa fa-delicious fa-lg"></i></a><?}?>
				<?if($cg_tumblr==1){?><a class="btn btn-theme btn-sm" href="javascript:executeTumblrLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Tumblr"><i class="fa fa-tumblr fa-lg"></i></a><?}?>
				<?if($cg_digg==1){?><a class="btn btn-theme btn-sm" href="javascript:executeDiggLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Digg"><i class="fa fa-digg fa-lg"></i></a><?}?>
				<?if($cg_stumbleupon==1){?><a class="btn btn-theme btn-sm" href="javascript:executeStumbleUponLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="StumbleUpon"><i class="fa fa-stumbleupon fa-lg"></i></a><?}?>
				<?if($cg_reddit==1){?><a class="btn btn-theme btn-sm" href="javascript:executeRedditLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Reddit"><i class="fa fa-reddit fa-lg"></i></a><?}?>
				<?if($cg_sinaweibo==1){?><a class="btn btn-theme btn-sm" href="javascript:executeSinaWeiboLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="新浪微博"><i class="fa fa-weibo fa-lg"></i></a><?}?>
				<?if($cg_qzone==1){?><a class="btn btn-theme btn-sm" href="javascript:executeQZoneLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="QQ 空间"><i class="fa fa-qq fa-lg"></i></a><?}?>
				<?if($cg_renren==1){?><a class="btn btn-theme btn-sm" href="javascript:executeRenrenLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="人人网"><i class="fa fa-renren fa-lg"></i></a><?}?>
				<?if($cg_tencentweibo==1){?><a class="btn btn-theme btn-sm" href="javascript:executeTencentWeiboLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="腾讯微博"><i class="fa fa-tencent-weibo fa-lg"></i></a><?}?>
			<?}else{?>
				<?if($cg_facebook==1){?><a href="javascript:executeFaceBookLink('<?=$fbUrl?>');" title="<?=national_language($iw[language],"a0305","페이스북");?>"><img src="<?=$iw[design_path]?>/img/btn_facebook.png" alt=""></a><?}?>
				<?if($cg_twitter==1){?><a href="javascript:executeTwitterLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$kakaoURL?>');" title="<?=national_language($iw[language],"a0306","트위터");?>"><img src="<?=$iw[design_path]?>/img/btn_twitter.png" alt=""></a><?}?>
				<?if($cg_googleplus==1){?><a href="javascript:executeGooglePlusLink('<?=$fbUrl?>');" title="Google+"><img src="<?=$iw[design_path]?>/img/btn_googleplus.png" alt=""></a><?}?>
				<?if($cg_pinterest==1){?><a href="javascript:executePinterestLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>','<?=$kakaoImg?>');" title="Pinterest"><img src="<?=$iw[design_path]?>/img/btn_pinterest.png" alt=""></a><?}?>
				<?if($cg_linkedin==1){?><a href="javascript:executeLinkedInLink('<?=$fbTitle?>','<?=$kakaoContent?>','<?=$fbUrl?>');" title="Linkedin"><img src="<?=$iw[design_path]?>/img/btn_linkedin.png" alt=""></a><?}?>
				<?if($cg_delicious==1){?><a href="javascript:executeDeliciousLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Delicious"><img src="<?=$iw[design_path]?>/img/btn_delicious.png" alt=""></a><?}?>
				<?if($cg_tumblr==1){?><a href="javascript:executeTumblrLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Tumblr"><img src="<?=$iw[design_path]?>/img/btn_tumblr.png" alt=""></a><?}?>
				<?if($cg_digg==1){?><a href="javascript:executeDiggLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Digg"><img src="<?=$iw[design_path]?>/img/btn_digg.png" alt=""></a><?}?>
				<?if($cg_stumbleupon==1){?><a href="javascript:executeStumbleUponLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="StumbleUpon"><img src="<?=$iw[design_path]?>/img/btn_stumbleupon.png" alt=""></a><?}?>
				<?if($cg_reddit==1){?><a href="javascript:executeRedditLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="Reddit"><img src="<?=$iw[design_path]?>/img/btn_reddit.png" alt=""></a><?}?>
				<?if($cg_sinaweibo==1){?><a href="javascript:executeSinaWeiboLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="新浪微博"><img src="<?=$iw[design_path]?>/img/btn_weibo.png" alt=""></a><?}?>
				<?if($cg_qzone==1){?><a href="javascript:executeQZoneLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="QQ 空间"><img src="<?=$iw[design_path]?>/img/btn_qq.png" alt=""></a><?}?>
				<?if($cg_renren==1){?><a href="javascript:executeRenrenLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="人人网"><img src="<?=$iw[design_path]?>/img/btn_renren.png" alt=""></a><?}?>
				<?if($cg_tencentweibo==1){?><a href="javascript:executeTencentWeiboLink('[<?=$fbTitle?>]<?=$kakaoContent?>','<?=$fbUrl?>');" title="腾讯微博"><img src="<?=$iw[design_path]?>/img/btn_tencentweibo.png" alt=""></a><?}?>
			<?}?>
				<?if (preg_match('/iPhone|iPod|iPad|BlackBerry|Android|Windows CE|LG|MOT|SAMSUNG|SonyEricsson|IEMobile|Mobile|lgtelecom|PPC|opera mobi|opera mini|nokia|webos/',$_SERVER['HTTP_USER_AGENT']) ){?>
					<?if($cg_kakaotalk==1){?>
					<a id="kakao-link-btn" href="javascript:;" title="<?=national_language($iw[language],"a0303","카카오톡");?>"><img src="<?=$iw[design_path]?>/img/btn_kakaotalk.png" alt=""></a>
					<script type="text/javascript">
						<?if($kakaoImg){?>
							executeKakaoTalkLinkImg("[<?=$fbTitle?>]\n<?=$kakaoContent?>","<?='http://'.$iw[default_domain].$_SERVER[REQUEST_URI]?>","<?=$kakaoImg?>","<?=$kakaoURL?>");
						<?}else{?>
							executeKakaoTalkLink("[<?=$fbTitle?>]\n<?=$kakaoContent?>","<?='http://'.$iw[default_domain].$_SERVER[REQUEST_URI]?>","<?=$kakaoURL?>");
						<?}?>
					</script>
					<?}?>
					<?if($cg_line==1){?>
					<span class="btn" style="padding:0; height:30px;">
						<script type="text/javascript" src="//media.line.me/js/line-button.js?v=20140411" ></script>
						<script type="text/javascript">
							new media_line_me.LineButton({"pc":false,"lang":"en","type":"c","text":"[<?=$fbTitle?>]<?=$kakaoContent?>","withUrl":true});
						</script>
					</span>
					<?}?>
				<?}?>
			</div> <!-- /.box -->
		</div> <!-- /.masonry-item -->
		<div class="clearfix"></div>
	

		<?if($cg_comment_view==1){?>
		<!-- /댓글 -->
		<div class="grid-sizer"></div>
		<!-- / 베스트댓글 리스트 -->
		<?
			$sql2 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$sd_code' and cm_display = 1 and cm_recomment = 0 and cm_recommend > 2 order by cm_recommend desc limit 0, 3";
			$result2 = sql_query($sql2);

			$i=0;
			while($row2 = @sql_fetch_array($result2)){
				$cm_no = $row2["cm_no"];
				$sql3 = "select mb_nick from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$row2[mb_code]'";
				$row3 = sql_fetch($sql3);
		?>
			<div id="best_comment_<?=$i?>" class="masonry-item w-full h-full">
				<div class="box br-theme">
					<h4 class="media-heading box-title">
						<i class="fa fa-trophy"></i>
						<?if($iw['anonymity']==0){
							echo $row3["mb_nick"];
						}else{
							echo cut_str($row3["mb_nick"],4,"")."*****";
						}?>
						<small><?=$row2["cm_datetime"]?></small>
						<a href="javascript:recommend_click('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','comment','<?=$cm_no?>');"><i class="fa fa-thumbs-up"></i> <span id="recommend_comment_<?=$cm_no?>"><?=$row2["cm_recommend"]?></span></a>
						<?if($row2["cm_secret"]==1){?>
							<i class="fa fa-lock"></i>
						<?}?>
					</h4>
					<p><?if($mb_code == $iw[member] || ($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row2["cm_secret"]!=1 || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row2["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
					<?if ((($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all")) && ($iw[mb_level] >= $row2[cg_level_comment])){?>
						<p><a href="javascript:comment_move('#best_comment_<?=$i?>','<?=$cm_no?>');">[<?=national_language($iw[language],"a0266","댓글");?>]</a></p>
					<?}?>
				</div>
			</div>
		<?
			$i++;
			}
		?>
		<!-- / 댓글 쓰기 -->
		<?if ((($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all")) && ($iw[mb_level] >= $row2[cg_level_comment])){?>
			<form id="cm_form" name="cm_form" action="<?=$iw['m_path']?>/all_data_comment.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<input type="hidden" name="cm_code" value="<?=$sd_code?>" />
					<input type="hidden" name="cm_recomment" value="0" />
					<textarea name="cm_content" rows="4" class="form-control"></textarea>
					<div class="btn-list">
						<a href="javascript:check_form();" class="btn btn-theme"><?=national_language($iw[language],"a0266","댓글");?></a>
						<label class="middle">
							<input type="checkbox" name="cm_secret" value="1">
							<span class="lbl"> 비밀글</span>
						</label>
					</div>
				</div>
			</div>
			</form>
		<?}else{?>
			<?
				if($iw[mb_level] < $row2[cg_level_comment]) $comment_login = "덧글쓰기 권한이 없습니다.";
				if($iw[gp_level] == "gp_guest" && $iw[group] != "all") $comment_login = "그룹 가입 후  댓글 작성이 가능합니다.";
				if($iw[level] == "guest" && $iw[group] == "all") $comment_login = "로그인 후 댓글 작성이 가능합니다.";
			?>
			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<textarea name="cm_content" rows="4" class="form-control" onclick="javascript:comment_login('<?=$comment_login?>');"></textarea>
					<div class="btn-list">
						<a href="javascript:comment_login();" class="btn btn-theme"><?=national_language($iw[language],"a0266","댓글");?></a>
					</div>
				</div>
			</div>
		<?}?>
		
		<form id="re_form" name="re_form" action="<?=$iw['m_path']?>/all_data_comment.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post" style="display:none;">
			<div class="box br-theme">
				<input type="hidden" name="cm_code" value="<?=$sd_code?>" />
				<input type="hidden" name="cm_recomment" value="" />
				<textarea name="cm_content" rows="2" class="form-control"></textarea>
				<div class="btn-list">
					<a href="javascript:re_check_form();" class="btn btn-theme"><?=national_language($iw[language],"a0266","댓글");?></a>
					<label class="middle">
						<input type="checkbox" name="cm_secret" value="1">
						<span class="lbl"> 비밀글</span>
					</label>
				</div>
			</div>
		</form>

		<!-- / 댓글 리스트 -->
		<?
			$sql2 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$sd_code' and cm_display = 1 and cm_recomment = 0 order by cm_no desc";
			$result2 = sql_query($sql2);

			$i=0;
			while($row2 = @sql_fetch_array($result2)){
				$cm_no = $row2["cm_no"];
				$sql3 = "select mb_nick from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$row2[mb_code]'";
				$row3 = sql_fetch($sql3);
		?>
			<div id="comment_<?=$i?>" class="masonry-item w-full h-full">
				<div class="box br-theme">
					<h4 class="media-heading box-title">
						<?if($iw['anonymity']==0){
							echo $row3["mb_nick"];
						}else{
							echo cut_str($row3["mb_nick"],4,"")."*****";
						}?>
						<small><?=$row2["cm_datetime"]?></small>
						<a href="javascript:recommend_click('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','comment','<?=$cm_no?>');"><i class="fa fa-thumbs-up"></i> <span id="recommend_comment_<?=$cm_no?>"><?=$row2["cm_recommend"]?></span></a>
						<?if($row2["mb_code"] == $iw[member]){?>
							<a href="javascript:comment_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$sd_code?>','<?=$cm_no?>');" class="text-danger"><i class="fa fa-times-circle"></i></a>
						<?}?>
						<?if($row2["cm_secret"]==1){?>
							<i class="fa fa-lock"></i>
						<?}?>
					</h4>
					<p><?if($mb_code == $iw[member] || ($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row2["cm_secret"]!=1 || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row2["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
					<?if ((($iw[gp_level] != "gp_guest" && $iw[group] != "all") || ($iw[level] != "guest" && $iw[group] == "all")) && ($iw[mb_level] >= $row2[cg_level_comment])){?>
						<p><a href="javascript:comment_move('#comment_<?=$i?>','<?=$cm_no?>');">[<?=national_language($iw[language],"a0266","댓글");?>]</a></p>
					<?}?>
					<?
						$sql4 = "select * from $iw[comment_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and state_sort = '$iw[type]' and cm_code = '$sd_code' and cm_display = 1 and cm_recomment = '$cm_no' order by cm_no asc";
						$result4 = sql_query($sql4);

						while($row4 = @sql_fetch_array($result4)){
							$cm_no = $row4["cm_no"];
							$sql3 = "select mb_nick from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$row4[mb_code]'";
							$row3 = sql_fetch($sql3);
					?>
						<div class="box br-theme">
							<h4 class="media-heading box-title">
								<?if($iw['anonymity']==0){
									echo $row3["mb_nick"];
								}else{
									echo cut_str($row3["mb_nick"],4,"")."*****";
								}?>
								<small><?=$row4["cm_datetime"]?></small>
								<?if($row4["mb_code"] == $iw[member]){?>
									<a href="javascript:comment_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$sd_code?>','<?=$cm_no?>');" class="text-danger"><i class="fa fa-times-circle"></i></a>
								<?}?>
								<?if($row4["cm_secret"]==1){?>
									<i class="fa fa-lock"></i>
								<?}?>
							</h4>
							<p><?if($mb_code == $iw[member] || ($iw[gp_level] == "gp_admin" && $iw[group] != "all") || ($iw[level] == "admin" && $iw[group] == "all") || $row4["cm_secret"]!=1 || $row4["mb_code"] == $iw[member] || $row2["mb_code"] == $iw[member]){?><?=stripslashes($row4["cm_content"])?><?}else{?>비밀글입니다.<?}?></p>
						</div>
					<?
						}
					?>
				</div>
			</div>
		<?
			$i++;
			}
		?>
		<?}?>
		<div class="clearfix"></div>
	</div> <!-- .row -->
</div> <!-- .content -->

<script type="text/javascript">
	function jsSetComa(str_result){
		var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
		str_result += '';  // 숫자를 문자열로 변환

		while (reg.test(str_result)){
			str_result = str_result.replace(reg, '$1' + ',' + '$2');
		}
		return str_result;
	}
	var now_option = 0;
	var now_amount = 0;
	var total_price = 0;
	function add_file(so_i)
	{
		if (so_i == "")
		{
			return;
		}
		document.getElementById("so_group").options[0].selected = true;
		
		if (total_max <= Number(now_amount)+Number(cart_amount))
		{
			alert("<?=national_language($iw[language],'a0221','1인당 최대 구매 가능 수량 :');?> "+total_max);
			return;
		}
		
		if (option_arr[so_i][4] == 1)
		{
			alert("<?=national_language($iw[language],'a0243','이미 선택한 항목입니다.');?>");
			return;
		}
		option_arr[so_i][4] = 1;
		option_arr[so_i][5] = 1;
		
		var option_price = option_arr[so_i][3];
		<?php if($iw[language]=="en"){?>
			option_price = option_price/1000;
		<?php }?>

		var up_div    = document.getElementById('productBrowser');
		var div       = document.createElement('tr');
		div.id        = now_option;

		div.innerHTML = "<td>"+option_arr[so_i][1]+"</td>";
		div.innerHTML += "<td class='text-right'><input type='text' class='form-control' size='1' style='min-width:50px;' name='so_amount["+now_option+"]' id='so_amount["+now_option+"]' value='1' readonly /></td>";
		div.innerHTML += "<td><a href=\"javascript:add_count('"+so_i+"','pl','so_amount["+now_option+"]');\"><i class='fa fa-plus-square fa-lg'></i></a>&nbsp;<a href=\"javascript:add_count('"+so_i+"','mi','so_amount["+now_option+"]');\"><i class='fa fa-minus-square fa-lg'></i></a></td>";
		div.innerHTML += "<td class='text-right'>"+jsSetComa(option_price)+"&nbsp;<a href=\"javascript:del_file('"+now_option+"','"+so_i+"');\"><i class='fa fa-times-circle fa-lg'></i></a><input type='hidden' name='so_no["+now_option+"]' value='"+option_arr[so_i][0]+"' /></td>";

		up_div.appendChild(div);

		now_option += 1;
		now_amount += 1;
		total_price += Number(option_arr[so_i][3]);
		var total_return = total_price;
		<?php if($iw[language]=="en"){?>
			total_return = total_return/1000;
		<?php }?>
		document.getElementById('priceTotal').innerHTML = <?php if($iw[language]=="en"){?>"US$ "+<?php }?>jsSetComa(total_return)<?php if($iw[language]=="ko"){?>+"원"<?php }?>;
	}

	function del_file(so_no,so_i)
	{
		var order = Number(option_arr[so_i][5]);
		now_amount -= order;
		total_price -= Number(option_arr[so_i][3])*order;
		var total_return = total_price;
		<?php if($iw[language]=="en"){?>
			total_return = total_return/1000;
		<?php }?>
		document.getElementById('priceTotal').innerHTML = <?php if($iw[language]=="en"){?>"US$ "+<?php }?>jsSetComa(total_return)<?php if($iw[language]=="ko"){?>+"원"<?php }?>;

		var up_div = document.getElementById('productBrowser');
		var div = document.getElementById(so_no);
		up_div.removeChild(div);

		option_arr[so_i][4] = 0;
		option_arr[so_i][5] = 0;
		now_option -= 1;
	}

	function add_count(so_i,plus,amount)
	{
		if (plus == "pl")
		{
			if (total_max <= Number(now_amount)+Number(cart_amount))
			{
				alert("<?=national_language($iw[language],'a0221','1인당 최대 구매 가능 수량 :');?> "+total_max);
				return;
			}
			now_amount += 1;
			option_arr[so_i][5] += 1;
			total_price += Number(option_arr[so_i][3]);
		}
		if (plus == "mi"){
			if (1 < Number(option_arr[so_i][5]))
			{
				now_amount -= 1;
				option_arr[so_i][5] -= 1;
				total_price -= Number(option_arr[so_i][3]);
			}
		}
		var total_return = total_price;
		<?php if($iw[language]=="en"){?>
			total_return = total_return/1000;
		<?php }?>
		document.getElementById('priceTotal').innerHTML = <?php if($iw[language]=="en"){?>"US$ "+<?php }?>jsSetComa(total_return)<?php if($iw[language]=="ko"){?>+"원"<?php }?>;
		document.getElementById(amount).value = option_arr[so_i][5];
	}

	function check_shop() {
		if (1 > Number(now_amount))
		{
			alert("<?=national_language($iw[language],'a0244','상품옵션을 선택하여 주십시오.');?>");
			return;
		}
		sd_form.submit();
	}
	
	function comment_login(txt){
		alert(txt);
		return;
	}
	function comment_delete(type,ep,gp,item,co) { 
		if (confirm("<?=national_language($iw[language],'a0176','댓글을 삭제하시겠습니까?');?>")) {
			location.href="all_data_comment_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&item="+item+"&co="+co;
		}
	}
	function check_form() {
		if (cm_form.cm_content.value == "") {
			alert("<?=national_language($iw[language],'a0177','내용을 입력하여 주십시오.')?>");
			cm_form.cm_content.focus();
			return;
		}
		cm_form.submit();
	}

	function re_check_form() {
		if (re_form.cm_content.value == "") {
			alert("<?=national_language($iw[language],'a0177','내용을 입력하여 주십시오.');?>");
			re_form.cm_content.focus();
			return;
		}
		re_form.submit();
	}
	function comment_move(comment,re) {
		re_form.style.display = "";
		re_form.cm_content.value = "";
		re_form.cm_recomment.value = re;
		$('#re_form').appendTo(comment);
		re_form.cm_content.focus();
	}

	function recommend_click(type,ep,gp,board,idx) { 

		if (window.XMLHttpRequest)
		{ // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{ // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if (board == "board"){
					document.getElementById("recommend_board_1").innerHTML = xmlhttp.responseText;
					document.getElementById("recommend_board_2").innerHTML = xmlhttp.responseText;
				}else{
					document.getElementById("recommend_comment_"+idx).innerHTML =xmlhttp.responseText;
				}
			}
		}
		xmlhttp.open("GET","all_data_recommend.php?type="+type+"&ep="+ep+"&gp="+gp+"&board="+board+"&idx="+idx,true);
		xmlhttp.send();
	}
</script>

<?
$sql = "update $iw[shop_data_table] set
		sd_hit = sd_hit+1
		where ep_code = '$iw[store]' and sd_code = '$sd_code'";
sql_query($sql);

$sql = "update $iw[total_data_table] set
		td_hit = td_hit+1
		where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' and td_code = '$sd_code'";
sql_query($sql);

include_once("_tail.php");
?>