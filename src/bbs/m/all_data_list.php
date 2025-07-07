<?php
include_once("_common.php");
include_once("_head.php");

global $db_conn;
if (!$db_conn) {
    $db_conn = $connect_db;
}

$sql_ep = "select ep_nick from {$iw['enterprise_table']} where ep_code = ?";
$stmt_ep = mysqli_prepare($db_conn, $sql_ep);
mysqli_stmt_bind_param($stmt_ep, "s", $iw['store']);
mysqli_stmt_execute($stmt_ep);
$result_ep = mysqli_stmt_get_result($stmt_ep);
$row_ep = mysqli_fetch_assoc($result_ep);
mysqli_stmt_close($stmt_ep);
$upload_path = "/".$row_ep['ep_nick'];
$book_path = "/$row_ep[ep_nick]/book";
$author_path = "/$row_ep[ep_nick]/author";
$contest_path = "/$row_ep[ep_nick]/contest";
$exhibit_path = "/$row_ep[ep_nick]/exhibit";

if ($iw['group'] == "all"){
	$upload_path .= "/all";
}else{
	$sql_gp = "select gp_nick from {$iw['group_table']} where ep_code = ? and gp_code = ?";
    $stmt_gp = mysqli_prepare($db_conn, $sql_gp);
    mysqli_stmt_bind_param($stmt_gp, "ss", $iw['store'], $iw['group']);
    mysqli_stmt_execute($stmt_gp);
    $result_gp = mysqli_stmt_get_result($stmt_gp);
	$row_gp = mysqli_fetch_assoc($result_gp);
    mysqli_stmt_close($stmt_gp);
	$upload_path .= "/".$row_gp['gp_nick'];
}

$hm_code = $_GET["menu"];
$sql_menu = "select * from {$iw['home_menu_table']} where ep_code = ? and gp_code= ? and hm_code = ?";
$stmt_menu = mysqli_prepare($db_conn, $sql_menu);
mysqli_stmt_bind_param($stmt_menu, "sss", $iw['store'], $iw['group'], $hm_code);
mysqli_stmt_execute($stmt_menu);
$result_menu = mysqli_stmt_get_result($stmt_menu);
$row_menu = mysqli_fetch_assoc($result_menu);
mysqli_stmt_close($stmt_menu);

