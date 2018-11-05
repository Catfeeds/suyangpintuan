//表单提示特效
function validTip(msg, o, cttl) {
    if (msg) {
        layer.tips(msg, o.obj, { tips: 1, tipsMore: false });
    }
}
//确认消息
function zz_confirm(msg, url, target) {
    layer.confirm(msg, { offset: '150px' }, function() {
        if (url) {
            if (target != undefined) {
                target.location.href = url;
            } else {
                location.href = url;
            }
        }
    });
}

$(".head_fx").height($(".top_fx").height());

$('.bodyright .br_return').click(function() {
    $('html,body').animate({
        scrollTop: '0px'
    }, 300); //返回顶部所用的时间 返回顶部也可调用goto()函数
});

//第三方登录
function oauth(url) {
    window.location.href = url;
}
//跳转
function tourl(url) {
    window.location.href = url;
}

//获取验证码 act操作类型
function getSmsVerify(act, btn, mobile,is_scode) {
    mobile = mobile ? mobile : $('#mobile').val();
    if (!mobile) {
        layer.alert('请输入手机号码', { icon: 0 });
        return;
    }
    btn = btn ? btn : '#btnSms';
    act = act ? act : 'sms_register';
    is_scode = is_scode ? is_scode : 2;
    var scode = $('#scode').val();
    var D = { act: act, mobile: mobile, scode: scode ,is_scode:is_scode};
    $.post('/welcome/sms', D,
        function(data) {

            var type = data.error;
            if (data.error == 0) {

                layer.alert(data.msg, { icon: 1, closeBtn: 0, title: 0 }, function(index) {
                    RemainTime(btn);
                    layer.close(index);
                    // layer.closeAll();
                });
            } else {
                if (data.msg) {
                    layer.alert(data.msg, { icon: 2, closeBtn: 0, title: 0 }, function(index) {
                        layer.close(index);
                        $('.refresh_scode').click();
                        $('#scode').val('').focus();
                    });
                }
            }

        }, 'json');
}
//短信验证码按钮倒计时
var iTime = zTime = 59;
var Account;

function RemainTime(btn) {
    $(btn).attr('disabled', true);
    var iSecond, sSecond = "",
        sTime = "";
    if (iTime >= 0) {
        iSecond = parseInt(iTime % 60);
        iMinute = parseInt(iTime / 60);
        if (iSecond >= 0) {
            if (iMinute > 0) {
                sSecond = iMinute + "分" + iSecond + "秒";
            } else {
                sSecond = iSecond + "秒";
            }
        }
        sTime = sSecond;
        if (iTime == 0) {
            clearTimeout(Account);
            sTime = '手机验证码';
            $(btn).attr('disabled', false);
            iTime = zTime;
        } else {
            Account = setTimeout(function() { RemainTime(btn); }, 1000);
            iTime = iTime - 1;
        }
    }
    $(btn).val(sTime);
}

//加入收藏
function addFav(id, obj) {
    $.post('/goods/addFav', { id: id }, function(result) {
        if (result.error == 1) {
            if (confirm('请先登陆！')) {
                location.href = '/member/login?back_url=' + location;
            }

        } else if (result.error == 2) {
            alert(result.msg);
        } else {
            if (result.type == 'insert') {                
                $(obj).addClass('active');                
            } else if (result.type == 'delete') {
                $(obj).removeClass('active');                 
            }
        }
    }, 'json');
}

//加入购物车
function addToCart(id, type, obj, act, qty,spec) {
    obj = obj ? obj : '';
    act = act ? act : '';
    qty = qty ? qty : 1;
    spec = spec ? spec : '';
    id = parseInt(id);

    $.post('/flow/add_cart', { id: id, qty: qty, act: act,spec:spec }, function(data) {
        if (data.error) {
            layer.alert(data.error, { icon: 8 });
        } else {
            if (type == 'buy') {
                location.href = '/flow/cart';
            } else if (type == 'tip') {
                layer.confirm('商品成功加入购物车！', {
                    offset: '150px',
                    icon: 1,
                    btn: ['去结算', '继续购物']
                }, function() {
                    location.href = '/flow/cart';
                }, function() {
                    if ($('.cartNum').length > 0) {
                        $('.cartNum').html(data.cartNum);
                    }
                    layer.close();
                    if ($('.shul').length > 0) {
                        $('.shul').hide();
                    }
                    if ($('.gouwu').length > 0) {
                        $('.gouwu').hide();
                    }
                });
            } else {
                var qty_new = parseInt($('#qty_' + id).val());
                if (act == '+') {
                    qty_new = qty_new + 1
                } else if (act == '-') {
                    qty_new = qty_new - 1
                } else if (act == '|') {}

                var data_id = $(obj).attr('data-id');
                var data_url = $(obj).attr('data-url');

                if (qty_new >= 0) {
                    $('#qty_' + id).val(qty_new);
                } else {
                    if (data_url == 'cart') {
                        $('#cart_' + id).remove();
                    }
                }

                $(obj).attr('onclick', clickVal);
                if ($('.cartNum').length > 0) {
                    $('.cartNum').html(data.cartNum);
                }

                if ($('#cart_subtotal_' + data_id).length > 0) {
                    $('#cart_subtotal_' + data_id).html(data.subtotal);
                }
                if ($('#cart_total').length > 0) {
                    $('#cart_total').html(data.cart_total);
                }

                var move_id = id;
                if (data_url == 'cart') {
                    move_id = data_id;
                }
                if (act == '-' && qty_new <= 0) {} else { MoveBox(obj, move_id, act); }

                // taggleCartNext(data.cart_total);
            }
        }
    }, "json");
}

