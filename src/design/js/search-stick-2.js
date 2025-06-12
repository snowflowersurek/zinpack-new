$(function() {
  // 검색 버튼
  $('#zp-top-search').click(function(ev){
    ev.preventDefault();
  });
  $('#zp-top-search').popover({
    content: '<div class="input-group"><input type="text" class="form-control input-sm" name="search"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default">통합검색</button></span></div><div style="display:block;font-size:1.0rem;padding-top:8px;"><strong>\'키워드\'</strong>로 검색할 때는 앞에 <strong style="color:red;">#</strong>을 붙여주세요.(예: #가족)</div>',
    placement: 'bottom',
    html: true
  });
  // 사이트가 스트롤 될때 상단 헤더 고정
  $(window).scroll(sticky_relocate);
  sticky_relocate();

});

function checkSearchForm(frm) {
	if (frm.search.value.trim().length < 2) {
		alert("검색어를  2자 이상 입력해주세요");
		frm.search.value = frm.search.value.trim();
		frm.search.focus();
		return false;
	}
	
	return true;
}

// 사이트가 스트롤 될때 상단 헤더 고정
function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var div_top = $('#sticky-anchor').offset().top;
    if (window_top > div_top) {
      if($('.stick').length === 0){
        $('#main-navbar').wrap('<div class="stick"></div>');
        //$('.searchbox').addClass('container').removeClass('searchbox');
      }
    } else if($('.stick').length){
      //$('.stick').children().removeClass('container').addClass('searchbox');
      $('#main-navbar').unwrap();
    }
}