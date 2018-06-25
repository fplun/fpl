<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>登录-云链</title>
		<link rel="stylesheet" href="/home/css/base.css" />
		<link rel="stylesheet" href="/home/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js" ></script>
		<script type="text/javascript" src="/home/js/meta.js" ></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js" ></script>
		<link rel="stylesheet" href="/home/css/login.css" />
		<script type="text/javascript" src="/home/js/canvas-particle.js" ></script>
	</head>
	<body id="mydiv">
	<img class="guide" src="/home_us/images/guide.jpg"/>
	<div>
		<div class="top">
			<!--<a href="/register">注册</a>-->
			<span onclick="location.href='/english'">切换到英文</span>
		</div>
			<div class="login_con">
				<img style="width: 15rem;height: 15rem;display: block;margin: 0 auto;" src="/home/images/logo.png"/>
	            <form action="/login_make" method="post">
	            	{{--<input type="hidden" name="language" value="CN"/>--}}
	                {{ csrf_field() }}
	                <div class="com">
	                    <input name="phone" type="text" value="" placeholder="请输入账号" />
	                    <i></i>
	                </div>
	                <div class="com" style=" padding-right:5.5rem;">
	                    <input  name="password" type="password" value="" placeholder="请输入密码" />
	                    <i style="right:3rem;"></i>
	                    <b></b>
	                </div>
					<a class="forget" href="forget">忘记密码?</a>
	                <input type="button" value="登录" class="submit" onclick="this.form.submit()"/>
	            </form>
	
			</div>
		</div>
	</body>
	<script>
		window.onload=function(){
			var config = {
				vx: 4, //小球x轴速度,正为右，负为左
				vy: 4, //小球y轴速度
				height: 2, //小球高宽，其实为正方形，所以不宜太大
				width: 2,
				count: 80, //点个数
				color: "101,127,197", //点颜色
				stroke: "101,127,197", //线条颜色
				dist: 6000, //点吸附距离
				e_dist: 1000, //鼠标吸附加速距离
				max_conn: 2 //点到点最大连接数
			}
			CanvasParticle(config);
			$('canvas').parent().css({'z-index':'10'});
			setTimeout(function(){
				$('.guide').hide();
				$('canvas').parent().css({'z-index':'2'});
				$('.top').parent().css({'position':'relative','z-index':'3'});
			},4900)
			if($('body').height()<$(window).height()){
				$('body').height($(window).height())
			}
			$(window).resize(function(){
				if($('body').height()<$(window).height()){
					$('body').height($(window).height())
				}
			})
			$('.login_con .com input').keyup(function(){
				if($(this).val().length<1){
					$(this).parent().find('i').hide();
					$(this).parent().find('b').hide();
				}else{
					$(this).parent().find('i').show();
					$(this).parent().find('b').show();
				}
			})
			$('.login_con .com i').tap(function(){
				$(this).parent().find('input').val('');
				$(this).parent().find('input').focus();
			})

			$('.login_con .com b').tap(function(){
				if($(this).hasClass('se')){
					$(this).parent().find('input').attr('type','password');
					$(this).removeClass('se');
				}else{
					$(this).parent().find('input').attr('type','text');
					$(this).addClass('se');
				}
			})

			$('.submit').tap(function(){
				// var data={name:$('.login_con .com input[name="name"]').val(),password:$('.login_con .com input[name="password"]').val()};
                //
				// if(!data.name||data.name.length<1){
				// 	layer.open({content: '用户名不能为空',skin: 'msg',time: 2});
				// 	$('.login_con .com input[name="name"]').focus();
				// 	return false;
				// }
				// if(!data.password||data.password.length<1){
				// 	layer.open({content: '密码不能为空',skin: 'msg',time: 2});
				// 	$('.login_con .com input[name="password"]').focus();
				// 	return false;
				// }
                // $('#login_make').submit();
			});
            @if (count($errors) > 0)
            layer.open({content:'{{ $errors->first() }}',skin:'msg',time:2});
            @endif
            @if (session('code')>1)
            layer.open({content:'{{ session('message') }}',skin:'msg',time:2});
            @elseif (session('code')==1)
                window.location.href='/login';
            @endif
		}
	</script>
</html>
