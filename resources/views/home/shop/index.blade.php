<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>矿机商城</title>
		<link rel="stylesheet" href="/home/css/base.css" />
		<link rel="stylesheet" href="/home/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js" ></script>
		<script type="text/javascript" src="/home/js/meta.js" ></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js" ></script>
		<link rel="stylesheet" href="/home/css/gmqstyle.css" />
		<script type="text/javascript" src="/home/js/leftswipe.js" ></script>
	</head>
	<body>
		<!--头部开始-->
		<div class="index_top">
			<div class="index_top_con">
				<i id="leftswipes"></i>	
				<h1>矿机商城</h1>
				<b></b>
			</div>
		</div>
		<!--头部结束-->
		<!--主体内容开始-->
		<div class="mill_shop_wrap">
			<div class="mill_shop_box">
				<ul class="mill_shop_ul">
					<li class="mill_shop_li">
						<div class="kj_img">
							<img class="kj_photo" src="/home/images/kj_gy.png"/>
						</div>
						<div class="text_box">
							<p class="text_box1">公益云矿机</p>						
						</div>
						<a class="mill_btn">体验</a>
					</li>
					@foreach($miner as $v)
					<li class="mill_shop_li">
						<div class="kj_img">
							<img class="kj_photo" src="/uploads/{{$v->img}}"/>
						</div>
						<div class="text_box">
							<p class="text_box1">{{$v->class}}</p>
							<p class="text_box2">产量/小时：{{$v->yield/24}}</p>
							<p class="text_box2">每天产{{$v->yield}}    实际产{{$v->yield * $v->cycle}}</p>
							<p class="text_box2">运行周期：{{$v->cycle*24}}小时</p>
							<p class="text_box3">{{$v->price}} 云</p>
						</div>
						<a class="mill_btn" href="/shop/buy_make/{{$v->id}}">租用</a>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
		<!--主体内容结束-->		
		
	</body>
	<script>
		@if (count($errors) > 0)
        layer.open({content:'{{ $errors->first() }}',skin:'msg',time:2});
		@endif

		@if (session('code'))
        layer.open({content:'{{ session('message') }}',skin:'msg',time:2});
		{{--@elseif (session('code')==1)--}}
            {{--window.location.href='/shop/index';--}}
		@endif
	</script>
</html>
