/*>>>>>field模型字段*/
var field=function(){

};
field.prototype={

    //根据字段类型更新字段配置表单
    chang_field:function(type,id,nofield){
        if(type=='verify'){
            $('#fieldID').val('verifyCode');
            $('#fieldTR').hide();
            $('#nameID').val('验证码');
        }else{
            $('#fieldTR').css('display','');
        }

        var D={type:type,id:id?id:'',nofield:nofield?true:false};
        $post({
            url:'field/chang_field',
            method:'post',
            data:D,
            callback:function(x){
                $('#field_step').html(x);
            }
        });
    }

};field = new field;