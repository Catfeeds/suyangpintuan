var order=function(){
};
order.prototype={

    //收到货款
    receivedPay:function(order_id){
        $post({
            url:'/order/receivePay/'+order_id,
            callback:function(x){
                com.xshow('收到货款',x,{trueEvent:'e2-order-subReceivePay'});
                G('state_info').focus();
            }
        });
    },

    subReceivePay:function(){
        var orderid = G('order_id').value;
        var state_info = G('state_info').value;
        $post({
            url:'/order/subReceivePay',
            data:{orderid:orderid,state_info:state_info},
            callback:function(x){}
        });

    },

    //取消订单
    cancel:function(order_id){
        $post({
            url:'order/cancel/'+order_id,
            callback:function(x){
                com.xshow('取消订单',x,{trueEvent:'e2-order-doCancel'});
            }
        });
    },
    //提交取消订单信息.
    doCancel:function(){
        var Data = xForm.getFormValues('order-cancel-form');
        console.log(Data);
        $post({
            url:'order/doCancel/',
            data:Data,
            callback:function(x){

            }
        });
    },

    //发货管理
    send:function(){
        var Data = xForm.getFormValues('order-send-form');
        //console.log(Data);

        $post({
            url:'/order/doSend',
            data: Data,
            callback:function(x){

            }
        });

    },
    //选择关闭/取消订单原因.
    selCancelType:function(){
        var label=this._self;
        var ipt = $('input',label)[0];
        $('#state_info').val(ipt.value);
    },

    viewExpress:function(order_id){

        $post({
            url:'/order/viewExpress/'+order_id,
            callback:function(x){
                com.xshow('物流信息',x,{hideBtn:true});
            }
        });

    },

    //调整订单价格
    chPrice:function(order_id){
        $post({
            url:'/order/chPrice/'+order_id,
            callback:function(x){
                com.xshow('调整价格',x,{trueEvent:'e2-order-subNewPrice'});
                G('new_order_price').focus();
            }
        });
    },
    //提交调整后的新价格.
    subNewPrice:function(){
        var Data = xForm.getFormValues('chprice-form');
        //console.log(Data);

        $post({
            url:'/order/subNewPrice',
            data: Data,
            callback:function(x){

            }
        });
    },

    //关闭超过3天未付款的订单.
    close3day:function(){
        if(confirm("确认取消三天未付款的订单?"))$post({
            url:'/order/close3day',
            callback:function(x){

            }
        });
    }



};order=new order;