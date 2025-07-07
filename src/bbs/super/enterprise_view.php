<?php
include_once("_common.php");
include_once("_head.php");

if(!$_GET["idx"])exit;
$ep_no = $_GET["idx"];

$sql = "select * from $iw[enterprise_table] where ep_no = $ep_no";
$row = sql_fetch($sql);
if (!$row["ep_no"]) alert("잘못된 접근입니다!","");

$ep_code = $row["ep_code"];
$mb_code = $row["mb_code"];
$ep_nick = $row["ep_nick"];
$ep_corporate = $row["ep_corporate"];
$ep_permit_number = $row["ep_permit_number"];
$ep_state_mcb = $row["ep_state_mcb"];
$ep_state_publishing = $row["ep_state_publishing"];
$ep_state_doc = $row["ep_state_doc"];
$ep_state_shop = $row["ep_state_shop"];
$ep_state_book = $row["ep_state_book"];
$ep_datetime = $row["ep_datetime"];
$ep_exposed = $row["ep_exposed"];
$ep_autocode = $row["ep_autocode"];
$ep_jointype = $row["ep_jointype"];
$ep_language = $row["ep_language"];
$ep_anonymity = $row["ep_anonymity"];
$ep_domain = $row["ep_domain"];
$ep_upload = $row["ep_upload"];
$ep_upload_size = $row["ep_upload_size"];
$ep_point_seller = $row["ep_point_seller"];
$ep_point_site = $row["ep_point_site"];
$ep_point_super = $row["ep_point_super"];
$ep_copy_off = $row["ep_copy_off"];
$ep_expiry_date = ($row["ep_expiry_date"]=="0000-00-00")?"입력해주세요!":$row["ep_expiry_date"];
$ep_charge = ($row["ep_charge"]=="0" || $row["ep_charge"]=="")?"입력해주세요!":number_format($row["ep_charge"]);
$mb_mail = $row["admin_email"];	// 어드민 이메일을 별도로 만들었음.

$sql = " select * from $iw[member_table] where ep_code = '$ep_code' and mb_code = '$mb_code'";
$row = sql_fetch($sql);

$mb_name = $row["mb_name"];
//$mb_mail = $row["mb_mail"];
$mb_tel = $row["mb_tel"];
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">업체정보</li>
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
			업체정보
			<small>
				<i class="fa fa-angle-double-right"></i>
				상세
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal no-input-detail">
					<div class="form-group">
						<label class="col-sm-1 control-label">사이트코드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ep_code?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">서버사용</label>
						<div class="col-sm-11" id="dir_size">
							<button class="btn btn-success ladda-button" data-style="expand-left" onclick="getDirectorySize(this, '<?=$ep_nick?>');">조회</button>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">업체명</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ep_corporate?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사업자번호</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ep_permit_number?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">포워딩ID</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ep_nick?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">도메인</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ep_domain?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">언어설정</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>한글 / LG U+<?php }else if($ep_language=="en"){?>영문 / PAYPAL & ALIPAY<?php }?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">게시판</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>사용<?php }else{?>미사용<?php }?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">출판도서</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>사용<?php }else{?>미사용<?php }?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">쇼핑몰</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>사용<?php }else{?>미사용<?php }?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">컨텐츠몰</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>사용<?php }else{?>미사용<?php }?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이북몰</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>사용<?php }else{?>미사용<?php }?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">정산비율</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">위즈윈디지털(<?=$ep_point_super?>%), 사이트(<?=$ep_point_site?>%), 판매자(<?=$ep_point_seller?>%)</p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">복제 방지</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>ON<?php }else{?>OFF<?php }?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">파일업로드</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?php if{?>가능 (<?=$ep_upload_size?> MB)<?php }else{?>불가능<?php }?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">노출설정</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?php if($ep_exposed==1){?>회원에게만 노출<?php }else{?>비회원에게도 노출<?php }?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">가입방식</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?php if($ep_jointype==0){?>가입불가
								<?php }else if($ep_jointype==1){?>가입신청 > 관리자승인
								<?php }else if($ep_jointype==2){?>무조건 가입
								<?php }else if($ep_jointype==4){?>초대후 가입
								<?php }else if($ep_jointype==5){?>가입코드 입력후 자동승인 (<?=$ep_autocode;?>)
								<?php }?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">닉네임 공개</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">
								<?php if($ep_anonymity==0){?>공개
								<?php }else if($ep_anonymity==1){?>비공개
								<?php }?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">가입일</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ep_datetime?></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-1 control-label">담당자</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_name?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이메일주소</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_mail?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">전화번호</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$mb_tel?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">주소</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static">[<?=$row["mb_zip_code"]?>] <?=$row["mb_address"]?> <?=$row["mb_address_sub"]?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">만료일자</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static" style="color:red;"><?=$ep_expiry_date?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">사용료/년</label>
						<div class="col-sm-11">
							<p class="col-xs-12 col-sm-8 form-control-static"><?=$ep_charge?> 원</p>
						</div>
					</div>
					
				</form>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" onclick="location='<?=$iw["super_path"]?>/enterprise_edit.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=<?=$ep_no?>'">
							<i class="fa fa-check"></i>
							수정
						</button>
						<button class="btn btn-default" type="button" onclick="location='<?=$iw["super_path"]?>/enterprise_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
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
<script>
$(function() {
	Ladda.bind('button.ladda-button');
});

function getDirectorySize(element, ep_nick) {
	$(element).text("최대 1분 이상 소요될 수 있습니다.");
	var loading = Ladda.create(element);
	loading.start();

	setTimeout(function() {
		$.ajax({
			type: "GET", 
			url: "<?=$iw['super_path']?>/ajax/_directory_size.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&ep_nick=" + ep_nick, 
			dataType: "json",
			success: function(resData){
				loading.stop();
				$("#dir_size").html('<p class="col-xs-12 col-sm-8 form-control-static">'+ resData.dir_size +'</p>');
			},
			error: function(response){
				loading.stop();
				$(element).text("조회");
				alert('error\n\n' + response.responseText);
				return false;
			}
		});
	}, 500);
}
</script>
<?php
include_once("_tail.php");
?>



