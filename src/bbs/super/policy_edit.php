<?php
include_once("_common.php");
set_cookie("iw_upload","/super",time()+36000);
include_once("_head.php");

$sql = "select * from {$iw['master_table']} where ma_no = 1";
$row = sql_fetch($sql);

// 마스터 데이터가 없으면 기본값으로 생성
if (!$row) {
    $sql = "insert into {$iw['master_table']} (ma_no, ma_userid, ma_password, ma_buy_rate, ma_sell_rate, ma_shop_rate, ma_display, ma_policy_agreement, ma_policy_private, ma_policy_email) 
            values (1, '', '', 0, 0, 0, 0, '', '', '')";
    sql_query($sql);
    $row = sql_fetch("select * from {$iw['master_table']} where ma_no = 1");
}
?>
<!-- Quill 에디터 (완전 무료, API 키 불필요) -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<style>
/* 이용약관 폼 레이아웃 수정 */
.policy-form .form-group {
    margin-bottom: 30px;
    display: flex;
    align-items: flex-start;
}

.policy-form .control-label {
    width: 200px;
    min-width: 200px;
    padding-top: 10px;
    text-align: left;
    font-weight: bold;
    flex-shrink: 0;
}

.policy-form .editor-container {
    flex: 1;
    margin-left: 20px;
}

.policy-form .ck-editor {
    width: 100% !important;
}

/* Quill 에디터 스타일 */
.policy-form .ql-container {
    font-family: 'Malgun Gothic', Arial, sans-serif !important;
    font-size: 14px !important;
    line-height: 1.6 !important;
}

.policy-form .ql-editor {
    min-height: 250px !important;
    padding: 15px !important;
}

.policy-form .ql-toolbar {
    border-top: 1px solid #ccc !important;
    border-left: 1px solid #ccc !important;
    border-right: 1px solid #ccc !important;
    border-bottom: none !important;
    background: #f8f9fa !important;
}

.policy-form .ql-container {
    border-left: 1px solid #ccc !important;
    border-right: 1px solid #ccc !important;
    border-bottom: 1px solid #ccc !important;
    border-top: none !important;
}

.policy-form .quill {
    margin-bottom: 15px !important;
}

/* 일반 textarea 백업 스타일 */
.policy-form textarea {
    width: 100% !important;
    min-height: 300px !important;
    padding: 15px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    font-family: 'Malgun Gothic', Arial, sans-serif !important;
    font-size: 14px !important;
    line-height: 1.6 !important;
    resize: vertical !important;
}
</style>

<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="fa fa-sitemap"></i>
			사이트관리
		</li>
		<li class="active">이용약관</li>
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
			이용약관
			<small>
				<i class="fa fa-angle-double-right"></i>
				설정
			</small>
		</h1>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
				<form class="policy-form" id="ma_form" name="ma_form" action="<?=$iw['super_path']?>/policy_edit_ok.php?ep=<?=$iw['store']?>&gp=<?=$iw['group']?>" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label class="control-label">이용약관</label>
						<div class="editor-container">
							<div id="editor1" style="height: 300px;"><?=($row["ma_policy_agreement"] ?? '')?></div>
							<textarea id="contents1" name="contents1" style="display: none;"><?=htmlspecialchars($row["ma_policy_agreement"] ?? '')?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label">개인정보 처리방침</label>
						<div class="editor-container">
							<div id="editor2" style="height: 300px;"><?=($row["ma_policy_private"] ?? '')?></div>
							<textarea id="contents2" name="contents2" style="display: none;"><?=htmlspecialchars($row["ma_policy_private"] ?? '')?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label">이메일주소 무단수집거부</label>
						<div class="editor-container">
							<div id="editor3" style="height: 300px;"><?=($row["ma_policy_email"] ?? '')?></div>
							<textarea id="contents3" name="contents3" style="display: none;"><?=htmlspecialchars($row["ma_policy_email"] ?? '')?></textarea>
						</div>
					</div>
					
					<div class="form-actions" style="margin-top: 40px; text-align: center;">
						<button class="btn btn-primary btn-lg" type="button" onclick="javascript:check_form();">
							<i class="fa fa-check"></i>
							확인
						</button>
					</div>
				</form>
			<!-- PAGE CONTENT ENDS -->
			</div><!-- /col -->
		</div><!-- /row -->
	</div><!-- /container -->
</div><!-- /end .page-content -->

