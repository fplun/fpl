(function(window,document){ 
	//屏幕自适应处理
	var meta={
		DPI:96,
		w_w:750,
		getwindow:function(){
			return  document.body.clientWidth;
		},
		getdpi:function(){
			  var arrDPI = new Array();
		    if (window.screen.deviceXDPI != undefined) {
		        arrDPI[0] = window.screen.deviceXDPI;
		        arrDPI[1] = window.screen.deviceYDPI;
		    }
		    else {
		        var tmpNode = document.createElement("DIV");
		        tmpNode.style.cssText = "width:1in;height:1in;position:absolute;left:0px;top:0px;z-index:99;visibility:hidden";
		        document.body.appendChild(tmpNode);
		        arrDPI[0] = parseInt(tmpNode.offsetWidth);
		        arrDPI[1] = parseInt(tmpNode.offsetHeight);
		        tmpNode.parentNode.removeChild(tmpNode);    
		    }
		    return arrDPI;
		},
		getnul:function(){
			var g=this,nul;
			if(g.getdpi()[0]==g.DPI && g.getwindow()!=g.w_w && g.getwindow()<g.w_w){			
				nul=g.w_w/g.getwindow();
			}else{
				nul=g.DPI/g.getdpi()[0]
			}
			return nul;
			
		},	
		setfonsize:function(){
			var g=this;
	        var html = document.getElementsByTagName("html")[0],
	            reEvt = "orientationchange" in window ? "orientationchange" : "resize",
	            reFontSize = function() {
	               const nul=g.getnul();
	                if(!nul) {
	                    return;
	                }
	                html.style.fontSize = 16/nul+'px';
	                document.documentElement.style.fontSize= 16/nul+'px';
	                
	        	}
	        if(!document.body){
	        	setTimeout(function(){
	        		g.setfonsize.call(g);
	        	})
	        }else{
	        	reFontSize();
	       	 	window.addEventListener(reEvt, reFontSize);	 
	       	 	g.setmeta();
	        }	        
	 	} ,
	 	setmeta:function(){
	 		var g=this,
	 			metas=document.getElementsByTagName('meta'),
	 			viewport=true,
	 			capable=true,
	 			apple=true,
	 			format=true;
	 		for(var i =0;i<metas.length;i++){
	 			if(metas[i].name=="viewport"){
	 				viewport=false;
	 			}	
	 			if(metas[i].name=="apple-mobile-web-app-capable"){
	 				capable=false;
	 			}
	 			if(metas[i].name=="apple-mobile-web-app-status-bar-style"){
	 				apple=false;
	 			}
	 			if(metas[i].name=="format-detection"){
	 				format=false;
	 			}
		 		
	       }
	 		
	 		var meta = document.createElement("meta");
	 			if(viewport){
	 				meta.name="viewport"
		 			meta.content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no";
		        	document.head.appendChild(meta);
	 			}
		 		if(capable){
			        meta = document.createElement("meta");
			        meta.name="apple-mobile-web-app-capable"
			 		meta.content="yes";
			 		document.head.appendChild(meta);
		 		}
		 		if(apple){
			 		meta = document.createElement("meta");
			 		meta.name="apple-mobile-web-app-status-bar-style"
			 		meta.content="black";
			 		document.head.appendChild(meta);
		 		}
		 		if(format){
			 		meta = document.createElement("meta");
			 		meta.name="format-detection"
			 		meta.content="telephone=no";
			 		document.head.appendChild(meta);
		 		}
	 	}
		
	}
	document.addEventListener("DOMContentLoaded", meta.setfonsize());
})(window,document)

/*
 扩展JUQERY 
 'swipe'（滑动）, 'swipeLeft'（左滑）, 'swipeRight'（右滑）, 'swipeUp'(上滑), 'swipeDown'（下滑）,
    'doubleTap'（双击）, 'tap'(轻击), 'singleTap'（单击）, 'longTap'（长按）事件,
    返回两个参数（参数1：qjuery对象，参数2：touch对象）
 源码来自zepto.JS
 * */

