/*
	�̸�		: jquery.touchSlider
	�ۼ���		: �ڴ���
	�ۼ���		: 2013.12.24
	����		: 0.1.1
	Ȩ������	: http://blog.naver.com/sinesul 
				: http://www.redmussa.com
	
	//����
	$(".flickingWrapper").touchSlider({
		viewport: ".flicking"				//�ø�ŷ�� ����������Ʈ
	});

	//�߰����
	viewport: ".flicking",					//�ø�ŷ�� ����������Ʈ
	prev : ".prev",
	next : ".next",
	pagination : ".flickPaging > a",		//��������ư
	currentClass : "on",					//������ ��ư ����Ʈ Ȱ��ȭ Ŭ����
	rag : 0.2,								//�����̽� �������� 0~1
	duration: 500,							//�����̵�ӵ�
	initComplete : function(e){				//�����̵� ��ǿϷ��� �̺�Ʈ
		alert( e.current )					// ������ �ε�����
	}

*/

(function($) {
	window.touchSlider = function(options) {

		options = $.extend({
				container: this/*null*/,	//container
				viewport : null,	//slidesList
				autoplay: false,	//autoplay boolean	�ӽ��ڵ�
				delay: 3000,		//delay time		�ӽ��ڵ�
				margin: 0,			//magin				�ӽ��ڵ�
				prev: null,			//prevBtn
				next: null,			//nextBtn
				pagination: null,	//����Ʈ��
				currentClass: null,	//����Ʈ on ����
				rag : 0.1,			//�����̽� ��������
				duration: 500,		//�ӵ�
				mouseTouch: true,	//mouseTouch		�ӽ��ڵ�
				initComplete : null
			}, options);

		//setting 
		var containerWidth = $( options.container ).width();
		var viewport = $( options.container ).find( options.viewport );
		//������������ȣ
		var current = 0;
		//�����̵�� ��ü��
		var slides = $( options.container ).find( $( options.viewport + " > ul > li" ) );
		//��ü ��ü��
		var slidesTotal = slides.length;
		var startX = 0;
		var startY = 0;
		var diffX = 0;
		//�����̴��� �̵��ҹ���
		var arrow = "";
		var startCoords = {}, endCoords = {};
		startCoords.pageX = endCoords.pageX = 0;
		var movie_is = false;		//move, drag boolean	//���콺 �巡�� �����̴� ����
		var sliding_is = false;		//sliding boolean		//�����̵�ǰ��ִ»���
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
		//���� �ڽĳ�� Ŭ���̺�Ʈ capturing ����
		//IE8���� �޼ҵ� ��������
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
			if( e.data != null ) { //next, prev ��ư���� �̺�Ʈ ���޽�
				_arrow = e.data.arrow;	
			}
			
			viewport.unbind(touchMove, mouseMove);
			if( !isTouchWebkit ) viewport.unbind( leave, mouseLeave);
			//options.container.unbind(touchMove, mouseMove);
			//if( !isTouchWebkit ) options.container.unbind( leave, mouseLeave);
			
			//�¿��ư ���� �̺�Ʈ ������ �ƴѰ�쿡�� �̺�Ʈ�� ���翡�� �����.
			if( !movie_is && e.data == null) {
				if( !isTouchWebkit ) return;
			} else if( e.data == null ){
				//�ø�ŷ�� ������� ����
				//�����̵� �������� üũ boolean
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
		var slideSideOld = [ slidesTotal - 1, 0, 1] //��Ÿ�Կ��� ����� ��������Ʈ�迭��
		//����Ʈ���� ����
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

			//�̵����� ���۵Ǹ鼭 ���۵� ��ġ
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
				//�ø�ŷ���¿������ �ø�ŷ���� �̵��� �����Ǹ�ŭ ��ġ������
				_position += startX - getPointPosition( endCoords.pageX, "x" );
			}

			for( i = 0; i < 3; i++ ){				
				slideMove( $( slides[ slideSide[ i ] ] ), ( containerWidth * i ) - _position, 0 );
			}
		}

		//�¿���� ��ư����
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

		//�ε��� ��ȣ ����
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

		//�¿� �����ư �̺�Ʈ ���뿩��
		if( options.prev != null ) prevBtn.bind( "click", prevFn );
		if( options.next != null ) nextBtn.bind( "click", nextFn );

		//����Ʈ��ư����
		if( options.pagination != null ) $( options.container ).find( $( options.pagination ) ).bind("click", listBtnClick )
		
		function listBtnClick(e){
			e.preventDefault()
			if( !sliding_is ){
				if( current != $(this).index() ){
					//�����̵� �迭���� �ӽ������� ���
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

		//���������������� �����̵��� ���콺Ŭ����ġ ��밪���ϱ�
		function getPointPosition( x, arrow ){
			return (arrow == "x" )? x - viewport.position().left : x - viewport.position().top;
		}

		//�����̵� �������� boolean
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