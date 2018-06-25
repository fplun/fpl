<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>我的资产</title>
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
				<h1>我的资产</h1>
				<b></b>
			</div>
		</div>
		<!--头部结束-->
		<!--主体内容开始-->
		<div class="wdsc_wrap">
			<form class="wdqb_form">
				<ul class="wdqb_ul">
					<li class="wdqb_li">
						<span class="wdqb_span">钱包地址：</span>
						<div class="wdqb_inp">
							<p class="wdqb_inp_p">F71AE512371B4E43A6D6D47440</p>
						</div>
					</li>
					<li class="wdqb_li">
						<span class="wdqb_span">OOC数量：</span>
						<div class="wdqb_inp">
							<p class="wdqb_inp_p wdqb_inp_p2">{{$now_coin}}</p>
						</div>
					</li>
					<li class="wdqb_li">
						<span class="wdqb_span">锁仓数量：</span>
						<div class="wdqb_inp">
							<p class="wdqb_inp_p wdqb_inp_p2">{{$sc}}</p>
						</div>
					</li>
					<li class="wdqb_li">
						<span class="wdqb_span">ETH数量：</span>
						<div class="wdqb_inp">
							<p class="wdqb_inp_p wdqb_inp_p2">0</p>
						</div>
					</li>
				</ul>
				<div class="wdqb_opt">
					{{--<a class="wdqb_submit"/>复制地址</a>--}}
					<a class="wdqb_submit" href="/deal/my_deal" />明细</a>
					<a class="wdqb_submit"/>返回</a>
				</div>	
				<p class="wdqb_tips">转账提醒：体验用户无法转账。</p>
			</form>
		</div>
		<!--主体内容结束-->		
	</body>
</html>
