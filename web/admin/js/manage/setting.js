/*>>>>>setting设置*/
var setting=function(){

};
setting.prototype={
    //修改密码
    chpass:function(){
        var D={};
        $('#passForm.sitem').each(function(){D[this.id] = this.value.trim();});
        $post({url:'setting/subChpass',method:'post',data:D,callback:function(x){}});
    },

    //根据字段更新表单
    cfield:function(field){
        var D={field:field};
        $post({
            url:'setting/cfield',
            method:'post',
            data:D,
            callback:function(x){
                G('field_setup').innerHTML=x;
            }
        });
    },

    //测试邮箱
    testMail:function(){
        var mailto = $('#mailto').val();
        var D={
            mailto:mailto
        };
        $post({
            url:'setting/testMail',
            method:'post',
            data:D,
            callback:function(x){
                if(x=='1'){
                    com.xtip('测试邮件已经成功发送到：'+mailto);
                }else{
                    com.xtip(x,{type:1});
                }
            }
        });
    }

};setting = new setting;