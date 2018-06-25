<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>My mine</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/home_us/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/home_us/css/gmqstyle.css"/>
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home_us/js/meta.js"></script>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader" style="background-color:#132f69">
			<div class="myheader_con">
				<i class="myreturn" onclick="window.location = '/miner/index'"></i>
				<span class="mytitle">My {{$my_miner->class}}</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="wdkj_wrap">
			<!--背景滚动数字 开始-->
			<canvas id="matrix"></canvas>
			<!--背景滚动数字 结束-->
			<div class="earnings_wrap">
				<div class="operation_wrap">
					<div class="operation_box">
				        <img class="quan loading" src="/home_us/images/operation.png" />
				        <p class="wakuang">Mining</p>
				    </div>
				</div>
			</div>
			<div class="operation_num">
				<p class="operation_num1"><span id="now_yield" class="operation_num1">{{$my_miner->now_yield}}</span>OCC</p>
				<p class="operation_num2">Computing power：{{$my_miner->com_power}}GH/s</p>
				<p  class="operation_num3">Accumulative：<span id="sum_yield" class="operation_num3">{{$my_miner->sum_yield}}</span>OCC</p>
				<p  class="operation_num3">Total computing power：{{$sum_price}}GH/s</p>
			</div>
	
		</div>
		
		
	</body>
	<script type="text/javascript">
		var matrix=document.getElementById("matrix");
	    var context=matrix.getContext("2d");
	    matrix.height=window.innerHeight;
	    matrix.width=window.innerWidth;
	    var drop=[];
	    var font_size=16;
	    var columns=matrix.width/font_size;
	    for(var i=0;i<columns;i++){
	        drop[i]=1;	
		    function drawMatrix(){	
		        context.fillStyle="#132f69";
		        context.fillRect(0,0,matrix.width,matrix.height);		
		        context.fillStyle="#1357a6";
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
            var end_now_yield=i+{{$my_miner->sec_yield}}*5;
            $("#now_yield").html(end_now_yield.toFixed(8));
        },5000);

        var sum_yield=setInterval(function(){
            var i=parseFloat($("#sum_yield").html());
            var end_sum_yield=i+{{$my_miner->sec_yield}}*5;
            $("#sum_yield").html(end_sum_yield.toFixed(8));
        },5000);
	</script>
</html>
