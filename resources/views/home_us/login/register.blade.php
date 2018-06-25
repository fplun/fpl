<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="_token" content="{{ csrf_token() }}"/>
		<title>注册-YunLian</title>
		<link rel="stylesheet" href="/home_us/css/base.css" />
		<link rel="stylesheet" href="/home_us/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js" ></script>
		<script type="text/javascript" src="/home_us/js/meta.js" ></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js" ></script>
		<link rel="stylesheet" href="/home_us/css/register.css" />
	</head>
	<body>
		<div class="login_con">
			<img style="width: 15rem;height: 15rem;display: block;margin: 0 auto 1rem;" src="/home_us/images/logo.png"/>
			<form action="/register_make" method="post">
				{{csrf_field()}}
			<div class="com">
				<input name="phone" id="phone" type="text" value="" placeholder="Cell-phone number" />
				<i></i>
			</div>
			<div class="com" style="padding-right:9rem;">
				<input name="sms_code" type="text" value="" placeholder="SMS authentication code" />
				<i></i>
				<button type="button" class="yzm" id="send_code" style="position: absolute;top:0.7rem;right:0;height: 3.5rem;line-height: 3.2rem;font-size:1.4rem;padding: 0 0.2rem;">verification code</button>
			</div>
			<div class="com" style=" padding-right:5.5rem;">
				<input  name="password" type="password" value="" placeholder="password" />
				<i style="right:3rem;"></i>
				<b></b>
			</div>
			<div class="com">
				<input name="code" type="text" value="{{$code}}" placeholder="Invitation code" />
				<i></i>
			</div>
			<input type="submit" value="OK" class="submit" />
			</form>
			<a class="forget" href="login">Already there is an account</a>
		</div>
		
	</body>
	<script>		
		window.onload=function(){
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
			
		}
	</script>
	<script type="text/javascript">
		@if (count($errors) > 0)
        layer.open({content:'{{ $errors->first() }}',skin:'msg',time:2});
		@endif

		@if (session('code')>1)
        layer.open({content:'{{ session('message') }}',skin:'msg',time:2});
		@elseif (session('code')==1)
        layer.open({content:'{{ session('message') }}',skin:'msg',time:2});
            window.location.href='/login';
		@endif



        $("#send_code").click(function(){
            var	phone=$("#phone").val();
            var send_code=$("#send_code").html();
            if(send_code=="Recapture" || send_code=="verification code"){
                my_post("/send_code",{phone:phone,type:1},function(data){
                    if(data.message=='Success'){
                        var i=120;
                        var intval = setInterval(function () {
                            $("#send_code").html(i);
                            i--;
                            if (i < 0) {
                                $("#send_code").html("Recapture");
                                clearInterval(intval);
                            }
                        }, 1000);
                    }else{
                        layer.open({content:data.message,skin:'msg',time:2});
					}
                });
            }

        });
	</script>
</html>
