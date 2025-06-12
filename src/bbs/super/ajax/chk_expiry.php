<?php
header('Content-Type: text/html; charset=utf-8');
$iw_path = "../../.."; // common.php 의 상대 경로
include_once("$iw_path/include/common.php");
include_once("../../../include/mailer.php");

//include_once("$iw[include_path]/member_super.php");
//if($iw['default_domain'] != $_SERVER['HTTP_HOST']) exit;

$chkdate = date("Y-m-d");
$title = "";
$chk_date = get_cookie("expiry_chk_date");
if($chk_date=="" || $chk_date != $chkdate){
	$sql = "SELECT * FROM $iw[enterprise_table] WHERE avail_type=1 ORDER BY ep_no ASC";
	$result = sql_query($sql);
	$chkcnt = 0;
	while($row = sql_fetch_array($result)){
		$exp_date = $row['ep_expiry_date'];
		//$exp_date = "2023-02-04";
		if($exp_date=="" || $exp_date=="0000-00-00") continue;

		$sdate = trim($row['expiry_email']);
		if($sdate==""){
			$last_diff = 0;
		}else{
			$row_date = explode("|",$sdate);
			$last_date = $row_date[(sizeof($row_date))-1];
			$last_diff = abs(strtotime($chkdate) - strtotime($last_date));
			echo "last_date: ".$last_date."<br>";
			echo "last_diff: ".$last_diff."<br>";
		}
		$epcode = $row['ep_code'];
		$mbcode = $row['mb_code'];
		$memsql = "SELECT * FROM $iw[member_table] WHERE ep_code = '".$epcode."' AND mb_code = '".$mbcode."'";
		$memrow = sql_fetch($memsql);
		//$to = $memrow['mb_mail'];
		//$to = "ohmaga@naver.com";
		if($row['admin_email']==""){
			$to = $memrow['mb_mail'];
		}else{
			$to = $row['admin_email'];
		}

		$diff = strtotime($exp_date) - strtotime($chkdate);
		$diff_days = $diff / (60*60*24);
		$sent_date = "|".date("Y-m-d");

		if($diff_days < 32 && $diff_days > 0){
			if($sdate==""){
				// 만료기간 이메일 발송 (첫 발송)
				$usql = "UPDATE $iw[enterprise_table] SET expiry_email = '".$sent_date."' WHERE ep_no = ".$row['ep_no'];
				sql_query($usql);

				$title = '<strong style="color:#df0100;">ZINPACK 이용 만료일자</strong>가 다가옵니다.';
				$subject = "안녕하세요. 귀사의 사이트 갱신을 위한 공지를 보내드립니다.";
				expiry_notice_email($to, $subject, $epcode, $title);
			}else{
				if($last_diff >= (3600 * 24 * 7)){
					// 일주일 후, 다시 이메일 발송 (두번째 발송부터)
					$usql = "UPDATE $iw[enterprise_table] SET expiry_email = CONCAT(expiry_email, '".$sent_date."') WHERE ep_no = ".$row['ep_no'];
					sql_query($usql);

					$title = '<strong style="color:#df0100;">ZINPACK 이용 만료일자</strong>가 얼마 남지 않았습니다.';
					$subject = "안녕하세요. 잊고 계신건 아닌가 해서 다시 귀사의 사이트 갱신 공지를 보내드립니다.";
					expiry_notice_email($to, $subject, $epcode, $title);
				}else{
					echo "later";
				}
			}
		}else if($diff_days < -7 && $diff_days > -15){	// 유효기간이 지난지 일주일 ~ 보름 사이라면
			// 최종 공지 이메일을 보낸다.
			if($last_diff > (3600 * 24 * 7)){
				$usql = "UPDATE $iw[enterprise_table] SET expiry_email = CONCAT(expiry_email, '".$sent_date."') WHERE ep_no = ".$row['ep_no'];
				sql_query($usql);

				$title = '<strong style="color:#df0100;">ZINPACK 이용 만료일자</strong>가 지났습니다.';
				$subject = "안녕하세요. 귀사의 사이트 갱신을 위한 최종 공지를 보내드립니다.";
				expiry_notice_email($to, $subject, $epcode, $title);
			}
		}else if($diff_days < -16){	// 유효기간이 지난지 보름이 지났다면..
			$usql = "UPDATE $iw[enterprise_table] SET avail_type=4, expiry_email = CONCAT(expiry_email, '".$sent_date."'), ep_code = CONCAT(ep_code, '.del') WHERE ep_no = ".$row['ep_no'];
			sql_query($usql);

			$epcode .= ".del";
			$title = '<strong style="color:#df0100;">ZINPACK 이용 연장 기한</strong>이 지났습니다.';
			$subject = "그동안 이용해 주셔서 감사드립니다. 귀사의 사이트가 자동으로 삭제됨을 알려드립니다. 궁금하신 점이 있으시면 연락바랍니다.";
			expiry_notice_email($to, $subject, $epcode, $title);
		}else{
			echo "between";
		}
		$chkcnt++;
	}
	set_cookie("expiry_chk_date", $chkdate, time()+3600*24);
	echo $chkcnt;
}else{
	echo "already";
	//set_cookie("expiry_chk_date", "", time()+3600*24);
}

