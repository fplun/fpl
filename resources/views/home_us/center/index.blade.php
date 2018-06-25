<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>personal</title>
		<link rel="stylesheet" href="/home_us/css/base.css" />
		<link rel="stylesheet" href="/home_us/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js" ></script>
		<script type="text/javascript" src="/home_us/js/meta.js" ></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js" ></script>
		<link rel="stylesheet" href="/home_us/css/myhome.css" />
		<script type="text/javascript" src="/home_us/js/leftswipe.js" ></script>
	</head>
	<body>
		<!--头部开始-->
		<div class="index_top">
			<div class="index_top_con">
				<i id="leftswipes"></i>	
				<h1>personal</h1>
				<b></b>
				<a href="/out" style="display: block; position: absolute; right:0; top: 0; height:5.5rem; width:6rem;font-size: 1.5rem;color:#fff;line-height: 5.5rem;text-align: center;">quit</a>
			</div>
		</div>
		<!--头部结束-->
		<!--主体内容开始-->
		<div class="content">
			<div class="banner">
				The world's first cloud block chain
			</div>
			<div class="user">
				<p>OCC grade：<span>Experience users</span></p>
				<p>Computing power grade：<span>Not applying for</span></p>
			</div>
			<ul class="myurl">
				<li><a href="/center/sc">My lock house<b>>>Get into</b></a></li>
				<li><a href="/center/info">certification<b>>>Get into</b></a></li>
				<li><a href="/center/sq">Apply Computing power<b>>>Get into</b></a></li>
				<li><a href="/center/password">My transaction password<b>>>Get into</b></a></li>
			</ul>
		</div>
		<!--主体内容结束-->
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