$cg_code_array = array();
if (!$row_menu['hm_no']){
	//alert(national_language($iw[language],"a0165","카테고리가 존재하지 않습니다."),"$iw[m_path]/main.php?type=main&ep=$iw[store]&gp=$iw[group]");
	alert(national_language($iw[language],"a0165","카테고리가 존재하지 않습니다."),""); exit;
}else{
	$hm_name = stripslashes($row_menu['hm_name']);
	$state_sort = $row_menu['state_sort'];
	$hm_deep = $row_menu['hm_deep'];
	$hm_list_scrap = $row_menu['hm_list_scrap];
	$hm_list_style = $row_menu['hm_list_style'];
	$hm_list_order = $row_menu['hm_list_order'];
	
	if ($row_menu['cg_code']) {
		$cg_code_array[] = $row_menu['cg_code'];
	}
	
	$hm_upper_code_array[] = $row_menu['hm_code'];
	
	for ($i = ($hm_deep + 1); $i <= 4; $i++) {
        if (empty($hm_upper_code_array)) break;
        
        $placeholders = implode(',', array_fill(0, count($hm_upper_code_array), '?'));
		$query = "
				SELECT 
					state_sort, hm_code, cg_code 
				FROM {$iw['home_menu_table']} 
				WHERE ep_code = ? AND gp_code = ? AND hm_deep = ? AND hm_upper_code IN ( {$placeholders} ) 
				ORDER BY hm_no asc
		";
        $stmt_sub = mysqli_prepare($db_conn, $query);
        $types = "ssi" . str_repeat('s', count($hm_upper_code_array));
        $params = array_merge([$iw['store'], $iw['group'], $i], $hm_upper_code_array);
        mysqli_stmt_bind_param($stmt_sub, $types, ...$params);
        mysqli_stmt_execute($stmt_sub);
		$hm_result = mysqli_stmt_get_result($stmt_sub);
		
		$hm_upper_code_array = array();
		
		while ($hm_row = mysqli_fetch_assoc($hm_result)){
			$hm_upper_code_array[] = $hm_row['hm_code'];
			if ($hm_row['cg_code']) {
				$cg_code_array[] = $hm_row['cg_code'];
			}
		}
        mysqli_stmt_close($stmt_sub);
	}
}
?>

<div class="content">
	<div class="row">
		<div class="breadcrumb-box input-group">
			<ol class="breadcrumb ">
				<li><a href="<?=$iw[m_path]?>/all_data_list.php?type=<?=$iw[type]?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$hm_code?>"><?=$hm_name?></a></li>
			</ol>
			<?php if($state_sort=="mcb"){?>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="javascript:rss_feed('<?=$iw[cookie_domain]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$hm_code?>');" title="RSS Feed"><i class="fa fa-rss fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw['m_path']?>/mcb_data_write.php?type=mcb&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$hm_code?>" title="<?=national_language($iw[language],"a0198","글쓰기");?>"><i class="fa fa-pencil fa-lg"></i></a>
			</span>
			<?php }else if($state_sort=="book"){?>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/book_buy_list.php?type=book&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-book fa-lg"></i></a>
			</span>
			<?php }else if($state_sort=="doc"){?>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/all_point_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0292","포인트");?>"><i class="fa fa-money fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/doc_buy_list.php?type=doc&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0005","구매자료");?>"><i class="fa fa-cloud-download fa-lg"></i></a>
			</span>
			<?php }else if($state_sort=="shop"){?>
			<span class="input-group-btn">
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_cart_form.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0009","장바구니");?>"><i class="fa fa-shopping-cart fa-lg"></i></a>
				<a class="btn btn-theme" href="<?=$iw[m_path]?>/shop_buy_list.php?type=shop&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>" title="<?=national_language($iw[language],"a0008","구매내역");?>"><i class="fa fa-truck fa-lg"></i></a>
			</span>
			<?php }?>
		</div>
		<div class="masonry js-masonry" data-masonry-options='{ "columnWidth": ".grid-sizer", "itemSelector": ".masonry-item"}'>
		<div class="grid-sizer"></div>
		<?php
			if($hm_list_scrap==1){
				$scrap_type = "list";
				include_once("all_home_scrap.php");
			}	
		?>
		</div> <!-- /.masonry -->
		
		<?php if($hm_list_style==1){ if($iw[type] == "mcb"){ //게시판?>
		<form class="row" style="padding:10px 20px 0px;" action="<?=$PHP_SELF?>" method="get">
			<input type="hidden" name="type" value="<?=$iw[type]?>" />
			<input type="hidden" name="ep" value="<?=$iw[store]?>" />
			<input type="hidden" name="gp" value="<?=$iw[group]?>" />
			<input type="hidden" name="menu" value="<?=$menu?>" />
			<input type="hidden" name="page" value="1" />
			<div class="col-xs-6 col-sm-4 col-md-3 col-xs-offset-6 col-sm-offset-8 col-md-offset-9">
				<div class="input-group">
					<input type="text" class="form-control input-sm" name="keyword" maxlength="20" value="<?=$keyword?>" placeholder="제목" />
					<span class="input-group-btn">
						<button type="submit" class="btn btn-sm btn-default">검색</button>
					</span>
				</div>
			</div>
		</form>
			<?php }?>
		
		<div class="box br-theme">
			<table class="table responsive-table">
				<colgroup>
				   <col style="width: 80px;">
				</colgroup>
				<thead>
				
				<?php if($state_sort == "publishing"){ //출판도서?>
					<tr>
						<th>번호</th>
						<th>도서명</th>
						<th>저자</th>
						<th>출판일</th>
						<th>조회</th>
					</tr>
				<?php }else if($state_sort == "author"){ //저자?>
					<tr>
						<th>번호</th>
						<th>작가명</th>
						<th></th>
						<th>날짜</th>
						<th>조회</th>
					</tr>
				<?php }else if($state_sort == "publishing_contest"){ //공모전?>
					<tr>
						<th>번호</th>
						<th>제목</th>
						<th>공모기간</th>
						<th>공모상태</th>
						<th>등록일</th>
					</tr>
				<?php }else if($state_sort == "exhibit"){ //그림전시?>
					<tr>
						<th>번호</th>
						<th>그림전시명</th>
						<th>액자수(점)</th>
						<th>액자크기</th>
						<th>도서정보</th>
					</tr>
				<?php }else{ //게시판, 이북몰, 컨텐츠몰, 쇼핑몰?>
					<tr>
						<th>번호</th>
						<th>제목</th>
						<th>작성자</th>
						<th>날짜</th>
						<th>조회</th>
					</tr>
				<?php }?>
				</thead>
				<tbody>
		<?php }else if($hm_list_style==4 || $hm_list_style==7){?>
		<div class="masonry">
			<div class="grid-sizer"></div>
		<?php }
				$search_sql = "";
                $keyword = "";
				if (!empty($_GET['keyword'])) {
                    $keyword = trim($_GET['keyword']);
					$search_sql = " AND td_title like ? ";
				}
				
                $cg_code_regex = implode("|", array_filter($cg_code_array));
                if (empty($cg_code_regex)) {
                    $cg_code_regex = "a^"; // 매칭되는 결과가 없도록
                }

                $sql_total = "SELECT count(*) as cnt FROM {$iw['total_data_table']} WHERE ep_code = ? AND gp_code = ? AND td_display = 1 AND cg_code REGEXP ? {$search_sql}";
                $stmt_total = mysqli_prepare($db_conn, $sql_total);
                if ($keyword) {
                    $keyword_param = "%{$keyword}%";
                    mysqli_stmt_bind_param($stmt_total, "ssss", $iw['store'], $iw['group'], $cg_code_regex, $keyword_param);
                } else {
                    mysqli_stmt_bind_param($stmt_total, "sss", $iw['store'], $iw['group'], $cg_code_regex);
                }
                mysqli_stmt_execute($stmt_total);
                $result_total = mysqli_stmt_get_result($stmt_total);
				$row_total = mysqli_fetch_assoc($result_total);
                $total_line = $row_total['cnt'];
                mysqli_stmt_close($stmt_total);

				if($hm_list_style==1){
					$max_line = 15;
				}else if($hm_list_style==5 || $hm_list_style==8){
					$max_line = ($hm_list_style-2)*6;
				}else if($hm_list_style==4 || $hm_list_style==7){
					$max_line = ($hm_list_style-1)*6;
				}else{
					$max_line = $hm_list_style*6;
				}
				$max_page = 5;
					
				$page = $_GET["page"];
				if(!$page) $page=1;
				$start_line = ($page-1)*$max_line;
				$total_page = ceil($total_line/$max_line);
				
				if($total_line < $max_line) {
					$end_line = $total_line;
				} else if($page==$total_page) {
					$end_line = $total_line - ($max_line*($total_page-1));
				} else {
					$end_line = $max_line;
				}
				
				if ($state_sort == "author") {
					$td_data_order = "td_title asc, td_code desc";
				} else {
					if($hm_list_order == 2){
						$td_data_order = "td_edit_datetime desc, td_title asc, td_code desc";
					}else{
						$td_data_order = "td_datetime desc, td_title asc, td_code desc";
					}
				}
				
				$sql = "SELECT td_code, cg_code, state_sort FROM {$iw['total_data_table']} 
						WHERE ep_code = ? AND gp_code = ? AND td_display = 1 AND cg_code REGEXP ? {$search_sql}
						ORDER BY {$td_data_order} 
						LIMIT ?, ?";
                $stmt_list = mysqli_prepare($db_conn, $sql);
                if ($keyword) {
                    mysqli_stmt_bind_param($stmt_list, "ssssii", $iw['store'], $iw['group'], $cg_code_regex, $keyword_param, $start_line, $max_line);
                } else {
                    mysqli_stmt_bind_param($stmt_list, "sssii", $iw['store'], $iw['group'], $cg_code_regex, $start_line, $max_line);
                }
                mysqli_stmt_execute($stmt_list);
				$result = mysqli_stmt_get_result($stmt_list);
				
				$i=0;
				while($row = mysqli_fetch_assoc($result)){
					$td_code = $row["td_code"];
					$cg_code_arr = explode("|", $row["cg_code"]);
					$cg_code = $cg_code_arr[0];
					$check_state_sort = $row["state_sort"];
					
					$list_true = "no";
					$row2 = null;

					if($check_state_sort == "mcb"){ //게시판
						$sql_detail = "select * from {$iw['mcb_data_table']} where ep_code = ? and gp_code= ? and cg_code = ? and md_code = ? and md_display=1";
                        $stmt_detail = mysqli_prepare($db_conn, $sql_detail);
                        mysqli_stmt_bind_param($stmt_detail, "ssss", $iw['store'], $iw['group'], $cg_code, $td_code);
                        mysqli_stmt_execute($stmt_detail);
                        $result_detail = mysqli_stmt_get_result($stmt_detail);
						$row2 = mysqli_fetch_assoc($result_detail);
                        mysqli_stmt_close($stmt_detail);

						if (!$row2['md_no']){
							$list_true = "no";
						}else{
							$list_true = "yes";
							$mb_code = $row2["mb_code"];
							$md_code = $row2["md_code"];
							$md_type = $row2["md_type"];
							$md_subject = stripslashes($row2["md_subject"]);
							$md_content = $row2["md_content"];
							$md_youtube = $row2["md_youtube"];
							$md_file_1 = $row2["md_file_1"];
							$md_hit = number_format($row2["md_hit"]);
							$md_recommend = number_format($row2["md_recommend"]);
							$md_attach = explode(".",$row2["md_attach_name"]);
							$md_attach = strtoupper($md_attach[count($md_attach)-1]);
							$md_secret = $row2["md_secret"];
							$md_datetime = date("y.m.d", strtotime($row2["md_datetime"]));
							$md_no = $row2["md_no"];
								
							if($md_type == 1){
								$pattern = "/(mms|http|HTTP|https|HTTPS|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"\']+)/"; 
								$youtube_code="";
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
								$pattern = "!<(.*?)\>!is";
								preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($md_content),$md_images);
							}

							if($md_secret == 1){
								$md_content = "<i class='fa fa-lock'></i> 비밀글입니다.";
							}else{
								$md_content = preg_replace($pattern, "", $md_content);
							}

							if(($md_type == 1 && !$row2["md_file_1"] && !$row2["md_youtube"]) || ($md_type == 2 && !$md_images[1][0])){
								$no_image = "no-img";
								$content_size = 250;
							}else{
								$no_image = "";
								$content_size = 150;
							}
						}
					}else if($check_state_sort == "publishing"){ //출판도서
						$sql2 = "select * from {$iw['publishing_books_table']} where ep_code = ? and gp_code= ? and cg_code = ? and BookID = ? and book_display=1";
                        $stmt2 = mysqli_prepare($db_conn, $sql2);
                        mysqli_stmt_bind_param($stmt2, "ssss", $iw['store'], $iw['group'], $cg_code, $td_code);
                        mysqli_stmt_execute($stmt2);
                        $result2 = mysqli_stmt_get_result($stmt2);
						$row2 = mysqli_fetch_assoc($result2);
                        mysqli_stmt_close($stmt2);
                        
						if (!$row2['BookID']){
							$list_true = "no";
						}else{
							$list_true = "yes";
							$mb_code = $row2["mb_code"];
							$BookID = $row2["BookID"];
							$cg_code = $row2["cg_code"];
							$BookImage = stripslashes($row2["BookImage"]);
							$BookName = stripslashes($row2["BookName"]);
							$PubDate = $row2["PubDate"];
							$Hit = $row2["Hit"];
							$book_recommend = $row2["book_recommend"];
							$book_display = $row2["book_display"];

							$author_sql = "select at.Author from {$iw['publishing_books_author_table']} bat JOIN {$iw['publishing_author_table']} at ON bat.authorID = at.AuthorID where bat.ep_code = ? and bat.BookID = ? and (at.authorType = '1' or at.authorType = '3') order by at.authorType asc";
                            $author_stmt = mysqli_prepare($db_conn, $author_sql);
                            mysqli_stmt_bind_param($author_stmt, "ss", $iw['store'], $BookID);
                            mysqli_stmt_execute($author_stmt);
                            $author_result = mysqli_stmt_get_result($author_stmt);
							
                            $authorName = "";
							while($author_row = mysqli_fetch_assoc($author_result)){
								if ($authorName != "") {
									$authorName .= " / ";
								}
								$authorName .= $author_row["Author"];
							}
                            mysqli_stmt_close($author_stmt);
						}
					}else if($check_state_sort == "author"){ //저자
						$sql2 = "select * from {$iw['publishing_author_table']} where ep_code = ? and gp_code = ? and AuthorID = ? and author_display=1";
                        $stmt2 = mysqli_prepare($db_conn, $sql2);
                        mysqli_stmt_bind_param($stmt2, "sss", $iw['store'], $iw['group'], $td_code);
                        mysqli_stmt_execute($stmt2);
                        $result2 = mysqli_stmt_get_result($stmt2);
						$row2 = mysqli_fetch_assoc($result2);
                        mysqli_stmt_close($stmt2);

						if (!$row2['AuthorID']){
							$list_true = "no";
						}else{
							$list_true = "yes";
							$mb_code = $row2["mb_code"];
							$AuthorID = $row2["AuthorID"];
							$Author = stripslashes($row2["Author"]);
							$Photo = $row2["Photo"];
							$RegDate = explode(" ",$row2["RegDate"]);
							$Hit = $row2["Hit"];
							$author_recommend = $row2["author_recommend"];
							$author_display = $row2["author_display"];
						}
					}else if($check_state_sort == "publishing_contest"){ //공모전
						$sql2 = "select * from iw_publishing_contest where ep_code = ? and gp_code = ? and contest_code = ?";
                        $stmt2 = mysqli_prepare($db_conn, $sql2);
                        mysqli_stmt_bind_param($stmt2, "sss", $iw['store'], $iw['group'], $td_code);
                        mysqli_stmt_execute($stmt2);
                        $result2 = mysqli_stmt_get_result($stmt2);
						$row2 = mysqli_fetch_assoc($result2);
                        mysqli_stmt_close($stmt2);

						if (!$row2['contest_code']){
							$list_true = "no";
						}else{
							$list_true = "yes";
							$mb_code = $row2["mb_code"];
							$contest_code = $row2["contest_code"];
							$subject = stripslashes($row2["subject"]);
							$contest_content = $row2["content"];
							$start_date = substr($row2["start_date"], 0, 10);
							$end_date = substr($row2["end_date"], 0, 10);
							$reg_date = substr($row2["reg_date"], 0, 10);
							$pattern = "!<(.*?)\>!is";
							preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i",stripslashes($contest_content),$contest_images);
						}
					}else if($check_state_sort == "exhibit"){ //그림전시
						$sql2 = "select * from {$iw['publishing_exhibit_table']} where ep_code = ? and gp_code = ? and picture_idx = ?";
                        $stmt2 = mysqli_prepare($db_conn, $sql2);
                        mysqli_stmt_bind_param($stmt2, "sss", $iw['store'], $iw['group'], $td_code);
                        mysqli_stmt_execute($stmt2);
                        $result2 = mysqli_stmt_get_result($stmt2);
						$row2 = mysqli_fetch_assoc($result2);
                        mysqli_stmt_close($stmt2);

						if (!$row2['picture_idx']){
							$list_true = "no";
						}else{
							$list_true = "yes";
							$mb_code = $row2["mb_code"];
							$picture_idx = $row2["picture_idx"];
							$picture_name = stripslashes($row2["picture_name"]);
							$how_many = $row2["how_many"];
							$picture_size = $row2["size"];
							$book_id = $row2["book_id"];
							
							$picture_photo = "";
							
							if ($book_id != "") {
								$sql_book = "select BookImage from {$iw['publishing_books_table']} where ep_code = ? and BookID = ?";
                                $stmt_book = mysqli_prepare($db_conn, $sql_book);
                                mysqli_stmt_bind_param($stmt_book, "ss", $iw['store'], $book_id);
                                mysqli_stmt_execute($stmt_book);
                                $result_book = mysqli_stmt_get_result($stmt_book);
								$book_row = mysqli_fetch_assoc($result_book);
                                mysqli_stmt_close($stmt_book);
								$picture_photo = $book_row["BookImage"];
								
								if ($picture_photo != "") {
									$picture_photo = "{$iw['path']}/publishing{$book_path}/{$picture_photo}";
								}
							}
						}
					}else if($check_state_sort == "book"){ //이북몰
						$sql2 = "select * from {$iw['book_data_table']} where ep_code = ? and gp_code = ? and cg_code = ? and bd_code = ? and bd_display=1";
                        $stmt2 = mysqli_prepare($db_conn, $sql2);
                        mysqli_stmt_bind_param($stmt2, "ssss", $iw['store'], $iw['group'], $cg_code, $td_code);
                        mysqli_stmt_execute($stmt2);
                        $result2 = mysqli_stmt_get_result($stmt2);
						$row2 = mysqli_fetch_assoc($result2);
                        mysqli_stmt_close($stmt2);

						if (!$row2['bd_no']){
							$list_true = "no";
						}else{
							$list_true = "yes";
							$mb_code = $row2["mb_code"];
							$bd_code = $row2["bd_code"];
							$bd_subject = cut_str(stripslashes($row2["bd_subject"]),50);
							$bd_image = $row2["bd_image"];
							$bd_price = number_format($row2["bd_price"]);
							$bd_hit = number_format($row2["bd_hit"]);
							$bd_recommend = number_format($row2["bd_recommend"]);
							$bd_type = $row2["bd_type"];
							$bd_author = $row2["bd_author"];
							$bd_publisher = $row2["bd_publisher"];
							$bd_datetime = date("y.m.d", strtotime($row2["bd_datetime"]));
							$bd_no = $row2["bd_no"];

							if($bd_type == 1){
								$bd_style = "PDF";
							}else if($bd_type == 2){
								$bd_style = "미디어";
							}else if($bd_type == 3){
								$bd_style = "블로그";
							}else if($bd_type == 4){
								$bd_style = "논문";
							}
						}
					}else if($check_state_sort == "doc"){ //컨텐츠몰
						$sql2 = "select * from {$iw['doc_data_table']} where ep_code = ? and gp_code = ? and cg_code = ? and dd_code = ? and dd_display=1";
                        $stmt2 = mysqli_prepare($db_conn, $sql2);
                        mysqli_stmt_bind_param($stmt2, "ssss", $iw['store'], $iw['group'], $cg_code, $td_code);
                        mysqli_stmt_execute($stmt2);
                        $result2 = mysqli_stmt_get_result($stmt2);
						$row2 = mysqli_fetch_assoc($result2);
                        mysqli_stmt_close($stmt2);

						if (!$row2['dd_no']){
							$list_true = "no";
						}else{
							$list_true = "yes";
							$mb_code = $row2["mb_code"];
							$dd_code = $row2["dd_code"];
							$dd_subject = stripslashes($row2["dd_subject"]);
							$dd_file = explode(".",$row2["dd_file_name"]);
							$dd_file = strtoupper($dd_file[count($dd_file)-1]);
							$dd_file_size = number_format($row2["dd_file_size"]/1024/1000, 1);
							$dd_image = $row2["dd_image"];
							$dd_price = number_format($row2["dd_price"]);
							$dd_hit = number_format($row2["dd_hit"]);
							$dd_recommend = number_format($row2["dd_recommend"]);
							$dd_type = $row2["dd_type"];
							$dd_amount = $row2["dd_amount"];
							$dd_datetime = date("y.m.d", strtotime($row2["dd_datetime"]));
							$dd_no = $row2["dd_no"];
						}
					}else if($check_state_sort == "shop"){ //쇼핑몰
						$sql2 = "select * from {$iw['shop_data_table']} where ep_code = ? and gp_code = ? and cg_code = ? and sd_code = ? and sd_display=1";
                        $stmt2 = mysqli_prepare($db_conn, $sql2);
                        mysqli_stmt_bind_param($stmt2, "ssss", $iw['store'], $iw['group'], $cg_code, $td_code);
                        mysqli_stmt_execute($stmt2);
                        $result2 = mysqli_stmt_get_result($stmt2);
						$row2 = mysqli_fetch_assoc($result2);
                        mysqli_stmt_close($stmt2);
                        
						if (!$row2['sd_no']){
							$list_true = "no";
						}else{
							$list_true = "yes";
							$mb_code = $row2["mb_code"];
							$sd_code = $row2["sd_code"];
							$sd_image = $row2["sd_image"];
							$sd_subject = stripslashes($row2["sd_subject"]);
							$sd_sale = $row2["sd_sale"];
							$sd_price = $row2["sd_price"];
							if ($sd_price != $sd_sale && $sd_price > 0) {
								$sd_percent = floor(100-($sd_sale/$sd_price*100));
							}
							$sd_sell = number_format($row2["sd_sell"]);
							$sd_hit = number_format($row2["sd_hit"]);
							$sd_recommend = number_format($row2["sd_recommend"]);
							$sd_datetime = date("y.m.d", strtotime($row2["sd_datetime"]));
							$sd_no = $row2["sd_no"];
						}
					}
					
					if($list_true == "yes"){
						$sql_comment = "select count(*) as cnt from {$iw['comment_table']} where ep_code = ? and gp_code = ? and state_sort = ? and cm_code = ? and cm_display = 1";
                        $stmt_comment = mysqli_prepare($db_conn, $sql_comment);
                        mysqli_stmt_bind_param($stmt_comment, "ssss", $iw['store'], $iw['group'], $check_state_sort, $td_code);
                        mysqli_stmt_execute($stmt_comment);
                        $result_comment = mysqli_stmt_get_result($stmt_comment);
						$row_comment = mysqli_fetch_assoc($result_comment);
                        mysqli_stmt_close($stmt_comment);
						$reply_count = number_format($row_comment['cnt']);

						$sql_cat = "select * from {$iw['category_table']} where ep_code = ? and gp_code = ? and state_sort = ? and cg_code = ?";
						$stmt_cat = mysqli_prepare($db_conn, $sql_cat);
                        mysqli_stmt_bind_param($stmt_cat, "ssss", $iw['store'], $iw['group'], $check_state_sort, $cg_code);
                        mysqli_stmt_execute($stmt_cat);
                        $result_cat = mysqli_stmt_get_result($stmt_cat);
						$row2_cat = mysqli_fetch_assoc($result_cat);
                        mysqli_stmt_close($stmt_cat);
						$cg_hit = $row2_cat['cg_hit'];
						$cg_comment = $row2_cat['cg_comment'];
						$cg_recommend = $row2_cat['cg_recommend'];

						$sql_nick = "select mb_nick from {$iw['member_table']} where ep_code = ? and mb_code = ?";
						$stmt_nick = mysqli_prepare($db_conn, $sql_nick);
                        mysqli_stmt_bind_param($stmt_nick, "ss", $iw['store'], $mb_code);
                        mysqli_stmt_execute($stmt_nick);
                        $result_nick = mysqli_stmt_get_result($stmt_nick);
						$row2_nick = mysqli_fetch_assoc($result_nick);
                        mysqli_stmt_close($stmt_nick);
						if($iw['anonymity']==0){
							$mb_nick = $row2_nick["mb_nick"];
						}else{
							$mb_nick = cut_str($row2_nick["mb_nick"],4,"")."*****";
						}
			 if($hm_list_style==1){?>
				<tr>
					<?php if($check_state_sort == "mcb"){ //게시판?>
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="제목">
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<?=$md_subject if{?> [<?=$reply_count?>]<?php } if{?><i class="fa fa-file"></i><?php }?>
							</a>
						</td>
						<td data-th="작성자"><?=$mb_nick?></td>
						<td data-th="날짜"><?=$md_datetime?></td>
						<td data-th="조회"><?=$md_hit?></td>
					<?php }else if($check_state_sort == "publishing"){ //출판도서?>
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="도서명">
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<?=$BookName if{?> [<?=$reply_count?>]<?php }?>
							</a>
						</td>
						<td data-th="저자"><?=$authorName?></td>
						<td data-th="출판일"><?=$PubDate?></td>
						<td data-th="조회"><?=$Hit?></td>
					<?php }else if($check_state_sort == "author"){ //저자?>
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="작가명">
							<a href="<?=$iw['m_path']?>/publishing_<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<?=$Author if{?> [<?=$reply_count?>]<?php }?>
							</a>
						</td>
						<td>&nbsp;</td>
						<td data-th="날짜"><?=$RegDate[0]?></td>
						<td data-th="조회"><?=$Hit?></td>
					<?php }else if($check_state_sort == "publishing_contest"){ //공모전?>
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="제목">
							<a href="<?=$iw['m_path']?>/publishing_contest_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<?=$subject?>
							</a>
						</td>
						<td data-th="공모기간"><?=$start_date." ~ ".$end_date?></td>
						<td data-th="공모상태">
							<?php if(strtotime($end_date) - strtotime(date("Y-m-d")) > -1){?>
								<span class="label label-sm label-success">진행 중</span>
							<?php }else{?>
								<span class="label label-sm label-default">마감</span>
							<?php }?>
						</td>
						<td data-th="등록일"><?=$reg_date?></td>
					<?php }else if($check_state_sort == "exhibit"){ //그림전시?>
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="그림전시명">
							<a href="<?=$iw['m_path']?>/publishing_<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$menu?>&item=<?=$td_code?>">
								<?=$picture_name?>
							</a>
						</td>
						<td data-th="액자수(점)"><?=$how_many?></td>
						<td data-th="액자크기"><?=$picture_size?></td>
						<td data-th="도서정보"><?php if{?><a href="<?=$iw['m_path']?>/publishing_data_view.php?type=publishing&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$book_id?>" target="_blank">바로가기</a><?php }?></td>
					<?php }else if($check_state_sort == "book"){ //이북몰?>
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="제목">
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<?=$bd_subject if{?> [<?=$reply_count?>]<?php }?>
							</a>
						</td>
						<td data-th="작성자"><?=$mb_nick?></td>
						<td data-th="날짜"><?=$bd_datetime?></td>
						<td data-th="조회"><?=$bd_hit?></td>
					<?php }else if($check_state_sort == "doc"){ //컨텐츠몰?>
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="제목">
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<?=$dd_subject if{?> [<?=$reply_count?>]<?php }?>
							</a>
						</td>
						<td data-th="작성자"><?=$mb_nick?></td>
						<td data-th="날짜"><?=$dd_datetime?></td>
						<td data-th="조회"><?=$dd_hit?></td>
					<?php }else if($check_state_sort == "shop"){ //쇼핑몰?>
						<td data-th="번호"><?=($total_line - $start_line - $i)?></td>
						<td data-th="제목">
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<?=$sd_subject if{?> [<?=$reply_count?>]<?php }?>
							</a>
						</td>
						<td data-th="작성자"><?=$mb_nick?></td>
						<td data-th="날짜"><?=$sd_datetime?></td>
						<td data-th="조회"><?=$sd_hit?></td>
					<?php }?>

				</tr>
			<?php }else if($hm_list_style==2){?>
				<div class="masonry-item w-6 h-2">
					<div class="box br-theme box-media">
						<div class="media">
							<?php if($check_state_sort == "mcb"){ //게시판?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div class="img-frame pull-left <?=$no_image?>">
								<?php if($no_image !="no-img"){?><table style="height:100%;width:100%;background-color:#000000;"><tr><td><?php } ?><?php if($md_file_1){?>
									<img src="<?=$iw[path]."/mcb".$upload_path."/".$md_code."/".$md_file_1?>" class="media-object img-responsive" alt=""/>
								<?php }else if($md_youtube){?>
									<img src="//img.youtube.com/vi/<?=$youtube_code?>/0.jpg" class="media-object img-responsive" alt=""/>
								<?php }else if($md_type == 2 && $md_images[1][0]){?>
									<img src="<?=htmlspecialchars($md_images[1][0]);?>" class="media-object img-responsive" alt=""/>
								<?php } ?><?php if($no_image !="no-img"){?></td></tr></table><?php }?>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$md_subject?></h4>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$md_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$md_recommend?></li><?php } ?><?php if($md_attach){?>
										<li><i class="fa fa-file"></i> <?=$md_attach?></li>
										<?php }?>
									</ul>
									<p><?=cut_str($md_content,$content_size)?></p>
								</div>
							</a>
							<?php }else if($check_state_sort == "publishing"){ //출판도서?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#fff;"><tr><td>
									<?php if ($BookImage) {?>
									<img class="media-object img-responsive" src="<?=$iw[path]."/publishing".$book_path."/".$BookImage?>" alt="">
									<?php } ?>
									</td></tr></table>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$BookName?></h4>
									<p><?=$authorName?></p>
									<p><?=$PubDate?></p>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$book_recommend?></li><?php }?>
									</ul>
								</div>
							</a>
							<?php }else if($check_state_sort == "author"){ //저자?>
							<a href="<?=$iw['m_path']?>/publishing_<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#fff;"><tr><td>
									<?php if ($Photo) {?>
									<img class="media-object img-responsive" src="<?=$iw[path]."/publishing".$author_path."/".$Photo?>" alt="">
									<?php } ?>
									</td></tr></table>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$Author?></h4>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$author_recommend?></li><?php }?>
									</ul>
								</div>
							</a>
							<?php }else if($check_state_sort == "publishing_contest"){ //공모전?>
							<a href="<?=$iw['m_path']?>/publishing_contest_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#fff;">
									<tr><td>
									<?php if ($contest_images[1][0]) {?>
									<img src="<?=htmlspecialchars($contest_images[1][0]);?>" class="media-object img-responsive" alt=""/>
									<?php } ?>
									</td></tr>
									</table>
								</div>
								<div class="media-body">
									<h4 class="media-heading box-title"><?=$subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-calendar"></i> <?=$start_date." ~ ".$end_date?></li>
									</ul>
								</div>
							</a>
							<?php }else if($check_state_sort == "exhibit"){ //그림전시?>
							<a href="<?=$iw['m_path']?>/publishing_<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$menu?>&item=<?=$td_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#fff;"><tr><td>
									<?php if ($picture_photo == "") {?>
									<!--<img class="media-object img-responsive" src="<?=$iw[path]."/publishing/no_image.jpg"?>" style='max-height:100%;'>-->
									<?php } else {?>
									<img class="media-object img-responsive" src="<?=$picture_photo?>" alt="">
									<?php } ?>
									</td></tr></table>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$picture_name?></h4>
								</div>
							</a>
							<?php }else if($check_state_sort == "book"){ //이북몰?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div>
									<img class="media-object img-responsive" src="<?=$iw[path]."/book".$upload_path."/".$bd_code."/".$bd_image?>" alt="">
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$bd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-pencil"></i> <?=$bd_author?></li>
										<li><i class="fa fa-info-circle"></i> <?=$bd_publisher?></li>
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$bd_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$bd_recommend?></li><?php }?>
									</ul>
									<p><?php if{?><?=national_language($iw[language],"a0265","무료");?><?php }else{?><?=$bd_price?> Point<?php }?></p>
								</div>
							</a>
							<?php }else if($check_state_sort == "doc"){ //컨텐츠몰?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div>
									<?php if($dd_image){?>
										<img class="media-object img-responsive" src="<?=$iw[path]."/doc".$upload_path."/".$dd_code."/".$dd_image?>" alt="">
									<?php }?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$dd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-info-circle"></i> <?=$dd_amount if{?><?=national_language($iw[language],"a0167","쪽"); }else if($dd_type==2){?><?=national_language($iw[language],"a0168","분"); }?></li>
										<li><i class="fa fa-file"></i> <?=$dd_file;?>(<?=$dd_file_size?> MB)</li>
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$dd_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$dd_recommend?></li><?php }?>
									</ul>
									<span class="label label-info"><?php if{?><?=national_language($iw[language],"a0265","무료");?><?php }else{?><?=$dd_price?> Point<?php }?></span>
								</div>
							</a>
							<?php }else if($check_state_sort == "shop"){ //쇼핑몰?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div>
									<?php if($sd_price != $sd_sale){?>
									<span class="sale-tag">
										<i class="fa fa-certificate fa-5x"></i>
										<i class="text-icon"><?=$sd_percent?>%</i>
									</span>
									<?php }?>
									<img class="media-object img-responsive" src="<?=$iw[path]."/shop".$upload_path."/".$sd_code."/".$sd_image?>" alt="">
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$sd_subject?></h4>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$sd_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$sd_recommend?></li><?php }?>
									</ul>
									<?php if($sd_price != $sd_sale){?><p class="line-through"><small><?=national_money($iw[language], $sd_price);?></small></p><?php }?>
									<p><?=national_money($iw[language], $sd_sale);?></p>
								</div>
							</a>
							<?php }?>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?php }else if($hm_list_style>=3 && $hm_list_style<=8){ if($hm_list_style>=3 && $hm_list_style<=5){?>
				<div class="<?php if{?> clearfix-6<?php } if{?> clearfix-4<?php } if{?> clearfix-2<?php }?>"></div>
				<?php }else if($hm_list_style>=6 && $hm_list_style<=8){?>
				<div class="<?php if{?> clearfix-6<?php } if{?> clearfix-4<?php } if{?> clearfix-2<?php }?>"></div>
				<?php }?>
				<div class="masonry-item <?php if{?>h-4<?php }else{?>h-full<?php } if{?>w-4<?php }else{?>w-2<?php }?>">
					<div class="box br-theme box-media">
						<div class="media">
							<?php if($check_state_sort == "mcb"){ //게시판?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div>
									<?php if($md_file_1){?>
										<img src="<?=$iw[path]."/mcb".$upload_path."/".$md_code."/".$md_file_1?>" class="media-object img-responsive" alt=""/>
									<?php }else if($md_youtube){?>
										<img src="//img.youtube.com/vi/<?=$youtube_code?>/0.jpg" class="media-object img-responsive" alt=""/>
									<?php }else if($md_type == 2 && $md_images[1][0]){?>
										<img src="<?=htmlspecialchars($md_images[1][0]);?>" class="media-object img-responsive" alt=""/>
									<?php }?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$md_subject?></h4>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$md_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$md_recommend?></li><?php } ?><?php if($md_attach){?>
										<li><i class="fa fa-file"></i> <?=$md_attach?></li>
										<?php }?>
									</ul>
									<p><?=cut_str($md_content,$content_size)?></p>
								</div>
							</a>
							<?php }else if($check_state_sort == "publishing"){ //출판도서?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div>
									<?php if ($BookImage) {?>
									<img class="media-object img-responsive" src="<?=$iw[path]."/publishing".$book_path."/".$BookImage?>" alt="">
									<?php } ?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$BookName?></h4>
									<p><?=$authorName?></p>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$book_recommend?></li><?php }?>
									</ul>
								</div>
							</a>
							<?php }else if($check_state_sort == "author"){ //저자?>
							<a href="<?=$iw['m_path']?>/publishing_<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#fff;"><tr><td>
									<?php if ($Photo) {?>
									<img class="media-object img-responsive" src="<?=$iw[path]."/publishing".$author_path."/".$Photo?>" alt="">
									<?php } ?>
									</td></tr></table>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$Author?></h4>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$Hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$author_recommend?></li><?php }?>
									</ul>
								</div>
							</a>
							<?php }else if($check_state_sort == "publishing_contest"){ //공모전?>
							<a href="<?=$iw['m_path']?>/publishing_contest_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#fff;">
									<tr><td>
									<?php if ($contest_images[1][0]) {?>
									<img src="<?=htmlspecialchars($contest_images[1][0]);?>" class="media-object img-responsive" alt=""/>
									<?php } ?>
									</td></tr>
									</table>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-calendar"></i> <?=$start_date." ~ ".$end_date?></li>
									</ul>
								</div>
							</a>
							<?php }else if($check_state_sort == "exhibit"){ //그림전시?>
							<a href="<?=$iw['m_path']?>/publishing_<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&menu=<?=$menu?>&item=<?=$td_code?>">
								<div class="img-frame pull-left">
									<table style="height:100%;width:100%;background-color:#fff;"><tr><td>
									<?php if ($picture_photo == "") {?>
									<!--<img class="media-object img-responsive" src="<?=$iw[path]."/publishing/no_image.jpg"?>" style='max-height:100%;'>-->
									<?php } else {?>
									<img class="media-object img-responsive" src="<?=$picture_photo?>" alt="">
									<?php } ?>
									</td></tr></table>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$picture_name?></h4>
								</div>
							</a>
							<?php }else if($check_state_sort == "book"){ //이북몰?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div>
									<img class="media-object img-responsive" src="<?=$iw[path]."/book".$upload_path."/".$bd_code."/".$bd_image?>" alt="">
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$bd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-pencil"></i> <?=$bd_author?></li>
										<li><i class="fa fa-info-circle"></i> <?=$bd_publisher?></li>
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$bd_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$bd_recommend?></li><?php }?>
									</ul>
									<p><?php if{?><?=national_language($iw[language],"a0265","무료");?><?php }else{?><?=$bd_price?> Point<?php }?></p>
								</div>
							</a>
							<?php }else if($check_state_sort == "doc"){ //컨텐츠몰?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div>
									<?php if($dd_image){?>
										<img class="media-object img-responsive" src="<?=$iw[path]."/doc".$upload_path."/".$dd_code."/".$dd_image?>" alt="">
									<?php }?>
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$dd_subject?></h4>
									<ul class="list-inline">
										<li><i class="fa fa-info-circle"></i> <?=$dd_amount if{?><?=national_language($iw[language],"a0167","쪽"); }else if($dd_type==2){?><?=national_language($iw[language],"a0168","분"); }?></li>
										<li><i class="fa fa-file"></i> <?=$dd_file;?>(<?=$dd_file_size?> MB)</li>
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$dd_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$dd_recommend?></li><?php }?>
									</ul>
									<span class="label label-info"><?php if{?><?=national_language($iw[language],"a0265","무료");?><?php }else{?><?=$dd_price?> Point<?php }?></span>
								</div>
							</a>
							<?php }else if($check_state_sort == "shop"){ //쇼핑몰?>
							<a href="<?=$iw['m_path']?>/<?=$check_state_sort?>_data_view.php?type=<?=$check_state_sort?>&ep=<?=$iw[store]?>&gp=<?=$iw[group]?>&item=<?=$td_code?>">
								<div>
									<?php if($sd_price != $sd_sale){?>
									<span class="sale-tag">
										<i class="fa fa-certificate fa-5x"></i>
										<i class="text-icon"><?=$sd_percent?>%</i>
									</span>
									<?php }?>
									<img class="media-object img-responsive" src="<?=$iw[path]."/shop".$upload_path."/".$sd_code."/".$sd_image?>" alt="">
								</div>
								<div class="media-body">
									<h4 class="media-heading"><?=$sd_subject?></h4>
									<ul class="list-inline">
										<?php if($cg_hit==1){?><li><i class="fa fa-eye"></i> <?=$sd_hit?></li><?php } ?><?php if($cg_comment==1){?><li><i class="fa fa-comment"></i> <?=$reply_count?></li><?php } ?><?php if($cg_recommend==1){?><li><i class="fa fa-thumbs-up"></i> <?=$sd_recommend?></li><?php }?>
									</ul>
									<?php if($sd_price != $sd_sale){?><p class="line-through"><small><?=national_money($iw[language], $sd_price);?></small></p><?php }?>
									<p><?=national_money($iw[language], $sd_sale);?></p>
								</div>
							</a>
							<?php }?>
						</div>
					</div> <!-- /.box -->
				</div> <!-- /.masonry-item -->
			<?php }
					$i++;
					}
				}
			 if($hm_list_style==1){?>
				</tbody>
			</table>
		</div> <!-- /.box -->
		<?php }else if($hm_list_style==4 || $hm_list_style==7){?>
		</div> <!-- /#grid -->
		<?php }?>
		<div class="clearfix"></div>
		<div class="pagContainer text-center">
			<ul class="pagination">
				<?php
					if($total_page!=0){
						if($page>$total_page) { $page=$total_page; }
						$start_page = ((ceil($page/$max_page)-1)*$max_page)+1;
						$end_page = $start_page+$max_page-1;
					 
						if($end_page>$total_page) {$end_page=$total_page;}
					 
						if($page>$max_page) {
							$pre = $start_page - 1;
							echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&menu=$hm_code&page=$pre&keyword=$keyword'>&laquo;</a></li>";
						} else {
							echo "<li><a href='javascript:void(0);'>&laquo;</a></li>";
						}
						
						for($i=$start_page;$i<=$end_page;$i++) {
							if($i==$page) echo "<li class='active'><a href='#'>$i</a></li>";
							else          echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&menu=$hm_code&page=$i&keyword=$keyword'>$i</a></li>";
						}
					 
						if($end_page<$total_page) {
							$next = $end_page + 1;
							echo "<li><a href='$PHP_SELF?type=$iw[type]&ep=$iw[store]&gp=$iw[group]&menu=$hm_code&page=$next&keyword=$keyword'>&raquo;</a></li>";
						} else {
							echo "<li><a href='javascript:void(0);'>&raquo;</a></li>";
						}
					}
				?>
			</ul>
		</div>
	</div> <!-- /.row -->
</div> <!-- /.content -->

<?php
include_once("_tail.php");
?>



