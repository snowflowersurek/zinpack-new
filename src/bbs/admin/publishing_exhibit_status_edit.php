<?php
include_once("_common.php");
if ($iw[type] != "publishing" || $iw[level] != "admin" || $iw[group] != "all") alert("잘못된 접근입니다!","");

include_once("_head.php");

$idx = $_GET["id"];

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
$md_date = substr($row["md_date"], 0, 16);

$arrUserTel = explode("-", $userTel);
$arrUserPhone = explode("-", $userPhone);

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
?>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-book"></i>
			출판도서
		</li>
		<li class="active">그림전시 신청내역</li>
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
			그림전시 신청내역
			<small>
				<i class="fa fa-angle-double-right"></i>
				상세정보
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="form-horizontal" id="exhibit_form" name="exhibit_form" action="<?=$iw[admin_path]?>/publishing_exhibit_status_edit_ok.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" method="post">

					<input type="hidden" name="idx" value="<?=$idx?>" />

					<div class="form-group">
						<label class="col-sm-1 control-label">신청현황 상태</label>
						<div class="col-sm-8">
							<select name="stat">
								<option value="1" <?php if{?>selected="selected"<?php }?>>대기 중</option>
								<option value="2" <?php if{?>selected="selected"<?php }?>>전시확정</option>
								<option value="3" <?php if{?>selected="selected"<?php }?>>보류</option>
								<option value="4" <?php if{?>selected="selected"<?php }?>>전시연기</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청자</label>
						<div class="col-sm-8">
							<p class="col-sm-12 form-control-static"><?=$userName?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청일</label>
						<div class="col-sm-8">
							<p class="col-sm-12 form-control-static"><?=$md_date?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청기관</label>
						<div class="col-sm-8">
							<input type="radio" name="strgubun" id="strgubun1" value="일반 도서관" <?php if{?>checked<?php }?> onclick = "setGubunTxt('N');">
							<label for="strgubun1">일반 도서관</label>
							&nbsp;
							<input type="radio" name="strgubun" id="strgubun2" value="어린이 도서관" <?php if{?>checked<?php }?> onclick = "setGubunTxt('N');">
							<label for="strgubun2">어린이 도서관</label>
							&nbsp;
							<input type="radio" name="strgubun" id="strgubun3" value="초등학교" <?php if{?>checked<?php }?> onclick = "setGubunTxt('N');">
							<label for="strgubun3">초등학교</label>
							&nbsp;
							<input type="radio" name="strgubun" id="strgubun4" value="중/고등학교" <?php if{?>checked<?php }?> onclick = "setGubunTxt('N');">
							<label for="strgubun4">중/고등학교</label>
							&nbsp;
							<input type="radio" name="strgubun" id="strgubun5" value="기타" <?php if{?>checked<?php }?> onclick = "setGubunTxt('Y');">
							<label for="strgubun5">기타</label>
							<input type="text" name="strgubunTxt" id="strgubunTxt" value="<?=$strgubunTxt?>" maxlength="100" style="display:none;">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">기관명</label>
						<div class="col-sm-8">
							<input type="text" name="strOrgan" value="<?=$strOrgan?>" maxlength="100">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">연락처</label>
						<div class="col-sm-8">
							<input type="text" name="userTel1" value="<?=$arrUserTel[0]?>" maxlength="3" onblur="checkNumber(this)" style="width:50px;"> -
							<input type="text" name="userTel2" value="<?=$arrUserTel[1]?>" maxlength="4" onblur="checkNumber(this)" style="width:50px;"> -
							<input type="text" name="userTel3" value="<?=$arrUserTel[2]?>" maxlength="4" onblur="checkNumber(this)" style="width:50px;">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">휴대전화</label>
						<div class="col-sm-8">
							<input type="text" name="userPhone1" value="<?=$arrUserPhone[0]?>" maxlength="3" onblur="checkNumber(this)" style="width:50px;"> -
							<input type="text" name="userPhone2" value="<?=$arrUserPhone[1]?>" maxlength="4" onblur="checkNumber(this)" style="width:50px;"> -
							<input type="text" name="userPhone3" value="<?=$arrUserPhone[2]?>" maxlength="4" onblur="checkNumber(this)" style="width:50px;">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이메일</label>
						<div class="col-sm-8">
							<input type="text" name="userEmail" value="<?=$userEmail?>" maxlength="100">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">주소</label>
						<div class="col-sm-8">
							<input type="text" name="zipcode" id="zipcode" value="<?=$zipcode?>" maxlength="7" onclick="fnFindAd();" readonly>
							<button type="button" onclick="fnFindAd();">우편번호 검색</button>
							<br>
							<input type="text" class="col-sm-12" name="addr1" id="addr1" value="<?=$addr1?>" maxlength="50" onclick="fnFindAd();" readonly>
							<input type="text" class="col-sm-12" name="addr2" id="addr2" value="<?=$addr2?>" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">홈페이지</label>
						<div class="col-sm-8">
							http://<input type="text" name="homepage" value="<?=$homepage?>" maxlength="50">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">신청 그림</label>
						<div class="col-sm-8">
							<input type="hidden" name="picture_idx" id="picture_idx" value="<?=$picture_idx?>" />
							<input type="text" name="picture_name" id="picture_name" value="<?=$picture_name?>" maxlength="50" onclick="searchExhibit();" readonly>
							<button type="button" onclick="searchExhibit();">검색</button>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">전시일정</label>
						<div class="col-sm-8">
							<?php
							if ($stat == "2") {
								echo '<input type="hidden" name="exhibitDate" value="'.$year.'-'.$month.'" />';
								echo $year."년 ".sprintf("%02d", $month)."월";
							} else {
								echo '<select name="exhibitDate" id="exhibitDate">';
								
								$current_year = date("Y");
								$current_month = date("n");
								
								if ($year == "" || $month == "") {
									echo "<option value='' selected>선택</option>";
								} else {
									if ($year < $current_year || ($year == $current_year && $month < $current_month)) {
										$origin_date_value = $year."-".$month;
										$origin_date_text = $year."년 ".sprintf("%02d", $month)."월";
										echo "<option value='$origin_date_value' selected>$origin_date_text</option>";
									}
								}
								
								for ($i=$current_month; $i<=12; $i++) {
									$date_value = $current_year."-".$i;
									$date_text = $current_year."년 ".sprintf("%02d", $i)."월";
									
									$check_row = sql_fetch("select idx from $iw[publishing_exhibit_status_table] where ep_code = '$iw[store]' and picture_idx = '$picture_idx' and year = $current_year and month = $i");
									$check_idx = $check_row["idx"];
									
									if (!$check_idx) {
										echo "<option value='$date_value'>$date_text</option>";
									} else {
										if ($check_idx == $idx) {
											echo "<option value='$date_value' selected>$date_text</option>";
										}
									}
								}
								
								echo '</select> 항목에 표시되지 않는 월은 다른 기관에서 먼저 신청한 일정입니다.';
							}
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">이전 전시기관</label>
						<div class="col-sm-8">
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
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">다음 전시기관</label>
						<div class="col-sm-8">
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
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">남기고싶은말(기타 요청 사항)</label>
						<div class="col-sm-8">
							<textarea name="else_txt" class="col-sm-12" style="height:150px;"><?=$else_txt?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-1 control-label">관리자 메모</label>
						<div class="col-sm-8">
							<textarea name="admin_txt" class="col-sm-12" style="height:150px;"><?=$admin_txt?></textarea>
						</div>
					</div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-primary" type="button" onclick="javascript:check_form();">
								<i class="fa fa-check"></i>
								저장
							</button>
							<button class="btn btn-danger" type="button" onclick="javascript:delete_info('<?=$iw[type]?>', '<?=$iw[store]?>', '<?=$iw[group]?>', '<?=$idx?>');">
								<i class="fa fa-check"></i>
								삭제
							</button>
							<button class="btn btn-default" type="button" onclick="location='<?=$iw[admin_path]?>/publishing_exhibit_status_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>'">
								<i class="fa fa-undo"></i>
								취소
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
$(document).ready(function() {
	$("#picture_idx").change(function(){
		$.ajax({
			type: "GET", 
			url: "/bbs/m/publishing_exhibit_month_list.php?ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&idx=" + $(this).val(), 
			dataType: "json", 
			success: function(responseText){
				$("#exhibitDate option").remove();
				$("#exhibitDate").append($("<option>선택</option>"));
				
				for(var i = 0; i < responseText.length; i++){
					$("#exhibitDate").append($("<option>" + responseText[i].date_text + "</option>").attr({value: responseText[i].date_value}));
				}
			},
			error: function(response){
				$("#exhibitDate option").remove();
				alert('error\n\n' + response.responseText);
				return false;
			}
		});
	});
	
	$("#exhibitDate").on("click", function(){
		if ($("#picture_idx").val() == "") {
			alert("신청 그림을 먼저 선택하여 주세요.");
		}
	});
});

