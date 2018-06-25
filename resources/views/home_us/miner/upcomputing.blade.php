<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Improving computing power</title>
		<link rel="stylesheet" type="text/css" href="/home_us/css/base.css"/>
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home_us/js/meta.js"></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js"></script>
		<link rel="stylesheet" href="/home_us/css/deal.css" />
		<link rel="stylesheet" href="/home_us/css/upcomputing.css" />
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="window.location='/index'"></i>
				<span class="mytitle">Improving computing power</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="upcom">
			<h5>Energy purchase</h5>
			<div class="upcom_con">
				<select class="sele">
					@foreach($miner as $v)
					<option value="{{$v->price}}">VIPI-{{$v->yield}}Energy</option>
						@endforeach
				</select>
				<p>Use<span id="money">100</span>OCC to buy energy,get<span id="power">1</span>computing power！</p>
				<button class="buy">purchase</button>
			</div>
			
		</div>
		
		<div class="deal_con">
			<div class="deal_top">
				Details
			</div>
			<dl class="deal_con_con" id="date" style="display: block;">
				<dt><span>date</span><span>classify</span><span>computing power</span><span>state</span></dt>
				@foreach($my_miner as $v)
				<dd><span>{{$v->created_at}}</span><span>{{$v->class}}</span><span>{{$v->yield}}</span><span>{{$v->id}}</span></dd>
					@endforeach
			</dl>
			<p class="page">
				Total record：<b>{{ $my_miner->total() }}</b> &nbsp;
				total of<b>{{ $my_miner->lastPage() }}</b>page
				<select onchange='window.location = this.value'>
					@for($i=1;$i<=$my_miner->lastPage();$i++)
						<option @if ($i == request()->page) selected @endif value="{{ $my_miner->url($i) }}">{{ $i }}</option>
					@endfor
				</select>
			</p>			
		</div>
	</body>
	<script>
		$('.sele').change(function () {
			$('#money').html($('.sele').val());
			$('#power').html($('.sele').val()/100);
        })
		$('.buy').click(function () {
		    var id = $('.sele').val();
		    var token = "{{csrf_token()}}";
            $.post('/miner/buy_make/',{id:id,_token:token},function (data) {
				if (data.data.code == 2) {
                    layer.open({content: data.data.message, skin: 'msg', time: 2});
				}
				if (data.data.code == 1) {
                    layer.open({content: data.data.message, skin: 'msg', time: 2});
				    window.location.reload();
				}
            })
        })
	</script>
</html>
