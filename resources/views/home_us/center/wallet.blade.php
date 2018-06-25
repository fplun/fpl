<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>My assets</title>
		<link rel="stylesheet" href="/home_us/css/base.css" />
		<link rel="stylesheet" href="/home_us/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js" ></script>
		<script type="text/javascript" src="/home_us/js/meta.js" ></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js" ></script>
		<link rel="stylesheet" href="/home_us/css/gmqstyle.css" />
		<script type="text/javascript" src="/home_us/js/leftswipe.js" ></script>
	</head>
	<body>
		<!--头部开始-->
		<div class="index_top">
			<div class="index_top_con">
				<i id="leftswipes"></i>	
				<h1>My assets</h1>
				<b></b>
			</div>
		</div>
		<!--头部结束-->
		<!--主体内容开始-->
		<div class="wdsc_wrap">
			<form class="wdqb_form">
				<ul class="wdqb_ul">
					<li class="wdqb_li">
						<span class="wdqb_span">Wallet address：</span>
						<div class="wdqb_inp">
							<p class="wdqb_inp_p">F71AE512371B4E43A6D6D47440</p>
						</div>
					</li>
					<li class="wdqb_li">
						<span class="wdqb_span">OOC amount：</span>
						<div class="wdqb_inp">
							<p class="wdqb_inp_p wdqb_inp_p2">{{$now_coin}}</p>
						</div>
					</li>
					<li class="wdqb_li">
						<span class="wdqb_span">Lock amount：</span>
						<div class="wdqb_inp">
							<p class="wdqb_inp_p wdqb_inp_p2">{{$sc}}</p>
						</div>
					</li>
					<li class="wdqb_li">
						<span class="wdqb_span">ETH amount：</span>
						<div class="wdqb_inp">
							<p class="wdqb_inp_p wdqb_inp_p2">0</p>
						</div>
					</li>
				</ul>
				<div class="wdqb_opt">
					{{--<a class="wdqb_submit"/>复制地址</a>--}}
					<a class="wdqb_submit" href="/deal/my_deal" />Detailed</a>
					<a class="wdqb_submit"/>Back</a>
				</div>	
				<p class="wdqb_tips">Transfer reminding: experience users can not transfer accounts.</p>
			</form>
		</div>
		<!--主体内容结束-->		
	</body>
</html>
