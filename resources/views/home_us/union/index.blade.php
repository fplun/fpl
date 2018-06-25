<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Invite Friends</title>
		<link rel="stylesheet" type="text/css" href="/home_us/css/base.css"/>
		<script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js" ></script>
		<script type="text/javascript" src="/home_us/js/meta.js"></script>
		<link rel="stylesheet" href="/home_us/css/myfriend.css" />
		<script type="text/javascript" src="/home_us/libs/clipboard.min.js" ></script>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="history.back()"></i>
				<span class="mytitle">Invite Friends</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="myfriend_con">
			<div class="myfriend_top clearfix">
				<a href="/union/rank1" style="width:32%;margin-right:2%;">
					one-level friends<br />
					<b>{{$rank1}}</b>
					<span>details view &nbsp; →</span>
				</a>
				<a href="/union/rank2" style="width:32%;background-color:#b642dc;margin-right:2%;">
					two-level friends<br />
					<b>{{$rank2}}</b>
					<span>details view &nbsp; →</span>
				</a>
				<a href="/union/rank3" style="width:32%;background-color:#7444ff;">
					three-level friends<br />
					<b>{{$rank3}}</b>
					<span>details view &nbsp; →</span>
				</a>
			</div>
			<p class="fen">Exclusive sharing：<input id="copyUrlBtn"  type="button" value="copy"></p>
			<p class="fen con">
				The world's first YunLian block chain, which digs out digital assets intelligently, makes money every day：
				<br />
				{{$url}}
			</p>
			<button class="sub">Generate the invitation card sent to friends</button>
		</div>
		<style>
			.shade {
				width: 100%;
				height: 100%;
				background-color: rgba(0, 0, 0, 0.6);
				position: fixed;
				top: 0;
				left: 0;
				display: none;
			}

			.tc_wrap {
				max-width: 80%;
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translate(-50%, -50%);
				display: none;
			}

			.tc_wrap img {
				width: 100%;
			}
		</style>
		<div class="shade"></div>
		<div class="tc_wrap">
			<img id="my_img" src="/{{$img_url}}" alt="">
		</div>
	</body>
	<script>
		$(function(){
			 var btn = $('#copyUrlBtn')[0],
		        text = $('.con').text(),
		        zc = new ClipboardJS(btn);
		    zc.on('beforecopy', function(e){
		        zc.setText(text);
		    });
		    btn.onclick = function(){
		        layer.open({content: 'Success',skin: 'msg',time:2});
		    }

		})
        $(".shade").click(function () {
            $(".shade").hide();
            $(".tc_wrap").hide();
        });
		$('.sub').click(function () {
            $(".shade").show();
            $(".tc_wrap").show();
        })

	</script>
</html>
{{--Illuminate\Database\QueryException thrown with message "SQLSTATE[22007]: Invalid datetime format: 1366 Incorrect integer value: 'qrcodes/18-06-22/21a90046d29af8f2dd2ad41613cffa08.png' for column 'code_img' at row 1 (SQL: update `users` set `updated_at` = 2018-06-22 15:53:39, `code_img` = qrcodes/18-06-22/21a90046d29af8f2dd2ad41613cffa08.png where `id` = 6)"--}}
