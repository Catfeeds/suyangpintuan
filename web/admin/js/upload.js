// JavaScript Document
var upload=function(){
    this.reload_mark=true;
    this.init();
    this.upIndex=0;
};upload.prototype={
    init:function(){
        var t=this;
        t.getCookies = function(){
            var postParams = {};
            var i, docCookie = document.cookie.split(';'),
                caLength = docCookie.length, c, eqIndex, name, value;
            for (i = 0; i < caLength; i++) {
                c = docCookie[i];

                // Left Trim spaces
                while (c.charAt(0) === " ") {
                    c = c.substring(1, c.length);
                }
                eqIndex = c.indexOf("=");
                if (eqIndex > 0) {
                    name = c.substring(0, eqIndex);
                    value = c.substring(eqIndex + 1);
                    postParams[name] = value;
                }
            }
            return postParams;
        };

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
            var postParams = t.getCookies();
            postParams.tag_id = G(file.id).getAttribute('tag_id');
            postParams.sid = G("sid").value;
            //图片分类
            postParams.category = t.params.category;
            //是否生成水印
            postParams.watermark = t.params.watermark;

            this.setPostParams(postParams);
            this.customSettings.progressCount = 0;
        }
        //上传进度
        t.uploadProgress=function(file, bytesLoaded, bytesTotal){
            var precent = Math.floor(bytesLoaded/bytesTotal*100);

            if(precent>0){
                $('#'+file.id+'.u0')[0].style.width=Math.floor(precent*4.36)+'px';
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

    //点击加载上传列表.
    showUp:function(){
        var t=this;
        var ubox=$('#img-uploader.tab-mod')[0];
        if(ubox.innerHTML!='')return;

        var url = this.params.type == 'image' ? '/upload/uploadBox' : '/upload/upfileBox';

        $post({url:url,data:{id:123,category: t.params.category},callback:function(x){
            ubox.innerHTML=x;
            $.loadJs('/admin/js/swf_up/swfupload.js',function(){
                $.loadJs('/admin/js/swf_up/swfupload.queue.js',function(){
                    $.loadJs('/admin/js/swf_up/swfupload.speed.js',function(){
                        //alert(ProductCates);
                        t.showUpBack();
                    })})});
        }});
    },
    //点击加载上传列表.
    showUpBusiness:function(){
        var t=this;
        var ubox=$('#img-uploader.tab-mod')[0];
        if(ubox.innerHTML!='')return;

        var url = this.params.type == 'image' ? '/upload/uploadBox' : '/upload/upfileBox';

        $post({url:url,data:{id:123,category: t.params.category},callback:function(x){
            ubox.innerHTML=x;
            $.loadJs('/admin/js/swf_up/swfupload.js',function(){
                $.loadJs('/admin/js/swf_up/swfupload.queue.js',function(){
                    $.loadJs('/admin/js/swf_up/swfupload.speed.js',function(){
                        //alert(ProductCates);
                        t.showUpBackBusiness();
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
    showUpBack:function(){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        var upload_url = this.params.type == 'image' ? '/manage/upload/upImage' : '/manage/upload/upFile';
        var file_types = this.params.file_types;
        if(typeof(file_types)=='undefined' || file_types==null)file_types = this.params.type == 'image'? '*.gif;*.jpg;*.jpeg;*.png' : '*.zip;*.pdf;*.7gz;*.doc;*.docx;*.xls;*.ppt;*.xlsx';        
        //var button_img_url = this.params.type == 'image' ? '/images/swfBtn.png' : '/images/swfFileBtn.png';

        var swfu = new SWFUpload({
            flash_url : "/admin/js/swf_up/swfupload.swf",
            upload_url: upload_url,
            file_size_limit : "50 MB",
            file_types : file_types,//*.*
            file_types_description : "图片",
            file_upload_limit : 100,
            file_queue_limit : 0,
            //debug: true,

            // Button settings
            button_image_url: '/admin/images/swfBtn1.gif',
            button_width: "70",
            button_height: "24",
            button_placeholder_id: "swfbtn_2",

            moving_average_history_size: 40,

            // The event handler functions are defined in handlers.js
            file_queued_handler : upload.fileQueued,
            file_dialog_complete_handler: upload.fileDialogComplete,
            upload_start_handler : upload.uploadStart,
            upload_progress_handler : upload.uploadProgress,
            upload_success_handler : upload.uploadSuccess,
            upload_complete_handler : upload.uploadComplete,
            //debug_handler : upload.debug,
            button_cursor:-2,
            // 上传相关的一些变量
            file_post_name:'upFile'
        });
    },
    showUpBackBusiness:function(){
        var upload_url = this.params.type == 'image' ? '/business/upload/upImage' : '/business/upload/upFile';
        var file_types = this.params.file_types;
        if(typeof(file_types)=='undefined' || file_types==null)file_types = this.params.type == 'image'? '*.gif;*.jpg;*.jpeg;*.png' : '*.zip;*.pdf;*.7gz;*.doc;*.docx;*.xls;*.ppt;*.xlsx';        
        //var button_img_url = this.params.type == 'image' ? '/images/swfBtn.png' : '/images/swfFileBtn.png';

        var swfu = new SWFUpload({
            flash_url : "/admin/js/swf_up/swfupload.swf",
            upload_url: upload_url,
            file_size_limit : "50 MB",
            file_types : file_types,//*.*
            file_types_description : "图片",
            file_upload_limit : 100,
            file_queue_limit : 0,
            //debug: true,

            // Button settings
            button_image_url: '/admin/images/swfBtn1.gif',
            button_width: "70",
            button_height: "24",
            button_placeholder_id: "swfbtn_2",

            moving_average_history_size: 40,

            // The event handler functions are defined in handlers.js
            file_queued_handler : upload.fileQueued,
            file_dialog_complete_handler: upload.fileDialogComplete,
            upload_start_handler : upload.uploadStart,
            upload_progress_handler : upload.uploadProgress,
            upload_success_handler : upload.uploadSuccess,
            upload_complete_handler : upload.uploadComplete,
            //debug_handler : upload.debug,
            button_cursor:-2,
            // 上传相关的一些变量
            file_post_name:'upFile'
        });
    },
    //将图片插入编辑器
    imgToEditor:function(){
        var url=$('img',this._self)[0].src;
        $('li',this._self.parentNode).removeClass('on');
        url=url.replace('_thumb','_middle');
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
        com.KEdit.insertHtml('<img'+(align?' align="'+align+'"':'')+' src="'+ipts[0].value+'" width="'+ipts[1].value+'" height="'+ipts[2].value+'" alt="'+ipts[6].value+'" title="'+ipts[6].value+'" />');
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
        var ipts = $('#product-imgs input');
        var imgs = $('#product-imgs img');
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
        var ipts = $('#product-imgs input');
        var imgs = $('#product-imgs img');
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
        var uri = this.params.type == 'image' ? "/upload/getImageList" : "/upload/getFileList";
        //var tag_id = $('#g_tags .on');
        //tag_id = tag_id.length==0 ? 1 : tag_id[0].rel;
        var D={page:page||1,list:1,category:this.params.category};
        if(typeof tag_id!='undefined')D.tag_id = tag_id;

        $post({url:uri,method:'get',data:D,callback:function(x){
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
    },

    /**
     * 初始化,调出上传框.
     * @param options
     */
    show:function(options){
        var params = {
            maxnum:1,//允许选择的最大数量.
            type:'image',//类型
            from:'editor',//从编辑器来,需要设置可显示编辑图片尺寸.
            category:'gallery',
            watermark:0,//添加水印
            callback:function(data){
                console.log(data);
            },
            //选择图片时的处理函数
            onselect:function(data){
                return true;
            }
        };
        params = Extend(params, options||{});
        //console.log(params);

        this.params = params;

        var uri = this.params.type == 'image' ? "upload/showBox" : "upload/showFileBox";

        $post({
            url:uri,
            data:{category:this.params.category},
            method:'get',
            callback:function(x){
                $('#img-uploader').html(x).show();
                $('#img-overlay').show();
            }
        });

    },

    /**
     * 获取图片属性
     * @param imageObj
     * @returns {{imgSrc: string, imgName: string, imgID: string}}
     */
    getImgAttr:function(imageObj){
        var imgsrc = imageObj.getAttribute('imgsrc');
        var imgname = imageObj.getAttribute('imgname');
        var imgid = imageObj.getAttribute('imgid');
        var imgsize = imageObj.getAttribute('imgsize');

        var options={imgSrc:imgsrc,imgName:imgname, imgID : imgid, imgSize:imgsize};
        if(imgsrc.indexOf('http://')!==-1){
            options.imgPath = imgsrc.substr(imgsrc.indexOf('/upload/'));
        }else{
            options.imgPath = imgsrc;
        }
        return options;
    },
    /**
     * 勾选图片
     */
    setChecked:function(){
        var result = true,t=this;
        /*if(typeof this.params.onselect=='function'){
            var options = t.getImgAttr(this._self);
            result = this.params.onselect(options);
        }*/

        if(result){
            if([this._self].hasClass('on')){
                [this._self].removeClass('on');
            }else{
                if($('#img-uploader .box_ul .on').length>=this.params.maxnum){
                    com.xtip('最多只能选择'+this.params.maxnum+'个',{type:2});
                }else{
                    [this._self].addClass('on');
                }
            }
        }
    },
    /**
     * 获取选中的图片信息.
     */
    getCheckedItem:function(){
        var imgs=[],domain=document.domain,t=this;
        $('#img-uploader .imgsBox .box_ul .on').each(function(){
            var options = t.getImgAttr(this);
            imgs.push(options);
        });

        this.hidebox();

        if(typeof this.params.callback!='undefined'){
            this.params.callback(imgs);
        }
    },
    /**
     * 获取选中的文件信息
     */
    getCheckedFile:function(){
        var files=[],domain=document.domain;
        $('#img-uploader .imgsBox .on').each(function(){
            var filename = this.getAttribute('filename');
            var fileurl = this.getAttribute('filepath');
            var fileext = this.getAttribute('fileext');
            var options={fileUrl:fileurl,fileName:filename,fileExt:fileext};
            /*if(fileurl.indexOf(domain)!==-1){
                options.filePath = fileurl.substr(fileurl.indexOf(domain)+domain.length);
            }*/
            options.filePath = fileurl;
            files.push(options);
        });

        this.hidebox();

        if(typeof this.params.callback!='undefined'){
            this.params.callback(files);
        }
    },

    //删除媒体图片
    rmMediaImage:function(id){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        if(confirm('确定删除该图片?')){
            $post({
                url:'/upload/delMediaImage/'+id,
                callback:function(x){
                    main.refresh();
                }
            });
        }
    },
    //生成云图片
    cloudImg:function(id){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        if(confirm('确定生成云图片?')){
            $post({
                url:'/upload/cloudimg/'+id,
                callback:function(x){
                }
            });
        }
    },
    //媒体图片分页
    selMediaImgPage:function(){
        var a=this._self,b=this._target;
        if(b.parentNode.tagName.toLowerCase()=='a')b=b.parentNode;
        if(b.tagName.toLowerCase()=='a'){
            if(b.rel!=''){
                //var tag_id = $('#g_tags .on');
                //tag_id = tag_id.length==0 ? 1 : tag_id[0].rel;
                var D={page:b.rel||1,list:1};

                $post({url:'/upload/media/'+page,method:'get',data:D,callback:function(x){
                    G('galleryBox').innerHTML=x}
                });
            }
        }
    },

    rmMediaFile:function(id){
        if(vor==1){
            com.xtip('无操作权限',{type:2});return;
        }
        if(confirm('确定删除该文件?')){
            $post({
                url:'/upload/delMediaFile/'+id,
                callback:function(x){
                    main.refresh();
                }
            });
        }
    }

};upload=new upload;