<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>交易明细</title>
    <link rel="stylesheet" type="text/css" href="/home/css/base.css"/>
    <script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="/home/js/meta.js"></script>
    <link rel="stylesheet" href="/home/css/deal.css"/>
    <link rel="stylesheet" href="/home/libs/layer_mobile/need/layer.css"/>
    <script type="text/javascript" src="/home/libs/layer_mobile/layer.js"></script>
</head>
<body>
<!--header 开始-->
<div class="myheader">
    <div class="myheader_con">
        <i class="myreturn" onclick="history.back()"></i>
        <span class="mytitle">交易明细</span>
    </div>
</div>
<!--header 结束-->
<div class="deal_con">
    <div class="deal_top">
        <a href="javascript:;" class="list" data-id="date">日期</a>
        <a href="javascript:;" data-id="num">求购</a>
        <a href="javascript:;" data-id="shop">出售</a>
        <a href="javascript:;" data-id="ael">交易</a>
        <a href="javascript:;" data-id="complete">已完成</a>
    </div>
    <dl class="deal_con_con" id="date" style="display: block;">
        <dt><span>日期</span><span>个数(OCC)</span><span>类型</span><span>状态</span></dt>
        @foreach($all_deal as $v)
            <dd><span>{{date('Ymd',$v->create_time)}}</span><span>{{$v->num}}</span><span>@if($v->user_id == $user_id)
                        @if($v->type == 1)
                            买入
                        @else
                            卖出
                        @endif
                    @else
                        @if($v->type == 1)
                            卖出
                        @else
                            买入
                        @endif
                    @endif
                    </span>
                <span>
                    @if($v->state == 0)
                        等待中
                        @elseif($v->state == 3)
                        交易成功
                    @elseif($v->state == 4)
                        交易取消
                        @else
                        交易中
                @endif
                </span></dd>
        @endforeach
    </dl>
    <dl class="deal_con_con num " id="num">
        <dt><span>昵称</span><span>个数(OCC)</span><span>价格($)</span><span>是否取消</span><span>状态</span></dt>
        @foreach($buy as $v)
            <dd><span>{{$v->nickname->nickname}}</span><span>{{$v->num}}</span><span>{{$v->price}}</span><span
                        order="{{$v->order}}" class="buy_cancel">取消</span><span>未交易</span></dd>
        @endforeach
    </dl>
    <script>
        $(".buy_cancel").click(function () {
            var order = $(this).attr('order');
            my_post('/deal/buy_cancel', {order: order}, function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        layer.open({shadeClose: false, content: '<p class="lock">取消成功</p>', btn: '确定'});
                        window.location.href = "/deal/my_deal";
                    } else {
                        layer.open({
                            shadeClose: false,
                            content: '<p class="lock">' + data.data.message + '</p>',
                            btn: '确定'
                        });
                    }
                } else {
                    layer.open({shadeClose: false, content: '<p class="lock">' + data.message + '</p>', btn: '确定'});
                }
            });
        });
    </script>
    <dl class="deal_con_con num" id="shop">
        <dt><span>昵称</span><span>个数(OCC)</span><span>价格($)</span><span>是否取消</span><span>状态</span></dt>
        @foreach($sell as $v)
            <dd><span>{{$v->nickname->nickname}}</span><span>{{$v->num}}</span><span>{{$v->price}}</span><span
                        order="{{$v->order}}" class="sell_cancel">取消</span><span>未交易</span></dd>
        @endforeach
    </dl>
    <script>
        $(".sell_cancel").click(function () {
            var order = $(this).attr('order');
            my_post('/deal/sell_cancel', {order: order}, function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        layer.open({shadeClose: false, content: '<p class="lock">取消成功</p>', btn: '确定'});
                        window.location.href = "/deal/my_deal";
                    } else {
                        layer.open({
                            shadeClose: false,
                            content: '<p class="lock">' + data.data.message + '</p>',
                            btn: '确定'
                        });
                    }
                } else {
                    layer.open({shadeClose: false, content: '<p class="lock">' + data.message + '</p>', btn: '确定'});
                }
            });
        });
    </script>
    <dl class="deal_con_con ael" id="ael">
        <dt><span>操作</span><span>对方昵称</span>{{--<span>等级</span>--}}<span>OCC</span><span>价格($)</span><span>类型</span>
        </dt>
        @foreach($deal_buy as $v)
            <dd>
                <label>
						<span><input class="deal_radio" name="deal_order" type="radio" value="{{$v->order}}"
                                     @if($v->user_id==$user_id)
                                     @if($v->type==1)
                                     deal_type="1"
                                     @else
                                     deal_type="2"
                                     @endif
                                     @else
                                     @if($v->type==2)
                                     deal_type="1"
                                     @else
                                     deal_type="2"
                                    @endif
                                    @endif/></span>
                    <span>@if($v->user_id == $user_id){{$v->deal_nickname->nickname}}@else{{$v->nickname->nickname}}@endif</span>
                    <span>{{$v->num}}</span>
                    <span>{{$v->price}}</span>
                    <span>
						@if($v->user_id == $user_id)
                            @if($v->type == 1)
                                买入
                            @else
                                卖出
                            @endif
                        @else
                            @if($v->type == 1)
                                卖出
                            @else
                                买入
                            @endif
                        @endif
						</span>
                </label>
            </dd>
        @endforeach
    </dl>
    <dl class="deal_con_con num" id="complete">
        <dt><span>昵称</span>{{--<span>等级</span>--}}<span>OCC</span><span>价格($)</span><span>类型</span></dt>
        @foreach($deal_finish as $v)
            <dd>
                <span>@if($v->user_id == $user_id){{$v->nickname->nickname}}@else{{$v->deal_nickname->nickname}}@endif</span>
                {{--<span>100</span>--}}
                <span>{{$v->num}}</span>
                <span>{{$v->price}}</span>
                <span>
                        @if($v->user_id == $user_id)
                        @if($v->type == 1)
                            买入
                        @else
                            卖出
                        @endif
                    @else
                        @if($v->type == 1)
                            卖出
                        @else
                            买入
                        @endif
                    @endif
                    </span>
            </dd>
        @endforeach

    </dl>
    <script>
        $(".deal_radio").change(function () {
            var order = $(this).val();
            var deal_type = $(this).attr('deal_type');
            if (deal_type == 1) {
                $("#img").attr('tie', 'upload');
                $("#img").html("<label style='color:#FFFFFF;'>点击上传<input class='img_class' type='file' style='display:none' name='upload_img'></label>");

                $("#deal_state").attr('tie', 'cancel');
                $("#deal_state").html('取消交易');

            } else {
                $("#img").attr('tie', 'look');
                $("#img").html("查看图片");

                $("#deal_state").attr('tie', 'confirm');
                $("#deal_state").html('确认收款');

            }
        });
    </script>
    <div class="button">
        <a id="img" tie="" href="javascript:;">点击查看</a>
        <a href="javascript:lock();">对方信息</a>
        <a href="javascript:tousu();">投诉</a>
        <a id="deal_state" tie="" href="javascript:;">取消交易</a>
    </div>
    <p class="page">
        总记录：<b>0</b> &nbsp;
        共<b>1</b>页
        <select>
            <option>第1页</option>
        </select>
    </p>
