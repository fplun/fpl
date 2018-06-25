<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>My lock house</title>
		<link rel="stylesheet" type="text/css" href="/home_us/css/base.css"/>
			<link rel="stylesheet" href="/home_us/libs/layer_mobile/need/layer.css" />
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js" ></script>
		<script type="text/javascript" src="/home_us/js/meta.js"></script>
		<link rel="stylesheet" href="/home_us/css/deal.css" />
		<link rel="stylesheet" type="text/css" href="/home_us/css/gmqstyle.css"/>
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
				<span class="mytitle">My lock house</span>
			</div>
		</div>
		<!--header 结束-->
		<form class="wdsc_form" action="/center/sc_make" method="post">
			{{ csrf_field() }}
			<ul class="wdsc_ul  mysc_ul">
				<li class="wdsc_li">
					<span class="apply_span">My OCC amount：</span>
					<div class="apply_div">
						<p class="apply_p1">{{$my_occ}}</p>
					</div>
				</li>
				<li class="wdsc_li">
					<span class="apply_span mysc_span">OCC amount：</span>
					<div class="apply_div">
						<p class="apply_p1 mysc_p1">{{$sc_occ}}</p>
					</div>
				</li>
				<li class="wdsc_li">
					<span class="apply_span">Apply amount：</span>
					<div class="apply_div">
						<input id="suoc" class="apply_p1 mysc_p2" name="sc_num" style="width:auto; text-align: left;" value="" placeholder="Apply lock house amount" type="text" maxlength="10"/>
					</div>
				</li>
				<li class="wdsc_li">
					<p class="mysc_tips1">1、the time limit for the lock is 30 days</p>
					<p class="mysc_tips1">2、The number of applications is 100.</p>
					<p class="mysc_tips1">3、Interest is issued on a daily basis;</p>
					<p class="mysc_tips1">4、When the application is submitted, it is irrevocable.</p>
				</li>
			</ul>
			<div class="wdsc_opt">
				<button class="wdsc_submit" type="submit"/>Submission</button>
				<a onclick="window.location='/center/index'" class="wdsc_submit"/>back</a>
			</div>			
		</form>
		<div class="deal_con">			
			<div class="deal_top">
				My lock list
			</div>
			<dl class="deal_con_con" id="date" style="display: block;">
				<dt><span>Application date</span><span>amount</span><span>Interest</span><span>End date</span></dt>

				@foreach($sc as $v)
				<dd><span>{{ date('Ymd',$v->start_time) }}</span><span>{{ $v->num }}</span><span>{{ $v->interest }}%</span><span>{{ date('Ymd',$v->end_time) }}</span></dd>
					@endforeach
			</dl>
			<p class="page">
				Total record：<b>{{ $sc->total() }}</b> &nbsp;
				total of<b>{{ $sc->lastPage() }}</b>page
				<select onchange='window.location = this.value'>
					@for($i=1;$i<=$sc->lastPage();$i++)
					<option @if ($i == request()->page) selected @endif value="{{ $sc->url($i) }}">{{ $i }}</option>
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
                    layer.open({content: 'Please fill in the number of the lock.', skin: 'msg', time: 2});
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
