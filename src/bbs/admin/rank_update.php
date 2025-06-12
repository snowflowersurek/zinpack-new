<?
$row = sql_fetch("select * from $iw[rank_table] where rk_no = 1");
$rk_month = date("Ymd", strtotime($row["rk_month"]));
$rk_day = date("Ymd", strtotime($row["rk_day"]));
$now_date = date("Ymd");

$datetime = date("Y-m-d H:i:s");

if($now_date != $rk_day){
	$day_start = date("Y-m-d H:i:s", strtotime(date("Ymd").' - 1 days'));
	$day_end = date("Y-m-d H:i:s", strtotime(date("Ymd").' - 1 days + 23 hours + 59 minutes + 59 seconds'));

	$sql = "delete from $iw[rank_day_table]";
	sql_query($sql);
	
	$sql = "select * from $iw[mcb_support_table] where ms_datetime >= '$day_start' and ms_datetime <= '$day_end' order by ms_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$ms_price = $row["ms_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_day_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rd_no"]){
			$sql3 = "insert into $iw[rank_day_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rd_price = '$ms_price',
					rd_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_day_table] set
					rd_price = rd_price + $ms_price,
					rd_total = rd_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "select * from $iw[doc_buy_table] where db_datetime >= '$day_start' and db_datetime <= '$day_end' order by db_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$db_price = $row["db_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_day_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rd_no"]){
			$sql3 = "insert into $iw[rank_day_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rd_price = '$db_price',
					rd_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_day_table] set
					rd_price = rd_price + $db_price,
					rd_total = rd_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "select * from $iw[doc_support_table] where ds_datetime >= '$day_start' and ds_datetime <= '$day_end' order by ds_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$ds_price = $row["ds_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_day_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rd_no"]){
			$sql3 = "insert into $iw[rank_day_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rd_price = '$ds_price',
					rd_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_day_table] set
					rd_price = rd_price + $ds_price,
					rd_total = rd_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "select * from $iw[book_buy_table] where bb_datetime >= '$day_start' and bb_datetime <= '$day_end' order by bb_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$bb_price = $row["bb_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_day_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rd_no"]){
			$sql3 = "insert into $iw[rank_day_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rd_price = '$bb_price',
					rd_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_day_table] set
					rd_price = rd_price + $bb_price,
					rd_total = rd_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "select * from $iw[book_support_table] where bs_datetime >= '$day_start' and bs_datetime <= '$day_end' order by bs_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$bs_price = $row["bs_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_day_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rd_no"]){
			$sql3 = "insert into $iw[rank_day_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rd_price = '$bs_price',
					rd_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_day_table] set
					rd_price = rd_price + $bs_price,
					rd_total = rd_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "update $iw[rank_table] set
			rk_day = '$datetime'
			where rk_no = 1
			";
	sql_query($sql);
}

if($now_date != $rk_month){
	$day_start = date("Y-m-d H:i:s", strtotime(date("Ymd")-date("day").'+ 1 day'));
	$day_end = date("Y-m-d H:i:s", strtotime(date("Ymd").'+ 23 hours + 59 minutes + 59 seconds'));
	
	$sql = "update $iw[total_data_table] set td_hit = 0";
	sql_query($sql);

	$sql = "delete from $iw[rank_month_table]";
	sql_query($sql);

	$sql = "select * from $iw[mcb_support_table] where ms_datetime >= '$day_start' and ms_datetime <= '$day_end' order by ms_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$ms_price = $row["ms_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_month_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rm_no"]){
			$sql3 = "insert into $iw[rank_month_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rm_price = '$ms_price',
					rm_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_month_table] set
					rm_price = rm_price + $ms_price,
					rm_total = rm_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "select * from $iw[doc_buy_table] where db_datetime >= '$day_start' and db_datetime <= '$day_end' order by db_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$db_price = $row["db_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_month_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rm_no"]){
			$sql3 = "insert into $iw[rank_month_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rm_price = '$db_price',
					rm_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_month_table] set
					rm_price = rm_price + $db_price,
					rm_total = rm_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "select * from $iw[doc_support_table] where ds_datetime >= '$day_start' and ds_datetime <= '$day_end' order by ds_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$ds_price = $row["ds_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_month_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rm_no"]){
			$sql3 = "insert into $iw[rank_month_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rm_price = '$ds_price',
					rm_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_month_table] set
					rm_price = rm_price + $ds_price,
					rm_total = rm_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}
	
	$sql = "select * from $iw[book_buy_table] where bb_datetime >= '$day_start' and bb_datetime <= '$day_end' order by bb_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$bb_price = $row["bb_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_month_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rm_no"]){
			$sql3 = "insert into $iw[rank_month_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rm_price = '$bb_price',
					rm_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_month_table] set
					rm_price = rm_price + $bb_price,
					rm_total = rm_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "select * from $iw[book_support_table] where bs_datetime >= '$day_start' and bs_datetime <= '$day_end' order by bs_no desc";
	$result = sql_query($sql);

	while($row = @sql_fetch_array($result)){
		$bs_price = $row["bs_price"];
		$ep_code = $row["ep_code"];
		$seller_mb_code = $row["seller_mb_code"];

		$sql2 = "select * from $iw[rank_month_table] where ep_code = '$ep_code' and mb_code = '$seller_mb_code'";
		$row2 = sql_fetch($sql2);
		if(!$row2["rm_no"]){
			$sql3 = "insert into $iw[rank_month_table] set
					ep_code = '$ep_code',
					mb_code = '$seller_mb_code',
					rm_price = '$bs_price',
					rm_total = 1
					";
			sql_query($sql3);
		}else{
			$sql3 = "update $iw[rank_month_table] set
					rm_price = rm_price + $bs_price,
					rm_total = rm_total + 1
					where ep_code = '$ep_code' and mb_code = '$seller_mb_code'
					";
			sql_query($sql3);
		}
	}

	$sql = "update $iw[rank_table] set
			rk_month = '$datetime'
			where rk_no = 1
			";
	sql_query($sql);
}

?>