</div>
<p class="tishi">
    <strong>交易提示：</strong><br/>
    为保护您的资金安全，请使用您个人资料内绑定的支付宝方式向对方绑定的收款账号内付款，付款后请上传付款凭证图片，如使用绑定账户以外的账户交易，系统无法监管，会检测为虚假交易。<br/>
    购买5个以上包含5个，平台会收取20%的手续费。
</p>


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
    <img id="my_img" src="" alt="">
</div>
</body>
<script>
    $(function () {
        $('.deal_top a').click(function () {
            $('.deal_top a').removeClass('list');
            $(this).addClass('list');
            var id = $(this).attr('data-id');
            $('.deal_con_con').hide();
            $('#' + id).show();
            if (id == 'ael') {
                $('.button').show();
                $('.tishi').show();
            } else {
                $('.button').hide();
                $('.tishi').hide();
            }
        })
    })

    function lock() {
        layer.closeAll();
        if ($('#ael input:checked').length < 1) {
            layer.open({shadeClose: false, content: '<p class="lock">请选择要查看的信息</p>', btn: '确定'});
            return;
        }
        var order = $("input[name='deal_order']:checked ").val();
        my_post('/deal/look_info', {order: order}, function (data) {
            if (data.code == 200) {
                if (data.data.state == 0) {
                    var html = "<p class='lock'><span>昵称：</span>" + data.data.message.nickname + "<br/><span>真实姓名：</span>" + data.data.message.truename + "<br/><span>支付宝账号：</span>" + data.data.message.zfb_num + "<br/><span>微信：</span>" + data.data.message.weixin_num + "<br/></p>";
                } else {
                    var html = data.data.message
                }
            } else {
                var html = data.data.message
            }
            layer.open({
                shadeClose: false,
                title: ['对方信息', 'background-color: #f9f9f9;border-bottom:1px solid #ddd'],
                content: html,
                btn: '确定'
            });
        });
    }

    function tousu() {
        layer.closeAll();
        if ($('#ael input:checked').length < 1) {
            layer.open({shadeClose: false, content: '<p class="lock">请选择用户</p>', btn: '确定'});
            return;
        }
        var html = '<textarea id="complaint_text"></textarea>';
        layer.open({
            className: 'tousu',
            shadeClose: false,
            title: ['请输入投诉内容', 'background-color: #f9f9f9;border-bottom:1px solid #ddd'],
            content: html,
            btn: ['确定', '取消'],
            yes: function () {
                var order = $("input[name='deal_order']:checked ").val();
                var complaint_text = $("#complaint_text").val();
                my_post('/deal/complaint', {order: order, text: complaint_text}, function (data) {
                    if (data.code == 200) {
                        if (data.data.state == 0) {
                            var html = '<p class="lock">投诉成功</p>';
                        } else {
                            var html = data.data.message
                        }
                    } else {
                        var html = data.data.message
                    }
                    layer.open({shadeClose: false, content: html, btn: '确定'});
                });

            }
        });
        setTimeout(function () {
            $('.tousu .layui-m-layercont textarea').focus();
        }, 100)
    }

    function quxiao() {
        if ($('#ael input:checked').length < 1) {
            layer.open({shadeClose: false, content: '<p class="lock">请选择要取消交易的信息</p>', btn: '确定'});
            return;
        }
        layer.open({
            shadeClose: false, content: '<p class="lock">您确定要取消交易吗</p>', btn: ['确定', '取消'], yes: function () {

                layer.open({shadeClose: false, content: '<p class="lock">取消成功</p>', btn: '确定'});

            }
        });
    }