function delCart(ids, goods_id) {
    $.post('/flow/del_cart/' + ids, { ajax: 1 }, function(data) {
        if (!data.error) {
            $('#cart_' + goods_id).remove();

            if ($('.cartNum').length > 0) {
                $('.cartNum').html(data.cartNum);
            }

            if ($('#cart_total').length > 0) {
                $('#cart_total').html(data.cart_total);
            }

            // taggleCartNext(data.cart_total);
        }
    }, 'json');
}

//立即购买促销商品prompt层，积分，秒杀，团购
function promptAuc(obj) {
    eval('data =' + $(obj).attr('data-param'));
    var input_num = 0;
    var num_str = '';
    var str = '购买数量';
    if (data.min_num > 0) {
        input_num = data.min_num;
        num_str += '最少' + data.min_num + data.unit + ' ';
    }
    if (data.max_num > 0) {
        if (data.min_num > 0) {
            input_num = data.min_num;
        } else {
            input_num = data.max_num;
        }
        num_str += '最多' + data.max_num + data.unit;
    }
    //积分兑换
    if (data.type == 5) {
        if (data.max_num == 0) {
            layer.msg('您的积分不足以兑换此商品', { icon: 0 });
            return;
        }
        str = '兑换数量';
    }
    layer.prompt({
        title: str + '（' + num_str + '）',
        value: input_num,
        formType: 0
    }, function(num) {
        var D = {
            type: data.type,
            id: data.id,
            qty: num
        };
        addCartGo(D);
    });
}
//加入直购车
function addCartGo(D) {
    $.post('/flow/buy', D, function(result) {
        if (result.error == 1) {
            layer.confirm('请先登陆！', {
                icon: 0,
                btn: ['去登陆', '关闭']
            }, function() {
                location.href = '/member/login?back_url=' + location;
            }, function() {
                layer.close();
            });
        } else if (result.error == 2) {
            layer.msg(result.msg, { icon: 0 });
        } else {
            location.href = '/flow/checkout';
        }
    }, 'json');
}

/**
 * 客服页面js 添加按钮
 * 注意 必须 父结构必须 包含class  top_fx > head  标题文字为 "客服中心" 然后再 see_articel下面 增加按钮
 * @param  {[type]} ){                 var head [description]
 * @return {[type]}     [description]
 */
/* $(function() {
    var head = $('.top_fx .head:first').html();
    if (head == '客服中心' && 　$('.see_articel').length) {
        $('.see1:first').prepend(
            '<input type="button" value="点击咨询在线客服" class="enter_s1 enter_w1 customer_service" style="margin:10px 0;"/>'
        );
    }
})
 */

/**
 * 点击触发多客服 2016-03-03
 */
$(document).on('click', '.customer_service', function() {
    var $this = $(this);

    if (!$this.data('action')) {
        $.ajax({
            url: '/wechat/customService',
            type: 'get',
            cache: false,
            dataType: 'json',
            beforeSend: function() {
                $this.data('action', 1);
            },
            success: function(result) {
                switch (result.error) {
                    case 0:
                        // 连接成功
                        layer.confirm(result.msg, {
                            icon: 1,
                            btn: ['现在关闭', '取消']
                        }, function() {
                            location.href = '/wechat/close';
                        }, function() {
                            layer.close();
                        });
                        break;
                    case 1:
                        layer.confirm('请先登陆！', {
                            icon: 0,
                            btn: ['去登陆', '关闭']
                        }, function() {
                            location.href = '/member/login?back_url=' + location;
                        }, function() {
                            layer.close();
                        });
                        break;
                    case 2:
                        layer.msg(result.msg, {
                            icon: 0
                        });
                        break;
                    default:
                        layer.msg('系统繁忙，请稍后重试', {
                            icon: 0
                        });
                }
            },
            error: function() {
                layer.msg('当前无在线客服，请稍后重试', {
                    icon: 0
                });
            },
            complete: function() {
                $this.data('action', 0);
            }
        });
    }
});

//团购下单 type:下单类型，num：数量，spec：属性，common_id：团ID，option:自选团参数
function ajax_cart(id,type,num,spec,common_id,option){
    mui.post("/flow/add_cart",{id:id,type:type,num:num,spec:spec,common_id:common_id,option:option}, function(data) {
        if (data.error=='success') {
            location.href='/flow/team/'+id;
        } else if (data.error=='subscribe'){
            $("#subscribe").removeClass('hide');
        }else {
            mui.toast(data.msg);
        }
    },'json');
}

$(document).on('tap', '.guanz', function() {  
        var _this = $(this);
        var sid=_this.data('sid');
        $.post("/store/ajax_guanz/"+sid, function(res) {
            var fav_num = parseInt($("#fans_num").html());
            if (res.error === 0) {    
                if(res.status==1){
                    $("#fans_num").html(fav_num+1);
                    _this.addClass("active");
                }else{
                    $("#fans_num").html(fav_num-1);
                    _this.removeClass("active");
                }     
            } else {
                layer.alert(res.msg, { icon: 2, closeBtn: 0, title: 0 },function(){
                    location.href="/member/login";
                });
            }
        }, 'json');
    });
