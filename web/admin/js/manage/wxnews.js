var wxnews=function(){
    //this.addItemIndex = 2;
    this.reset();
};
wxnews.prototype={

    reset:function(){
        this.addItemIndex = 1;
        this.editIndex = 1;

        //微信编辑的数据
        this.TextData = {};
        //图片ID,用于提交上传
        this.imgData = {};
        this.srclinkData = {};
        this.coverInDetailData = {};//封面是否显示在正文中.


        //缩略图,用于显示
        this.imgThumb = {};
    },

    //导入多图文编辑的信息.
    initMutilNews:function(mutil_news_data){
        //console.log(mutil_news_data);
        if(typeof mutil_news_data.items=='undefined')return;

        var t=this;
        var index = 0;
        t.reset();

        mutil_news_data.items.each(function(){
            t.TextData[t.editIndex] = this.content;
            t.imgData[t.editIndex] = this.cover;
            t.srclinkData[t.editIndex] = this.src_link;
            t.imgThumb[t.editIndex] = this.imgurl_thumb;
            t.coverInDetailData[t.editIndex] = this.cover_in_detail=='1'?true:false;

            if(index++==0){
                if(typeof this.imgurl_src!='undefined' && this.imgurl_src!=''){
                    $('#wx-msg1 img')[0].src = this.imgurl_src;
                    $('#wx-msg1 .msg-thumb-wrp').addClass('has-thumb');
                }
                if(this.title!='')$('#wx-msg1 .msg-title a')[0].innerHTML = this.title;
            }else{
                t.addmsg(this);
            }

            t.editIndex++;
        });

        t.initImportFlag = true;//!!
        t.editItem(1,G('initEditBtn'));
    },

    /**
     * 新增一条图文消息
     */
    addmsg:function(data){
        var len=$('#msg-board .msg-item').length;
        if(len>7){
            com.xtip('最多只可以加入8条图文消息',{type:1});return;
        }

        this.addItemIndex++;
        var index = this.addItemIndex;
        var newMsg = document.createElement('div');
        newMsg.id = 'wx-msg'+index;
        newMsg.className = 'msg-item'+(typeof data!='undefined' && typeof data.imgurl_src!='undefined'?' has-thumb':'');
        var html='<img class="msg-thumb"'+(
            typeof data!='undefined' && typeof data.imgurl_src!='undefined' ? ' src="'+data.imgurl_src+'"' : ''
            )+'>\
            <i class="msg-thumb-default">缩略图</i>\
            <h4 class="msg-title">\
            <a onclick="return false;" href="javascript:void(0);" target="_blank">'+(typeof data!='undefined' && typeof data.title!='undefined' ? data.title : '标题')+'</a>\
            </h4>\
            <div class="msg-mask">\
                <a href="javascript:;" class="ico-edit e2-wxnews-editItem-'+index+'"><i class="iconfont">&#xe603;</i></a>\
                <a href="javascript:;" class="ico-del e2-wxnews-rmItem-'+index+'"><i class="iconfont">&#xe606;</i></a>\
            </div>';
        newMsg.innerHTML = html;
        G('msg-board').appendChild(newMsg);
    },
    /**
     * 删除图文消息项
     * @param index
     */
    rmItem:function(index){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        var itemEl = this._self.parentNode.parentNode;
        var len = $('.msg-item',itemEl.parentNode).length;
        if(len==1){
            com.xtip('无法删除，多条图文至少需要2条消息。',{type:1});
            return;
        }
        $(itemEl).remove();

        if(typeof this.srclinkData[index]!='undefined')delete(this.srclinkData[index]);
        if(typeof this.TextData[index]!='undefined')delete(this.TextData[index]);
        if(typeof this.imgData[index]!='undefined')delete(this.imgData[index]);
        if(typeof this.imgThumb[index]!='undefined')delete(this.imgThumb[index]);

        this.editItem(1);
        //删除相关信息.
    },
    /**
     * 编辑图文消息
     * @param index
     */
    editItem:function(index,initEditBtn){

        initEditBtn = initEditBtn || this._self;
        var itemEl = initEditBtn.parentNode.parentNode,marginTop;

        if(index==1){
            marginTop = 0;
        }else{
            var pos = $.ePos(itemEl);
            var boxpos = $.ePos(G('msg-board'));
            marginTop = pos.y-boxpos.y;
        }
        var offsetTop = 300;
        if(marginTop>offsetTop){
            $('#edit_area .arrow').css('margin-top:'+offsetTop+'px');

            marginTop = marginTop-offsetTop;
        }else{
            $('#edit_area .arrow').css('margin-top:0');
        }


        //设置当前索引的文本内容:设置上次文本的内容.
        if(this.initImportFlag){
            this.initImportFlag = false;
        }else{
            var currentText = com.Keditor.html();
            this.setText(this.editIndex,currentText);
        }

        this.editIndex = index;
        $('#edit_area').css('margin-top:'+marginTop+'px');
        this.loadNews(index);
    },

    setText:function(index,data){
        data = data ? data : '';
        if(typeof this.TextData[index]=='undefined'){
            this.TextData[index] = data;
        }else{
            this.TextData[index] = data;
        }
    },
    /**
     * 加载图文消息数据
     * @param index
     */
    loadNews:function(index){
        var iTitle = $('#wx-msg'+index+' .msg-title a').html();
        if(iTitle=='标题')iTitle = '';

        if(typeof this.TextData[index]=='undefined'){
            this.TextData[index] = '';
        }
        var iText = this.TextData[index];

        $('#msg_title').val(iTitle);
        com.Keditor.html(iText);

        this.autuShowMiniThumb();
        //图片,编辑器,标题,摘要,原文链接
    },

    setTitle:function(obj){
        var news_id = '#wx-msg'+this.editIndex;
        $(news_id+' h4 a').html(obj.value=='' ? '标题' : obj.value);
        //G('newsTitle').innerHTML=obj.value=='' ? '标题' : obj.value;
    },
    setSrcLink:function(obj){
        if(typeof this.srclinkData[this.editIndex]=='undefined'){
            this.srclinkData[this.editIndex] = '';
        }
        this.srclinkData[this.editIndex] = obj.value.substr(0,1024);
    },
    setCoverInDetail:function(obj){
        if(typeof this.coverInDetailData[this.editIndex]=='undefined'){
            this.coverInDetailData[this.editIndex] = false;
        }

        this.coverInDetailData[this.editIndex] = obj.checked ? true : false;
    },


    /**
     * 自动显示当前编辑的图文是否有图片
     */
    autuShowMiniThumb:function(){
        if(typeof this.imgThumb[this.editIndex]!='undefined'){
            $('#up_thumb').addClass('up_thumb');
            $('#up_thumb img')[0].src = this.imgThumb[this.editIndex];
        }else{
            $('#up_thumb').removeClass('up_thumb');
            $('#up_thumb img')[0].src = '';
        }

        this.autoShowSrcLink();
        this.autoSetInDetail();
    },
    /**
     * 添加原始链接
     */
    addSrclink:function(){
        $('#src_link_area').addClass('has_src');
    },


    autoSetInDetail:function(){
        if(typeof this.coverInDetailData[this.editIndex]!='undefined' && this.coverInDetailData[this.editIndex]){
            G('cover_in_detail').checked = 'checked';
        }else{
            G('cover_in_detail').checked = '';
        }
    },
    /**
     * 自动显示当前编辑的图文是否有原文链接
     */
    autoShowSrcLink:function(){
        if(typeof this.srclinkData[this.editIndex]!='undefined'){
            if(this.srclinkData[this.editIndex]!=''){
                $('#src_link_area').addClass('has_src');
                $('#ipt_src_link').val(this.srclinkData[this.editIndex]);
                return;
            }
        }

        $('#src_link_area').removeClass('has_src');
        $('#ipt_src_link').val('');
    },

    /**
     * 删除当前编辑的图片
     */
    rmImg:function(){
        //需保存在用于提交的临时变量中.
        delete(this.imgData[this.editIndex]);
        $('#up_thumb').removeClass('up_thumb');
        $('#up_thumb img')[0].src = '';

        var editImg = $('#wx-msg'+this.editIndex+' img')[0];
        editImg.src = '';
        $(editImg.parentNode).removeClass('has-thumb');

        var el = G('wx_cover_'+this.editIndex);
        if(el)el.value = '';

        delete(this.imgData[this.editIndex]);
        delete(this.imgThumb[this.editIndex]);

    },

    //提交单图文消息
    submitAppMsg:function(){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        var Data = xForm.getFormValues('appmsg-form');
        Data.content = com.Keditor.html();
        //Data.cover = this.imgData[this.editIndex];

        $post({
            url:'/wxmedia/appmsg/create',
            data:Data,
            method:'post',
            callback:function(x){
                //alert(x);
            }
        });
    },

    /**
     * 提交多图文信息
     */
    submitMultiNews:function(){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        //设置最后编辑的内容
        var currentText = com.Keditor.html();
        this.setText(this.editIndex,currentText);

        var _title,_text,_imgId,_srclink,_indetail,TextObj = this.TextData;

        var data = {texts:[],covers:[],titles:[],srclinks:[],cover_in_detail:[],news_id:G('news_id').value};
        //todo:check hasOwnProperty


        for(var Index in TextObj){
            //if(typeof this.imgData[Index]=='undefined')continue;

            _text = TextObj[Index];
            _title = $('#wx-msg'+ Index+' .msg-title a').html();
            if(typeof this.imgData[Index]=='undefined'){
                com.xtip('必须插入一张图片',{type:1});return;
            }

            _imgId = this.imgData[Index];
            if(!this.checkTitleLen(_title)){
                //定位Index
                return;
            }
            //原文链接
            if(typeof this.srclinkData[Index]=='undefined'){
                _srclink = '';
            }else{
                _srclink = this.srclinkData[Index];
            }

            _indetail = 0;
            if(typeof this.coverInDetailData[Index]!='undefined' && this.coverInDetailData[Index]){
                _indetail = 1;
            }

            data.texts.push(_text);
            data.titles.push(_title);
            data.covers.push(_imgId);
            data.srclinks.push(_srclink);
            data.cover_in_detail.push(_indetail);
        }

        $post({
            url:'/wxmedia/appmsg/createMutil',
            data:data,
            type:'post',
            success:function(x){
                //alert(x);
            }
        });
    },
    /**
     * 检查标题长度
     * @param title
     * @returns {boolean}
     */
    checkTitleLen:function(title){
        var strlen=title.replace(/[^\x00-\xff]/g, "**").length;
        if(strlen==0 || strlen>64){
            com.xtip('标题为空或长度超过了64个字符',{type:1});
            return false;
        }
        return true;
    },

    //输入框内容改变,同时体现在对应的显示区.
    soulLink:function(obj, target){
        G(target).innerHTML = obj.value=='' ? '' : obj.value;
    },


    /**
     * 选择商品图
     */
    selImage:function(){
        var t=this;
        var self = t._self;
        upload.show({
            maxnum:1,//允许选择的图片数量.
            type:'image',
            category:'gallery',//所属分类.
            //返回的数据回调函数:data格式为:
            // [{ imgSrc:'原图路径',imgPath:'相对路径',imgName:'图片名称'}]
            callback:function(data){
                data.each(function(){
                    t.stuffWechatImg(this);
                });
            },
            //在选择的时候做处理
            onselect:function(data){
                console.log(data);
                var res = t.stuffWechatImg(data);
                if(res)upload.hidebox();
                return res;
            }
        });
    },
    /**
     * 图片上传完成后,处理显示
     */
    stuffWechatImg:function(data){
        var t=this;
        var imgId = data.imgID;
        var imgurl_src = data.imgSrc;
        var imgSize = data.imgSize;
        var img_name = data.imgName;
        var img_path = data.imgPath;
        var img_thumb = img_path.replace('_src.','_thumb.');

        var editImg = $('#wx-msg'+this.editIndex+' img')[0];
        editImg.src = imgurl_src;
        //t.wechatImageResize(editImg,imgurl_src,imgSize,180,90);

        $(editImg.parentNode).addClass('has-thumb');

        var el = G('wx_cover_'+this.editIndex);
        if(el)el.value = imgId;

        this.imgData[this.editIndex] = imgId;
        this.imgThumb[this.editIndex] = img_thumb;

        //设置显示缩略图
        this.autuShowMiniThumb();
        return true;
    },
    //将图片填充到商品图片div中.
    wechatImageResize:function(img,src,sizestr,width,height){
        var sizes = sizestr.split('x');
        if(sizes.length!=2)return;
        var w=sizes[0],h=sizes[1];

        if(width / w < height / h){
            height = Math.floor(width * h / w);
        }else{
            width = Math.floor(height * w / h);
        }
        //alert(height+'@'+width);
        img.src = src;
        img.width = width;
        img.height = height;
    },

    addsummary:function(){
        var parentBox = this._self.parentNode.parentNode;
        $(parentBox).addClass('has_src');
    },

    //设置封面是否显示于正文中.
    setCoverFlag:function(id){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        var is_show = [this._self].hasClass('sw-on') ? 1 : 0;

        $post({
            url:'/wxmedia/appmsg/setCoverFlag/'+id,
            data:{cover_in_detail:is_show},
            callback:function(x){

            }
        });
    },

    rmNews:function(news_id){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        if(confirm("确定删除该图文?"))$post({
            url:'/wxmedia/appmsg/rmNews/'+news_id,
            callback:function(x){}
        });
    }

};wxnews=new wxnews;