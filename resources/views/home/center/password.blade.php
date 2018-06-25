<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>我的交易密码</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/home/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/home/css/gmqstyle.css"/>
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js"></script>
		<script type="text/javascript" src="/home/js/meta.js"></script>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="window.location='/center/index'"></i>
				<span class="mytitle">我的交易密码</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="wdsc_wrap">
			<div class="wdsc_img">全球首款实物区块链</div>
			<form class="wdsc_form" action="/center/password_make" method="post">
                {{ csrf_field() }}
				<ul class="wdsc_ul">
					<li class="wdsc_li">
						<span class="wdsc_span">原密码：</span>
						<div class="wdsc_inp">
							<input type="text" name="old_security" placeholder="原密码默认等于登录密码"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">新密码：</span>
						<div class="wdsc_inp">
							<input type="text" name="security" placeholder="新密码"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">再次输入：</span>
						<div class="wdsc_inp">
							<input type="text" name="security_confirmation" placeholder="再次输入"/>
						</div>
					</li>
				</ul>
				<div class="wdsc_opt">
					<button class="wdsc_submit" type="submit"/>提交</button>
					<a onclick="window.location='/center/index'" class="wdsc_submit"/>返回</a>
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
