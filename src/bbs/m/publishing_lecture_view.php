<?php
include_once("_common.php");
include_once("_head_sub.php");

$sql = "select * from $iw[member_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
$row = sql_fetch($sql);
if (!$row["mb_no"]) alert(national_language($iw[language],"a0003","로그인 후 이용 가능합니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");

$menu = $_GET["menu"];
$intSeq = $_GET["item"];

$sql = "select * from $iw[publishing_lecture_table] where ep_code = '$iw[store]' and intSeq = '$intSeq'";
$row = sql_fetch($sql);
if (!$row["intSeq"]) alert("잘못된 접근입니다!","");

$userName = $row["userName"];
$strConfirm = $row["strConfirm"];
$strGubun = $row["strGubun"];
$strGubunTxt = $row["strGubunTxt"];
$strOrgan = $row["strOrgan"];
$strCharge = $row["strCharge"];
$userTel = $row["userTel"];
$userEmail = $row["userEmail"];
$userAddr = $row["userAddr"];
$strTarget = $row["strTarget"];
$strTargetTxt = $row["strTargetTxt"];
$strTargetInfo = $row["strTargetInfo"];
$strNum = $row["strNum"];

$confirm_Author = $row["confirm_Author"];
$strAuthor1 = $row["strAuthor1"];
$strAuthor2 = $row["strAuthor2"];
$strAuthor3 = $row["strAuthor3"];
$strAuthorBook1 = $row["strAuthorBook1"];
$strAuthorBook2 = $row["strAuthorBook2"];
$strAuthorBook3 = $row["strAuthorBook3"];

$confirm_date = $row["confirm_date"];
$strDate1 = $row["strDate1"];
$strDate2 = $row["strDate2"];
$strDate3 = $row["strDate3"];

$strPrice = $row["strPrice"];
$strPreView = $row["strPreView"];
$strPlan = stripslashes($row["strPlan"]);
$strContent = stripslashes($row["strContent"]);
$strAdminMemo = stripslashes($row["strAdminMemo"]);
$strRegDate = substr($row["strRegDate"], 0, 10);

if ($strDate1 != "") {
	$strDateTxt1 = substr($strDate1, 0, 4)."-".substr($strDate1, 4, 2)."-".substr($strDate1, 6, 2)." ".substr($strDate1, 8, 2).":".substr($strDate1, 10, 2)." ~ ".substr($strDate1, 12, 2).":".substr($strDate1, 14, 2);
}

if ($strDate2 != "") {
	$strDateTxt2 = substr($strDate2, 0, 4)."-".substr($strDate2, 4, 2)."-".substr($strDate2, 6, 2)." ".substr($strDate2, 8, 2).":".substr($strDate2, 10, 2)." ~ ".substr($strDate2, 12, 2).":".substr($strDate2, 14, 2);
}

if ($strDate3 != "") {
	$strDateTxt3 = substr($strDate3, 0, 4)."-".substr($strDate3, 4, 2)."-".substr($strDate3, 6, 2)." ".substr($strDate3, 8, 2).":".substr($strDate3, 10, 2)." ~ ".substr($strDate3, 12, 2).":".substr($strDate3, 14, 2);
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
				<li>
					<?php
					$row2 = sql_fetch(" select * from $iw[home_menu_table] where ep_code = '$iw[store]' and gp_code='$iw[group]' and hm_code = '$menu'");
					echo stripslashes($row2[hm_name])." 상세정보";
					?>
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
								<?php if($strConfirm == "N"){?>
									<span class="label label-sm label-default">접수대기</span>
								<?php }else if($strConfirm == "A"){?>
									<span class="label label-sm label-primary">접수완료</span>
								<?php }else if($strConfirm == "D"){?>
									<span class="label label-sm label-warning">도서관연락</span>
								<?php }else if($strConfirm == "J"){?>
									<span class="label label-sm label-info">작가섭외</span>
								<?php }else if($strConfirm == "Y"){?>
									<span class="label label-sm label-success">강연확정</span>
								<?php }else if($strConfirm == "C"){?>
									<span class="label label-sm label-danger">강연취소</span>
								<?php }?>
							</th>
						</tr>
						<tr>
							<th>신청자</th>
							<th style="font-weight: normal;"><?=$userName?></th>
						</tr>
						<tr>
							<th>신청기관 분류</th>
							<th style="font-weight: normal;"><?=$strGubun?></th>
						</tr>
						<tr>
							<th>기관명</th>
							<th style="font-weight: normal;"><?=$strOrgan?></th>
						</tr>
						<tr>
							<th>담당자명</th>
							<th style="font-weight: normal;"><?=$strCharge?></th>
						</tr>
						<tr>
							<th>연락처</th>
							<th style="font-weight: normal;"><?=$userTel?></th>
						</tr>
						<tr>
							<th>이메일</th>
							<th style="font-weight: normal;"><?=$userEmail?></th>
						</tr>
						<tr>
							<th>주소</th>
							<th style="font-weight: normal;"><?=$userAddr?></th>
						</tr>
						<tr>
							<th>강연회대상</th>
							<th style="font-weight: normal;"><?=$strTarget?></th>
						</tr>
						<tr>
							<th>대상 연령대</th>
							<th style="font-weight: normal;"><?=$strTargetInfo?></th>
						</tr>
						<tr>
							<th>참석 인원</th>
							<th style="font-weight: normal;"><?=$strNum?></th>
						</tr>
						<tr>
							<th>희망작가</th>
							<th style="font-weight: normal;">
								<?php if ($strAuthor1) {echo "1지망 : $strAuthor1 - $strAuthorBook1";} if ($strAuthor2) {echo "<br>2지망 : $strAuthor2 - $strAuthorBook2";} if ($strAuthor3) {echo "<br>3지망 : $strAuthor3 - $strAuthorBook3";}?>
							</th>
						</tr>
						<tr>
							<th>희망일정</th>
							<th style="font-weight: normal;">
								<?php if ($strDateTxt1) {echo "1지망 : $strDateTxt1";} if ($strDateTxt2) {echo "<br>2지망 : $strDateTxt2";} if ($strDateTxt3) {echo "<br>3지망 : $strDateTxt3";}?>
							</th>
						</tr>
						<tr>
							<th>강연료(예산)</th>
							<th style="font-weight: normal;"><?=$strPrice?> 원</th>
						</tr>
						<tr>
							<th>사전독서여부</th>
							<th style="font-weight: normal;"><?=$strPreView?></th>
						</tr>
						<tr>
							<th>독후 활동 계획</th>
							<th style="font-weight: normal;"><?=$strPlan?></th>
						</tr>
						<tr>
							<th>남기고싶은말(기타 요청 사항)</th>
							<th style="font-weight: normal;"><?=$strContent?></th>
						</tr>
					</table>
				</div>
			</div> <!-- /.masonry-item -->

			<div class="clearfix"></div>

		</div> <!-- /.masonry -->

		<div class="clearfix"></div>
	</div> <!-- .row -->
</div> <!-- .content -->

<?php
include_once("_tail.php");
?>



