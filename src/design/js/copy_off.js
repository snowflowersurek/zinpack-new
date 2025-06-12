//기본동작 수행을 금지 Type1
function blockEvent(e){        
	 console.log("blockEvent:"+e);
	 if(e){ 
		  e.preventDefault(); //DOM 레벨 2
	 }else{             
		  event.keyCode = 0;
		  event.returnValue = false; //IE
	 }
}

//기본동작 수행을 금지 Type2
function _stopEvent(e) {
	if (window.event) { //IE            
		window.event.cancelBubble = true; //전파 방지
		window.event.returnValue = false; //기본 동작 수행방지
	}
	//DOM 레벨 2
	if (e && e.stopPropagation && e.preventDefault) {
		e.stopPropagation(); //이벤트 전파 중지
		e.preventDefault(); //기본 동작 수행방지
	}
}

//이벤트 등록을 처리해주는 함수 (크로스 브라우징)
function _addEvent(element, eventType, handler) {       
	if (window.addEventListener) {
		element.addEventListener(eventType, handler, false)
	} else {
		if (window.attachEvent) { //IE8이하 버전에서
			element.attachEvent("on" + eventType, handler)
		}
	}
}

//마우스 드래그, 오른쪽 팝업메뉴, 선택 막기
function _addBlockEvent() {
	_addEvent(document, "dragstart", _stopEvent); //마우스 드래그  방지
	_addEvent(document, "selectstart", _stopEvent); //마우스 선택 방지
	_addEvent(document, "contextmenu", _stopEvent); //마우스 오른쪽 클릭 시 팝업메뉴 막기
	if (document.body && document.body.style.MozUserSelect != undefined) { 
		document.body.style.MozUserSelect = "none" //파이어폭스에서 마우스 선택 방지
	}
}


//특정키 사용을 방지
//이 함수는 복사, 붙여넣기의 단축키인 ctrl+c, ctrl+v 키를 막음
document.onkeydown = function(e){

	var code = document.all ? event.keyCode : e.keyCode;
	var ctrl = document.all ? event.ctrlKey : e.ctrlKey;

	if (ctrl && code==67) {
		blockEvent(e);
	}
}

//JavaScript 오른쪽 마우스 사용금지 함수 (IE용)

function clickIE4(){
	//console.log("e:"+event+"event.button="+event.button);
	//event.button == 0 : 마우스 왼쪽 버튼
	//event.button == 1 : 마우스 가운데 버튼(휠버튼)
	//event.button == 2 : 마우스 오른쪽 버튼

	if (event.button==2 ){
		event.keyCode = 0;
		   event.returnValue = false; //IE

		//alert("오른쪽 마우스 사용을 허용하지 않습니다.");
		//blockEvent();
		
		return false;
	}    
}

//JavaScript 오른쪽 마우스 사용금지 함수 
function clickNS4(e){

	if (document.layers||document.getElementById&&!document.all){
		if (e.which==2||e.which==3){
			blockEvent(e);
			return false;
		}
	}
}

//JavaScript 오른쪽 마우스 사용금지 함수 (크로스 브라우징 처리)
function rightbutton(e)
{
	if (navigator.appName == 'Netscape' &&  (e.which == 3 || e.which == 2))
		return false;
	else if (navigator.appName == 'Microsoft Internet Explorer' 
								&& (event.button == 2 || event.button == 3))
	{
		alert("오른쪽 마우스 사용을 허용하지 않습니다.");
		return false;
	}
	return true;
}


//마우스 드래그, 오른쪽 팝업메뉴, 선택 막기 함수 실행.
_addBlockEvent();