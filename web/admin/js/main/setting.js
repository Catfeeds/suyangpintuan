/*>>>>>setting设置*/
var setting=function(){
	this.CFields='';
};
setting.prototype={
	//修改密码
	chpass:function(){
		var D={};
		$('#passForm.sitem').each(function(){D[this.id] = this.value.trim();});
		$post({url:'setting/subChpass',method:'post',data:D,callback:function(x){}});
	},

    /**
     * 导入角色
     */
    importRole:function(){
        $post({
            url:'setting/importRole',
            callback:function(x){
                com.xshow('导入账号角色',x,{trueEvent:'e1-setting-subImportRole'});
                G('gAccount').focus();
            }
        });
    },
    subImportRole:function(){
        var account = G('gAccount').value.trim();
        $post({
            url:'setting/subImportRole',
            data:{account:account},
            callback:function(x){
                com.xshow('选择挂机角色',x,{trueEvent:'e2-setting-setAutoRole'});
            }
        });
    },

    setAutoRole:function(){
        var checkedNum = 0;
        var ids=[];
        $('#xauto-role>input').each(function(){
            if(this.type.toLowerCase()=='checkbox' && this.checked){
                checkedNum++;
                ids.push(this.value);
            }
        });
        var allowNum = G('allow_num').value;
        if(allowNum=='0'){
            com.xtip('不能再添加角色了.',{type:2});return;
        }
        if(checkedNum==0){
            com.xtip('请选择角色.',{type:2});return;
        }
        if(checkedNum>allowNum){
            com.xtip('当前最多只能设置['+allowNum+']个角色',{type:2});return;
        }
        $post({
            url:'/setting/setAutoRole',
            data:{ids:ids.join(',')},
            callback:function(x){}
        });
    }

};var setting=new setting;
/*setting设置<<<<<*/
//----------------*----------------*----------------*----------------*----------------*----------------*----------------*----------------*----------------#