</script>
<script>
    $(".shade").click(function () {
        $(".shade").hide();
        $(".tc_wrap").hide();
    });
    $("#img").click(function () {
        var tie = $(this).attr('tie');
        if (tie == 'upload') {
        } else if (tie == 'look') {
            var order = $("input[name='deal_order']:checked ").val();
            my_post('/deal/look_img', {order: order}, function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        $("#my_img").attr('src', '/' + data.data.message);
                        $(".shade").show();
                        $(".tc_wrap").show();
                    } else {
                        layer.open({content:data.data.message,skin:'msg',time:2});
                    }
                } else {
                    layer.open({content:data.data.message,skin:'msg',time:2});
                }
            });
        } else {
            layer.open({content:'请选择订单',skin:'msg',time:2});
        }
    });

    $("body").on('change', '.img_class', function () {
        var order = $("input[name='deal_order']:checked ").val();
        var formData = new FormData();
        formData.append('file', $(this)[0].files[0]);
        formData.append('order', order);
        $.ajax({
            type: "POST",
            url: '/deal/upload_img',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            error: function (request) {
                var html = request.message.message

                layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: '确定'});
            },
            success: function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        var html = data.data.message

                        layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: '确定'});
                        $('#deal_state').attr();
                        window.location.href = "/deal/my_deal";
                    } else {
                        var html = data.data.message;
                    }
                } else {
                    var html = data.message;
                }
                layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: '确定'});
            }
        });
    });


</script>
<script>
    $("#deal_state").click(function () {
        var tie = $(this).attr('tie');
        if (tie == 'confirm') {
            var order = $("input[name='deal_order']:checked ").val();
            my_post('/deal/deal_finish', {order: order}, function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        layer.open({shadeClose: false, content: '<p class="lock">交易成功</p>', btn: '确定'});
                        window.location.href = "/deal/my_deal";
                    } else {
                        var html = data.data.message;
                        layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: '确定'});
                    }
                } else {
                    var html = data.message;
                    layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: '确定'});
                }
            });
        } else if (tie == 'cancel') {
            var order = $("input[name='deal_order']:checked ").val();
            my_post('/deal/deal_cancel', {order: order}, function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        var html = data.data.message

                        layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: '确定'});
                        window.location.href = "/deal/my_deal";
                    } else {
                        var html = data.data.message;
                    }
                } else {
                    var html = data.message;
                }
                layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: '确定'});
            });
        } else {
            layer.open({shadeClose: false, content: '<p class="lock">请选择订单</p>', btn: '确定'})
        }
    });
</script>
</html>
