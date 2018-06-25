<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>Transaction details</title>
    <link rel="stylesheet" type="text/css" href="/home_us/css/base.css"/>
    <script type="text/javascript" src="/home_us/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="/home_us/js/meta.js"></script>
    <link rel="stylesheet" href="/home_us/css/deal.css"/>
    <link rel="stylesheet" href="/home_us/libs/layer_mobile/need/layer.css"/>
    <script type="text/javascript" src="/home_us/libs/layer_mobile/layer.js"></script>
</head>
<body>
<!--header 开始-->
<div class="myheader">
    <div class="myheader_con">
        <i class="myreturn" onclick="history.back()"></i>
        <span class="mytitle">Transaction details</span>
    </div>
</div>
<!--header 结束-->
<div class="deal_con">
    <div class="deal_top">
        <a href="javascript:;" class="list" data-id="date">date</a>
        <a href="javascript:;" data-id="num">to buy</a>
        <a href="javascript:;" data-id="shop">Sell</a>
        <a href="javascript:;" data-id="ael">trade</a>
        <a href="javascript:;" data-id="complete">Completed</a>
    </div>
    <dl class="deal_con_con" id="date" style="display: block;">
        <dt><span>date</span><span>amount(OCC)</span><span>classify</span><span>state</span></dt>
        @foreach($all_deal as $v)
            <dd><span>{{date('Ymd',$v->create_time)}}</span><span>{{$v->num}}</span><span>@if($v->user_id == $user_id)
                        @if($v->type == 1)
            Purchase
                        @else
            Sell out
                        @endif
                    @else
                        @if($v->type == 1)
            Sell out
                        @else
          	Purchase
                        @endif
                    @endif
                    </span>
                <span>
                    @if($v->state == 0)
            Waiting
                        @elseif($v->state == 3)
            Successful
                    @elseif($v->state == 4)
            cancel
                        @else
            under way
                @endif
                </span></dd>
        @endforeach
    </dl>
    <dl class="deal_con_con num " id="num">
        <dt><span>Nickname</span><span>amount(OCC)</span><span>Price($)</span><span>cancel?</span><span>state</span></dt>
        @foreach($buy as $v)
            <dd><span>{{$v->nickname->nickname}}</span><span>{{$v->num}}</span><span>{{$v->price}}</span><span
                        order="{{$v->order}}" class="buy_cancel">cancel</span><span>Untraded</span></dd>
        @endforeach
    </dl>
    <script>
        $(".buy_cancel").click(function () {
            var order = $(this).attr('order');
            my_post('/deal/buy_cancel', {order: order}, function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        layer.open({shadeClose: false, content: '<p class="lock">Success</p>', btn: 'OK'});
                        window.location.href = "/deal/my_deal";
                    } else {
                        layer.open({
                            shadeClose: false,
                            content: '<p class="lock">' + data.data.message + '</p>',
                            btn: 'OK'
                        });
                    }
                } else {
                    layer.open({shadeClose: false, content: '<p class="lock">' + data.message + '</p>', btn: 'OK'});
                }
            });
        });
    </script>
    <dl class="deal_con_con num" id="shop">
        <dt><span>Nickname</span><span>amount(OCC)</span><span>Price($)</span><span>cancel?</span><span>state</span></dt>
        @foreach($sell as $v)
            <dd><span>{{$v->nickname->nickname}}</span><span>{{$v->num}}</span><span>{{$v->price}}</span><span
                        order="{{$v->order}}" class="sell_cancel">cancel</span><span>Untraded</span></dd>
        @endforeach
    </dl>
    <script>
        $(".sell_cancel").click(function () {
            var order = $(this).attr('order');
            my_post('/deal/sell_cancel', {order: order}, function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        layer.open({shadeClose: false, content: '<p class="lock">Success</p>', btn: 'OK'});
                        window.location.href = "/deal/my_deal";
                    } else {
                        layer.open({
                            shadeClose: false,
                            content: '<p class="lock">' + data.data.message + '</p>',
                            btn: 'OK'
                        });
                    }
                } else {
                    layer.open({shadeClose: false, content: '<p class="lock">' + data.message + '</p>', btn: 'OK'});
                }
            });
        });
    </script>
    <dl class="deal_con_con ael" id="ael">
        <dt><span>operation</span><span>Nickname</span>{{--<span>等级</span>--}}<span>OCC</span><span>Price($)</span><span>classify</span>
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
                               Purchase
                            @else
                                Sell out
                            @endif
                        @else
                            @if($v->type == 1)
                                Sell out
                            @else
                               Purchase
                            @endif
                        @endif
						</span>
                </label>
            </dd>
        @endforeach
    </dl>
    <dl class="deal_con_con num" id="complete">
        <dt><span>Nickname</span>{{--<span>等级</span>--}}<span>OCC</span><span>Price($)</span><span>classify</span></dt>
        @foreach($deal_finish as $v)
            <dd>
                <span>@if($v->user_id == $user_id){{$v->nickname->nickname}}@else{{$v->deal_nickname->nickname}}@endif</span>
                {{--<span>100</span>--}}
                <span>{{$v->num}}</span>
                <span>{{$v->price}}</span>
                <span>
                        @if($v->user_id == $user_id)
                        @if($v->type == 1)
                           Purchase
                        @else
                            Sell out
                        @endif
                    @else
                        @if($v->type == 1)
                            Sell out
                        @else
                           Purchase
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
                $("#img").html("<label style='color:#FFFFFF;'>upload<input class='img_class' type='file' style='display:none' name='upload_img'></label>");

                $("#deal_state").attr('tie', 'cancel');
                $("#deal_state").html('cancel');

            } else {
                $("#img").attr('tie', 'look');
                $("#img").html("view picture");

                $("#deal_state").attr('tie', 'confirm');
                $("#deal_state").html('Confirmation');

            }
        });
    </script>
    <div class="button">
        <a id="img" tie="" href="javascript:;">check</a>
        <a href="javascript:lock();">information</a>
        <a href="javascript:tousu();">Complaint</a>
        <a id="deal_state" tie="" href="javascript:;">cancel</a>
    </div>
    <p class="page">
      Total record：<b>0</b> &nbsp;
        total of<b>1</b>page
        <select>
            <option>1</option>
        </select>
    </p>
