<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>交易中心</title>
    <link rel="stylesheet" href="/home/css/base.css"/>
    <link rel="stylesheet" href="/home/libs/layer_mobile/need/layer.css"/>
    <link rel="stylesheet" href="/home/css/gmqstyle.css"/>
    <script type="text/javascript" src="/home/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="/home/js/meta.js"></script>
    <script type="text/javascript" src="/home/libs/layer_mobile/layer.js"></script>
    <script type="text/javascript" src="/home/js/leftswipe.js"></script>
    <script type="text/javascript" src="/home/js/echarts.js"></script>
</head>
<body style="background-color: #fff;">
<!--头部开始-->
<div class="index_top">
    <div class="index_top_con">
        <i id="leftswipes"></i>
        <h1>交易中心</h1>
        <b></b>
    </div>
</div>
<!--头部结束-->
<!--主体内容开始-->
<div class="mill_shop_wrap">
    <div class="chart_box">
        <div id="chart1" style="width: 100%;height: 100%;"></div>
    </div>
    <div class="trade_price">
        最新价格：<span>{{$top_data['price']}}CNY</span>
    </div>
    @if($deal_password == 1)
        <form class="tradeCenter_top">

            <input class="trade_input" id="deal_password" type="password" placeholder="交易密码"/>
            <button class="trade_button" type="button">提交</button>
        </form>
        <div class="tradeCenter_bottom">
            <div class="tradeCenter_tips">
                <p>交易规则提醒：</p>
                <p><span>1、</span><span>卖出、买入所有操作均无法撤销，确定需求后再卖出、买入；</span></p>
                <p><span>2、</span><span>买方买入后，必须在两小时之内完成款项拨付；</span></p>
                <p><span>3、</span><span>卖方收到款项后，必须在两小时之内完成款项确认；</span></p>
                <p><span>4、</span><span>买方、卖方必须保持注册手机畅通；</span></p>
                <p><span>5、</span><span>体验用户无法参与卖出、买入，如果您是第一次参与请联系推荐人；</span></p>
                <p><span>6、</span><span>违反第二、三条超过3次，系统自动永久性封停。</span></p>
            </div>
        </div>
    @else
        <div class="trade_wrap">
            <div class="trade_tab_box">
                <ul class="trade_tab">
                    <li class="trade_tab_li1 active" data-id="trade_center_box1"><a>买入OCC</a></li>
                    <li class="trade_tab_li2" data-id="trade_center_box2"><a>卖出OCC</a></li>
                </ul>
            </div>
            <div class="trade_center_box" id="trade_center_box1">
                <div class="trade_inp_box">
                    <ul class="trade_inp_div">
                        <li><input type="text" id="buy_num" placeholder="请输入要购买的数量"/></li>
                        <li><input type="text" id="buy_price" placeholder="请输入购买单价"/></li>
                    </ul>
                    <a class="trade_opt" id="buy_button">买入</a>
                </div>
                <script>
                    $("#buy_button").click(function(){
                        var num=$("#buy_num").val();
                        var price=$("#buy_price").val();
                        my_post('/deal/buy_make',{num:num,price:price},function(data){
                            if(data.code==200){
                                if(data.data.state==0){
                                    layer.open({content:data.data.message,skin:'msg',time:2});
                                    window.location.href="/deal/index";
                                }else{
                                    layer.open({content:data.data.message,skin:'msg',time:2});
                                }
                            }else{
                                layer.open({content:'网络连接错误',skin:'msg',time:2});
                            }
                        });
                    });
                </script>
                <table class="earnings_table">
                    <tr>
                        <th>昵称</th>
                        <th>等级</th>
                        <th>OCC</th>
                        <th>总价($)</th>
                        <th>状态</th>
                    </tr>
                    @foreach ($buy as $v)
                        <tr>
                            <td class="th1">{{$v->user->nickname}}</td>
                            <td>未激活</td>
                            <td>{{$v->num}}</td>
                            <td>{{$v->num*$v->price}}</td>
                            <td>
                                @if($v->state==0)
                                    <a order="{{$v->order}}"  class="table_btn2 buy_accept" >等待中</a>
                                @else
                                    <span class="table_btn" class="">交易中</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <script>
                $("body").on('click','.buy_accept',function(){
                    var order=$(this).attr('order');
                    my_post('/deal/buy_accept',{order:order},function(data){
                        if(data.code==200){
                            if(data.data.state==0){
                                layer.open({content:data.data.message,skin:'msg',time:2});
                                window.location.href="/deal/index";
                            }else{
                                layer.open({content:data.data.message,skin:'msg',time:2});
                            }
                        }else{
                            layer.open({content:'网络连接错误',skin:'msg',time:2});
                        }
                    });
                });
            </script>
            <div class="trade_center_box hide" id="trade_center_box2">
                <div class="trade_inp_box">
                    <ul class="trade_inp_div">
                        <li><input type="text" id="sell_num" placeholder="请输入要卖出的数量"/></li>
                        <li><input type="text" id="sell_price" placeholder="请输入卖出单价"/></li>
                    </ul>
                    <a class="trade_opt" id="sell_button">卖出</a>
                </div>
                <script>
                    $("#sell_button").click(function(){
                        var num=$("#sell_num").val();
                        var price=$("#sell_price").val();
                        my_post('/deal/sell_make',{num:num,price:price},function(data){
                            if(data.code==200){
                                if(data.data.state==0){
                                    layer.open({content:data.data.message,skin:'msg',time:2});
                                    window.location.href="/deal/index";
                                }else{
                                    layer.open({content:data.data.message,skin:'msg',time:2});
                                }
                            }else{
                                layer.open({content:'网络连接错误',skin:'msg',time:2});
                            }
                        });
                    });
                </script>
                <table class="earnings_table">
                    <tr>
                        <th>昵称</th>
                        <th>等级</th>
                        <th>OCC</th>
                        <th>总价($)</th>
                        <th>状态</th>
                    </tr>
                    @foreach ($sell as $v)
                        <tr>
                            <td class="th1">{{$v->user->nickname}}</td>
                            <td>未激活</td>
                            <td>{{$v->num}}</td>
                            <td>{{$v->num*$v->price}}</td>
                            <td>
                                @if($v->state==0)
                                    <a order="{{$v->order}}"  class="table_btn2 buy_accept" >等待中</a>
                                @else
                                    <span class="table_btn">交易中</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
