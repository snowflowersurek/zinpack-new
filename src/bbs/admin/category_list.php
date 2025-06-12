<?php
include_once("_common.php");
if (($iw[group] == "all" && $iw[level] != "admin") || ($iw[group] != "all" && $iw[gp_level] != "gp_admin")) alert("잘못된 접근입니다!","");

include_once("_head.php");
?>
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<?
				if ($iw["type"]=="mcb"){
					$category_title = "분류관리";
					echo "<i class='fa fa-clipboard'></i> 게시판";
				}else if ($iw["type"]=="publishing"){
					$category_title = "분류관리";
					echo "<i class='fa fa-book'></i> 출판도서";
				}else if ($iw["type"]=="publishing_brand"){
					$category_title = "브랜드관리";
					echo "<i class='fa fa-book'></i> 출판도서";
				}else if ($iw["type"]=="publishing_contest"){
					$category_title = "공모전 분류관리";
					echo "<i class='fa fa-book'></i> 출판도서";
				}else if ($iw["type"]=="shop"){
					$category_title = "분류관리";
					echo "<i class='fa fa-shopping-cart'></i> 쇼핑몰";
				}else if($iw["type"]=="doc"){
					$category_title = "분류관리";
					echo "<i class='fa fa-inbox'></i> 컨텐츠몰";
				}else if($iw["type"]=="book"){
					$category_title = "분류관리";
					echo "<i class='fa fa-newspaper-o'></i> 이북몰";
				}
			?>
		</li>
		<li class="active"><?=$category_title?></li>
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
			<?=$category_title?>
			<small>
				<i class="fa fa-angle-double-right"></i>
				목록
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<div class="row">
					<div class="col-xs-12 col-sm-5">
						<button class='btn btn-success' type='button' onclick="javascript:categoryFrameNew('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','new');"><i class="fa fa-plus"></i> 추가</button>
						<div class="dd" id="sortable">
							<ol class="dd-list">
							<?
								$sql = "select * from $iw[category_table] where ep_code = '$iw[store]' and gp_code = '$iw[group]' and state_sort = '$iw[type]' order by cg_name asc";
								$result = sql_query($sql);
								while($row = @sql_fetch_array($result)){
									$cg_no = $row["cg_no"];
									$cg_code = $row["cg_code"];
									$cg_name = $row["cg_name"];
									$cg_level = $row["cg_level"];
									$cg_display = $row["cg_display"];
							?>
								<li class="dd-item dd2-item" data-id="1">
									<div class="dd-handle dd2-handle">
									</div>
									<div class="dd2-content">
										<span><?=$cg_name?></span>
										<div class="pull-right action-buttons">
											<?if($cg_display==3){?><i class="fa fa-eye-slash"></i><?}?>
											<?if($cg_display==2){?><i class="fa fa-link"></i><?}?>
											<a class="blue" href="javascript:categoryFrameEdit('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$cg_code?>');">
												<i class="fa fa-cog"></i>
											</a>
											<a class="red" href="javascript:category_delete('<?=$iw[type]?>','<?=$iw[store]?>','<?=$iw[group]?>','<?=$cg_code?>','<?=$cg_name?>');">
												<i class="fa fa-trash-o"></i>
											</a>
										</div>
									</div>
								</li>
							<?}?>
							</ol>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<iframe id="categoryFrame" name="categoryFrame" width="100%" height="650px" frameborder="0" scrolling="no" src="">
						</iframe>
					</div>
				</div>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div>
</div><!-- /end .page-content -->

<script language="javascript">
	function categoryFrameNew(type,ep,gp,menu){
		document.getElementById('categoryFrame').height = 1050;
		categoryFrame.location.href="category_write.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu;
	}
	function categoryFrameEdit(type,ep,gp,menu){
		document.getElementById('categoryFrame').height = 1050;
		categoryFrame.location.href="category_edit.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu;
	}

	function category_delete(type,ep,gp,menu,name_cg) {
		if (confirm(name_cg+'를 삭제하시겠습니까?')) {
			location.href="category_delete.php?type="+type+"&ep="+ep+"&gp="+gp+"&menu="+menu;
		}
	}
</script>

<?
include_once("_tail.php");
?>