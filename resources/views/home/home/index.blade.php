<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>首页-云链</title>
		<link rel="stylesheet" href="/home/css/base.css" />
		<link rel="stylesheet" href="/home/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js" ></script>
		<script type="text/javascript" src="/home/js/meta.js" ></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js" ></script>
		<link rel="stylesheet" href="/home/css/index.css" />
		<script type="text/javascript" src="/home/js/leftswipe.js" ></script>
		<script type="text/javascript" src="/home/js/index.js" ></script>
	</head>
	<body>
		<!--头部开始-->
		<div class="index_top">
			<div class="index_top_con">
				<i id="leftswipes"></i>	
				<h1>云链</h1>
				<b></b>
				<a href="/out" style="display: block; position: absolute; right:0; top: 0; height:5.5rem; width:6rem;font-size: 1.5rem;color:#fff;line-height: 5.5rem;text-align: center;">退出</a>
			</div>
		</div>
		<!--头部结束-->
		<div class="content">
			<div class="banner">
				<div class="banner_body left">
					<p>数据挖宝</p>
					<h5>0</h5>
					<a href="/union/index">邀请好友&nbsp;→</a>
				</div>
				<div class="banner_body con">
					<p>OCC</p>
					<h5>{{$coin}}</h5>
					<a href="/union/index">邀请好友&nbsp;→</a>
				</div>
				<div class="banner_body right">
					<p>我的算力</p>
					<h5>{{$power+$jd_power}}GH/s</h5>
					<a href="/miner/upcomputing" style="letter-spacing: normal;">点击提升算力&nbsp;→</a>
				</div>
			</div>
			<div class="con_data">
				<canvas id="matrix"></canvas>
				<div class="text"><!--<span>矿池剩余数量：{{50000000 - $produce}}</span>--><span>挖矿已挖数量：{{$produce}}</span></div>
				<div class="wk">
					<p>运算中</p>
				</div>
				<div class="nums">
					<h2 ><b id="now_yield">{{$income}}</b></h2>
					<h3><span>我的算力:</span><b>{{$power+$jd_power}}</b>GH/s</h3>
					<h4><span>累计获得:</span><b id="sum_yield">{{$income}}</b>OCC</h4>
					<h5><span>全网算力:</span><b>{{$all}}</b>GH/s</h5>
				</div>
			</div>
			<div class="con_data_1">
				<h5>挖矿数据</h5>
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
					<li>我的算力排名<b>{{$rank}}</b></li>
					<li>我当前的挖宝算力<b>{{$power+$jd_power}}</b></li>
					<li>我的算力产出<b>{{$income}}</b></li>
					<li>挖矿合计=算力产出+锁仓生息<b>{{$all}}</b></li>
					<li>能量算力：<b>{{$power}}</b></li>
					<li>节点算力：<b>{{$jd_power}}</b></li>
					<li>网络算力：<b>0</b></li>
				</ul>
			</div>
			<div class="con_data_2">
				<h5>最新挖矿记录</h5>
				<dl>
					<dt><span>矿机名称</span><span>编号</span><span>运行时间</span><span>收入(OCC)</span></dt>
					@if(empty($log))
						<dd>无</dd>
					@else
					<dd><span>{{$log->class}}</span><span>{{$log->order}}</span><span>{{number_format((time()-$log->run_time)/3600,1)}}小时</span><span>{{number_format((time()-$log->run_time)*($log->yield/24/3600),8)}}</span></dd>
						@endif
				</dl>
			</div>
		</div>
	</body>
</html>
