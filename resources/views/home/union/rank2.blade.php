<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>好友明细</title>
		<link rel="stylesheet" type="text/css" href="/home/css/base.css"/>
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home/js/meta.js"></script>
		<link rel="stylesheet" href="/home/css/deal.css" />
		<style>
			.deal_con .deal_top{ padding: 0 2rem; text-align: left;width: auto;}
			.deal_con .deal_con_con span:nth-child(1){ width:20%; }
			.deal_con .deal_con_con span:nth-child(2){ width:25%; }
			.deal_con .deal_con_con span:nth-child(3){ width:25%; }
			.deal_con .deal_con_con span:nth-child(4){ width:30%;}			
		</style>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="history.back()"></i>
				<span class="mytitle">好友明细</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="deal_con">
			<div class="deal_top">
				我的二级好友
			</div>
			<dl class="deal_con_con" id="date" style="display: block;">
				<dt><span>昵称</span><span>手机</span><span>算力</span><span>注册日期</span></dt>
				@foreach($friend as $v)
					<dd><span>{{$v->nickname}}</span><span>{{$v->phone}}</span><span>{{$v->power}}</span><span>{{$v->time}}</span></dd>
				@endforeach
			</dl>
			<p class="page">
				总记录：<b>{{ $friend->total() }}</b> &nbsp;
				共<b>{{ $friend->lastPage() }}</b>页
				<select onchange='window.location = this.value'>
					@for($i=1;$i<=$friend->lastPage();$i++)
						<option @if ($i == request()->page) selected @endif value="{{ $friend->url($i) }}">第{{ $i }}页</option>
					@endfor
				</select>
			</p>			
		</div>
	</body>
</html>