</div>
<p class="tishi">
    <strong>Transaction hints：</strong><br/>
In order to protect your capital security, please use your personal data in the binding of Alipay to pay the accounts receivable account to the other party, after payment, please upload the payment voucher pictures, such as the use of bindings account transactions outside the account, the system can not be supervised, will be detected as a false transaction. <br/>
The purchase of more than 5 or 5, the platform will charge a fee of 20%.
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
            layer.open({shadeClose: false, content: '<p class="lock">Please select the information to view</p>', btn: 'OK'});
            return;
        }
        var order = $("input[name='deal_order']:checked ").val();
        my_post('/deal/look_info', {order: order}, function (data) {
            if (data.code == 200) {
                if (data.data.state == 0) {
                    var html = "<p class='lock'><span>Nickname：</span>" + data.data.message.nickname + "<br/><span>Real name：</span>" + data.data.message.truename + "<br/><span>Alipay：</span>" + data.data.message.zfb_num + "<br/><span>WeChat：</span>" + data.data.message.weixin_num + "<br/></p>";
                } else {
                    var html = data.data.message
                }
            } else {
                var html = data.data.message
            }
            layer.open({
                shadeClose: false,
                title: ["Each other's information", 'background-color: #f9f9f9;border-bottom:1px solid #ddd'],
                content: html,
                btn: 'OK'
            });
        });
    }

    function tousu() {
        layer.closeAll();
        if ($('#ael input:checked').length < 1) {
            layer.open({shadeClose: false, content: '<p class="lock">Please select the user</p>', btn: 'OK'});
            return;
        }
        var html = '<textarea id="complaint_text"></textarea>';
        layer.open({
            className: 'tousu',
            shadeClose: false,
            title: ['Please enter the complaint content', 'background-color: #f9f9f9;border-bottom:1px solid #ddd'],
            content: html,
            btn: ['OK', 'cancel'],
            yes: function () {
                var order = $("input[name='deal_order']:checked ").val();
                var complaint_text = $("#complaint_text").val();
                my_post('/deal/complaint', {order: order, text: complaint_text}, function (data) {
                    if (data.code == 200) {
                        if (data.data.state == 0) {
                            var html = '<p class="lock">Success</p>';
                        } else {
                            var html = data.data.message
                        }
                    } else {
                        var html = data.data.message
                    }
                    layer.open({shadeClose: false, content: html, btn: 'OK'});
                });

            }
        });
        setTimeout(function () {
            $('.tousu .layui-m-layercont textarea').focus();
        }, 100)
    }

    function quxiao() {
        if ($('#ael input:checked').length < 1) {
            layer.open({shadeClose: false, content: '<p class="lock">Please select the information for the cancel transaction</p>', btn: 'OK'});
            return;
        }
        layer.open({
            shadeClose: false, content: '<p class="lock">Are you sure you want to deal with cancel?</p>', btn: ['Yes', 'cancel'], yes: function () {

                layer.open({shadeClose: false, content: '<p class="lock">Success</p>', btn: 'OK'});

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
            layer.open({content:'Please choose the order',skin:'msg',time:2});
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

                layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: 'OK'});
            },
            success: function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        var html = data.data.message

                        layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: 'OK'});
                        $('#deal_state').attr();
                        window.location.href = "/deal/my_deal";
                    } else {
                        var html = data.data.message;
                    }
                } else {
                    var html = data.message;
                }
                layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: 'OK'});
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
                        layer.open({shadeClose: false, content: '<p class="lock">Success</p>', btn: 'OK'});
                        window.location.href = "/deal/my_deal";
                    } else {
                        var html = data.data.message;
                        layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: 'OK'});
                    }
                } else {
                    var html = data.message;
                    layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: 'OK'});
                }
            });
        } else if (tie == 'cancel') {
            var order = $("input[name='deal_order']:checked ").val();
            my_post('/deal/deal_cancel', {order: order}, function (data) {
                if (data.code == 200) {
                    if (data.data.state == 0) {
                        var html = data.data.message

                        layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: 'OK'});
                        window.location.href = "/deal/my_deal";
                    } else {
                        var html = data.data.message;
                    }
                } else {
                    var html = data.message;
                }
                layer.open({shadeClose: false, content: '<p class="lock">' + html + '</p>', btn: 'OK'});
            });
        } else {
            layer.open({shadeClose: false, content: '<p class="lock">Please choose the order</p>', btn: 'OK'})
        }
    });
</script>
</html>
