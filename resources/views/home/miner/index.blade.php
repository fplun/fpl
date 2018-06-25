<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>我的矿机</title>
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
				<h1>我的矿机</h1>
				<b></b>
			</div>
		</div>
		<!--头部结束-->
		<!--主体内容开始-->
		<div class="mill_shop_wrap">
			<div class="mill_shop_box">
				<ul class="mill_shop_ul">
					@foreach($no_run as $v)
					<li class="mill_shop_li">
						<div class="kj_img">
							<img class="kj_photo" src="/uploads/{{$miner[$v->level - 1]}}"/>
						</div>
						<div class="text_box">
							<p class="text_box1">{{$v->class}}</p>
							<p class="text_box2 text_box4">矿机编号：{{$v->order}}</p>
							<p class="text_box2">租用到期时间：{{$v->cycle}}天</p>
						</div>
						<a class="mill_btn" href="/miner/run_make/{{$v->id}}">运行</a>
					</li>
					@endforeach

						@foreach($run as $v)
							<li class="mill_shop_li">
								<div class="kj_img">
									<img class="kj_photo" src="/uploads/{{$miner[$v->level - 1]}}"/>
								</div>
								<div class="text_box">
									<p class="text_box1">{{$v->class}}</p>
									<p class="text_box2 text_box4">矿机编号：{{$v->order}}</p>
									<p class="text_box2">租用到期时间：{{ceil(($v->die_time - time())/3600/24)}} 天</p>
								</div>
								<a class="mill_btn" href="/miner/run/{{$v->id}}">查看</a>
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

		@if (session('code')>1)
        layer.open({content:'{{ session('message') }}',skin:'msg',time:2});
		@elseif (session('code')==1)
            window.location.href='/miner/index';
		@endif

	</script>
</html>
