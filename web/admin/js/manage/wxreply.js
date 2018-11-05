var wxreply=function(){
};wxreply.prototype={
    //提交表单
    submitForm:function(){
        if(vor==1){
            com.xtip('无操作权限',{type:1});return;
        }
        var data = xForm.getFormValues('subform');
        $post({
            url:'wxreply/submitForm',
            method: 'post',
            data:data,
            callback:function(x){
            }
        });
    },
    //添加匹配关键字
    addKw:function(){
        var tb = G("kwlist"),
            tbRows=tb.rows.length,
            tr = tb.insertRow(tbRows);//插入行

        var td1 = tr.insertCell(0);
        var td2 = tr.insertCell(1);
        var td3 = tr.insertCell(2);
        var kwIndex = (new Date()).getTime();
        td1.innerHTML = '<input type="text" class="form-i LH22 w200"  name="kw['+kwIndex+']">';
        td2.innerHTML = '<input type="checkbox" value="1" name="match['+kwIndex+']">';
        td3.innerHTML = '<a href="javascript:;" class="uiBtn BtnOrange e2-wxreply-delKw"><i class="iconfont">&#xe606;</i></a>';

        $('input',td1)[0].focus();
    },
    //移除匹配关键字
    delKw:function(){
        [this._self.parentNode.parentNode].remove();
    },
    //提交匹配关键字
    submitKey:function(){
        var data = xForm.getFormValues('keyform');
        $post({
            url:'wxreply/submitkey',
            method: 'post',
            data:data,
            callback:function(x){ }
        });
    },
    //删除规则
    delrule:function(id){
        if(vor==1){
            com.xtip('无操作权限',{type:1});return;
        }
        if(confirm("确定删除？"))$post({
            url:'/wxreply/del/'+id,
            callback:function(x){}
        })
    },
    //图文回复
    getNewsList:function(page){
        $post({
            url:'/wxreply/getNewsList',
            method:'post',
            callback:function(x){
                com.xshow('选择图文',x,{hideBtn:true});
            }
        });
    },
    //文字回复
    setcontent:function(){
        $("#news").hide();
        $("#content").show();
    },
    //选择图文
    selectnews:function(id){
        $("#content").hide();
        $post({
            url:'/wxreply/selectNews/'+id,
            method:'post',
            callback:function(x){
                $('#newsid').val(id);
                $('#data_type').val(3);
                $("#news").html(x).show();
                com.xhide();
            }
        });
    },


    initEditReply:function(data){
        var type = $.getDataType(data.reply_list);
        var html = '';
        var t=this;
        if(type=='array'){
            data.reply_list.each(function(){
                var xdata = {
                    type:this.msg_type,
                    data:this.msg_type=='text'?this.content:this.msg_id
                };
                if(this.msg_type=='news'){
                    if(this.news_list.length==0)return;
                    if(this.news_list.length==1){
                        xdata.html = t.appmsgTpl(this.news_list[0]);
                        wxmenu.appendMsgBox(xdata);
                    }else{
                        xdata.html = t.appMutilMsgTpl(this.news_list);
                        wxmenu.appendMsgBox(xdata);
                    }
                }
                if(this.msg_type=='text'){
                    xdata.html = this.content;
                    wxmenu.appendMsgBox(xdata);
                }
                if(this.msg_type=='wheel'){
                    xdata.html = t.appmsgTpl(this.news_list[0]);
                    wxmenu.appendMsgBox(xdata);
                }
            });
        }
    },

    appmsgTpl:function(data){

        return '<div class="media_preview_area">\
            <div class="media_preview">\
            <div class="preview-box">\
                <div class="xmsg">\
                    <h4 class="mtitle"><a href="javascript:;" target="_blank">'+data.title+'</a></h4>\
                    <div class="msg-cover edit-area">\
                        <div class="msg-thumb-wrp has-thumb">\
                            <img class="msg-thumb" src="'+data.imgurl_src+'">\
                                <i class="msg-thumb-default">封面图片</i>\
                            </div>\
                        </div>\
                        <div class="msg-desc">'+data.desc+'</div>\
                    </div>\
                </div>\
            </div>\
            </div><div class="clear"></div>';
    },
    appMutilMsgTpl:function(data_list){

        var html='<div class="media_preview_area">\
            <div class="media-multi">\
            <div class="preview-box">';

        data_list.each(function(i){
            if(i==0){
                html+='<div class="msg-top">\
                    <div class="msg-info">\
                        <div class="msg-date"></div>\
                    </div>\
                    <div class="msg-cover edit-area">\
                        <div class="msg-thumb-wrp has-thumb">\
                            <img class="msg-thumb" src="'+this.imgurl_src+'">\
                                <i class="msg-thumb-default">封面图片</i>\
                                <h4 class="msg-title">\
                                    <a href="javascript:;" target="_blank">'+this.title+'</a>\
                                </h4>\
                            </div>\
                        </div>\
                    </div>';
            }else{
                html+='<div class="msg-item has-thumb"><img class="msg-thumb" src="'+this.imgurl_src+'">\
                        <h4 class="msg-title">\
                            <a href="javascript:void(0);" target="_blank">'+this.title+'</a>\
                        </h4>\
                    </div><div class="clear"></div>';
            }
        });
        html+='</div></div></div><div class="clear"></div>';
        return html;
    },

    textTpl:function(){

    },

    delRule:function(rule_id){
        if(vor==1){
            com.xtip('无操作权限',{type:1});return;
        }
        if(confirm("确定删除该规则?"))$post({
            url:'/wxreply/smartreply/delRule/'+rule_id,
            callback:function(x){

            }
        });
    }

};wxreply = new wxreply;