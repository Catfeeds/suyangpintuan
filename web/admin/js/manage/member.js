var member=function(){
};
member.prototype={

    search : function(page) {
        $('#page').val(page);
        xForm.submit('searchForm', {
            url:'member/index',
            callback:function(x){
                com.loadHtml(x);
            }
        });
    },

    searchlog : function(page) {
        $('#page').val(page);
        xForm.submit('searchForm', {
            url:'member/account_log',
            callback:function(x){
                com.loadHtml(x);
            }
        });
    },

    addForm : function (game_id) {
        $post({
            url:'/member/addForm',
            data : {'id' : game_id},
            callback:function(x){
                $('#tips').hide();
                com.xshow('添加游戏',x,{trueEvent:'e2-member-addGame-'+game_id, hideBtn : true});
                G('id').value = game_id;
                G('title').focus();
            }
        });
    },

    addGame : function() {
        xForm.submit('addForm',{
            url:'member/edit',
            callback:function(x){
                if (x == 1) {
                    com.xhide();
                    main.refresh();
                }
            }
        });
    },

    cfDelGame : function(id) {
        com.xshow('系统提示', '确定删除此游戏？', {trueEvent : 'e2-member-delGame-'+id});
    },

    delGame : function(id) {
        $post({
            url : '/member/delGame',
            data : {'id' : id},
            callback : function(x) {
                com.xhide();
                main.refresh();
            }
        })
    },

    batchAccount : function (){
        var ids = "";
        for (var i=0;i<checkboxs.length;i++) {
            var e=checkboxs[i];
            if(e.checked) ids = ids ? ids+','+e.value : e.value;
        }
        if(ids){
            if(confirm('您确定批量处理?'))$post({
                url:'/member/batch_account/',
                method:'get',
                data:{ids:ids,status:2},
                callback:function(x){
                }
            });
        }
    },

    online:function(act){
        act=act?act:'';
        $post({
            url:'/member/online',
            data:{ajax:1,act:act},
            callback:function(x){
                if(act=='bf') $('#online_BF').html(x);
                else $('#online').html(x);
            }
        });
    },

    selectBatch:function(value){
        $('.tpl').hide();
        if(value){
            $('#'+value+'tpl').show();
            $('#submit').html('搜索并批量操作');
        }else{
            $('#submit').html('搜索');
        }
    }

};member=new member;
