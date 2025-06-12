/*
	이름		: jquery.touchSlider
	작성자		: 박대진
	작성일		: 2013.12.24
	버전		: 0.1.1
	홈페이지	: http://blog.naver.com/sinesul 
				: http://www.redmussa.com
	
	//예제
	$(".flickingWrapper").touchSlider({
		viewport: ".flicking"				//플리킹될 페이지리스트
	});

	//추가기능
	viewport: ".flicking",					//플리킹될 페이지리스트
	prev : ".prev",
	next : ".next",
	pagination : ".flickPaging > a",		//페이지버튼
	currentClass : "on",					//페이지 버튼 리스트 활성화 클래스
	rag : 0.2,								//슬라이스 감지영역 0~1
	duration: 500,							//슬라이드속도
	initComplete : function(e){				//슬라이드 모션완료후 이벤트
		alert( e.current )					// 페이지 인덱스값
	}

*/

(function($) {
	window.touchSlider = function(options) {

		options = $.extend({
				container: this/*null*/,	//container
				viewport : null,	//slidesList
				autoplay: false,	//autoplay boolean	임시코드
				delay: 3000,		//delay time		임시코드
				margin: 0,			//magin				임시코드
				prev: null,			//prevBtn
				next: null,			//nextBtn
				pagination: null,	//리스트넘
				currentClass: null,	//리스트 on 상태
				rag : 0.1,			//슬라이스 감지영역
				duration: 500,		//속도
				mouseTouch: true,	//mouseTouch		임시코드
				initComplete : null
			}, options);

		//setting 
		var containerWidth = $( options.container ).width();
		var viewport = $( options.container ).find( options.viewport );
		//현재페이지번호
		var current = 0;
		//슬라이드될 객체들
		var slides = $( options.container ).find( $( options.viewport + " > ul > li" ) );
		//전체 객체수
		var slidesTotal = slides.length;
		var startX = 0;
		var startY = 0;
		var diffX = 0;
		//슬라이더가 이동할방향
		var arrow = "";
		var startCoords = {}, endCoords = {};
		startCoords.pageX = endCoords.pageX = 0;
		var movie_is = false;		//move, drag boolean	//마우스 드래그 움직이는 상태
		var sliding_is = false;		//sliding boolean		//슬라이드되고있는상태
		var slidesX = [ 0, 0, 0 ] //slide x
		if( options.prev != null ) var prevBtn = $( options.container ).find( $( options.prev ) )
		if( options.next != null ) var nextBtn = $( options.container ).find( $( options.next ) )

		isTouchWebkit = "ontouchstart" in window && "WebKitCSSMatrix" in window,
		touchDown = "touchstart", touchMove = "touchmove", touchUp = "touchend"
		
		//webkit check
		if (!isTouchWebkit) {

			touchDown = "mousedown";
			touchMove  = "mousemove";
			touchUp = "mouseup";
			leave = "mouseleave";

			$( slides ).css( { "position" : "absolute", left : -100000} );
			$( slides ).eq( current ).css( { "position" : "absolute", left : 0} );

		} else { 
			$( slides ).css( { "position" : "absolute"} ); 
			$( slides ).css({webkitTransitionDuration: "0" + "ms",//duration ? duration + "ms" : 0 + "ms",
				webkitTransform: "translate3d( " + -100000 + "px, 0, 0)" }	)

			$( slides ).eq( current ).css({webkitTransitionDuration: "0" + "ms",//duration ? duration + "ms" : 0 + "ms",
				webkitTransform: "translate3d( " + 0 + "px, 0, 0)" }	)
		}
		
		if( options.pagination != null ){
			$( options.container ).find( $( options.pagination ) ).removeClass("on")
			$( options.container ).find( $( options.pagination ) ).eq( current ).addClass("on")
		}

		//2013-07-15 
		$( options.viewport ).css({"position" : "relative"})

		
		viewport.bind( touchDown, mouseDown)
		viewport.bind( touchUp, mouseUp)
		//options.container.bind( touchDown, mouseDown)
		//options.container.bind( touchUp, mouseUp)

		function preventHandler( e ){
			if( movie_is ){
				e.stopPropagation();
				e.preventDefault();
			}
			movie_is = false;
		}
		//하위 자식노드 클릭이벤트 capturing 차단
		//IE8에서 메소드 지원안함
		//options.container[0].addEventListener("click", preventHandler, true);

		function mouseLeave(e){
			mouseUp(e);
		}

		function mouseDown(e){
			if(	sliding_is ) return;
			containerWidth = $( options.container ).width();
			//slideListSort();

			viewport.bind(touchMove, mouseMove);
			//options.container.bind(touchMove, mouseMove);
			
			if( !isTouchWebkit ) startCoords = endCoords = e, viewport.bind( leave, mouseLeave);//options.container.bind( leave, mouseLeave);
				else startCoords = endCoords = e.originalEvent.touches[0];
			startX = getPointPosition ( startCoords.pageX, "x" );
			startY = getPointPosition ( startCoords.pageY, "y" );
			slideListSort();

			// no drag image
			if( !isTouchWebkit ) e.preventDefault ? e.preventDefault() : e.returnValue = false
		}

		function mouseMove(e){
			movie_is = true;			

			if( !isTouchWebkit ) endCoords = e
				else endCoords = e.originalEvent.touches[0];

			for( i = 0; i < 3; i++ ){
				if( !isTouchWebkit ) $( slides[ slideSide[ i ] ] ).stop();
				leftPx = ( getPointPosition( endCoords.pageX, "x" ) - startX ) - (  containerWidth - ( containerWidth * i )  )
				slideMove( $( slides[ slideSide[ i ] ] ), leftPx, 0 );
			}

			var dragX = Math.abs( startX - getPointPosition( Math.abs( endCoords.pageX ), "x") );
			var dragY = Math.abs( startY - getPointPosition( Math.abs( endCoords.pageY ), "y") );
			if( dragX > dragY ){
				e.preventDefault ? e.preventDefault() : e.returnValue = false;
			}
		}

		function mouseUp(e){

			var _arrow = "";
			if( e.data != null ) { //next, prev 버튼으로 이벤트 전달시
				_arrow = e.data.arrow;	
			}
			
			viewport.unbind(touchMove, mouseMove);
			if( !isTouchWebkit ) viewport.unbind( leave, mouseLeave);
			//options.container.unbind(touchMove, mouseMove);
			//if( !isTouchWebkit ) options.container.unbind( leave, mouseLeave);
			
			//좌우버튼 으로 이벤트 접근이 아닌경우에는 이벤트를 현재에서 멈춤다.
			if( !movie_is && e.data == null) {
				if( !isTouchWebkit ) return;
			} else if( e.data == null ){
				//플리킹만 했을경우 상태
				//슬라이드 반응영역 체크 boolean
				if( getRag( startX - getPointPosition( endCoords.pageX, "x" ) ) ){
					if( startX - getPointPosition( endCoords.pageX, "x" ) > 0 ){
						//nextFn(e)
						e.data = { arrow: "left" }
						currentSet( e.data.arrow );
						slideListSort( e.data.arrow );
						mouseUp(e)
					} else {
						//prevFn(e)
						e.data = { arrow: "right" }
						currentSet( e.data.arrow );
						slideListSort( e.data.arrow );
						mouseUp(e)
					}
				}
			}
			for( i = 0; i < 3; i++ ){
				slideMove( $( slides[ slideSide[ i ] ] ), ( containerWidth * i ) - containerWidth, options.duration );
			}
			if( isTouchWebkit ) movie_is = false;

		}

		var slideSide = [ slidesTotal - 1, 0, 1 ] //left, current, right
		var slideSideOld = [ slidesTotal - 1, 0, 1] //퀵타입에서 저장될 기존리스트배열값
		//리스트순서 정렬
		function slideListSort( _arrow, slideQuickObj ){
			if( slideQuickObj ){
				slideSide = slideQuickObj
			}else{
				if( current == slidesTotal - 1){
					slideSide[0] = current - 1;
					slideSide[2] = null;
				} else if( current == 0 ){
					slideSide[0] = null;
					slideSide[2] = current + 1;
				} else {
					slideSide[0] = current - 1;
					slideSide[2] = current + 1;
				}
				slideSide[1] = current;
			}			

			//이동무비가 시작되면서 시작될 위치
			var _position = 0;
			if( _arrow == "right" ) {
				_position = containerWidth * 2
				if( !isTouchWebkit )_position -= parseInt( $( options.container ).css("padding-left") )
			} else if( _arrow == "left" ) { 
				_position = 0
				if( !isTouchWebkit )_position -= parseInt( $( options.container ).css("padding-right") )
			} else if( !_arrow ) {
				_position = containerWidth;
			}

			if( !isTouchWebkit ){
				//플리킹상태였을경우 플리킹으로 이동한 포지션만큼 위치값조정
				_position += startX - getPointPosition( endCoords.pageX, "x" );
			}

			for( i = 0; i < 3; i++ ){				
				slideMove( $( slides[ slideSide[ i ] ] ), ( containerWidth * i ) - _position, 0 );
			}
		}

		//좌우방향 버튼관련
		function prevFn(e){
			reset();
			e.preventDefault();

			if( !sliding_is ){
				e.data = { arrow: "right" }
				currentSet( e.data.arrow );
				slideListSort( e.data.arrow );
				setTimeout( function(){ mouseUp(e)	}, 1 );
			}
		};

		function nextFn(e){
			reset();
			e.preventDefault()

			if( !sliding_is ){
				e.data = { arrow: "left" }	
				currentSet( e.data.arrow );
				slideListSort( e.data.arrow );
				setTimeout( function(){ mouseUp(e)	}, 1 );
			}
		};

		//인덱스 번호 설정
		function currentSet( _arrow ){
			if( _arrow == "right" ){
				if( current == 0) current = slidesTotal - 1;
					else current--;
			} else if( _arrow == "left" ){
				if( current == slidesTotal - 1) current = 0;
					else current++;
			}
		}

		function reset(){
			//reset
			startX = 0;
			endCoords.pageX = 0;
		}

		//좌우 방향버튼 이벤트 적용여부
		if( options.prev != null ) prevBtn.bind( "click", prevFn );
		if( options.next != null ) nextBtn.bind( "click", nextFn );

		//리스트버튼관련
		if( options.pagination != null ) $( options.container ).find( $( options.pagination ) ).bind("click", listBtnClick )
		
		function listBtnClick(e){
			e.preventDefault()
			if( !sliding_is ){
				if( current != $(this).index() ){
					//슬라이드 배열값을 임시저장할 장소
					var tempSlideobj = [];

					if( current < $(this).index() ){
						e.data = { arrow: "left" }
						tempSlideobj[0] = slideSide[1];
					} else if( current > $(this).index() ){
						e.data = { arrow: "right" }
						tempSlideobj[2] = slideSide[1];
					}

					current = $( options.container ).find( $( options.pagination ) ).index( $(this) );

					tempSlideobj[1] = current
					reset();
					slideListSort( e.data.arrow, tempSlideobj);
					setTimeout( function(){ mouseUp(e)	}, 1 );
				}
			}
		}

		function slideMove( target, position, duration ){
			sliding_is = true;
			duration =  duration ? duration : 0
				
			if( isTouchWebkit ){
				target.css({webkitTransitionDuration: duration + "ms",//duration ? duration + "ms" : 0 + "ms",
				webkitTransform: "translate3d( " + position + "px, 0, 0)" }	)
				
				if( duration != 0  ){
					setTimeout( function(){ 
						sliding_is = false;
						slideMoveEnd();
					}, duration );
				}
				
			}else{
				target.stop();
				target.animate(
					{left: position },
					{duration: duration,//duration ? duration : 0,
					complete:function(e){
						sliding_is = false;
						if( duration != 0  ){
							slideMoveEnd();
						}
					}}
				)
			}
		}

		function slideMoveEnd(){
			sliding_is = false;
			if (!isTouchWebkit) {
				$( slides ).css( { "position" : "absolute", left : -100000} );
				$( slides ).eq( current ).css( { "position" : "absolute", left : 0} );
			} else {
				$( slides ).css({webkitTransitionDuration: "0" + "ms", webkitTransform: "translate3d( -10000000px, -10000000px, -10000000px)" }	)
				$( slides ).eq( current ).css({webkitTransitionDuration: "0" + "ms", webkitTransform: "translate3d( 0px, 0, 0)" }	)
			}
			if( options.pagination != null ){
				$( options.container ).find( $( options.pagination ) ).removeClass("on")
				$( options.container ).find( $( options.pagination ) ).eq( current ).addClass("on")
			}

			if( options.initComplete != null ) options.initComplete( { current : current } );
		}

		//실제뷰페이지에서 슬라이드의 마우스클릭위치 상대값구하기
		function getPointPosition( x, arrow ){
			return (arrow == "x" )? x - viewport.position().left : x - viewport.position().top;
		}

		//슬라이드 반응영역 boolean
		function getRag( x ){
			var rag_is
			( Math.abs( x / containerWidth ) >= options.rag ) ? rag_is = true : rag_is = false; 
			return rag_is;
		}

		$(window).resize(function(e){
			if (!isTouchWebkit) {
				$( slides ).css( { "position" : "absolute", left : -100000} );
				$( slides ).eq( current ).css( { "position" : "absolute", left : 0} );
			} else {
				$( slides ).css({webkitTransitionDuration: "0" + "ms", webkitTransform: "translate3d( -100000px, 0, 0)" }	)
				$( slides ).eq( current ).css({webkitTransitionDuration: "0" + "ms", webkitTransform: "translate3d( 0px, 0, 0)" }	)
			}
		})
	}

	$.fn.touchSlider = function(options) {
		//options = options || {};
		options.container = this;
		touchSlider(options);
		//return this;
	};

}(jQuery));