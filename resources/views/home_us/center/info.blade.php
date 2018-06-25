<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>certification</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/home_us/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/home_us/css/gmqstyle.css"/>
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js" ></script>
		<script type="text/javascript" src="/home_us/js/meta.js"></script>
	</head>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="history.back()"></i>
				<span class="mytitle">certification</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="wdsc_wrap">
			<form class="wdsc_form" action="/center/info_make" method="post" />
				{{ csrf_field() }}
				<ul class="wdsc_ul">
					<li class="wdsc_li">
						<span class="wdsc_span">ID card：</span>
						<div class="wdsc_inp">
							<input type="text" name="identity"
								   @if($info['is_perfect'] == 2)
								   value="{{ $info->identity }}" readonly
								   @else
								   value="{{ old('identity') }}"
								   @endif
							placeholder="ID card No."/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">Name：</span>
						<div class="wdsc_inp">
							<input type="text" name="truename"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->truename }}" readonly
								   @else
								   value="{{ old('truename') }}"
								   @endif
								   placeholder="Name"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">WeChat：</span>
						<div class="wdsc_inp">
							<input type="text" name="weixin_num"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->weixin_num }}" readonly
								   @else
								   value="{{ old('weixin_num') }}"
								   @endif
								   placeholder="WeChat"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">Alipay：</span>
						<div class="wdsc_inp">
							<input type="text" name="zfb_num"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->zfb_num }}" readonly
								   @else
								   value="{{ old('zfb_num') }}"
								   @endif
								   placeholder="Alipay"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">Bank name：</span>
						<div class="wdsc_inp">
							<input type="text" name="bankname"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->bankname }}" readonly
								   @else
								   value="{{ old('bankname') }}"
								   @endif
								   placeholder="Bank name"/>
						</div>
					</li>
					<li class="wdsc_li">
						<span class="wdsc_span">Bank account：</span>
						<div class="wdsc_inp">
							<input type="text" name="banknum"
								   @if($info['is_perfect'] == 2)
								   value = "{{ $info->banknum }}" readonly
								   @else
								   value="{{ old('banknum') }}"
								   @endif
								   placeholder="Bank account"/>
						</div>
					</li>
					<li class="wdsc_li">
						<p class="wdsc_tips">Reminder: no change can be made after submission, otherwise, the transaction will be affected.</p>
					</li>
				</ul>
			@if($info['is_perfect'] != 2)
				<div class="wdsc_opt">
					<button class="wdsc_submit" type="submit"/>Submission</button>
					<a class="wdsc_submit"/>back</a>
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

