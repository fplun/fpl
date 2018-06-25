function matrixset(){	
	var matrix=document.getElementById("matrix");
	    var context=matrix.getContext("2d");
	    var drop=[];	    
	    var font_size=Number(document.documentElement.style.fontSize.replace('px',''));
	    var columns=43.75;
	    matrix.width=43.75*font_size;
	    matrix.height=32.75*font_size;	    
	    for(var i=0;i<columns;i++){
	        drop[i]=1;	
		    function drawMatrix(){
		    	context.clearRect(0,0,matrix.width,matrix.height)
		    	context.fillStyle="rgba(255, 255, 255, 0.3)";
		        context.font=font_size+"px";
		        for(var i=0;i<columns;i++){
		            context.fillText(Math.floor(Math.random()*2),i*font_size,drop[i]*font_size);/*get 0 and 1*/		
		            if(drop[i]*font_size>(matrix.height*2/3)&&Math.random()>0.85){/*reset*/
		                drop[i]=0;
		            }
		            drop[i]++;
		        }
		        
		       
		    }
	    }
	    setInterval(drawMatrix,40);
		var now_yield=setInterval(function(){
			var i=parseFloat($("#now_yield").html());
			var end_now_yield=i+0.00000201*5;
			$("#now_yield").html(end_now_yield.toFixed(8));
		},5000);
		
		var sum_yield=setInterval(function(){
			var i=parseFloat($("#sum_yield").html());
			var end_sum_yield=i+0.00000201*5;
			$("#sum_yield").html(end_sum_yield.toFixed(8));
		},5000);
}
var safety_tchou=[];
function move(e){
	 e.preventDefault();
}
$(function(){
	var left=1,right=1,index=1,sw=false;	
	$('.banner')[0].addEventListener('touchmove', move);
	/*banner滑动事件*/
	$('.banner').swipeLeft(function(e,touch){
		if(right<1 ||sw){
			return
		}
		sw=true;
		$('.banner .banner_body').eq(index-1).removeClass('left').hide();
		$('.banner .banner_body').eq(index).removeClass('con').addClass('left');
		$('.banner .banner_body').eq(index+1).removeClass('right').addClass('con');
		if(right>1){
				$('.banner .banner_body').eq(index+2).show().addClass('right');
		}
		setTimeout(function(){sw=false;},100)
		right--;
		left++;
		index++
	;
	});
	$('.banner').swipeRight(function(e,touch){
		if(left<1){
			return
		}
		sw=true;
		$('.banner .banner_body').eq(index+1).removeClass('right').hide();
		$('.banner .banner_body').eq(index).removeClass('con').addClass('right');
		$('.banner .banner_body').eq(index-1).removeClass('left').addClass('con');
		if(index>1){
			$('.banner .banner_body').eq(index-2).show().addClass('left');	
		}
		setTimeout(function(){sw=false;
		},100)
		right++;
		left--;
		index--;
	});
		
	$('.con_data_ul li').click(function(){
		var index=$('.con_data_ul li').index(this);
		if($('.con_data_ul_bg li').eq(index).hasClass('list')){
			$('.con_data_ul_bg li').eq(index).removeClass('list');	
		}else{
			$('.con_data_ul_bg li').eq(index).addClass('list');	
		}
				
	});
})
window.onload=function(){
	setTimeout(function(){
		matrixset()	
	},100)
};