//expiry_notice_email("ohmaga@wizwindigital.com", "관리비 공지", "ep1822322763609cab5915c89", "사이트 관리비 공지입니다.");
function expiry_notice_email($to, $subject, $ep_code, $title){
	$headers = 'From: admin@info-way.co.kr'       . "\r\n" .
						'Reply-To: admin@info-way.co.kr' . "\r\n" .
						'MIME-Version: 1.0' . "\r\n" .
						"Content-type:text/html;charset=UTF-8" . "\r\n"; 

	$msql = "SELECT * FROM iw_enterprise WHERE ep_code = '".$ep_code."'";
	$eprow = sql_fetch($msql);
	$epcorp = $eprow['ep_corporate'];
	$expiry_date = $eprow['ep_expiry_date'];
	$expiry_ext_date = date("Y-m-d", strtotime($expiry_date. ' + 15 days'));
	$delete_date = date("Y-m-d", strtotime($expiry_date. ' + 16 days'));
	$pay_url = "https://www.info-way.co.kr/bbs/m/all_login.php?type=main&ep=".$ep_code."&gp=all";

	$content = '<div class="mail_view_body">
		<div class="mail_view_contents">
			<div class="mail_view_contents_inner" data-translate-body-144896="">
				<div>
					<div style="margin:0 auto; padding:20px 0 0; background-color:#f4f5f8;">
						<table align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="font-family:Malgun Gothic,sans-serif;">
							<tbody>
								<tr>
									<td>
										<table border="0" width="750px" cellpadding="0" cellspacing="0" style="width:100%; padding:0; max-width:750px;">
											<tbody>
												<!-- header -->
												<tr>
													<td>
														<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
															<tbody>
																<tr>
																	<td style="height:65px; padding-top:5px; vertical-align:middle; text-align:center;">
																		<a href="http://www.info-way.co.kr/bbs/admin/main.php?type=dashboard&ep=ep1822322763609cab5915c89&gp=all" target="_blank" rel="noreferrer noopener">
																			<img alt="ZINPACK" src="http://www.info-way.co.kr/design/img/admin_login_zinpack.gif" style="padding-bottom:0;" loading="lazy">
																		</a>
																	</td>
																</tr>
															</tbody>
													</table>
												</td>
											</tr>
											<tr><!--contents-->
													<td style="padding:45px 30px;">
														<div align="center" style="width:620px;padding:15px;border:7px solid #dbdbdb;">
															<table cellpadding="0" cellspacing="0" border="0">
																<tbody>
																	<tr>
																		<td align="center">
																			<!-- title -->
																			' .$title. '
																			<!-- //title -->
																		</td>
																	</tr>
																</tbody>
															</table>
														</div><br><br>
														<div align="center">
															<table style="font-size:12px;color:#666;font-family:dotum;line-height:16px;letter-spacing:-1px;" width="99%" border="0" cellspacing="0" cellpadding="0">
																<tbody>
																	<tr>
																		<td colspan="3">
																			<strong style="font-size:12px;color:#666;font-family:dotum;line-height:16px;">홍길동님, 안녕하세요.
																			</strong>
																		</td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3"></td>
																	</tr>
																	<tr>
																		<td colspan="3">
																			<strong>회원님께서 사용 중인 <span style="color:#db2422;text-decoration:underline;">진팩 이용계약 만료일</span>이 다가옵니다.</strong>
																		</td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3"></td>
																	</tr>
																	<tr>
																		<td colspan="3">이용계약 기간은 귀하의 관리자 페이지에서 결제하시면 바로 자동으로 연장될 것입니다. 만약 연장 가능일까지 결제가 이루어 지지 않을 경우, 모든 데이터가 삭제되어 복구가 불가능함을 알려드립니다.
																		</td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3"></td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3"></td>
																	</tr>
																	<tr>
																		<td colspan="3">
																			<table width="100%" border="0" cellspacing="1" bgcolor="#dbdbdb" cellpadding="10" style="font-size:12px;color:#858585;font-family:dotum;line-height:16px;">
																				<tbody>
																					<tr>
																						<td height="1" colspan="3" bgcolor="#dbdbdb"></td>
																					</tr>
																					<tr>
																						<td align="center" valign="middle" bgcolor="#eeeeee">
																							<span style="border-right:1px solid #e6e6e6;font-weight:bold;">계정명</span>
																						</td>
																						<td align="center" width="126" bgcolor="#eeeeee">
																							<span style="font-weight:bold;border-right:1px solid #e6e6e6;">사용 만료일</span>
																						</td>
																						<td align="center" width="126" bgcolor="#eeeeee">
																							<span style="font-weight:bold;border-right:1px solid #e6e6e6;">연장 가능일</span>
																						</td>
																						<td align="center" width="126" bgcolor="#eeeeee">
																							<span style="font-weight:bold;">삭제일</span>
																						</td>
																					</tr>
																					<tr>
																						<td align="center" valign="middle" width="177" bgcolor="#ffffff">'.$epcorp.'</td>
																						<td align="center" width="219" bgcolor="#ffffff">'.$expiry_date.'</td>
																						<td align="center" width="219" bgcolor="#ffffff">'.$expiry_ext_date.'</td>
																						<td align="center" valign="middle" width="153" bgcolor="#ffffff">'.$delete_date.'</td>
																					</tr>
																					<tr>
																						<td height="1" colspan="3" bgcolor="#dbdbdb"></td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3"></td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3">
																			* 해당 계정은 <span style="color:#db2422;">만료일 이후</span> <strong>‘삭제 대기’</strong> 상태가 되며, <span style="color:#db2422;">연장가능일 이후로는 <strong>연장이 불가능</strong></span>합니다.
																		</td>
																	</tr>
																	<tr>
																		<td colspan="3"></td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3"></td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3">
																			연장하시려면 아래 <span style="color:#db2422;">‘기간연장하기’</span> <strong> 버튼</strong>을 클릭하여 귀사의 사이트로 이동하시여 <span style="color:#db2422;"> <strong>관리자 </strong>계정으로 로그인</span>하신 다음, 관리자 페이지로 이동하여 첫 화면인 데쉬보드에 <strong>[결제하기]</strong> 버튼을 클릭하여 진행하시면 됩니다..
																		</td>
																	</tr>
																	<tr>
																		<td align="left" width="270">
																			<a href="'.$pay_url.'" target="_blank" rel="noreferrer noopener" style="text-decoration:none;"><button style="padding:6px 14px;color: #fff;font-size: 0.8rem; border-radius:6px;cursor:pointer;border:none;background: linear-gradient(180deg, #4B91F7 0%, #367AF6 100%);box-shadow: 0px 0.5px 1.5px rgb(54 122 246 / 25%), inset 0px 0.8px 0px -0.25px rgb(255 255 255 / 20%);">기간 연장하기</button></a>
																		</td>
																	</tr>
																	<tr>
																		<td height="11" colspan="3"></td>
																	</tr>
																</tbody>
															</table>
														</div>
													</td>
													<!-- // contents -->
												</tr>
												<tr>
													<td style="padding:32px 30px; text-align:left; border-top:2px solid #eceef1; border-bottom:2px solid #eceef1;">
														<div>
															<img alt="ZINPACK" src="http://www.info-way.co.kr/design/img/zinpack.png" style="display:inline-block;" loading="lazy">
														</div>
														<p style="margin:0; padding:14px 0 0; font-size:13px; color:#454545; line-height:1.5; letter-spacing:-0.5px;">(주)위즈윈디지털</p>
														<p style="margin:0; padding:3px 0 0; font-size:13px; color:#454545; line-height:1.5; letter-spacing:-0.5px;">통신판매업신고번호 : 제2015-서울마포-1663호</p>
														<p style="margin:0; padding:3px 0 0; font-size:13px; color:#454545; line-height:1.5; letter-spacing:-0.5px;">사업자등록번호 : 119-81-93821</p>
														<p style="margin:0; padding:3px 0 22px; font-size:13px; color:#454545; line-height:1.5; letter-spacing:-0.5px;">주소 : 경기도 파주시 송학2길 5-1 (야당동)</p>
														<a href="tel:1644-7378" style="text-decoration:none;" rel="noreferrer noopener" target="_blank">
															<span style="font-size:20px; color:#101717; font-weight:bold; line-height:1.5; letter-spacing:-0.5px;">02-852-5567</span>
														</a>
														<p style="margin:0; padding:3px 0 0; font-size:13px; color:#454545; line-height:1.5; letter-spacing:-0.5px;">
															<a href="mailto:help@hosting.kr" style="color:#454545; text-decoration:none;" target="_blank" rel="noreferrer noopener">Mail : zinpack@wizwindigital.com</a>
														</p>
													</td>
												</tr>
												<tr>
													<td>
														<ul style="margin:0;padding:0;">
															<li style="list-style:none;">
															<p style="text-align:center; font-size:14px; color:#909090; font-family:Malgun Gothic,sans-serif;">Copyright ©WIZWIN DIGITAL. All rights Reserved.</p>
															</li>
														</ul>
													</td>
												</tr>
												<!-- // footer -->
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>';
	//mail($to, $subject, $content, $headers);
	$from = "no-reply@wizwindigital.com";
	$fromName = "zinpack digital inc.";
	mailer($fromName, $from, $to, $subject, $content, true);
}
?>