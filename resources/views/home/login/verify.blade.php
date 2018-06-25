<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>安全验证</title>
		<link rel="stylesheet" type="text/css" href="/home/css/base.css"/>
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home/js/meta.js"></script>
		<link rel="stylesheet" href="/home/css/safety.css" />
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="history.back()"></i>
				<span class="mytitle">安全验证</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="safety_con">
			<div class="img">
				<img src="/home/images/safety_iocn2.png"/>
				<img src="/home/images/safety_iocn3.png" style="display: none;"/>
			</div>
			<p>为了你的账号安全，本次登录需要进行验证<br/>请将下方的图标，移动到圆形区域内</p>
			<div class="safety_cz">
				<img id="safety" src="/home/images/safety_iocn1.png">
				<span></span>
			</div>
		</div>
	</body>
	<script>
        var safety_tchou=[];
        $(function(){
            document.getElementById('safety').addEventListener('touchstart', str);
            document.getElementById('safety').addEventListener('touchmove', move);
            document.getElementById('safety').addEventListener('touchend', end);
        });
        function str(e){
            $('#safety').removeClass('admint');
            safety_tchou[0]=e.touches[0].pageX;
            safety_tchou[1]=Number($('#safety').css('left').replace('px',''));
            var fonsize=Number($('html').css('fontSize').replace('px',''));
            safety_tchou[2]=$('.safety_cz').width()-(0.9375*fonsize);
            safety_tchou[3]=$('.safety_cz').width()-(8.25*fonsize);
            safety_tchou[4]=$('.safety_cz').width()-(0.9375*fonsize);
            safety_tchou[5]=8.25*fonsize;
            safety_tchou[6]=safety_tchou[3]/(1.5*360);
        }
        function move(e){
            e.preventDefault();
            var left=e.touches[0].pageX-safety_tchou[0]+safety_tchou[1];
            if(left>0 && left<safety_tchou[2]){
                $('#safety').css('left',left+'px');
                if(left>=safety_tchou[3] && left<=safety_tchou[4] && !$('.safety_cz span').hasClass('list')){
                    $('.safety_cz span').addClass('list')
                }else if(left<=safety_tchou[3] || left>=safety_tchou[4]){
                    $('.safety_cz span').removeClass('list')
                }
                $('#safety').css('transform','rotate('+left/safety_tchou[6]+'deg)')

            }

        }
        function end(e){
            if($('.safety_cz span').hasClass('list')){
                $('#safety').addClass('list');
                $('.safety_con .img img').eq(0).hide();
                $('.safety_con .img img').eq(1).show();
                /*验证通过*/
                setTimeout(function(){
                    $('#safety').removeClass('list');
                    $('#safety').addClass('admint');
                    $('.safety_con .img img').eq(0).show();
                    $('.safety_con .img img').eq(1).hide();
                    $('#safety').css({'left':safety_tchou[5]+'px','transform':'rotate(0deg)'})
                    window.location.href="/index";
                },500)
            }else{
                $('#safety').removeClass('list');
                $('#safety').addClass('admint');
                $('.safety_con .img img').eq(0).show();
                $('.safety_con .img img').eq(1).hide();
                $('#safety').css({'left':safety_tchou[5]+'px','transform':'rotate(0deg)'})
            }

        }


	</script>
</html>
