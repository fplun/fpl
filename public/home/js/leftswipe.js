$(function () {
    var html = [];
    html.push('<div class="leftswipe">');
    html.push('	<div class="top">');
    html.push('		<div class="img">');
    html.push('			<img src="/home/images/touxiang.jpg" />');
    html.push('		</div>');
    html.push('		<h3>Hello</h3>');
    html.push('		<p><i class="lixiang"></i>Online</p>');
    html.push('	</div>');
    html.push('	<ul class="leftnav">');
    html.push('		<li><a href="/index">数据挖宝</a></li>');
    html.push('		<li><a href="/shop/index">矿机商城</a></li>');
    html.push('		<li><a href="/miner/index">我的矿机 </a></li>');
    html.push('		<li><a href="/union/index">我的团队</a></li>');
    html.push('		<li><a href="/wallet/index">我的钱包</a></li>');
    html.push('		<li><a href="/deal/index">交易中心</a></li>');
    html.push('		<li><a href="/center/index">个人信息</a></li>');
    html.push('		<li><a href="/center/read">云链白皮书中文版</a></li>');
    html.push('	</ul>');
    html.push('</div>');
    html.push('<div class="leftswipe_mark"></div>');
	$('body').append(html.join(''));
	/*侧滑栏*/
	$('#leftswipes').tap(function(){	
		$('.leftswipe').addClass('show');
		$('.leftswipe_mark').addClass('show');	
	});
	$('.leftswipe_mark').tap(function(){
		$('.leftswipe').removeClass('show');
		$('.leftswipe_mark').removeClass('show');
	})	
})
