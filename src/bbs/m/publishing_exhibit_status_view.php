<?php
include_once("_common.php");
include_once("_head_sub.php");

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 후 이용 가능합니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");

$idx = $_GET["item"];

$sql = "select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and idx = '$idx'";
$row = sql_fetch($sql);
if (!$row["idx"]) alert("잘못된 접근입니다!","");

$stat = $row["stat"];
$picture_name = $row["picture_name"];
$picture_idx = $row["picture_idx"];
$year = $row["year"];
$month = $row["month"];
$userName = $row["userName"];
$strgubun = $row["strgubun"];
$strgubunTxt = $row["strgubunTxt"];
$strOrgan = $row["strOrgan"];
$userTel = $row["userTel"];
$userPhone = $row["userPhone"];
$userEmail = $row["userEmail"];
$zipcode = $row["zipcode"];
$addr1 = $row["addr1"];
$addr2 = $row["addr2"];
$homepage = $row["homepage"];
$else_txt = stripslashes($row["else_txt"]);
$admin_txt = stripslashes($row["admin_txt"]);
$md_date = substr($row["md_date"], 0, 10);

if ($strgubun == "기타") {
	$strgubun = $strgubun."(".$strgubunTxt.")";
}

$addr = "(".$zipcode.") ".$addr1." ".$addr2;

$arrUserTel = explode("-", $userTel);
$arrUserPhone = explode("-", $userPhone);

$exhibitDate = $year."년 ".$month."월";

if ($year != "" && $month != "") {
	if ($month == 1) {
		$prev_year = $year - 1;
		$prev_month = 12;
		$next_year = $year;
		$next_month = $month + 1;
	} else if ($month == 12) {
		$prev_year = $year;
		$prev_month = $month - 1;
		$next_year = $year + 1;
		$next_month = 1;
	} else {
		$prev_year = $year;
		$prev_month = $month - 1;
		$next_year = $year;
		$next_month = $month + 1;
	}

	$prev_row = sql_fetch("select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' and year = '$prev_year' and month = '$prev_month'");
	$prev_idx = $prev_row["idx"];
	$prev_userName = $prev_row["userName"];
	$prev_strOrgan = $prev_row["strOrgan"];
	$prev_userTel = $prev_row["userTel"];
	$prev_userPhone = $prev_row["userPhone"];
	$prev_userEmail = $prev_row["userEmail"];
	$prev_zipcode = $prev_row["zipcode"];
	$prev_addr1 = $prev_row["addr1"];
	$prev_addr2 = $prev_row["addr2"];

	$next_row = sql_fetch("select * from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' and year = '$next_year' and month = '$next_month'");
	$next_idx = $next_row["idx"];
	$next_userName = $next_row["userName"];
	$next_strOrgan = $next_row["strOrgan"];
	$next_userTel = $next_row["userTel"];
	$next_userPhone = $next_row["userPhone"];
	$next_userEmail = $next_row["userEmail"];
	$next_zipcode = $next_row["zipcode"];
	$next_addr1 = $next_row["addr1"];
	$next_addr2 = $next_row["addr2"];
}

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

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li>그림전시신청 상세정보
				</li>
			</ol>
			<span class="input-group-btn">
			</span>
		</div>

		<!-- 우측에 이미지가 있는 상세 글 페이지 -->
		
		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
			<div class="grid-sizer"></div>

			<div class="masonry-item w-full h-full">
				<div class="box br-theme">
					<table class="table responsive-table">
						<colgroup>
						   <col style="width: 20%">
						   <col style="width: 80%">
						</colgroup>
						<tr>
							<th>신청현황</th>
							<th>
								<?if($stat == "1"){?>
									<span class="label label-sm label-default">대기 중</span>
								<?}else if($stat == "2"){?>
									<span class="label label-sm label-success">전시확정</span>
								<?}else if($stat == "3"){?>
									<span class="label label-sm label-warning">보류</span>
								<?}else if($stat == "4"){?>
									<span class="label label-sm label-danger">전시연기</span>
								<?}?>
							</th>
						</tr>
						<tr>
							<th>신청자</th>
							<th style="font-weight: normal;"><?=$userName?></th>
						</tr>
						<tr>
							<th>신청기관 분류</th>
							<th style="font-weight: normal;"><?=$strgubun?></th>
						</tr>
						<tr>
							<th>기관명</th>
							<th style="font-weight: normal;"><?=$strOrgan?></th>
						</tr>
						<tr>
							<th>일반전화</th>
							<th style="font-weight: normal;"><?=$userTel?></th>
						</tr>
						<tr>
							<th>휴대전화</th>
							<th style="font-weight: normal;"><?=$userPhone?></th>
						</tr>
						<tr>
							<th>이메일</th>
							<th style="font-weight: normal;"><?=$userEmail?></th>
						</tr>
						<tr>
							<th>주소</th>
							<th style="font-weight: normal;"><?=$addr?></th>
						</tr>
						<tr>
							<th>홈페이지</th>
							<th style="font-weight: normal;"><?=$homepage?></th>
						</tr>
						<tr>
							<th>신청 그림</th>
							<th style="font-weight: normal;"><?=$picture_name?></th>
						</tr>
						<tr>
							<th>전시일정</th>
							<th style="font-weight: normal;"><?=$exhibitDate?></th>
						</tr>
						<tr>
							<th>이전 전시기관</th>
							<th style="font-weight: normal;">
							<?php
							if ($prev_idx != "") {
								echo "기관명 : $prev_strOrgan<br/>";
								echo "신청자 : $prev_userName<br/>";
								echo "일반전화 : $prev_userTel<br/>";
								echo "휴대전화 : $prev_userPhone<br/>";
								echo "이메일 : $prev_userEmail<br/>";
								echo "주소 : ($prev_zipcode) $prev_addr1 $prev_addr2<br/>";
							} else {
								echo "없음";
							}
							?>
							</th>
						</tr>
						<tr>
							<th>다음 전시기관</th>
							<th style="font-weight: normal;">
							<?php
							if ($next_idx != "") {
								echo "기관명 : $next_strOrgan<br/>";
								echo "신청자 : $next_userName<br/>";
								echo "일반전화 : $next_userTel<br/>";
								echo "휴대전화 : $next_userPhone<br/>";
								echo "이메일 : $next_userEmail<br/>";
								echo "주소 : ($next_zipcode) $next_addr1 $next_addr2<br/>";
							} else {
								echo "없음";
							}
							?>
							</th>
						</tr>
						<tr>
							<th>남기고싶은말(기타 요청 사항)</th>
							<th style="font-weight: normal;"><?=$else_txt?></th>
						</tr>
					</table>
				</div>
			</div> <!-- /.masonry-item -->

			<div class="clearfix"></div>

		</div> <!-- /.masonry -->

		<div class="clearfix"></div>
	</div> <!-- .row -->
</div> <!-- .content -->

<?
include_once("_tail.php");
?>