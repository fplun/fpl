<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>My transaction password</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/home_us/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/home_us/css/gmqstyle.css"/>
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js"></script>
		<script type="text/javascript" src="/home_us/js/meta.js"></script>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="window.location='/center/index'"></i>
				<span class="mytitle">My transaction password</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="wdsc_wrap">
			<div class="wdsc_img">The world's first physical block chain</div>
			<form class="wdsc_form" action="/center/password_make" method="post">
                {{ csrf_field() }}
				<ul class="wdsc_ul">
					<li class="wdsc_li">
						<span class="wdsc_span">Old Password：</span>
						<div class="wdsc_inp">
							<input type="text" name="old_security" placeholder="The default of the original password is equal to the login password"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">New password：</span>
						<div class="wdsc_inp">
							<input type="text" name="security" placeholder="New password"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">New password again：</span>
						<div class="wdsc_inp">
							<input type="text" name="security_confirmation" placeholder="New password again"/>
						</div>
					</li>
				</ul>
				<div class="wdsc_opt">
					<button class="wdsc_submit" type="submit"/>Submission</button>
					<a onclick="window.location='/center/index'" class="wdsc_submit"/>back</a>
				</div>				
			</form>
		</div>
		
	</body>
	<script type="text/javascript">
		@if (count($errors) > 0)
        layer.open({content:'{{ $errors->first() }}',skin:'msg',time:2});
		@endif

		@if (session('code')>1)
        layer.open({content:'{{ session('message') }}',skin:'msg',time:2});
		@elseif (session('code')==1)
            window.location.href='/login';
		@endif
	</script>
</html>
