var biz=function(){};biz.prototype={
    //新增商家分类
    addCate:function(){
        $post({
            url:'/biz/addCate',
            //dataType:'json',
            callback:function(x){
                com.xshow('新增分类',x,{trueEvent:'e2-biz-commitCateForm'});
                G('catename').focus();
            }
        });
    },
    //编辑分类
    editCate:function(id){
        $post({
            url:'/biz/editCate/'+id,
            //dataType:'json',
            callback:function(x){
                com.xshow('编辑分类',x,{trueEvent:'e2-biz-commitCateForm'});
                G('catename').focus();
            }
        });
    },
    //添加区域
    addZone:function(id){
        $post({
            url:'/biz/addZone',
            data:{parent:id||0},
            callback:function(x){
                com.xshow(typeof id=='undefined'?'新增区域':'新增子区域',x,{trueEvent:'e2-biz-commitCateForm'});
                G('catename').focus();
            }
        });
    },
    //编辑区域
    editZone:function(id,isSub){
        $post({
            url:'/biz/editZone/'+id,
            //dataType:'json',
            callback:function(x){
                com.xshow('编辑区域',x,{trueEvent:'e2-biz-commitCateForm'});
                G('catename').focus();
            }
        });
    },
    editSubZone:function(id){
        $post({
            url:'/biz/selSubZone/'+id,
            //dataType:'json',
            callback:function(x){
                G('subZone').innerHTML=x;
            }
        })
    },
    subZonePage:function(){

    },

    //提交商家分类,商家区域表单
    commitCateForm:function(){
        //检查分类名称是否为空.
        G('cateForm').submit();
    },
    //删除商家分类
    rmCate:function(id){
        if(confirm('确定删除？'))$post({
            url:'/biz/rmCate/'+id,
            callback:function(x){
                main.refresh();
            }
        });
    },
    /**
     * 删除商家区域
     * @param id
     */
    rmZone:function(id){
        if(confirm('确定删除？'))$post({
            url:'/biz/rmZone/'+id,
            callback:function(x){
                main.refresh();
            }
        });
    },

    rmBiz:function(id){
        if(confirm('确定删除？'))$post({
            url:'/biz/rmBiz/'+id,
            callback:function(x){
                main.refresh();
            }
        });
    }


};biz=new biz;