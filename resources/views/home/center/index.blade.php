<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>个人中心</title>
		<link rel="stylesheet" href="/home/css/base.css" />
		<link rel="stylesheet" href="/home/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js" ></script>
		<script type="text/javascript" src="/home/js/meta.js" ></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js" ></script>
		<link rel="stylesheet" href="/home/css/myhome.css" />
		<script type="text/javascript" src="/home/js/leftswipe.js" ></script>
	</head>
	<body>
		<!--头部开始-->
		<div class="index_top">
			<div class="index_top_con">
				<i id="leftswipes"></i>	
				<h1>个人中心</h1>
				<b></b>
				<a href="/out" style="display: block; position: absolute; right:0; top: 0; height:5.5rem; width:6rem;font-size: 1.5rem;color:#fff;line-height: 5.5rem;text-align: center;">退出</a>
			</div>
		</div>
		<!--头部结束-->
		<!--主体内容开始-->
		<div class="content">
			<div class="banner">
				全球首款云区块链
			</div>
			<div class="user">
				<p>OCC会员等级：<span>体验用户</span></p>
				<p>网络算力等级：<span>未申请</span></p>
			</div>
			<ul class="myurl">
				<li><a href="/center/sc">我的锁仓<b>>>点击进入</b></a></li>
				<li><a href="/center/info">实名认证<b>>>点击进入</b></a></li>
				<li><a href="/center/sq">申请网络算力<b>>>点击进入</b></a></li>
				<li><a href="/center/password">我的交易密码<b>>>点击进入</b></a></li>
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
