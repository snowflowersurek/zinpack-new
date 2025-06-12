<?php
include_once("_common.php");
if ($iw[level]=="guest") alert(national_language($iw[language],"a0003","로그인 해주시기 바랍니다."),"$iw[m_path]/all_login.php?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&re_url=$iw[re_url]");
include_once("_head.php");
?>

<div class="content">
	<div class="row">
	    <div class="col-xs-12">
			<div class="breadcrumb-box input-group">
                <ol class="breadcrumb ">
					<li><a href="<?=$iw[m_path]?>/shop_cart_form.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>"><?=$st_shop_name?> <?=national_language($iw[language],"a0009","장바구니");?></a></li>
				</ol>
                <span class="input-group-btn">
					<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_cart_form.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0009","장바구니");?>"><i class="fa fa-shopping-cart fa-lg"></i></a>
					<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_buy_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0008","구매내역");?>"><i class="fa fa-truck fa-lg"></i></a>
                </span>
            </div>
			
			<?
				$sql = "select * from $iw[shop_cart_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]'";
				$result = sql_query($sql);
				while($row = @sql_fetch_array($result)){
					$sc_no = $row["sc_no"];
					$sd_code = $row["sd_code"];
					$so_no = $row["so_no"];
					$sc_amount = $row["sc_amount"];

					$rowdata = sql_fetch(" select count(*) as cnt from $iw[shop_option_table] where ep_code = '$iw[store]' and sd_code = '$sd_code' and so_no = '$so_no' and so_amount > 0");
					if (!$rowdata[cnt]) {
						echo "<script>alert('".national_language($iw[language],'a0222','품절되거나 삭제된 상품이 존재합니다. 해당상품은 장바구니에서 자동으로 삭제됩니다.')."');</script>";
						sql_query("delete from $iw[shop_cart_table] where mb_code = '$iw[member]' and sc_no = '$sc_no'");
					}else{
						$rowdata = sql_fetch(" select * from $iw[shop_option_table] where ep_code = '$iw[store]' and sd_code = '$sd_code' and so_no = '$so_no' and so_amount > 0");
						$so_amount = $rowdata["so_amount"];
						if ($so_amount < $sc_amount) {
							echo "<script>alert('".national_language($iw[language],'a0223','선택한 수량보다 재고수량이 적은 상품이 존재합니다. 해당상품은 장바구니에서 최대수량으로 대체됩니다.')."');</script>";
							sql_query("update $iw[shop_cart_table] set sc_amount = '$so_amount' where ep_code = '$iw[store]' and mb_code = '$iw[member]' and sc_no = '$sc_no' ");
						}
					}
				}

				$total_price = 0;
				$total_delivery = 0;
				$sd_code_check = "";
				$mb_code_check = "";
				$now_delivery = 0;
				$now_sy_price = 0;
				$a = 0;

				
				$sql = "select * from $iw[shop_cart_table] inner join $iw[shop_data_table] where $iw[shop_cart_table].sd_code = $iw[shop_data_table].sd_code and $iw[shop_cart_table].seller_mb_code = $iw[shop_data_table].mb_code and $iw[shop_cart_table].ep_code = '$iw[store]' and $iw[shop_cart_table].mb_code='$iw[member]' order by $iw[shop_cart_table].seller_mb_code asc, $iw[shop_data_table].sy_code asc, $iw[shop_cart_table].sd_code asc, $iw[shop_cart_table].so_no asc";

				$result = sql_query($sql);

				while($row = @sql_fetch_array($result)){
					$sc_no = $row["sc_no"];
					$sd_code = $row["sd_code"];
					$seller_mb_code = $row["seller_mb_code"];
					$so_no = $row["so_no"];
					$sc_amount = $row["sc_amount"];
					$sd_delivery_type = $row["sd_delivery_type"];

					$rowdata = sql_fetch(" select * from $iw[shop_data_table] where ep_code = '$iw[store]' and sd_code = '$sd_code'");
					$sd_image = $rowdata["sd_image"];
					$sd_subject = stripslashes($rowdata["sd_subject"]);
					$sy_code = $rowdata["sy_code"];

					$rowdata = sql_fetch(" select * from $iw[shop_delivery_table] where ep_code = '$iw[store]' and mb_code = '$seller_mb_code' and sy_code='$sy_code' ");
					$sy_price = $rowdata["sy_price"];
					$sy_max = $rowdata["sy_max"];
					$sy_display = $rowdata["sy_display"];

					$rowdata = sql_fetch(" select * from $iw[shop_option_table] where ep_code = '$iw[store]' and sd_code = '$sd_code' and so_no = '$so_no'");
					$so_amount = $rowdata["so_amount"];
					$so_name = stripslashes($rowdata["so_name"]);
					$so_price = $rowdata["so_price"];
					
					$rowpath = sql_fetch(" select ep_nick from $iw[enterprise_table] where ep_code = '$iw[store]'");
					$upload_path = "/$iw[type]/$rowpath[ep_nick]";

					if ($iw[group] == "all"){
						$upload_path .= "/all";
					}else{
						$rowpath = sql_fetch(" select gp_nick from $iw[group_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]'");
						$upload_path .= "/$rowpath[gp_nick]";
					}
					$upload_path = $iw["path"].$upload_path.'/'.$sd_code.'/'.$sd_image;

					if($sd_code != $sd_code_check){
						$sd_code_check = $sd_code;
						$rowcnt = sql_fetch(" select count(*) as cnt from $iw[shop_cart_table] where ep_code = '$iw[store]' and mb_code = '$iw[member]' and sd_code = '$sd_code' ");
						$rowspan = $rowcnt[cnt];
						$a = 0;
					}

					if($seller_mb_code != $mb_code_check || $now_sy_code != $sy_code){//판매자나 배송코드 바뀌면
						$mb_code_check = $seller_mb_code;
						$now_delivery = $sc_amount; //현재 갯수
						$now_sy_code = $sy_code; //현재 배송코드
						$now_sy_price = $sy_price; //배송비 입력

						if($sy_display == 1){ //무료배송일경우
							$rowcnt = sql_fetch(" select SUM(so_price*sc_amount) as cnt from $iw[shop_cart_table] inner join $iw[shop_data_table] inner join $iw[shop_option_table] where $iw[shop_cart_table].sd_code = $iw[shop_data_table].sd_code and $iw[shop_cart_table].sd_code = $iw[shop_option_table].sd_code and $iw[shop_cart_table].so_no = $iw[shop_option_table].so_no and $iw[shop_cart_table].ep_code = '$iw[store]' and $iw[shop_cart_table].mb_code='$iw[member]' and $iw[shop_data_table].sy_code='$sy_code'");

							if($sy_max > $rowcnt[cnt]){ //상품갯수보다 얼마이상 무료가 많을경우
								$total_delivery += $now_sy_price; //배송비를 배송비합계에 추가
							}else{
								$now_sy_price = 0; //배송비 입력
							}
						}						
					}else{
						$now_delivery += $sc_amount; //현재 갯수에 갯수 추가
					}
					if($sy_display != 1){
						if($sy_max < $now_delivery){ //최대 개수보다 현재갯수가 많은 경우
							$now_sy_price += $sy_price * (floor($now_delivery / $sy_max));
							$now_delivery %= $sy_max;
						}
						$total_delivery += $now_sy_price; //배송비를 배송비합계에 추가
					}
					$total_price += $so_price*$sc_amount;
			?>
			<?if($a == 0){?>
				<div class="masonry">
					<div class="grid-sizer"></div>

					<!-- 아이템 이미지 -->
					<div class="masonry-item w-2 h-2">
						<div class="box br-theme box-img">
							<div class="media">
								<a href="<?=$iw['m_path']?>/shop_data_view.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$sd_code?>">
									<img class="media-object img-responsive" src="<?=$upload_path?>" alt="">
								</a>
							</div>
						</div> <!-- /.box -->
					</div> <!-- /.masonry-item -->
					<!-- 아이템 정보 -->
					<div class="masonry-item w-10 w-sm-3 h-full">
						<div class="box br-theme">
							<h4 class="media-heading"><?=$sd_subject?></h4>
							<table class="table responsive-table">
								<tbody>
			<?}?>
									<tr>
										<td data-th="<?=national_language($iw[language],"a0286","상품명");?>"><?=$so_name?></td>
										<td data-th="<?=national_language($iw[language],"a0151","가격");?>"><?=national_money($iw[language], $so_price);?></td>
										<td data-th="<?=national_language($iw[language],"a0288","갯수");?>">
											<select class="form-control" onchange="javascript:changeAmount('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$sd_code?>','<?=$sc_no?>',this.value);">
												<?for($i=1 ; $i <= 100; $i++){?>
												<option value="<?=$i?>" <?if($sc_amount==$i){?>selected<?}?>><?=$i?> <?=national_language($iw[language],"a0215","개");?></option>
												<?}?>
											</select>
										</td>
										<td data-th="<?=national_language($iw[language],"a0294","합계");?>" class="text-right"><?=national_money($iw[language], $so_price*$sc_amount);?> <a href="<?=$iw['m_path']?>/shop_cart_delete.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$sc_no?>" class="text-danger"><i class="fa fa-times-circle"></i></a></td>
									</tr>
			<?if($rowspan == $a+1){?>
								</tbody>
							</table>
							<p>
								[<?=national_language($iw[language],"a0293","배송비");?>] 
								<?=national_money($iw[language], $now_sy_price);?> (<?=substr($seller_mb_code, 3, 3).substr($seller_mb_code,-3,3);?>-<?=$sy_code?>)
								<?$now_sy_price=0;?>
							</p>
						</div> <!-- /.box -->
					</div> <!-- /.masonry-item -->
				</div> <!-- /.masonry -->
			<?}?>
			<?
				$a++;
				}
			?>				
			
			<div class="masonry">
				<div class="grid-sizer"></div>
				<div class="masonry-item w-full h-full">
					<div class="box br-theme">
						<table class="table">
							<tbody>
								<tr>
									<td><?=national_language($iw[language],"a0224","총 상품금액");?></td>
									<td class="text-right"><?=national_money($iw[language], $total_price);?></td>
								</tr>
								<tr>
									<td><?=national_language($iw[language],"a0225","총 배송비");?></td>
									<td class="text-right"><?=national_money($iw[language], $total_delivery);?></td>
								</tr>
								<tr>
									<td><?=national_language($iw[language],"a0218","주문금액");?></td>
									<td class="text-right"><?=national_money($iw[language], $total_price+$total_delivery);?></td>
								</tr>
							</tbody>
						</table>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			</div> <!-- /.masonry -->
			<div class="col-sm-12">
				<div class="btn-list">
					<a href="<?=$iw['m_path']?>/main.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" class="btn btn-theme"><?=national_language($iw[language],"a0226","계속 쇼핑하기");?></a>
					<a href="<?=$iw['m_path']?>/shop_order_form.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" class="btn btn-theme"><?=national_language($iw[language],"a0227","바로 구매하기");?></a>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div> <!-- .row -->
</div> <!-- .content -->

<script type="text/javascript">
	function changeAmount(type,ep,gp,item,option,amount)
	{	
		location.href="shop_cart_amount.php?type="+type+"&ep="+ep+"&gp="+gp+"&item="+item+"&opt="+option+"&amount="+amount+"";
	}
</script>
<?
include_once("_tail.php");
?>