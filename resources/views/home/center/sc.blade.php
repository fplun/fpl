<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>我的锁仓</title>
		<link rel="stylesheet" type="text/css" href="/home/css/base.css"/>
			<link rel="stylesheet" href="/home/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js" ></script>
		<script type="text/javascript" src="/home/js/meta.js"></script>
		<link rel="stylesheet" href="/home/css/deal.css" />
		<link rel="stylesheet" type="text/css" href="/home/css/gmqstyle.css"/>
		<style>
			.deal_con .deal_top{ padding: 0 2rem; text-align: left; width: auto;}
			.deal_con .deal_con_con span:nth-child(1){ width:20%; }
			.deal_con .deal_con_con span:nth-child(2){ width:30%; }
			.deal_con .deal_con_con span:nth-child(3){ width:30%; }
			.deal_con .deal_con_con span:nth-child(4){ width:20%;}	
			.deal_con .deal_top{height: 4.5rem;line-height: 4.5rem;}		
		</style>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="window.location='/center/index'"></i>
				<span class="mytitle">我的锁仓</span>
			</div>
		</div>
		<!--header 结束-->
		<form class="wdsc_form" action="/center/sc_make" method="post">
			{{ csrf_field() }}
			<ul class="wdsc_ul  mysc_ul">
				<li class="wdsc_li">
					<span class="apply_span">我的OCC数量：</span>
					<div class="apply_div">
						<p class="apply_p1">{{$my_occ}}</p>
					</div>
				</li>
				<li class="wdsc_li">
					<span class="apply_span mysc_span">锁仓OCC数量：</span>
					<div class="apply_div">
						<p class="apply_p1 mysc_p1">{{$sc_occ}}</p>
					</div>
				</li>
				<li class="wdsc_li">
					<span class="apply_span">申请锁仓数量：</span>
					<div class="apply_div">
						<input id="suoc" class="apply_p1 mysc_p2" name="sc_num" style="width:auto; text-align: left;" value="" placeholder="请输入锁仓数量" type="text" maxlength="10"/>
					</div>
				</li>
				<li class="wdsc_li">
					<p class="mysc_tips1">1、锁仓期限为30天；</p>
					<p class="mysc_tips1">2、申请数量为100的倍数；</p>
					<p class="mysc_tips1">3、利息每日发放；</p>
					<p class="mysc_tips1">4、申请提交后，不可撤销。</p>
				</li>
			</ul>
			<div class="wdsc_opt">
				<button class="wdsc_submit" type="submit"/>提交</button>
				<a onclick="window.location='/center/index'" class="wdsc_submit"/>返回</a>
			</div>			
		</form>
		<div class="deal_con">			
			<div class="deal_top">
				我的锁仓列表
			</div>
			<dl class="deal_con_con" id="date" style="display: block;">
				<dt><span>申请日期</span><span>数量</span><span>利息</span><span>结束日期</span></dt>

				@foreach($sc as $v)
				<dd><span>{{ date('Ymd',$v->start_time) }}</span><span>{{ $v->num }}</span><span>{{ $v->interest }}%</span><span>{{ date('Ymd',$v->end_time) }}</span></dd>
					@endforeach
			</dl>
			<p class="page">
				总记录：<b>{{ $sc->total() }}</b> &nbsp;
				共<b>{{ $sc->lastPage() }}</b>页
				<select onchange='window.location = this.value'>
					@for($i=1;$i<=$sc->lastPage();$i++)
					<option @if ($i == request()->page) selected @endif value="{{ $sc->url($i) }}">第{{ $i }}页</option>
						@endfor
				</select>
			</p>			
		</div>
	</body>
	<script>
        $(function () {
            $('.wdsc_submit').on('click', function () {
                event.stopPropagation();
                event.preventDefault();
                var sc = $('#suoc').val();
                if (!/^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/.test(sc) || Number(sc) <= 0) {
                    layer.open({content: '请正确填写锁仓数量', skin: 'msg', time: 2});
                    return;
                }
                $('.wdsc_form').submit();
            })
        })
		@if (count($errors) > 0)
        layer.open({content: '{{ $errors->first() }}', skin: 'msg', time: 2});
		@endif

		@if (session('code')>1)
        layer.open({content: '{{ session('message') }}', skin: 'msg', time: 2});
		@elseif (session('code')==1)
        layer.open({content: '{{ session('message') }}', skin: 'msg', time: 2});
            // window.location.href = '/login';
		@endif
	</script>
</html>