</div>
<!--主体内容结束-->

</body>
<script type="text/javascript">
    $(".trade_tab li").on("click", function () {
        $(this).addClass("active").siblings("li").removeClass("active");
        var ids = $(this).attr("data-id");
        show1(ids);
    });

    function show1(id) {
        $("#" + id).show().siblings(".trade_center_box").hide();
    };

    function echarts_init() {
        var fonsize = Number($('html').css('fontSize').replace('px', ''));

        var myChart1 = echarts.init(document.getElementById('chart1'));
        option1 = {
            tooltip: {
                enterable: true,
                trigger: 'axis',
                axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                    type: 'line',       // 默认为直线，可选为：'line' | 'shadow'

                },
                formatter: "时间：{b}<br/>价格：{c}"
            },
            xAxis: {
                type: 'category',
                axisLine: {
                    lineStyle: {
                        type: 'solid',
                        color: '#d3d3d3',//左边线的颜色
                        width: '1'//坐标线的宽度
                    }
                },
                axisLabel: {
                    textStyle: {
                        color: '#333',//坐标值得具体的颜色
                    }
                },
                boundaryGap: false,
                data: {!! $day_all['time'] !!}/*['4月1日','4月2日','4月3日','4月4日','4月5日','4月6日','4月7日']*/
            },
            yAxis: {
                type: 'value',
                axisLine: {
                    lineStyle: {
                        type: 'solid',
                        color: '#fff',//左边线的颜色
                        width: '2'//坐标线的宽度
                    }
                },
                axisLabel: {
                    textStyle: {
                        color: '#333',//坐标值得具体的颜色
                    },
                    formatter: '{value}'
                }
            },
            grid: {
                top: 2.5 * fonsize,
                left: 5 * fonsize,
                right: 5 * fonsize,
                bottom: 5 * fonsize
            },
            series: [
                {
                    name: '最高气温',
                    type: 'line',
                    symbol: 'circle',
                    symbolSize: 6,
                    itemStyle: {
                        normal: {
                            color: "#226fdf",
                            lineStyle: {
                                color: "#226fdf"
                            }
                        }
                    },
                    data:{!!$day_all['max']!!},
                }
            ]
        };
        myChart1.setOption(option1);

        $(window).resize(function () {
            myChart1.resize();
        })
    }

    $(".trade_button").click(function () {
        var deal_password = $("#deal_password").val();
        $.post('/deal/deal_password', {deal_password: deal_password}, function (data) {
            if (data.data.state == 0) {
                window.location.reload();
            }else{
                layer.open({content: data.data.message, skin: 'msg', time: 2});
            }
        });
    });
    window.onload = echarts_init;
</script>
</html>
