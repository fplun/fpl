$(function () {
    var html = [];
    html.push('<div class="leftswipe">');
    html.push('	<div class="top">');
    html.push('		<div class="img">');
    html.push('			<img src="/home_us/images/touxiang.jpg" />');
    html.push('		</div>');
    html.push('		<h3>Hello</h3>');
    html.push('		<p><i class="lixiang"></i>Online</p>');
    html.push('	</div>');
    html.push('	<ul class="leftnav">');
    html.push('		<li><a href="/index">Data digging</a></li>');
    html.push('		<li><a href="/shop/index">Miner mall</a></li>');
    html.push('		<li><a href="/miner/index">My Miner</a></li>');
    html.push('		<li><a href="/union/index">My team</a></li>');
    html.push('		<li><a href="/wallet/index">My wallet</a></li>');
    html.push('		<li><a href="/deal/index">Trading Center</a></li>');
    html.push('		<li><a href="/center/index">Personal</a></li>');
    html.push('		<li><a href="/center/read">YunLian white paper</a></li>');
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
