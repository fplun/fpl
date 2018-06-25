<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>实名认证</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/home/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/home/css/gmqstyle.css"/>
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js" ></script>
		<script type="text/javascript" src="/home/js/meta.js"></script>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="history.back()"></i>
				<span class="mytitle">实名认证</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="wdsc_wrap">
			<form class="wdsc_form" action="/center/info_make" method="post" />
				{{ csrf_field() }}
				<ul class="wdsc_ul">
					<li class="wdsc_li">
						<span class="wdsc_span">身份证：</span>
						<div class="wdsc_inp">
							<input type="text" name="identity"
								   @if($info['is_perfect'] == 2)
								   value="{{ $info->identity }}" readonly
								   @else
								   value="{{ old('identity') }}"
								   @endif
							placeholder="身份证号码"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">姓名：</span>
						<div class="wdsc_inp">
							<input type="text" name="truename"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->truename }}" readonly
								   @else
								   value="{{ old('truename') }}"
								   @endif
								   placeholder="姓名"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">微信号码：</span>
						<div class="wdsc_inp">
							<input type="text" name="weixin_num"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->weixin_num }}" readonly
								   @else
								   value="{{ old('weixin_num') }}"
								   @endif
								   placeholder="微信号码"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">支付宝账号：</span>
						<div class="wdsc_inp">
							<input type="text" name="zfb_num"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->zfb_num }}" readonly
								   @else
								   value="{{ old('zfb_num') }}"
								   @endif
								   placeholder="支付宝账号"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">银行名称：</span>
						<div class="wdsc_inp">
							<input type="text" name="bankname"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->bankname }}" readonly
								   @else
								   value="{{ old('bankname') }}"
								   @endif
								   placeholder="银行名称"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">银行账号：</span>
						<div class="wdsc_inp">
							<input type="text" name="banknum"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->banknum }}" readonly
								   @else
								   value="{{ old('banknum') }}"
								   @endif
								   placeholder="银行账号"/>
						</div>
					</li>
					<li class="wdsc_li">
						<p class="wdsc_tips">提醒：提交后无法修改，否则影响交易收款。</p>
					</li>
				</ul>
			@if($info['is_perfect'] != 2)
				<div class="wdsc_opt">
					<button class="wdsc_submit" type="submit"/>提交</button>
					<a class="wdsc_submit"/>返回</a>
				</div>
				@endif
			</form>
		</div>
		
		
	</body>
	<script>

		@if (count($errors) > 0)
        layer.open({content:'{{ $errors->first() }}',skin:'msg',time:2});
		@endif

		@if (session('code')>1)
        layer.open({content:'{{ session('message') }}',skin:'msg',time:2});
		@elseif (session('code')==1)
            window.location.href='/center/info';
		@endif
        // $("#info_form").bind('submit',function(){
        //     ajaxSubmit(this, function(data){
        //         if(data.code!=400){
        //             if(data.message.state==0){
        //                 layer.open(data.message.message);
        //                 window.location.href='/center/info';
        //             }else{
        //                 layer.open(data.message.message);
        //             }
        //         }else{
        //             layer.open(data.message);
        //         }
        //     });
        //     return false;
        // });

	</script>
</html>