<script type="text/javascript">
	// Quill 에디터 변수
	var quill1, quill2, quill3;
	
	// Quill 에디터 초기화 (완전 무료, API 키 불필요)
	document.addEventListener('DOMContentLoaded', function() {
		console.log('Quill 에디터 초기화 시작...');
		
		if (typeof Quill !== 'undefined') {
			console.log('Quill 라이브러리 로드됨');
			
			// Quill 툴바 설정
			var toolbarOptions = [
				[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
				[{ 'font': ['맑은고딕', 'arial', 'serif', 'monospace'] }],
				[{ 'size': ['small', false, 'large', 'huge'] }],
				['bold', 'italic', 'underline', 'strike'],
				[{ 'color': [] }, { 'background': [] }],
				[{ 'list': 'ordered'}, { 'list': 'bullet' }],
				[{ 'indent': '-1'}, { 'indent': '+1' }],
				[{ 'align': [] }],
				['link', 'blockquote', 'code-block'],
				['clean']
			];
			
			// 에디터 1 초기화
			quill1 = new Quill('#editor1', {
				theme: 'snow',
				modules: {
					toolbar: toolbarOptions
				},
				placeholder: '이용약관을 입력하세요...'
			});
			
			// 에디터 2 초기화
			quill2 = new Quill('#editor2', {
				theme: 'snow',
				modules: {
					toolbar: toolbarOptions
				},
				placeholder: '개인정보 처리방침을 입력하세요...'
			});
			
			// 에디터 3 초기화
			quill3 = new Quill('#editor3', {
				theme: 'snow',
				modules: {
					toolbar: toolbarOptions
				},
				placeholder: '이메일주소 무단수집거부를 입력하세요...'
			});
			
			console.log('Quill 에디터 3개 초기화 완료');
			
			// 성공 메시지 표시
			var successDiv = document.createElement('div');
			successDiv.style.cssText = 'position: fixed; top: 10px; right: 10px; background: #4CAF50; color: white; padding: 10px; border-radius: 4px; z-index: 9999; font-size: 14px;';
			successDiv.innerHTML = '✅ Quill 에디터 로딩 성공! (3개)';
			document.body.appendChild(successDiv);
			
			// 3초 후 메시지 제거
			setTimeout(function() {
				if (successDiv.parentNode) {
					successDiv.parentNode.removeChild(successDiv);
				}
			}, 3000);
			
		} else {
			console.error('Quill 라이브러리 로드 실패');
			
			// 에러 메시지 표시
			var errorDiv = document.createElement('div');
			errorDiv.style.cssText = 'position: fixed; top: 10px; right: 10px; background: #f44336; color: white; padding: 10px; border-radius: 4px; z-index: 9999; font-size: 14px;';
			errorDiv.innerHTML = '❌ Quill 라이브러리 로드 실패';
			document.body.appendChild(errorDiv);
		}
	});
	
	function check_form() {
		console.log('폼 검증 시작...');
		let content1 = '', content2 = '', content3 = '';
		
		// Quill 에디터에서 데이터 가져오기
		if (quill1) {
			content1 = quill1.root.innerHTML;
			document.getElementById('contents1').value = content1;
		}
		if (quill2) {
			content2 = quill2.root.innerHTML;
			document.getElementById('contents2').value = content2;
		}
		if (quill3) {
			content3 = quill3.root.innerHTML;
			document.getElementById('contents3').value = content3;
		}
		
		// 만약 Quill이 없다면 textarea에서 가져오기
		if (!content1) content1 = document.getElementById('contents1').value;
		if (!content2) content2 = document.getElementById('contents2').value;
		if (!content3) content3 = document.getElementById('contents3').value;
		
		// HTML 태그 제거 후 텍스트만 체크 (간단한 검사)
		var textOnly1 = content1.replace(/<[^>]*>/g, '').trim();
		var textOnly2 = content2.replace(/<[^>]*>/g, '').trim();
		var textOnly3 = content3.replace(/<[^>]*>/g, '').trim();
		
		console.log('내용 길이:', textOnly1.length, textOnly2.length, textOnly3.length);
		
		if (textOnly1 === "" || textOnly1 === "<p><br></p>") {
			alert("이용약관을 입력하여 주십시오.");
			if (quill1) quill1.focus();
			return false;
		}
		if (textOnly2 === "" || textOnly2 === "<p><br></p>") {
			alert("개인정보 처리방침을 입력하여 주십시오.");
			if (quill2) quill2.focus();
			return false;
		}
		if (textOnly3 === "" || textOnly3 === "<p><br></p>") {
			alert("이메일주소 무단수집거부를 입력하여 주십시오.");
			if (quill3) quill3.focus();
			return false;
		}
		
		console.log('폼 검증 통과, 제출 중...');
		ma_form.submit();
	}
</script>
 
<?php
include_once("_tail.php");
?>



