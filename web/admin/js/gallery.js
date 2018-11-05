// JavaScript Document
var gallery=function(){
    this.reload_mark=true;
    this.init();
    this.upIndex=0;
};gallery.prototype={
    init:function(){
        var t=this;
        //将图片加入队列,初始化
        t.fileQueued=function(file){
            if($('#status_box_2.g-up').length==1)$('#status_box_2').html('');
            var el=document.createElement('div');
            el.id=file.id;
            //el.className='up-unit';
            el.className='u';
            el.setAttribute('tag_id',G('gallery_tag').value);
            var size=Math.round(file.size/102.4);
            size=size>10240?(Math.round(size/1024)/10)+'MB':(size/10)+'KB';
            el.innerHTML='<div class="u0"><i class="u1">'+file.name+'</i><i class="u2">等待上传</i><i class="u3">0%</i><i class="u4">'+size+'</i></div>';
            G('status_box_2').appendChild(el);
        }
        t.fileDialogComplete=function(){
            //alert('fileDialogComplete');
            this.startUpload();
        }
        //上传开始
        t.uploadStart=function(file){
            var swf=this.getStats();
            var str='正在上传 '+(swf.successful_uploads+1)+'/'+(swf.successful_uploads+swf.files_queued);
            //$('#upFileTip').html(str);
            $('#'+file.id+'.u2').addClass('loading').html('开始上传');
            //获取各种分类,然后设置参数
            this.setPostParams({tag_id:G(file.id).getAttribute('tag_id')});
            this.customSettings.progressCount = 0;
        }
        //上传进度
        t.uploadProgress=function(file, bytesLoaded, bytesTotal){
            var precent = Math.floor(bytesLoaded/bytesTotal*100);

            if(precent>0){
                $('#'+file.id+'.u0')[0].style.width=Math.floor(precent*5.42)+'px';
                //console.log('#'+file.id+" - "+Math.floor(precent*4.36)+'px');
            }
            $('#'+file.id+'.u2').html('上传中...');
            $('#'+file.id+'.u3').html(precent+'%');

            this.customSettings.progressCount++;
        }
        //上传完成
        t.uploadSuccess=function(file,serverData){
            t.reload_mark=true;
            //alert("server-data-back：\n"+serverData);//返回结果
            $('#'+file.id+'.u2').removeClass('loading').addClass('up-succ').html('上传完成');
            //console.log('#'+file.id+' auto!');
            $('#'+file.id+'.u3').html('100%');
            $('#'+file.id+'.u0')[0].style.width='auto';
        }
        t.uploadComplete=function(file){
            if(this.getStats().files_queued==0){
                com.xtip('所有图片已上传完成!');
                //$('#upFileTip').html('上传完成！');
            }
        }
    },


    submited:function(){
        com.xtip('添加成功！');
        com.xhide();
    },

    showUp:function(){
        var t=this;
        var ubox=$('#img-uploader.tab-mod')[1];
        if(ubox.innerHTML!='')return;
        $post({url:'/gallery/show_upload',data:{id:123},callback:function(x){
            ubox.innerHTML=x;
            $.loadJs('/admin/js/swf_up/swfupload.js',function(){
                $.loadJs('/admin/js/swf_up/swfupload.queue.js',function(){
                    $.loadJs('/admin/js/swf_up/swfupload.speed.js',function(){
                        //alert(ProductCates);
                        t.showUpBack('manage');
                    })})});
        }});
    },
    showUpBusiness:function(){
        var t=this;
        var ubox=$('#img-uploader.tab-mod')[1];
        if(ubox.innerHTML!='')return;
        $post({url:'/gallery/show_upload',data:{id:123},callback:function(x){
            ubox.innerHTML=x;
            $.loadJs('/admin/js/swf_up/swfupload.js',function(){
                $.loadJs('/admin/js/swf_up/swfupload.queue.js',function(){
                    $.loadJs('/admin/js/swf_up/swfupload.speed.js',function(){
                        //alert(ProductCates);
                        t.showUpBack('business');
                    })})});
        }});
    },
    initUploader:function(){
        var t=this;
        $.loadJs('/admin/js/swf_up/swfupload.js',function(){
            $.loadJs('/admin/js/swf_up/swfupload.queue.js',function(){
                $.loadJs('/admin/js/swf_up/swfupload.speed.js',function(){
                    //alert(ProductCates);
                    t.showUpBack();
                })})});
    },
    showUpBack:function(admin_type){
        
        var swfu = new SWFUpload({
            flash_url : "/admin/js/swf_up/swfupload.swf",
            upload_url: "/"+admin_type+"/gallery/upload",
            file_size_limit : "5 MB",
            file_types : "*.gif;*.jpg;*.jpeg;*.png",//*.*
            file_types_description : "图片",
            file_upload_limit : 100,
            file_queue_limit : 0,

            //debug: true,

            // Button settings
            button_image_url: '/admin/images/swfBtn.png',
            button_width: "70",
            button_height: "24",
            button_placeholder_id: "swfbtn_2",

            moving_average_history_size: 40,

            // The event handler functions are defined in handlers.js
            file_queued_handler : gallery.fileQueued,
            file_dialog_complete_handler: gallery.fileDialogComplete,
            upload_start_handler : gallery.uploadStart,
            upload_progress_handler : gallery.uploadProgress,
            upload_success_handler : gallery.uploadSuccess,
            upload_complete_handler : gallery.uploadComplete,
            //debug_handler : gallery.debug,
            button_cursor:-2,
            // 上传相关的一些变量
            file_post_name:'upFile'
        });
    },
    //将图片插入编辑器
    imgToEditor:function(){
        var url=$('img',this._self)[0].src;
        $('li',this._self.parentNode).removeClass('on');
        url=url.replace('_thumb','_src');
        $(this._self).addClass('on');
        var ipts=$('#imgCtrl input');
        ipts[0].value=url;
        [$('#imglib-box .tab-btn').removeClass('on')[0]].addClass('on');
        [$('.tab-mod',$('#imglib-box')[0].parentNode).hide()[0]].show();
        this.imgBaseSize(url);
    },
    //图片初始尺寸
    imgBaseSize:function(url){
        var ipts=$('#imgCtrl input');
        if(!url)url=ipts[0].value;
        var i=new Image;
        i.onload=function(){
            ipts[1].value=this.width;
            ipts[2].value=this.height;
        }
        i.src=url;
    },
    //添加图片到编辑器
    addImgToEdit:function(){
        var ipts=$('#imgCtrl>input');
        var align = '';
        $('#imglib-align input').each(function(){
            if(this.checked)align = this.value;
        });
        com.currentEditor.insertHtml('<img'+(align?' align="'+align+'"':'')+' src="'+ipts[0].value+'" width="'+ipts[1].value+'" height="'+ipts[2].value+'" alt="'+ipts[6].value+'" title="'+ipts[6].value+'" />');
        this.hidebox();
    },
    addToProductImg:function(img_id){
        var el = this._self;
        var imgsrc = $('>img',el)[0].src;
        var imgs = $('#product-imgs>img');
        var ipts = $('#product-imgs>input');
        var setedNum = $('#product-imgs.seted').length;

        if(setedNum==imgs.length){
            com.xtip("最多只能选择"+setedNum+'张',{type:1});return;
        }

        var issetNum = 0;

        for(var i=0;i<imgs.length;i++){
            if(ipts[i].value==img_id){
                com.xtip('同一张图片不能多次添加',{type:1});return;
            }
        }

        for(var i=0;i<imgs.length;i++){
            if(![ipts[i].parentNode].hasClass('seted')){
                imgs[i].src = imgsrc;
                [ipts[i].parentNode].addClass('seted');
                ipts[i].value = img_id;
                break;
            }
        }
        console.log('-----------------------');
    },
    inOpera:function(obj){
        if([obj].hasClass('seted')){
            $('.imgop',obj).addClass('showop');
        }else{
            $('.imgop',obj).removeClass('showop');
        }
    },
    outOpera:function(obj){
        $('.imgop',obj).removeClass('showop');
    },

    moveToPrev:function(){
        var ipts = $('#product-imgs>input');
        var imgs = $('#product-imgs>img');
        var selfEl = this._self.parentNode.parentNode.parentNode;
        var curr_ipt = $('>input',selfEl)[0];
        var curr_img = $('>img',selfEl)[0];
        var index = ipts.indexOf(curr_ipt);
        if(index==0)return;
        this.swapNode(ipts[index-1],curr_ipt);
        this.swapNode(imgs[index-1],curr_img);
        this.swapSeted(ipts[index-1].parentNode,curr_ipt.parentNode);
    },
    /**
     * 移动当前图片到下一张图片
     */
    moveToNext:function(){
        var ipts = $('#product-imgs>input');
        var imgs = $('#product-imgs>img');
        var selfEl = this._self.parentNode.parentNode.parentNode;
        var curr_ipt = $('>input',selfEl)[0];
        var curr_img = $('>img',selfEl)[0];
        var index = ipts.indexOf(curr_ipt);
        if(index==ipts.length-1)return;
        console.log();
        this.swapNode(ipts[index+1],curr_ipt);
        this.swapNode(imgs[index+1],curr_img);
        this.swapSeted(ipts[index+1].parentNode,curr_ipt.parentNode);
    },
    swapSeted:function(a,b){
        var hasa = [a].hasClass('seted');
        var hasb = [b].hasClass('seted');
        if(hasa && hasb)return;

        if([a].hasClass('seted')){
            [a].removeClass('seted');
            [b].addClass('seted');
        }else{
            [a].addClass('seted');
            [b].removeClass('seted');
        }
    },
    swapNode:function(node1,node2){
        var parent1 = node1.parentNode,parent2 = node2.parentNode;//父节点
        var t1 = node1.nextSibling,t2 = node2.nextSibling;
        //如果是插入到最后就用appendChild
        t1 ? parent1.insertBefore(node2,t1) : parent1.appendChild(node2);
        t2 ? parent2.insertBefore(node1,t2) : parent2.appendChild(node1);
    },
    /**
     * 移除产品图
     */
    rmProductImg:function(){
        var topEl = this._self.parentNode.parentNode;
        [topEl].removeClass('seted');
        $('>img',topEl)[0].src = '';
        $('>input',topEl).val('');
    },

    selImgPage:function(){
        var a=this._self,b=this._target;
        if(b.parentNode.tagName.toLowerCase()=='a')b=b.parentNode;
        if(b.tagName.toLowerCase()=='a'){
            if(b.rel!='')this.getSelImg(b.rel);
        }
    },
    getSelImg:function(page,tag_id){
        //var tag_id = $('#g_tags .on');
        //tag_id = tag_id.length==0 ? 1 : tag_id[0].rel;
        var D={page:page||1,list:1,tag_id:tag_id||1};
        console.log(D);
        $post({url:'/gallery/getImageList',method:'get',data:D,callback:function(x){
            G('galleryBox').innerHTML=x}})
    },
    imglib:function(){
        if(this.reload_mark){
            //重新加载
            this.getSelImg(1);
            this.reload_mark = false;
        }
    },
    hidebox:function(){
        $('#img-uploader').hide();
        $('#img-overlay').hide();
    },
    chTag:function(id){
        var el=this._self;
        //$('a',el.parentNode.parentNode).removeClass('on');
        //$(el).addClass('on');
        this.getSelImg(1,id);
    }

};gallery=new gallery;