;(function($){
  var touch = {},
    touchTimeout, tapTimeout, swipeTimeout, longTapTimeout,
    longTapDelay = 750,
    gesture,
    down, up, move,
    eventMap,
    initialized = false

  function swipeDirection(x1, x2, y1, y2) {
    return Math.abs(x1 - x2) >=
      Math.abs(y1 - y2) ? (x1 - x2 > 0 ? 'Left' : 'Right') : (y1 - y2 > 0 ? 'Up' : 'Down')
  }

  function longTap() {
    longTapTimeout = null
    if (touch.last) {
      touch.el.trigger('longTap',touch)
      touch = {}
    }
  }

  function cancelLongTap() {
    if (longTapTimeout) clearTimeout(longTapTimeout)
    longTapTimeout = null
  }

  function cancelAll() {
    if (touchTimeout) clearTimeout(touchTimeout)
    if (tapTimeout) clearTimeout(tapTimeout)
    if (swipeTimeout) clearTimeout(swipeTimeout)
    if (longTapTimeout) clearTimeout(longTapTimeout)
    touchTimeout = tapTimeout = swipeTimeout = longTapTimeout = null
    touch = {}
  }

  function isPrimaryTouch(event){
    return (event.pointerType == 'touch' ||
      event.pointerType == event.MSPOINTER_TYPE_TOUCH)
      && event.isPrimary
  }

  function isPointerEventType(e, type){
    return (e.type == 'pointer'+type ||
      e.type.toLowerCase() == 'mspointer'+type)
  }

  // helper function for tests, so they check for different APIs
  function unregisterTouchEvents(){
    if (!initialized) return
	document.removeEventListener(eventMap.up, up)
	document.removeEventListener(eventMap.down, down)
	document.removeEventListener(eventMap.move, move)
    $(document).off(eventMap.cancel, cancelAll)
    $(window).off('scroll', cancelAll)
    cancelAll()
    initialized = false
  }

  function setup(__eventMap){
    var now, delta, deltaX = 0, deltaY = 0, firstTouch, _isPointerType
	
    unregisterTouchEvents()

    eventMap = (__eventMap && ('down' in __eventMap)) ? __eventMap :
      ('ontouchstart' in document ?
      { 'down': 'touchstart', 'up': 'touchend',
        'move': 'touchmove', 'cancel': 'touchcancel' } :
      'onpointerdown' in document ?
      { 'down': 'pointerdown', 'up': 'pointerup',
        'move': 'pointermove', 'cancel': 'pointercancel' } :
       'onmspointerdown' in document ?
      { 'down': 'MSPointerDown', 'up': 'MSPointerUp',
        'move': 'MSPointerMove', 'cancel': 'MSPointerCancel' } : false)

    // No API availables for touch events
    if (!eventMap) return

    if ('MSGesture' in window) {
      gesture = new MSGesture()
      gesture.target = document.body

      $(document)
        .bind('MSGestureEnd', function(e){
          var swipeDirectionFromVelocity =
            e.velocityX > 1 ? 'Right' : e.velocityX < -1 ? 'Left' : e.velocityY > 1 ? 'Down' : e.velocityY < -1 ? 'Up' : null
          if (swipeDirectionFromVelocity) {
            touch.el.trigger('swipe',touch)
            touch.el.trigger('swipe'+ swipeDirectionFromVelocity,touch)
          }
        })
    }

    down = function(e){
      if((_isPointerType = isPointerEventType(e, 'down')) &&
        !isPrimaryTouch(e)) return
       firstTouch =(!_isPointerType && e.touches)?e.touches[0]:e
      if (e.touches && e.touches.length === 1 && touch.x2) {
        // Clear out touch movement data if we have it sticking around
        // This can occur if touchcancel doesn't fire due to preventDefault, etc.
        touch.x2 = undefined
        touch.y2 = undefined
      }
      now = Date.now()
      delta = now - (touch.last || now)
      touch.el = $('tagName' in firstTouch.target ?
        firstTouch.target : firstTouch.target.parentNode)
      touchTimeout && clearTimeout(touchTimeout)
      touch.x1 = firstTouch.pageX
      touch.y1 = firstTouch.pageY
      if (delta > 0 && delta <= 250) touch.isDoubleTap = true
      touch.last = now
      longTapTimeout = setTimeout(longTap, longTapDelay)
      // adds the current touch contact for IE gesture recognition
      if (gesture && _isPointerType) gesture.addPointer(e.pointerId)
    }

    move = function(e){
      if((_isPointerType = isPointerEventType(e, 'move')) &&
        !isPrimaryTouch(e)) return; 
      firstTouch =(!_isPointerType && e.touches)?e.touches[0]:e
      if (touch.el&&touch.el.data('swipe')){
         e.preventDefault();	
      }
      cancelLongTap()
      touch.x2 = firstTouch.pageX
      touch.y2 = firstTouch.pageY

      deltaX += Math.abs(touch.x1 - touch.x2)
      deltaY += Math.abs(touch.y1 - touch.y2)
    }

    up = function(e){
      if((_isPointerType = isPointerEventType(e, 'up')) &&
        !isPrimaryTouch(e)) return
      cancelLongTap()

      // swipe
      if ((touch.x2 && Math.abs(touch.x1 - touch.x2) > 30) ||
          (touch.y2 && Math.abs(touch.y1 - touch.y2) > 30))

        swipeTimeout = setTimeout(function() {
          if (touch.el){
            touch.el.trigger('swipe',touch)
            touch.el.trigger('swipe' + (swipeDirection(touch.x1, touch.x2, touch.y1, touch.y2)),touch)
          }
          touch = {}
        }, 0)

      // normal tap
      else if ('last' in touch)
        // don't fire tap when delta position changed by more than 30 pixels,
        // for instance when moving to a point and back to origin
        if (deltaX < 30 && deltaY < 30) {
          // delay by one tick so we can cancel the 'tap' event if 'scroll' fires
          // ('tap' fires before 'scroll')
          tapTimeout = setTimeout(function() {

            // trigger universal 'tap' with the option to cancelTouch()
            // (cancelTouch cancels processing of single vs double taps for faster 'tap' response)
            var event = $.Event('tap')
            event.cancelTouch = cancelAll
            // [by paper] fix -> "TypeError: 'undefined' is not an object (evaluating 'touch.el.trigger'), when double tap
            if (touch.el) touch.el.trigger(event,touch)

            // trigger double tap immediately
            if (touch.isDoubleTap) {
              if (touch.el) touch.el.trigger('doubleTap',touch)
              touch = {}
            }

            // trigger single tap after 250ms of inactivity
            else {
              touchTimeout = setTimeout(function(){
                touchTimeout = null
                if (touch.el) touch.el.trigger('singleTap',touch)
                touch = {}
              }, 250)
            }
          }, 0)
        } else {
          touch = {}
        }
        deltaX = deltaY = 0
    }
	document.addEventListener(eventMap.up, up);
	document.addEventListener(eventMap.down, down);
	document.addEventListener(eventMap.move, move);
    $(document).on(eventMap.cancel, cancelAll)
    // scrolling the window indicates intention of the user
    // to scroll, not tap or swipe, so cancel all ongoing events
    $(window).on('scroll', cancelAll)

    initialized = true
  }

  ;['swipe', 'swipeLeft', 'swipeRight', 'swipeUp', 'swipeDown',
    'doubleTap', 'tap', 'singleTap', 'longTap'].forEach(function(eventName){
    $.fn[eventName] = function(callback){
    	this.data('swipe',true);
    	return this.on(eventName, callback) 
    }
  })

  $.touch = { setup: setup }
  $(setup)
    //将form转为AJAX提交
    function ajaxSubmit(frm, fn, er) {
        var dataPara = getFormJson(frm);
        $.ajax({
            url: frm.action,
            type: frm.method,
            data: dataPara,
            success: fn,
            error:function(request){
                layer.open({content:'网络连接错误',skin:'msg',time:2});
            }
        });
    }

//将form中的值转换为键值对。
    function getFormJson(frm) {
        var o = {};
        var a = $(frm).serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });

        return o;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    function my_post(new_url,new_data,su){
        $.ajax({
            type:'post',
            url:new_url,
            data:new_data,
            error:function(request){

                layer.open({content:'网络连接错误',skin:'msg',time:2});
            },
            success:su
        });
    }
})($)









