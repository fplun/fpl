<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="_token" content="{{ csrf_token() }}"/>
		<title>Reset password</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/home_us/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/home_us/css/forgetPsd.css"/>
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home_us/js/meta.js"></script>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="history.back()"></i>
				<span class="mytitle">Reset password</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="login_con">
			<form action="/forget_make" method="post">
				{{csrf_field()}}
			<div class="com">
				<input type="text" name="phone" id="phone" placeholder="Cell-phone number"/>
				<i></i>
			</div>
			<div class="com" style="padding-right:9rem;">
				<input type="text" name="sms_code" style="width:;" placeholder="SMS authentication code" />
				<i></i>
				<button type="button" class="yzm" id="send_code" style="position: absolute;top:0.7rem;right:0;height: 3.5rem;line-height: 3.5rem;font-size:1.5rem;padding: 0 0.2rem;color:#009dff;border:none;background-color: transparent;">verification code</button>
			</div>
			<div class="com" style=" padding-right:5.5rem;">
				<input type="password" name="password" value="" placeholder="new password" />
				<i style="right:3rem;"></i>
				<b></b>
			</div>
			<div class="com" style=" padding-right:5.5rem;">
				<input type="password" value="" name="password_confirmation" placeholder="new password again" />
				<i style="right:3rem;"></i>
				<b></b>
			</div>
			<input type="submit" value="OK" class="submit" />
			</form>
			<a class="forget" href="login">Login >></a>
		</div>
	</body>
	<script type="text/javascript">
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
                my_post("/send_code",{phone:phone,type:2},function(data){
                    if(data.message=='success'){
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
