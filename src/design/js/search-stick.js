/*
  화면이 스크롤될때 검색바가 스크롤되지않고 상단에 고정시킴.
*/

$('.navbar-upbtn').css({display:'none'});

function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var div_top = $('#sticky-anchor').offset().top;
    if (window_top > div_top) {
      if($('.stick').length === 0){
        $('.searchbox').wrap('<div class="stick"></div>');
        $('.searchbox').addClass('container').removeClass('searchbox');
		$('.navbar-upbtn').css({display:''});
      }
    } else if($('.stick').length){
      $('.stick').children().removeClass('container').addClass('searchbox');
      $('.searchbox').unwrap();
	  $('.navbar-upbtn').css({display:'none'});
    }
}

function checkSearchForm(frm) {
	if (frm.search.value.trim().length < 2) {
		alert("검색어를 2자 이상 입력해주세요");
		frm.search.value = frm.search.value.trim();
		frm.search.focus();
		return false;
	}
	
	return true;
}

$(function () {
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});