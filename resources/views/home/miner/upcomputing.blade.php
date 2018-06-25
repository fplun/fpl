<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>提升算力</title>
		<link rel="stylesheet" type="text/css" href="/home/css/base.css"/>
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home/js/meta.js"></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js"></script>
		<link rel="stylesheet" href="/home/css/deal.css" />
		<link rel="stylesheet" href="/home/css/upcomputing.css" />
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="window.location='/index'"></i>
				<span class="mytitle">提升算力</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="upcom">
			<h5>能量购买</h5>
			<div class="upcom_con">
				<select class="sele">
					@foreach($miner as $v)
					<option value="{{$v->price}}">VIPI-{{$v->yield}}能量算力</option>
						@endforeach
				</select>
				<p>使用<span id="money">100</span>OCC购买能量，获得<span id="power">1</span>能量算力！</p>
				<button class="buy">购买</button>
			</div>
			
		</div>
		
		<div class="deal_con">
			<div class="deal_top">
				我的购买明细
			</div>
			<dl class="deal_con_con" id="date" style="display: block;">
				<dt><span>日期</span><span>算力分类</span><span>算力</span><span>状态</span></dt>
				@foreach($my_miner as $v)
				<dd><span>{{$v->created_at}}</span><span>{{$v->class}}</span><span>{{$v->yield}}</span><span>{{$v->id}}</span></dd>
					@endforeach
			</dl>
			<p class="page">
				总记录：<b>{{ $my_miner->total() }}</b> &nbsp;
				共<b>{{ $my_miner->lastPage() }}</b>页
				<select onchange='window.location = this.value'>
					@for($i=1;$i<=$my_miner->lastPage();$i++)
						<option @if ($i == request()->page) selected @endif value="{{ $my_miner->url($i) }}">第{{ $i }}页</option>
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
