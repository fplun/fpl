<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>index-YunLian</title>
		<link rel="stylesheet" href="/home_us/css/base.css" />
		<link rel="stylesheet" href="/home_us/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js" ></script>
		<script type="text/javascript" src="/home_us/js/meta.js" ></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js" ></script>
		<link rel="stylesheet" href="/home_us/css/index.css" />
		<script type="text/javascript" src="/home_us/js/leftswipe.js" ></script>
		<script type="text/javascript" src="/home_us/js/index.js" ></script>
	</head>
	<body>
		<!--头部开始-->
		<div class="index_top">
			<div class="index_top_con">
				<i id="leftswipes"></i>	
				<h1>YunLian</h1>
				<b></b>
				<a href="/out" style="display: block; position: absolute; right:0; top: 0; height:5.5rem; width:6rem;font-size: 1.5rem;color:#fff;line-height: 5.5rem;text-align: center;">quit</a>
			</div>
		</div>
		<!--头部结束-->
		<div class="content">
			<div class="banner">
				<div class="banner_body left">
					<p>Data digging</p>
					<h5>0</h5>
					<a href="/union/index">Invite Friends&nbsp;→</a>
				</div>
				<div class="banner_body con">
					<p>OCC</p>
					<h5>{{$coin}}</h5>
					<a href="/union/index">Invite Friends&nbsp;→</a>
				</div>
				<div class="banner_body right">
					<p>Computing power</p>
					<h5>{{$power+$jd_power}}GH/s</h5>
					<a href="/miner/upcomputing" style="letter-spacing: normal;">Improving computing power&nbsp;→</a>
				</div>
			</div>
			<div class="con_data">
				<canvas id="matrix"></canvas>
				<div class="text"><!--<span>矿池剩余数量：{{50000000 - $produce}}</span>--><span>Excavated quantity：{{$produce}}</span></div>
				<div class="wk">
					<p>In operation</p>
				</div>
				<div class="nums">
					<h2 ><b id="now_yield">{{$income}}</b></h2>
					<h3><span>Computing power:</span><b>{{$power+$jd_power}}</b>GH/s</h3>
					<h4><span>Accumulative:</span><b id="sum_yield">{{$income}}</b>OCC</h4>
					<h5><span>Total computing power:</span><b>{{$all}}</b>GH/s</h5>
				</div>
			</div>
			<div class="con_data_1">
				<h5>Mining data</h5>
				<ul class="con_data_ul_bg">
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
				</ul>
				<ul class="con_data_ul">
					<li>My Ranking:<b>{{$rank}}</b></li>
					<li>Data digging:<b>{{$power+$jd_power}}</b></li>
					<li>My Produce:<b>{{$income}}</b></li>
					<li>Digits:<b>{{$all}}</b></li>
					<li>Energy Computing power：<b>{{$power}}</b></li>
					<li>Node Computing power：<b>{{$jd_power}}</b></li>
					<li>Network Computing power：<b>0</b></li>
				</ul>
			</div>
			<div class="con_data_2">
				<h5>Latest mining record</h5>
				<dl>
					<dt><span>Name</span><span>number</span><span>time</span><span>income(OCC)</span></dt>
					@if(empty($log))
						<dd>nothing</dd>
					@else
					<dd><span>{{$log->class}}</span><span>{{$log->order}}</span><span>{{number_format((time()-$log->run_time)/3600,1)}}小时</span><span>{{number_format((time()-$log->run_time)*($log->yield/24/3600),8)}}</span></dd>
						@endif
				</dl>
			</div>
		</div>
	</body>
</html>
