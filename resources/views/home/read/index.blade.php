<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>云链白皮书</title>
		<link rel="stylesheet" type="text/css" href="/home/css/base.css"/>		
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home/js/meta.js"></script>
		<link rel="stylesheet" href="/home/libs/touchpdf/jquery.touchPDF.css" />
		<script type="text/javascript" src="/home/libs/touchpdf/pdf.compatibility.js" ></script>
		<script type="text/javascript" src="/home/libs/touchpdf/pdf.js" ></script>		
		<script type="text/javascript" src="/home/libs/touchpdf/jquery.panzoom.js" ></script>		
		<script type="text/javascript" src="/home/libs/touchpdf/jquery.touchSwipe.js" ></script>
		<script type="text/javascript" src="/home/libs/touchpdf/jquery.mousewheel.js" ></script>
		<script type="text/javascript" src="/home/libs/touchpdf/jquery.touchPDF.js?201806191145" ></script>	
		<style>
			#myPDF{width:100%; height:;}
		</style>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="history.back()"></i>
				<span class="mytitle">云链白皮书</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="deal_con">
			<div id="myPDF"></div>
		</div>
	</body>
	<script type="text/javascript">
		function loads(){
			var ww=$(window).width();
			var hh=$(window).height()-$('.myheader').height();
		  	$("#myPDF").height(hh);  	
		  	
		  	$("#myPDF").pdf({ 
		    		source: "/home/pdf/ylbps.pdf" ,
		    		title:"云链白皮书中文版",
		    		disableLinks:true,
		    		showToolbar:false,
		    		disableSwipe_direction:'leng',
		    		loaded:function(){
		    			
		    		},
		    		pdfScale:1,
		    		quality:1,
		    		loadingHTML:'加载中……',
		    		loadingHeight:hh,
		    		loadingWidth:ww,
		    		width:ww,
		    		height:hh,
		    		canvas_height:hh,
		    		canvas_width:ww
		    		
		   	});
		  }
	  window.onload=loads;
	  window.onresize=loads;
	  
	</script> 
</html>
