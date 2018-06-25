<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>邀请好友</title>
		<link rel="stylesheet" type="text/css" href="/home/css/base.css"/>
		<script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="/home/libs/layer_mobile/layer.js" ></script>
		<script type="text/javascript" src="/home/js/meta.js"></script>
		<link rel="stylesheet" href="/home/css/myfriend.css" />
		<script type="text/javascript" src="/home/libs/clipboard.min.js" ></script>
	<body>
		<!--header 开始-->
		<div class="myheader">
			<div class="myheader_con">
				<i class="myreturn" onclick="history.back()"></i>
				<span class="mytitle">邀请好友</span>
			</div>
		</div>
		<!--header 结束-->
		<div class="myfriend_con">
			<div class="myfriend_top clearfix">
				<a href="friend.html">
					一级好友<br />
					<b>0</b>
					<span>点击查看明细 &nbsp; →</span>
				</a>
				<a href="friend.html" style="float: right; background-color:#b642dc;">
					二级级好友<br />
					<b>0</b>
					<span>点击查看明细 &nbsp; →</span>
				</a>
			</div>
			<p class="fen">我的分享：<input id="copyUrlBtn"  type="button" value="点击复制"></p>
			<p class="fen con">
				全球首款云链区块链，智能挖出数字资产，每天躺着赚钱：
				<br />
				http://fnc7.com/reg?id=711
			</p>
			<button class="sub">点击生成邀请卡片（截图发给好友）</button>
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
		        layer.open({content: '复制成功',skin: 'msg',time:2});
		    }
			
		})
		
	</script>
</html>