function searchExhibit() {
	window.open('publishing_exhibit_search.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>', 'SearchExhibit', 'width=800,height=600');
}

function selectExhibit(code, name){
	document.exhibit_form.picture_idx.value = code;
	document.exhibit_form.picture_name.value = name;
	$("#picture_idx").trigger('change');
}

function checkNumber(element){
	var inText = element.value;
	var ret;

	for (var i = 0; i < inText.length; i++) {
    	ret = inText.charCodeAt(i);
		if (!((ret > 47) && (ret < 58))){
			alert("숫자만 입력이 가능합니다.");
			element.value = "";
			element.focus();
			return false;
		}
	}
	return true;
}

function check_form() {
	if (exhibit_form.strOrgan.value == "") {
		alert("기관명을 입력하세요.");
		exhibit_form.strOrgan.focus();
		return;
	}
	
	if (exhibit_form.userTel1.value == "" || exhibit_form.userTel2.value == "" || exhibit_form.userTel3.value == "") {
		alert("연락처를 입력하세요.");
		exhibit_form.userTel1.focus();
		return;
	}
	
	if (exhibit_form.userEmail.value == "") {
		alert("이메일을 입력하세요.");
		exhibit_form.userEmail.focus();
		return;
	}
	
	if (exhibit_form.addr2.value == "") {
		alert("주소를 입력하세요.");
		exhibit_form.addr2.focus();
		return;
	}
	
	if (exhibit_form.picture_name.value == "") {
		alert("신청 그림전시를 선택하세요.");
		exhibit_form.picture_name.focus();
		return;
	}
	
	exhibit_form.submit();
}

function delete_info(type ,ep, gp, idx) {
	if (confirm('그림전시 신청정보를 삭제하시겠습니까?')) {
		location.href="publishing_exhibit_status_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&idx="+idx;
	}
}

function setGubunTxt(str) {
	if(str =="N"){
		$("#strgubunTxt").hide();
		$("#strgubunTxt").val("");
	}else{
		$("#strgubunTxt").show();
	}
}

<?php 
if ($strgubun == "기타") {
	echo "setGubunTxt('Y');";
}
?>
</script>

<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function fnFindAd() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
                var fullAddr = ''; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수
                // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    fullAddr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    fullAddr = data.jibunAddress;
                }
                // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
                if(data.userSelectedType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById("zipcode").value = data.zonecode;
                document.getElementById("addr1").value = fullAddr;

                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("addr2").focus();
            }
        }).open();
    }
</script>

<?php
include_once("_tail.php");
?>



