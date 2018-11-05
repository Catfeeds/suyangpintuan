/*******************************************************************************
 * KindEditor - WYSIWYG HTML Editor for Internet
 * Copyright (C) 2006-2011 kindsoft.net
 *
 * @author Roddy <luolonghao@gmail.com>
 * @site http://www.kindsoft.net/
 * @licence http://www.kindsoft.net/license.php
 *******************************************************************************/

KindEditor.plugin('image', function(K) {
    var self = this, name = 'image',
        allowImageUpload = K.undef(self.allowImageUpload, true),
        allowFileManager = K.undef(self.allowFileManager, false),
        uploadJson = K.undef(self.uploadJson, self.basePath + '../lcg/upload_img'),
        imageTabIndex = K.undef(self.imageTabIndex, 0),
        imgPath = self.pluginsPath + 'image/images/',
        lang = self.lang(name + '.');

    self.plugin.imageDialog = function(options) {
        com.currentEditor = self;
        $post({
            url:'/gallery/loadGalleryBox',
            method:'get',
            data:{
                imageHeight:options.imageHeight,
                imageWidth:options.imageWidth,
                imageTitle:options.imageTitle,
                imageUrl:options.imageUrl,
                imageAlign:options.imageAlign
            },
            callback:function(x){
                $('#img-uploader').html(x).show();
                $('#img-overlay').show();
                //display: block; visibility: visible; top:123px; left: 269px;
            }
        });

        //$ini({url:'/gallery/selpics',callback:function(x){
        //	com.xshow('选择图片',x,{isText:false,trueEvent:'e1-gallery-addImgToEdit'});
        //}});
    };
    self.plugin.image = {
        edit : function() {
            var img = self.plugin.getSelectedImage();
            self.plugin.imageDialog({
                imageUrl : img ? img.attr('data-ke-src') : 'http://',
                imageWidth : img ? img.width() : '',
                imageHeight : img ? img.height() : '',
                imageTitle : img ? img.attr('title') : '',
                imageAlign : img ? img.attr('align') : '',
                tabIndex: img ? 0 : imageTabIndex,
                clickFn : function(url, title, width, height, border, align) {
                    self.exec('insertimage', url, title, width, height, border, align);
                    // Bugfix: [Firefox] 上传图片后，总是出现正在加载的样式，需要延迟执行hideDialog
                    setTimeout(function() {
                        self.hideDialog().focus();
                    }, 0);
                }
            });
        },
        'delete' : function() {
            self.plugin.getSelectedImage().remove();
        }
    };
    self.clickToolbar(name, self.plugin.image.edit);
});
