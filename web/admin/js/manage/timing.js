/*>>>>>timing模型字段*/
var timing=function(){

};
timing.prototype={

    //批量解冻
    frozen:function(){
        var D={};
        $post({
            url:'timing/frozen',
            method:'post',
            data:D,
            callback:function(x){
                if(x != 0){
                    com.xtip(x,{type:1})
                }else{
                    com.xtip('批量解冻完成');
                }
                main.refresh();
            }
        });
    },

    //更新订单统计
    cron:function(){
        $post({
            url:'timing/ajaxCron',
            method:'post',
            data:{ajax:1},
            dataType:'html',
            callback:function(x){                
                $('#timingCron').html(x);
                timing.updateLog();
            }
        });
    },

    //更新订单统计
    updateLog:function(){
        $post({
            url:'timing/updateLog',
            method:'get',
            data:{ajax:2},
            dataType:'html',
            callback:function(x){
                $('#timingLog').html(x);
            }
        });
    },

    //清理数据库
    clear:function(){
        if(!confirm('确认清理这些数据！')) return;
        var D={};
        $post({
            url:'timing/clear',
            method:'post',
            data:D,
            callback:function(x){
                if(x != 0){
                    com.xtip(x,{type:1})
                }else{
                    com.xtip('数据清理完成');
                    main.refresh();
                }
            }
        });
    },

    //数据库更新
    updateDb:function(){
        if(!confirm('确认刚升级完网站并更新数据库！')) return;
        var D={};
        $post({
            url:'databases/updateDb',
            method:'post',
            dataType:'json',
            data:D,
            callback:function(x){
                com.xtip(x.html,{type: x.type})
            }
        });
    },

    //检查更新、安装更新
    checkUpdate:function(type){
        type = type ? type : 0;
        var box = '#updateBox';
        $(box).show();
        $(box+' .loading').show();
        var D={type:type};
        $post({
            url:'timing/checkUpdate',
            method:'post',
            data:D,
            dataType:'json',
            callback:function(x){
                if(type==0 || (type>0 && x.status>0)){
                    //检查更新
                    $(box+' .loading').hide();
                    $(box).html(x.html);
                }else{
                    //安装更新
                    cron.init('update',true,x);
                }
            }
        });
    }

};timing = new timing;